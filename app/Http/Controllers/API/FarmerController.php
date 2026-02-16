<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FarmerController extends Controller
{
    /**
     * List farmers with filters
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $filters = $request->only(['status', 'beat_id', 'farmer_type', 'farmer_category', 'search', 'page', 'per_page']);
            $perPage = $filters['per_page'] ?? 15;

            // $farmers = Farmer::query();
            // if ($filters['status'] ?? null) {
            //     $farmers->where('status', $filters['status']);
            // }
            // if ($filters['beat_id'] ?? null) {
            //     $farmers->where('beat_id', $filters['beat_id']);
            // }
            // if ($filters['farmer_type'] ?? null) {
            //     $farmers->where('farmer_type', $filters['farmer_type']);
            // }
            // if ($filters['farmer_category'] ?? null) {
            //     $farmers->where('farmer_category', $filters['farmer_category']);
            // }
            // if ($filters['search'] ?? null) {
            //     $farmers->where('name', 'like', "%{$filters['search']}%");
            // }
            // $farmers = $farmers->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Farmers retrieved successfully',
                'data' => [
                    // 'farmers' => $farmers->items(),
                    'pagination' => [
                        // 'total' => $farmers->total(),
                        // 'per_page' => $farmers->perPage(),
                        // 'current_page' => $farmers->currentPage(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve farmers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single farmer details
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            // $farmer = Farmer::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Farmer retrieved successfully',
                'data' => [
                    // 'farmer' => $farmer,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Farmer not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Register new farmer with location and contacts
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'farmer_id' => 'nullable|string|unique:farmers,farmer_id',
                'phone' => 'required|string|max:20',
                'alternative_phone' => 'nullable|string|max:20',
                'email' => 'nullable|email',
                'beat_id' => 'required|exists:beats,id',
                'farmer_type' => 'required|in:marginal,small,medium,large',
                'farmer_category' => 'required|string|max:255',
                'land_holding' => 'nullable|numeric|min:0',
                'crops_grown' => 'nullable|array',
                'village' => 'required|string|max:255',
                'taluka' => 'required|string|max:255',
                'district' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'pincode' => 'nullable|string|max:10',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'status' => 'required|in:active,inactive',
            ]);

            // $farmer = Farmer::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Farmer registered successfully',
                'data' => [
                    // 'farmer' => $farmer,
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
     * Update farmer details
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
                'phone' => 'sometimes|string|max:20',
                'alternative_phone' => 'nullable|string|max:20',
                'email' => 'nullable|email',
                'farmer_type' => 'sometimes|in:marginal,small,medium,large',
                'farmer_category' => 'sometimes|string|max:255',
                'land_holding' => 'nullable|numeric|min:0',
                'crops_grown' => 'nullable|array',
                'status' => 'sometimes|in:active,inactive',
            ]);

            // $farmer = Farmer::findOrFail($id);
            // $farmer->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Farmer updated successfully',
                'data' => [
                    // 'farmer' => $farmer,
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
     * Soft delete farmer
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            // $farmer = Farmer::findOrFail($id);
            // $farmer->delete();

            return response()->json([
                'success' => true,
                'message' => 'Farmer deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete farmer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk upload farmers from Excel
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkUpload(Request $request)
    {
        try {
            $validated = $request->validate([
                'file' => 'required|file|mimes:xlsx,csv',
            ]);

            // Process and bulk import farmers from file

            return response()->json([
                'success' => true,
                'message' => 'Farmers bulk upload initiated',
                'data' => [
                    // 'imported_count' => $count,
                    // 'failed_count' => $failedCount,
                ]
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bulk upload failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Activate/Deactivate farmer
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleStatus(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:active,inactive',
            ]);

            // $farmer = Farmer::findOrFail($id);
            // $farmer->update(['status' => $validated['status']]);

            return response()->json([
                'success' => true,
                'message' => 'Farmer status updated successfully',
                'data' => [
                    // 'farmer' => $farmer,
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Update failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Get farmers assigned to a beat
     *
     * @param string $beat_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByBeat($beat_id)
    {
        try {
            // $farmers = Farmer::where('beat_id', $beat_id)->paginate(50);

            return response()->json([
                'success' => true,
                'message' => 'Farmers retrieved successfully',
                'data' => [
                    // 'farmers' => $farmers->items(),
                    'pagination' => [
                        // 'total' => $farmers->total(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve farmers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Link farmer to retailer
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function addRetailer(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'retailer_id' => 'required|exists:retailers,id',
            ]);

            // $farmer = Farmer::findOrFail($id);
            // $farmer->retailers()->syncWithoutDetaching([$validated['retailer_id']]);

            return response()->json([
                'success' => true,
                'message' => 'Retailer linked to farmer successfully'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Linking failed',
                'errors' => $e->errors()
            ], 422);
        }
    }
}
