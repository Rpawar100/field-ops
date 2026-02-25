<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RegionController extends Controller
{
    /**
     * List regions with optional zone_id filter
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $zone_id = $request->get('zone_id');
            $search = $request->get('search');
            $perPage = $request->get('per_page', 15);

            // $regions = Region::query();
            // if ($zone_id) {
            //     $regions->where('zone_id', $zone_id);
            // }
            // if ($search) {
            //     $regions->where('name', 'like', "%{$search}%")
            //             ->orWhere('code', 'like', "%{$search}%");
            // }
            // $regions = $regions->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Regions retrieved successfully',
                'data' => [
                    // 'regions' => $regions->items(),
                    'pagination' => [
                        // 'total' => $regions->total(),
                        // 'per_page' => $regions->perPage(),
                        // 'current_page' => $regions->currentPage(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve regions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get region with territories
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            // $region = Region::with('territories')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Region retrieved successfully',
                'data' => [
                    // 'region' => $region,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Region not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Create new region
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:50|unique:regions,code',
                'zone_id' => 'required|exists:zones,id',
                'description' => 'nullable|string',
                'status' => 'required|in:active,inactive',
            ]);

            // $region = Region::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Region created successfully',
                'data' => [
                    // 'region' => $region,
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
     * Update region details
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

            // $region = Region::findOrFail($id);
            // $region->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Region updated successfully',
                'data' => [
                    // 'region' => $region,
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
     * Delete region
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            // $region = Region::findOrFail($id);
            // if ($region->territories()->count() > 0) {
            //     return error response
            // }
            // $region->delete();

            return response()->json([
                'success' => true,
                'message' => 'Region deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete region',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Move region to different zone
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function realign(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'new_zone_id' => 'required|exists:zones,id',
                'reason' => 'nullable|string',
            ]);

            // $region = Region::findOrFail($id);
            // $region->update(['zone_id' => $validated['new_zone_id']]);

            return response()->json([
                'success' => true,
                'message' => 'Region realigned successfully',
                'data' => [
                    // 'region' => $region,
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
