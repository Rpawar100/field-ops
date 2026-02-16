<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TerritoryController extends Controller
{
    /**
     * List territories with optional region_id filter
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $region_id = $request->get('region_id');
            $search = $request->get('search');
            $perPage = $request->get('per_page', 15);

            // $territories = Territory::query();
            // if ($region_id) {
            //     $territories->where('region_id', $region_id);
            // }
            // if ($search) {
            //     $territories->where('name', 'like', "%{$search}%");
            // }
            // $territories = $territories->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Territories retrieved successfully',
                'data' => [
                    // 'territories' => $territories->items(),
                    'pagination' => [
                        // 'total' => $territories->total(),
                        // 'per_page' => $territories->perPage(),
                        // 'current_page' => $territories->currentPage(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve territories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get territory with beats
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            // $territory = Territory::with('beats')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Territory retrieved successfully',
                'data' => [
                    // 'territory' => $territory,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Territory not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Create new territory
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:50|unique:territories,code',
                'region_id' => 'required|exists:regions,id',
                'description' => 'nullable|string',
                'status' => 'required|in:active,inactive',
            ]);

            // $territory = Territory::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Territory created successfully',
                'data' => [
                    // 'territory' => $territory,
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
     * Update territory details
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

            // $territory = Territory::findOrFail($id);
            // $territory->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Territory updated successfully',
                'data' => [
                    // 'territory' => $territory,
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
     * Delete territory
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            // $territory = Territory::findOrFail($id);
            // if ($territory->beats()->count() > 0) {
            //     return conflict response
            // }
            // $territory->delete();

            return response()->json([
                'success' => true,
                'message' => 'Territory deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete territory',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Move territory to different region
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function realign(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'new_region_id' => 'required|exists:regions,id',
                'reason' => 'nullable|string',
            ]);

            // $territory = Territory::findOrFail($id);
            // $territory->update(['region_id' => $validated['new_region_id']]);

            return response()->json([
                'success' => true,
                'message' => 'Territory realigned successfully',
                'data' => [
                    // 'territory' => $territory,
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
