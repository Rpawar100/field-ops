<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BeatController extends Controller
{
    /**
     * List beats with filters
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $filters = $request->only(['territory_id', 'user_id', 'status', 'search', 'page', 'per_page']);
            $perPage = $filters['per_page'] ?? 15;

            // $beats = Beat::query();
            // if ($filters['territory_id'] ?? null) {
            //     $beats->where('territory_id', $filters['territory_id']);
            // }
            // if ($filters['user_id'] ?? null) {
            //     $beats->where('user_id', $filters['user_id']);
            // }
            // if ($filters['status'] ?? null) {
            //     $beats->where('status', $filters['status']);
            // }
            // if ($filters['search'] ?? null) {
            //     $beats->where('name', 'like', "%{$filters['search']}%");
            // }
            // $beats = $beats->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Beats retrieved successfully',
                'data' => [
                    // 'beats' => $beats->items(),
                    'pagination' => [
                        // 'total' => $beats->total(),
                        // 'per_page' => $beats->perPage(),
                        // 'current_page' => $beats->currentPage(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve beats',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get beat with assigned farmers and retailers
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            // $beat = Beat::with(['farmers', 'retailers'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Beat retrieved successfully',
                'data' => [
                    // 'beat' => $beat,
                    // 'farmers_count' => $beat->farmers()->count(),
                    // 'retailers_count' => $beat->retailers()->count(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Beat not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Create new beat
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:50|unique:beats,code',
                'territory_id' => 'required|exists:territories,id',
                'user_id' => 'nullable|exists:users,id',
                'description' => 'nullable|string',
                'status' => 'required|in:active,inactive',
            ]);

            // $beat = Beat::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Beat created successfully',
                'data' => [
                    // 'beat' => $beat,
                ]
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Update beat details
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'status' => 'sometimes|in:active,inactive',
            ]);

            // $beat = Beat::findOrFail($id);
            // $beat->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Beat updated successfully',
                'data' => [
                    // 'beat' => $beat,
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Delete beat
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            // $beat = Beat::findOrFail($id);
            // $beat->delete();

            return response()->json([
                'success' => true,
                'message' => 'Beat deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete beat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assign farmers to beat
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignFarmers(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'farmer_ids' => 'required|array',
                'farmer_ids.*' => 'exists:farmers,id',
            ]);

            // $beat = Beat::findOrFail($id);
            // $beat->farmers()->syncWithoutDetaching($validated['farmer_ids']);

            return response()->json([
                'success' => true,
                'message' => 'Farmers assigned to beat successfully',
                'data' => [
                    // 'assigned_count' => count($validated['farmer_ids']),
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Assign retailers to beat
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignRetailers(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'retailer_ids' => 'required|array',
                'retailer_ids.*' => 'exists:retailers,id',
            ]);

            // $beat = Beat::findOrFail($id);
            // $beat->retailers()->syncWithoutDetaching($validated['retailer_ids']);

            return response()->json([
                'success' => true,
                'message' => 'Retailers assigned to beat successfully',
                'data' => [
                    // 'assigned_count' => count($validated['retailer_ids']),
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Move beat to different territory
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function realign(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'new_territory_id' => 'required|exists:territories,id',
                'reason' => 'nullable|string',
            ]);

            // $beat = Beat::findOrFail($id);
            // $beat->update(['territory_id' => $validated['new_territory_id']]);

            return response()->json([
                'success' => true,
                'message' => 'Beat realigned successfully',
                'data' => [
                    // 'beat' => $beat,
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Realignment failed',
                'errors' => $e->errors()
            ], 422);
        }
    }
}
