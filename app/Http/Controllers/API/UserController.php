<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * List all users with filters
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $filters = $request->only(['role', 'zone_id', 'region_id', 'status', 'search', 'page', 'per_page']);
            $perPage = $filters['per_page'] ?? 15;

            // $users = User::query();
            // Apply filters
            // if ($filters['role'] ?? null) {
            //     $users->where('role', $filters['role']);
            // }
            // if ($filters['zone_id'] ?? null) {
            //     $users->where('zone_id', $filters['zone_id']);
            // }
            // // ... apply other filters
            // $users = $users->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Users retrieved successfully',
                'data' => [
                    // 'users' => $users->items(),
                    'pagination' => [
                        // 'total' => $users->total(),
                        // 'per_page' => $users->perPage(),
                        // 'current_page' => $users->currentPage(),
                        // 'last_page' => $users->lastPage(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve users',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single user
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            // $user = User::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'User retrieved successfully',
                'data' => [
                    // 'user' => $user,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Create new user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'mobile' => 'required|string|unique:users,mobile',
                'email' => 'nullable|email|unique:users,email',
                'password' => 'required|string|min:8',
                'role' => 'required|in:admin,manager,fa,distributor',
                'zone_id' => 'nullable|exists:zones,id',
                'region_id' => 'nullable|exists:regions,id',
                'territory_id' => 'nullable|exists:territories,id',
                'status' => 'required|in:active,inactive',
            ]);

            // $user = User::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => [
                    // 'user' => $user,
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
     * Update user details
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'sometimes|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'email' => 'sometimes|email|unique:users,email,' . $id,
                'status' => 'sometimes|in:active,inactive',
            ]);

            // $user = User::findOrFail($id);
            // $user->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => [
                    // 'user' => $user,
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
     * Soft delete user
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            // $user = User::findOrFail($id);
            // $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Realign user to different ZRTH
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function realign(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'zone_id' => 'nullable|exists:zones,id',
                'region_id' => 'nullable|exists:regions,id',
                'territory_id' => 'nullable|exists:territories,id',
                'headquarters_id' => 'nullable|exists:headquarters,id',
                'reason' => 'nullable|string',
            ]);

            // $user = User::findOrFail($id);
            // $user->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'User realigned successfully',
                'data' => [
                    // 'user' => $user,
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

    /**
     * Bulk upload users from Excel
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

            // Process Excel file and bulk create users
            // $path = $request->file('file')->store('uploads/users', 'local');

            return response()->json([
                'success' => true,
                'message' => 'Users bulk upload initiated',
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
     * Download users as Excel
     *
     * @param Request $request
     * @return mixed
     */
    public function bulkDownload(Request $request)
    {
        try {
            $filters = $request->only(['role', 'zone_id', 'region_id', 'status']);

            // Build query with filters
            // $users = User::query();
            // Export to Excel

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
}
