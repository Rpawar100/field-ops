<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SyncController extends Controller
{
    public function __construct(
        protected SyncService $syncService
    ) {}

    /**
     * Get master data for offline sync
     */
    public function masterData(Request $request): JsonResponse
    {
        $user = $request->user();
        $lastSyncAt = $request->get('last_sync_at');

        $data = $this->syncService->getMasterData($user, $lastSyncAt);

        return response()->json([
            'success' => true,
            'data' => $data,
            'synced_at' => now()->toIso8601String(),
        ]);
    }

    /**
     * Sync offline data to server
     */
    public function push(Request $request): JsonResponse
    {
        $request->validate([
            'activities' => 'array',
            'farmers' => 'array',
            'retailers' => 'array',
            'attendances' => 'array',
        ]);

        $user = $request->user();
        $results = $this->syncService->pushData($user, $request->all());

        return response()->json([
            'success' => true,
            'message' => 'Sync completed',
            'data' => $results,
        ]);
    }

    /**
     * Get changes since last sync
     */
    public function pull(Request $request): JsonResponse
    {
        $request->validate([
            'last_sync_at' => 'required|date',
        ]);

        $user = $request->user();
        $changes = $this->syncService->getChangesSince($user, $request->last_sync_at);

        return response()->json([
            'success' => true,
            'data' => $changes,
            'synced_at' => now()->toIso8601String(),
        ]);
    }

    /**
     * Resolve sync conflicts
     */
    public function resolveConflicts(Request $request): JsonResponse
    {
        $request->validate([
            'conflicts' => 'required|array',
            'conflicts.*.type' => 'required|in:activity,farmer,retailer',
            'conflicts.*.local_id' => 'required|string',
            'conflicts.*.server_id' => 'required|integer',
            'conflicts.*.resolution' => 'required|in:use_local,use_server,merge',
        ]);

        $user = $request->user();
        $results = $this->syncService->resolveConflicts($user, $request->conflicts);

        return response()->json([
            'success' => true,
            'message' => 'Conflicts resolved',
            'data' => $results,
        ]);
    }
}
