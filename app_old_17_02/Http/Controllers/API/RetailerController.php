<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RetailerController extends Controller
{
    /**
     * List retailers with filters
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $filters = $request->only(['status', 'beat_id', 'kyc_status', 'search', 'page', 'per_page']);
            $perPage = $filters['per_page'] ?? 15;

            // $retailers = Retailer::query();
            // if ($filters['status'] ?? null) {
            //     $retailers->where('status', $filters['status']);
            // }
            // if ($filters['beat_id'] ?? null) {
            //     $retailers->where('beat_id', $filters['beat_id']);
            // }
            // if ($filters['kyc_status'] ?? null) {
            //     $retailers->where('kyc_status', $filters['kyc_status']);
            // }
            // if ($filters['search'] ?? null) {
            //     $retailers->where('name', 'like', "%{$filters['search']}%");
            // }
            // $retailers = $retailers->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Retailers retrieved successfully',
                'data' => [
                    // 'retailers' => $retailers->items(),
                    'pagination' => [
                        // 'total' => $retailers->total(),
                        // 'per_page' => $retailers->perPage(),
                        // 'current_page' => $retailers->currentPage(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve retailers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single retailer details
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            // $retailer = Retailer::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Retailer retrieved successfully',
                'data' => [
                    // 'retailer' => $retailer,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Retailer not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Register new retailer with KYC
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'retailer_id' => 'nullable|string|unique:retailers,retailer_id',
                'phone' => 'required|string|max:20',
                'alternative_phone' => 'nullable|string|max:20',
                'email' => 'nullable|email',
                'beat_id' => 'required|exists:beats,id',
                'retailer_type' => 'required|in:kirana,super_market,general_store,cooperative',
                'shop_name' => 'required|string|max:255',
                'shop_address' => 'required|string',
                'village' => 'required|string|max:255',
                'taluka' => 'required|string|max:255',
                'district' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'pincode' => 'nullable|string|max:10',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'kyc_status' => 'required|in:pending,verified,rejected',
                'gstin' => 'nullable|string|max:20',
                'status' => 'required|in:active,inactive',
            ]);

            // $retailer = Retailer::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Retailer registered successfully',
                'data' => [
                    // 'retailer' => $retailer,
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
     * Update retailer details
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
                'retailer_type' => 'sometimes|in:kirana,super_market,general_store,cooperative',
                'shop_name' => 'sometimes|string|max:255',
                'shop_address' => 'sometimes|string',
                'status' => 'sometimes|in:active,inactive',
            ]);

            // $retailer = Retailer::findOrFail($id);
            // $retailer->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Retailer updated successfully',
                'data' => [
                    // 'retailer' => $retailer,
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
     * Soft delete retailer
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            // $retailer = Retailer::findOrFail($id);
            // $retailer->delete();

            return response()->json([
                'success' => true,
                'message' => 'Retailer deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete retailer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk upload retailers from Excel
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

            // Process and bulk import retailers from file

            return response()->json([
                'success' => true,
                'message' => 'Retailers bulk upload initiated',
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
     * Update KYC status and date
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function kycUpdate(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'kyc_status' => 'required|in:pending,verified,rejected',
                'kyc_date' => 'required|date',
                'kyc_remarks' => 'nullable|string',
            ]);

            // $retailer = Retailer::findOrFail($id);
            // $retailer->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'KYC updated successfully',
                'data' => [
                    // 'retailer' => $retailer,
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'KYC update failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Get retailers assigned to beat
     *
     * @param string $beat_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByBeat($beat_id)
    {
        try {
            // $retailers = Retailer::where('beat_id', $beat_id)->paginate(50);

            return response()->json([
                'success' => true,
                'message' => 'Retailers retrieved successfully',
                'data' => [
                    // 'retailers' => $retailers->items(),
                    'pagination' => [
                        // 'total' => $retailers->total(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve retailers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Link retailer to distributors
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function addDistributor(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'distributor_ids' => 'required|array',
                'distributor_ids.*' => 'exists:distributors,id',
            ]);

            // $retailer = Retailer::findOrFail($id);
            // $retailer->distributors()->syncWithoutDetaching($validated['distributor_ids']);

            return response()->json([
                'success' => true,
                'message' => 'Distributors linked to retailer successfully',
                'data' => [
                    // 'linked_count' => count($validated['distributor_ids']),
                ]
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
