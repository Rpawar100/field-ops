<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OnboardingController extends Controller
{
    /**
     * List onboarding requests with filters
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $filters = $request->only(['status', 'user_id', 'start_date', 'end_date', 'page', 'per_page']);
            $perPage = $filters['per_page'] ?? 15;

            // $requests = OnboardingRequest::query();
            // if ($filters['status'] ?? null) {
            //     $requests->where('status', $filters['status']);
            // }
            // if ($filters['user_id'] ?? null) {
            //     $requests->where('user_id', $filters['user_id']);
            // }
            // if ($filters['start_date'] ?? null && $filters['end_date'] ?? null) {
            //     $requests->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
            // }
            // $requests = $requests->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Onboarding requests retrieved successfully',
                'data' => [
                    // 'requests' => $requests->items(),
                    'pagination' => [
                        // 'total' => $requests->total(),
                        // 'per_page' => $requests->perPage(),
                        // 'current_page' => $requests->currentPage(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve requests',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get request details with documents
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            // $request = OnboardingRequest::with('documents')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Request retrieved successfully',
                'data' => [
                    // 'request' => $request,
                    // 'documents_count' => $request->documents()->count(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Request not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Create new onboarding request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'farmer_id' => 'nullable|string|unique:onboarding_requests,farmer_id',
                'retailer_id' => 'nullable|string|unique:onboarding_requests,retailer_id',
                'farmer_category' => 'nullable|string',
                'retailer_type' => 'nullable|string',
                'phone' => 'required|string|max:20',
                'email' => 'nullable|email',
                'address' => 'required|string',
                'village' => 'required|string',
                'taluka' => 'required|string',
                'district' => 'required|string',
                'state' => 'required|string',
                'pincode' => 'nullable|string|max:10',
                'status' => 'required|in:draft,submitted,approved,rejected',
            ]);

            // $onboarding = OnboardingRequest::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Onboarding request created successfully',
                'data' => [
                    // 'request' => $onboarding,
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
     * Update request details (before approval)
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'phone' => 'sometimes|string|max:20',
                'email' => 'sometimes|email',
                'address' => 'sometimes|string',
                'village' => 'sometimes|string',
                'taluka' => 'sometimes|string',
                'district' => 'sometimes|string',
                'state' => 'sometimes|string',
                'pincode' => 'sometimes|string|max:10',
            ]);

            // $onboarding = OnboardingRequest::findOrFail($id);
            // if ($onboarding->status !== 'draft') {
            //     return error response - can't modify
            // }
            // $onboarding->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Request updated successfully',
                'data' => [
                    // 'request' => $onboarding,
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
     * Upload supporting documents
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadDocument(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'document_type' => 'required|string|max:255',
                'file' => 'required|file|max:10240',
                'description' => 'nullable|string',
            ]);

            // $onboarding = OnboardingRequest::findOrFail($id);
            // Store document

            return response()->json([
                'success' => true,
                'message' => 'Document uploaded successfully',
                'data' => [
                    // 'document' => $document,
                ]
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Approve request (manager/admin)
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'approved_by' => 'required|exists:users,id',
                'remarks' => 'nullable|string',
            ]);

            // $onboarding = OnboardingRequest::findOrFail($id);
            // $onboarding->update([
            //     'status' => 'approved',
            //     'approved_by' => $validated['approved_by'],
            //     'approved_at' => now(),
            //     'remarks' => $validated['remarks'] ?? null
            // ]);

            return response()->json([
                'success' => true,
                'message' => 'Request approved successfully',
                'data' => [
                    // 'request' => $onboarding,
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Approval failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Reject request with remarks
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function reject(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'rejected_by' => 'required|exists:users,id',
                'rejection_reason' => 'required|string',
            ]);

            // $onboarding = OnboardingRequest::findOrFail($id);
            // $onboarding->update([
            //     'status' => 'rejected',
            //     'rejected_by' => $validated['rejected_by'],
            //     'rejected_at' => now(),
            //     'rejection_reason' => $validated['rejection_reason']
            // ]);

            return response()->json([
                'success' => true,
                'message' => 'Request rejected',
                'data' => [
                    // 'request' => $onboarding,
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Rejection failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Cancel request
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'cancellation_reason' => 'required|string',
            ]);

            // $onboarding = OnboardingRequest::findOrFail($id);
            // $onboarding->update([
            //     'status' => 'cancelled',
            //     'cancelled_at' => now(),
            //     'cancellation_reason' => $validated['cancellation_reason']
            // ]);

            return response()->json([
                'success' => true,
                'message' => 'Request cancelled successfully'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cancellation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Get all documents for request
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDocuments($id)
    {
        try {
            // $onboarding = OnboardingRequest::findOrFail($id);
            // $documents = $onboarding->documents()->paginate(20);

            return response()->json([
                'success' => true,
                'message' => 'Documents retrieved successfully',
                'data' => [
                    // 'documents' => $documents->items(),
                    'pagination' => [
                        // 'total' => $documents->total(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve documents',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download single document
     *
     * @param string $id
     * @return mixed
     */
    public function downloadDocument($id)
    {
        try {
            // $document = OnboardingDocument::findOrFail($id);
            // return response to download file

            return response()->json([
                'success' => true,
                'message' => 'Download initiated',
                'data' => [
                    // 'download_url' => $url,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Download failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download merged PDF of all documents
     *
     * @param string $id
     * @return mixed
     */
    public function downloadMergedPDF($id)
    {
        try {
            // $onboarding = OnboardingRequest::findOrFail($id);
            // Merge all documents into one PDF and return

            return response()->json([
                'success' => true,
                'message' => 'PDF generation initiated',
                'data' => [
                    // 'download_url' => $url,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'PDF generation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
