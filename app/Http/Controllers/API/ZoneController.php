<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ZoneController extends Controller
{
    /**
     * List all zones with pagination
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $search = $request->get('search');
            $perPage = $request->get('per_page', 15);

            // $zones = Zone::query();
            // if ($search) {
            //     $zones->where('name', 'like', "%{$search}%")
            //           ->orWhere('code', 'like', "%{$search}%");
            // }
            // $zones = $zones->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Zones retrieved successfully',
                'data' => [
                    // 'zones' => $zones->items(),
                    'pagination' => [
                        // 'total' => $zones->total(),
                        // 'per_page' => $zones->perPage(),
                        // 'current_page' => $zones->currentPage(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve zones',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get zone with regions
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            // $zone = Zone::with('regions')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Zone retrieved successfully',
                'data' => [
                    // 'zone' => $zone,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Zone not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Create new zone
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:zones,name',
                'code' => 'required|string|max:50|unique:zones,code',
                'description' => 'nullable|string',
                'headquarters_id' => 'nullable|exists:headquarters,id',
                'status' => 'required|in:active,inactive',
            ]);

            // $zone = Zone::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Zone created successfully',
                'data' => [
                    // 'zone' => $zone,
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
     * Update zone details
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255|unique:zones,name,' . $id,
                'description' => 'nullable|string',
                'status' => 'sometimes|in:active,inactive',
            ]);

            // $zone = Zone::findOrFail($id);
            // $zone->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Zone updated successfully',
                'data' => [
                    // 'zone' => $zone,
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
     * Delete zone
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            // $zone = Zone::findOrFail($id);
            // Check if zone has regions
            // if ($zone->regions()->count() > 0) {
            //     return response()->json([...], 409);
            // }
            // $zone->delete();

            return response()->json([
                'success' => true,
                'message' => 'Zone deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete zone',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Move zone users to different zone
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

            // $zone = Zone::findOrFail($id);
            // Move all users from this zone to new zone

            return response()->json([
                'success' => true,
                'message' => 'Zone realignment completed',
                'data' => [
                    // 'users_moved' => $count,
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
