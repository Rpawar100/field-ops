<?php

namespace App\Services;

use App\Models\User;
use App\Models\Activity;
use App\Models\Farmer;
use App\Models\Retailer;
use App\Models\Attendance;
use App\Models\Beat;
use App\Models\Product;
use App\Models\ActivityType;
use App\Models\DemoMaster;
use App\Models\SdtvNode;
use App\Models\ZrthNode;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncService
{
    /**
     * Get master data for initial offline sync
     *
     * @param User $user
     * @param string|null $lastSyncAt
     * @return array
     */
    public function getMasterData(User $user, ?string $lastSyncAt = null): array
    {
        $lastSync = $lastSyncAt ? Carbon::parse($lastSyncAt) : null;

        // Get user's assigned beats and their farmers/retailers
        $beatIds = Beat::forUser($user->id)->pluck('id');

        return [
            // Hierarchies
            'zrth_nodes' => $this->getZrthNodes($user, $lastSync),
            'sdtv_nodes' => $this->getSdtvNodes($user, $lastSync),

            // User's beats
            'beats' => Beat::forUser($user->id)
                ->with(['farmers', 'retailers'])
                ->when($lastSync, fn($q) => $q->where('updated_at', '>', $lastSync))
                ->get(),

            // Farmers in user's beats
            'farmers' => Farmer::whereHas('beats', fn($q) => $q->whereIn('beats.id', $beatIds))
                ->when($lastSync, fn($q) => $q->where('updated_at', '>', $lastSync))
                ->get(),

            // Retailers in user's beats
            'retailers' => Retailer::whereHas('beats', fn($q) => $q->whereIn('beats.id', $beatIds))
                ->when($lastSync, fn($q) => $q->where('updated_at', '>', $lastSync))
                ->get(),

            // Products (all active)
            'products' => Product::active()
                ->when($lastSync, fn($q) => $q->where('updated_at', '>', $lastSync))
                ->get(),

            // Activity types
            'activity_types' => ActivityType::active()
                ->when($lastSync, fn($q) => $q->where('updated_at', '>', $lastSync))
                ->get(),

            // Demo masters
            'demo_masters' => DemoMaster::active()
                ->with('stageTemplates')
                ->when($lastSync, fn($q) => $q->where('updated_at', '>', $lastSync))
                ->get(),
        ];
    }

    /**
     * Get ZRTH nodes for user's hierarchy
     */
    protected function getZrthNodes(User $user, ?Carbon $lastSync): \Illuminate\Database\Eloquent\Collection
    {
        $node = $user->zrthNode;

        if (!$node) {
            return collect();
        }

        // Get all ancestors and descendants
        $nodeIds = array_merge(
            $node->getAncestorIds(true),
            $node->getDescendantIds()
        );

        return ZrthNode::whereIn('id', $nodeIds)
            ->when($lastSync, fn($q) => $q->where('updated_at', '>', $lastSync))
            ->get();
    }

    /**
     * Get SDTV nodes for user's coverage area
     */
    protected function getSdtvNodes(User $user, ?Carbon $lastSync): \Illuminate\Database\Eloquent\Collection
    {
        // Get SDTV nodes from user's beats
        $sdtvNodeIds = Beat::forUser($user->id)->pluck('sdtv_node_id')->unique();

        $allNodeIds = collect();
        foreach ($sdtvNodeIds as $nodeId) {
            $node = SdtvNode::find($nodeId);
            if ($node) {
                $allNodeIds = $allNodeIds->merge($node->getAncestorIds(true));
            }
        }

        return SdtvNode::whereIn('id', $allNodeIds->unique())
            ->when($lastSync, fn($q) => $q->where('updated_at', '>', $lastSync))
            ->get();
    }

    /**
     * Push offline data to server
     *
     * @param User $user
     * @param array $data
     * @return array
     */
    public function pushData(User $user, array $data): array
    {
        $results = [
            'activities' => ['synced' => 0, 'failed' => 0, 'errors' => []],
            'farmers' => ['synced' => 0, 'failed' => 0, 'errors' => []],
            'retailers' => ['synced' => 0, 'failed' => 0, 'errors' => []],
            'attendances' => ['synced' => 0, 'failed' => 0, 'errors' => []],
        ];

        DB::beginTransaction();

        try {
            // Sync activities
            if (isset($data['activities'])) {
                $results['activities'] = $this->syncActivities($user, $data['activities']);
            }

            // Sync farmers
            if (isset($data['farmers'])) {
                $results['farmers'] = $this->syncFarmers($user, $data['farmers']);
            }

            // Sync retailers
            if (isset($data['retailers'])) {
                $results['retailers'] = $this->syncRetailers($user, $data['retailers']);
            }

            // Sync attendances
            if (isset($data['attendances'])) {
                $results['attendances'] = $this->syncAttendances($user, $data['attendances']);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Sync failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'exception' => $e,
            ]);
            throw $e;
        }

        return $results;
    }

    /**
     * Sync activities from offline
     *
     * @param User $user
     * @param array $activities
     * @return array
     */
    public function syncActivities(User $user, array $activities): array
    {
        $synced = 0;
        $failed = 0;
        $errors = [];
        $syncedIds = [];

        foreach ($activities as $activityData) {
            try {
                // Check if already synced by local_id
                $existing = Activity::where('local_id', $activityData['local_id'])
                    ->where('user_id', $user->id)
                    ->first();

                if ($existing) {
                    $syncedIds[$activityData['local_id']] = $existing->id;
                    $synced++;
                    continue;
                }

                // Determine target type
                $targetType = $activityData['target_type'] === 'farmer'
                    ? Farmer::class
                    : Retailer::class;

                $activity = Activity::create([
                    'user_id' => $user->id,
                    'activity_type_id' => $activityData['activity_type_id'],
                    'target_type' => $targetType,
                    'target_id' => $activityData['target_id'],
                    'product_id' => $activityData['product_id'] ?? null,
                    'latitude' => $activityData['latitude'] ?? null,
                    'longitude' => $activityData['longitude'] ?? null,
                    'address' => $activityData['address'] ?? null,
                    'photo' => $activityData['photo'] ?? null,
                    'notes' => $activityData['notes'] ?? null,
                    'metadata' => $activityData['metadata'] ?? null,
                    'sync_status' => Activity::SYNC_STATUS_SYNCED,
                    'local_id' => $activityData['local_id'],
                    'performed_at' => Carbon::parse($activityData['performed_at']),
                ]);

                $syncedIds[$activityData['local_id']] = $activity->id;
                $synced++;
            } catch (\Exception $e) {
                $failed++;
                $errors[] = [
                    'local_id' => $activityData['local_id'] ?? 'unknown',
                    'error' => $e->getMessage(),
                ];
                Log::warning('Activity sync failed', [
                    'user_id' => $user->id,
                    'local_id' => $activityData['local_id'] ?? 'unknown',
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return [
            'synced' => $synced,
            'failed' => $failed,
            'errors' => $errors,
            'synced_ids' => $syncedIds,
        ];
    }

    /**
     * Sync farmers from offline
     */
    protected function syncFarmers(User $user, array $farmers): array
    {
        $synced = 0;
        $failed = 0;
        $errors = [];
        $syncedIds = [];

        foreach ($farmers as $farmerData) {
            try {
                // Check for existing farmer by phone
                $existing = Farmer::where('phone', $farmerData['phone'])->first();

                if ($existing) {
                    // Update existing
                    $existing->update([
                        'name' => $farmerData['name'] ?? $existing->name,
                        'address' => $farmerData['address'] ?? $existing->address,
                        'latitude' => $farmerData['latitude'] ?? $existing->latitude,
                        'longitude' => $farmerData['longitude'] ?? $existing->longitude,
                        'land_holding_acres' => $farmerData['land_holding_acres'] ?? $existing->land_holding_acres,
                        'crops_grown' => $farmerData['crops_grown'] ?? $existing->crops_grown,
                    ]);
                    $syncedIds[$farmerData['local_id'] ?? $farmerData['phone']] = $existing->id;
                } else {
                    // Create new
                    $farmer = Farmer::create([
                        'name' => $farmerData['name'],
                        'phone' => $farmerData['phone'],
                        'alternate_phone' => $farmerData['alternate_phone'] ?? null,
                        'sdtv_node_id' => $farmerData['sdtv_node_id'],
                        'address' => $farmerData['address'] ?? null,
                        'latitude' => $farmerData['latitude'] ?? null,
                        'longitude' => $farmerData['longitude'] ?? null,
                        'land_holding_acres' => $farmerData['land_holding_acres'] ?? null,
                        'crops_grown' => $farmerData['crops_grown'] ?? null,
                        'preferred_retailer_id' => $farmerData['preferred_retailer_id'] ?? null,
                    ]);
                    $syncedIds[$farmerData['local_id'] ?? $farmerData['phone']] = $farmer->id;
                }

                $synced++;
            } catch (\Exception $e) {
                $failed++;
                $errors[] = [
                    'local_id' => $farmerData['local_id'] ?? $farmerData['phone'] ?? 'unknown',
                    'error' => $e->getMessage(),
                ];
            }
        }

        return [
            'synced' => $synced,
            'failed' => $failed,
            'errors' => $errors,
            'synced_ids' => $syncedIds,
        ];
    }

    /**
     * Sync retailers from offline
     */
    protected function syncRetailers(User $user, array $retailers): array
    {
        $synced = 0;
        $failed = 0;
        $errors = [];
        $syncedIds = [];

        foreach ($retailers as $retailerData) {
            try {
                $existing = Retailer::where('phone', $retailerData['phone'])->first();

                if ($existing) {
                    $existing->update([
                        'name' => $retailerData['name'] ?? $existing->name,
                        'shop_name' => $retailerData['shop_name'] ?? $existing->shop_name,
                        'address' => $retailerData['address'] ?? $existing->address,
                        'latitude' => $retailerData['latitude'] ?? $existing->latitude,
                        'longitude' => $retailerData['longitude'] ?? $existing->longitude,
                    ]);
                    $syncedIds[$retailerData['local_id'] ?? $retailerData['phone']] = $existing->id;
                } else {
                    $retailer = Retailer::create([
                        'name' => $retailerData['name'],
                        'shop_name' => $retailerData['shop_name'],
                        'phone' => $retailerData['phone'],
                        'alternate_phone' => $retailerData['alternate_phone'] ?? null,
                        'sdtv_node_id' => $retailerData['sdtv_node_id'],
                        'address' => $retailerData['address'] ?? null,
                        'latitude' => $retailerData['latitude'] ?? null,
                        'longitude' => $retailerData['longitude'] ?? null,
                        'gst_number' => $retailerData['gst_number'] ?? null,
                        'license_number' => $retailerData['license_number'] ?? null,
                    ]);
                    $syncedIds[$retailerData['local_id'] ?? $retailerData['phone']] = $retailer->id;
                }

                $synced++;
            } catch (\Exception $e) {
                $failed++;
                $errors[] = [
                    'local_id' => $retailerData['local_id'] ?? $retailerData['phone'] ?? 'unknown',
                    'error' => $e->getMessage(),
                ];
            }
        }

        return [
            'synced' => $synced,
            'failed' => $failed,
            'errors' => $errors,
            'synced_ids' => $syncedIds,
        ];
    }

    /**
     * Sync attendances from offline
     */
    protected function syncAttendances(User $user, array $attendances): array
    {
        $synced = 0;
        $failed = 0;
        $errors = [];

        foreach ($attendances as $attendanceData) {
            try {
                $date = Carbon::parse($attendanceData['date'])->toDateString();

                $existing = Attendance::where('user_id', $user->id)
                    ->where('date', $date)
                    ->first();

                if ($existing) {
                    // Update check-out if provided and not already set
                    if (isset($attendanceData['check_out_time']) && !$existing->hasCheckedOut()) {
                        $existing->update([
                            'check_out_time' => Carbon::parse($attendanceData['check_out_time']),
                            'check_out_latitude' => $attendanceData['check_out_latitude'] ?? null,
                            'check_out_longitude' => $attendanceData['check_out_longitude'] ?? null,
                            'check_out_address' => $attendanceData['check_out_address'] ?? null,
                        ]);
                    }
                } else {
                    Attendance::create([
                        'user_id' => $user->id,
                        'date' => $date,
                        'status' => Attendance::STATUS_PENDING,
                        'check_in_time' => Carbon::parse($attendanceData['check_in_time']),
                        'check_out_time' => isset($attendanceData['check_out_time'])
                            ? Carbon::parse($attendanceData['check_out_time'])
                            : null,
                        'check_in_latitude' => $attendanceData['check_in_latitude'] ?? null,
                        'check_in_longitude' => $attendanceData['check_in_longitude'] ?? null,
                        'check_in_address' => $attendanceData['check_in_address'] ?? null,
                        'check_out_latitude' => $attendanceData['check_out_latitude'] ?? null,
                        'check_out_longitude' => $attendanceData['check_out_longitude'] ?? null,
                        'check_out_address' => $attendanceData['check_out_address'] ?? null,
                    ]);
                }

                $synced++;
            } catch (\Exception $e) {
                $failed++;
                $errors[] = [
                    'date' => $attendanceData['date'] ?? 'unknown',
                    'error' => $e->getMessage(),
                ];
            }
        }

        return [
            'synced' => $synced,
            'failed' => $failed,
            'errors' => $errors,
        ];
    }

    /**
     * Get changes since last sync
     */
    public function getChangesSince(User $user, string $lastSyncAt): array
    {
        $lastSync = Carbon::parse($lastSyncAt);

        return $this->getMasterData($user, $lastSyncAt);
    }

    /**
     * Resolve sync conflicts
     */
    public function resolveConflicts(User $user, array $conflicts): array
    {
        $results = [];

        foreach ($conflicts as $conflict) {
            try {
                $result = match ($conflict['type']) {
                    'activity' => $this->resolveActivityConflict($user, $conflict),
                    'farmer' => $this->resolveFarmerConflict($conflict),
                    'retailer' => $this->resolveRetailerConflict($conflict),
                    default => ['status' => 'error', 'message' => 'Unknown conflict type'],
                };

                $results[] = [
                    'local_id' => $conflict['local_id'],
                    'server_id' => $conflict['server_id'],
                    ...$result,
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'local_id' => $conflict['local_id'],
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    protected function resolveActivityConflict(User $user, array $conflict): array
    {
        if ($conflict['resolution'] === 'use_server') {
            // Delete local, keep server version
            return ['status' => 'resolved', 'action' => 'kept_server'];
        }

        // For use_local or merge, this would typically be handled on client side
        return ['status' => 'resolved', 'action' => $conflict['resolution']];
    }

    protected function resolveFarmerConflict(array $conflict): array
    {
        // Similar logic for farmers
        return ['status' => 'resolved', 'action' => $conflict['resolution']];
    }

    protected function resolveRetailerConflict(array $conflict): array
    {
        // Similar logic for retailers
        return ['status' => 'resolved', 'action' => $conflict['resolution']];
    }
}
