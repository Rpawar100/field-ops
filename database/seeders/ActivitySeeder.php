<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ActivitySeeder extends Seeder
{
    /**
     * Seed activity_types, activity_masters, and 100 activities
     * with activity_photos, activity_products, and activity_comments.
     */
    public function run(): void
    {
        if (DB::table('activity_types')->where('code', 'AT-FV')->exists()) {
            $this->command->info('ActivitySeeder: Data already exists, skipping.');
            return;
        }

        $now = Carbon::now();

        DB::transaction(function () use ($now) {

            // ─── ACTIVITY TYPES (5) ──────────────────────────────
            $typeDefs = [
                [
                    'code'             => 'AT-FV',
                    'name'             => 'Farmer Visit',
                    'category'         => 'farmer_visit',
                    'requires_farmer'  => true,
                    'requires_retailer' => false,
                    'requires_beat'    => true,
                    'requires_photo'   => true,
                    'min_photos'       => 1,
                    'max_photos'       => 5,
                    'requires_gps'     => true,
                    'counts_for_attendance' => true,
                    'points'           => 5,
                ],
                [
                    'code'             => 'AT-RV',
                    'name'             => 'Retailer Visit',
                    'category'         => 'retailer_visit',
                    'requires_farmer'  => false,
                    'requires_retailer' => true,
                    'requires_beat'    => true,
                    'requires_photo'   => true,
                    'min_photos'       => 1,
                    'max_photos'       => 3,
                    'requires_gps'     => true,
                    'counts_for_attendance' => true,
                    'points'           => 5,
                ],
                [
                    'code'             => 'AT-DA',
                    'name'             => 'Demo Activity',
                    'category'         => 'demo_cycle',
                    'requires_farmer'  => true,
                    'requires_retailer' => false,
                    'requires_beat'    => false,
                    'requires_photo'   => true,
                    'min_photos'       => 2,
                    'max_photos'       => 10,
                    'requires_gps'     => true,
                    'counts_for_attendance' => true,
                    'points'           => 10,
                ],
                [
                    'code'             => 'AT-MS',
                    'name'             => 'Market Survey',
                    'category'         => 'market_survey',
                    'requires_farmer'  => false,
                    'requires_retailer' => false,
                    'requires_beat'    => true,
                    'requires_photo'   => true,
                    'min_photos'       => 2,
                    'max_photos'       => 8,
                    'requires_gps'     => true,
                    'counts_for_attendance' => true,
                    'points'           => 8,
                ],
                [
                    'code'             => 'AT-TR',
                    'name'             => 'Training',
                    'category'         => 'training',
                    'requires_farmer'  => false,
                    'requires_retailer' => false,
                    'requires_beat'    => false,
                    'requires_photo'   => true,
                    'min_photos'       => 1,
                    'max_photos'       => 5,
                    'requires_gps'     => true,
                    'counts_for_attendance' => true,
                    'points'           => 15,
                ],
            ];

            $typeIds = [];
            foreach ($typeDefs as $td) {
                $id = DB::table('activity_types')->insertGetId([
                    'code'                 => $td['code'],
                    'name'                 => $td['name'],
                    'category'             => $td['category'],
                    'description'          => $td['name'] . ' activity type',
                    'requires_farmer'      => $td['requires_farmer'],
                    'requires_retailer'    => $td['requires_retailer'],
                    'requires_beat'        => $td['requires_beat'],
                    'requires_photo'       => $td['requires_photo'],
                    'min_photos'           => $td['min_photos'],
                    'max_photos'           => $td['max_photos'],
                    'requires_gps'         => $td['requires_gps'],
                    'counts_for_attendance' => $td['counts_for_attendance'],
                    'points'               => $td['points'],
                    'status'               => 'active',
                    'created_at'           => $now,
                    'updated_at'           => $now,
                ]);
                $typeIds[$td['code']] = $id;
            }

            // ─── ACTIVITY MASTERS (5) ────────────────────────────
            $masterDefs = [
                ['type' => 'AT-FV', 'code' => 'AM-FV01', 'name' => 'Standard Farmer Visit',       'roles' => ['fa', 'tsl']],
                ['type' => 'AT-RV', 'code' => 'AM-RV01', 'name' => 'Standard Retailer Visit',     'roles' => ['fa', 'tsl']],
                ['type' => 'AT-DA', 'code' => 'AM-DA01', 'name' => 'Demo Stage Observation',      'roles' => ['fa']],
                ['type' => 'AT-MS', 'code' => 'AM-MS01', 'name' => 'Market Intelligence Survey',  'roles' => ['fa', 'tsl', 'rm']],
                ['type' => 'AT-TR', 'code' => 'AM-TR01', 'name' => 'Field Training Session',      'roles' => ['tsl', 'rm']],
            ];

            $masterIds = [];
            foreach ($masterDefs as $md) {
                $id = DB::table('activity_masters')->insertGetId([
                    'activity_type_id'    => $typeIds[$md['type']],
                    'code'                => $md['code'],
                    'name'                => $md['name'],
                    'description'         => $md['name'] . ' master configuration',
                    'attributes_schema'   => json_encode(['type' => 'object', 'properties' => []]),
                    'validation_rules'    => null,
                    'applicable_roles'    => json_encode($md['roles']),
                    'applicable_crop_ids' => null,
                    'applicable_product_ids' => null,
                    'allowed_start_time'  => '07:00:00',
                    'allowed_end_time'    => '20:00:00',
                    'allow_backdated'     => false,
                    'backdate_days_limit' => 0,
                    'status'              => 'active',
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ]);
                $masterIds[$md['code']] = $id;
            }

            // ─── 100 ACTIVITIES ──────────────────────────────────
            $faUsers    = DB::table('users')->where('role', 'fa')->where('status', 'active')->get();
            $farmers    = DB::table('farmers')->where('status', 'active')->pluck('id')->toArray();
            $retailers  = DB::table('retailers')->where('status', 'active')->pluck('id')->toArray();
            $beats      = DB::table('beats')->where('status', 'active')->pluck('id')->toArray();
            $villages   = DB::table('villages')->where('status', 'active')->pluck('id')->toArray();
            $hqs        = DB::table('zrth_hierarchies')->where('level', 'hq')->pluck('id')->toArray();
            $products   = DB::table('products')->where('status', 'active')->pluck('id')->toArray();
            $attendances = DB::table('attendances')
                ->whereNotIn('attendance_type', ['leave', 'week_off'])
                ->whereNotNull('check_in_time')
                ->pluck('id')
                ->toArray();

            $statuses   = ['draft', 'submitted', 'completed', 'submitted', 'completed', 'completed'];
            $typeCodes  = ['AT-FV', 'AT-RV', 'AT-DA', 'AT-MS', 'AT-TR'];
            $masterCodes = ['AM-FV01', 'AM-RV01', 'AM-DA01', 'AM-MS01', 'AM-TR01'];

            // GPS base coordinates
            $gpsCoords = [
                ['lat' => 18.15, 'lng' => 74.58],
                ['lat' => 19.61, 'lng' => 75.95],
                ['lat' => 27.81, 'lng' => 75.04],
                ['lat' => 23.01, 'lng' => 77.09],
                ['lat' => 16.51, 'lng' => 75.29],
                ['lat' => 15.55, 'lng' => 75.38],
                ['lat' => 17.39, 'lng' => 78.49],
            ];

            $activityIds = [];

            for ($i = 0; $i < 100; $i++) {
                $typeIdx     = $i % 5;
                $typeCode    = $typeCodes[$typeIdx];
                $masterCode  = $masterCodes[$typeIdx];
                $faUser      = $faUsers[$i % $faUsers->count()];
                $dayOffset   = ($i % 30) + 1;
                $date        = Carbon::today()->subDays($dayOffset);

                $startHour = 9 + ($i % 4);
                $endHour   = $startHour + 1;

                $coordIdx = $i % count($gpsCoords);
                $lat = $gpsCoords[$coordIdx]['lat'] + ($i * 0.001);
                $lng = $gpsCoords[$coordIdx]['lng'] + ($i * 0.001);

                $farmerId   = null;
                $retailerId = null;
                if ($typeCode === 'AT-FV' || $typeCode === 'AT-DA') {
                    $farmerId = $farmers[$i % count($farmers)];
                }
                if ($typeCode === 'AT-RV') {
                    $retailerId = $retailers[$i % count($retailers)];
                }

                $beatId    = !empty($beats) ? $beats[$i % count($beats)] : null;
                $villageId = !empty($villages) ? $villages[$i % count($villages)] : null;
                $hqId      = !empty($hqs) ? $hqs[$i % count($hqs)] : null;
                $attId     = !empty($attendances) ? $attendances[$i % count($attendances)] : null;
                $status    = $statuses[$i % count($statuses)];

                $activityId = DB::table('activities')->insertGetId([
                    'uuid'                       => Str::uuid()->toString(),
                    'user_id'                    => $faUser->id,
                    'activity_type_id'           => $typeIds[$typeCode],
                    'activity_master_id'         => $masterIds[$masterCode],
                    'farmer_id'                  => $farmerId,
                    'retailer_id'                => $retailerId,
                    'distributor_id'             => null,
                    'beat_id'                    => $beatId,
                    'village_id'                 => $villageId,
                    'zrth_hierarchy_id'          => $hqId,
                    'execution_date'             => $date->format('Y-m-d'),
                    'start_time'                 => sprintf('%02d:00:00', $startHour),
                    'end_time'                   => sprintf('%02d:00:00', $endHour),
                    'duration_minutes'           => 60,
                    'attributes'                 => json_encode(['notes' => 'Seeded activity ' . ($i + 1)]),
                    'products'                   => null,
                    'gps_latitude'               => round($lat, 8),
                    'gps_longitude'              => round($lng, 8),
                    'gps_accuracy'               => round(5 + ($i % 20), 2),
                    'address'                    => 'Field location ' . ($i + 1),
                    'photos'                     => null,
                    'voice_note_path'            => null,
                    'voice_note_duration'        => null,
                    'remarks'                    => 'Activity completed successfully',
                    'next_action'                => ($i % 3 === 0) ? 'Follow up next week' : null,
                    'next_visit_date'            => ($i % 3 === 0) ? $date->addDays(7)->format('Y-m-d') : null,
                    'attendance_id'              => $attId,
                    'demo_farmer_assignment_id'  => null,
                    'demo_stage_activity_id'     => null,
                    'approval_status'            => ($status === 'completed') ? 'approved' : 'not_required',
                    'approved_by'                => null,
                    'approved_at'                => null,
                    'approval_remarks'           => null,
                    'sync_status'                => 'synced',
                    'synced_at'                  => $now,
                    'sync_attempts'              => 1,
                    'sync_error'                 => null,
                    'device_id'                  => 'DEV-' . str_pad($faUser->id, 4, '0', STR_PAD_LEFT),
                    'app_version'                => '1.2.0',
                    'status'                     => $status,
                    'created_at'                 => $now,
                    'updated_at'                 => $now,
                ]);

                $activityIds[] = $activityId;
            }

            // ─── ACTIVITY PHOTOS (for first 30 activities) ──────
            $photoRows = [];
            for ($p = 0; $p < 30; $p++) {
                $aId = $activityIds[$p];
                $photoCount = 1 + ($p % 3);
                for ($ph = 0; $ph < $photoCount; $ph++) {
                    $photoRows[] = [
                        'activity_id'  => $aId,
                        'photo_path'   => 'activities/' . $aId . '/photo_' . ($ph + 1) . '.jpg',
                        'photo_type'   => ($ph === 0) ? 'before' : 'after',
                        'caption'      => 'Activity photo ' . ($ph + 1),
                        'sort_order'   => $ph,
                        'latitude'     => null,
                        'longitude'    => null,
                        'taken_at'     => $now,
                        'file_size'    => (string)(150000 + $ph * 50000),
                        'created_at'   => $now,
                        'updated_at'   => $now,
                    ];
                }
            }
            foreach (array_chunk($photoRows, 50) as $chunk) {
                DB::table('activity_photos')->insert($chunk);
            }

            // ─── ACTIVITY PRODUCTS (for first 40 activities) ────
            $prodActions = ['discussed', 'demonstrated', 'sampled', 'sold', 'ordered'];
            $apRows = [];
            for ($ap = 0; $ap < 40; $ap++) {
                $aId = $activityIds[$ap];
                $productId = $products[$ap % count($products)];
                $apRows[] = [
                    'activity_id'   => $aId,
                    'product_id'    => $productId,
                    'quantity'      => 1 + ($ap % 5),
                    'quantity_unit' => 'pcs',
                    'action'        => $prodActions[$ap % count($prodActions)],
                    'remarks'       => null,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ];
            }
            foreach (array_chunk($apRows, 50) as $chunk) {
                DB::table('activity_products')->insert($chunk);
            }

            // ─── ACTIVITY COMMENTS (for first 20 activities) ────
            $commentTypes = ['note', 'follow_up', 'escalation', 'resolution'];
            $commentRows = [];
            for ($ac = 0; $ac < 20; $ac++) {
                $aId = $activityIds[$ac];
                $commentRows[] = [
                    'activity_id'  => $aId,
                    'user_id'      => $faUsers[$ac % $faUsers->count()]->id,
                    'comment'      => 'Activity observation note #' . ($ac + 1),
                    'comment_type' => $commentTypes[$ac % count($commentTypes)],
                    'is_internal'  => ($ac % 4 === 0),
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ];
            }
            DB::table('activity_comments')->insert($commentRows);
        });

        $this->command->info('ActivitySeeder: 5 activity types, 5 masters, 100 activities with photos/products/comments.');
    }
}
