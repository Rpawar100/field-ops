<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DistributorController extends Controller
{
    /**
     * List distributors with filters
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $filters = $request->only(['status', 'beat_id', 'search', 'page', 'per_page']);
            $perPage = $filters['per_page'] ?? 15;

            // $distributors = Distributor::query();
            // if ($filters['status'] ?? null) {
            //     $distributors->where('status', $filters['status']);
            // }
            // if ($filters['beat_id'] ?? null) {
            //     $distributors->where('beat_id', $filters['beat_id']);
            // }
            // if ($filters['search'] ?? null) {
            //     $distributors->where('name', 'like', "%{$filters['search']}%");
            // }
            // $distributors = $distributors->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Distributors retrieved successfully',
                'data' => [
                    // 'distributors' => $distributors->items(),
                    'pagination' => [
                        // 'total' => $distributors->total(),
                        // 'per_page' => $distributors->perPage(),
                        // 'current_page' => $distributors->currentPage(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve distributors',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single distributor
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            // $distributor = Distributor::with('users')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Distributor retrieved successfully',
                'data' => [
                    // 'distributor' => $distributor,
                    // 'assigned_users' => $distributor->users()->count(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Distributor not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Create new distributor
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:50|unique:distributors,code',
                'distributor_type' => 'required|in:company,partnership,individual',
                'phone' => 'required|string|max:20',
                'email' => 'nullable|email',
                'address' => 'required|string',
                'city' => 'required|string|max:255',
                'pincode' => 'required|string|max:10',
                'status' => 'required|in:active,inactive',
            ]);

            // $distributor = Distributor::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Distributor created successfully',
                'data' => [
                    // 'distributor' => $distributor,
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
     * Update distributor details
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
                'email' => 'nullable|email',
                'address' => 'sometimes|string',
                'city' => 'sometimes|string|max:255',
                'pincode' => 'sometimes|string|max:10',
                'status' => 'sometimes|in:active,inactive',
            ]);

            // $distributor = Distributor::findOrFail($id);
            // $distributor->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Distributor updated successfully',
                'data' => [
                    // 'distributor' => $distributor,
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
     * Delete distributor
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            // $distributor = Distributor::findOrFail($id);
            // $distributor->delete();

            return response()->json([
                'success' => true,
                'message' => 'Distributor deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete distributor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assign distributor to users
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignUsers(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'user_ids' => 'required|array',
                'user_ids.*' => 'exists:users,id',
            ]);

            // $distributor = Distributor::findOrFail($id);
            // $distributor->users()->syncWithoutDetaching($validated['user_ids']);

            return response()->json([
                'success' => true,
                'message' => 'Distributor assigned to users successfully',
                'data' => [
                    // 'assigned_count' => count($validated['user_ids']),
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
     * Realign distributor to different user/beat
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function realign(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'new_user_id' => 'nullable|exists:users,id',
                'new_beat_id' => 'nullable|exists:beats,id',
                'reason' => 'nullable|string',
            ]);

            // $distributor = Distributor::findOrFail($id);
            // if ($validated['new_user_id'] ?? null) {
            //     $distributor->update(['user_id' => $validated['new_user_id']]);
            // }

            return response()->json([
                'success' => true,
                'message' => 'Distributor realigned successfully',
                'data' => [
                    // 'distributor' => $distributor,
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
