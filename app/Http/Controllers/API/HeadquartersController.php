<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class HeadquartersController extends Controller
{
    /**
     * List all headquarters
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $search = $request->get('search');
            $perPage = $request->get('per_page', 15);

            // $headquarters = Headquarters::query();
            // if ($search) {
            //     $headquarters->where('name', 'like', "%{$search}%")
            //                   ->orWhere('city', 'like', "%{$search}%");
            // }
            // $headquarters = $headquarters->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Headquarters retrieved successfully',
                'data' => [
                    // 'headquarters' => $headquarters->items(),
                    'pagination' => [
                        // 'total' => $headquarters->total(),
                        // 'per_page' => $headquarters->perPage(),
                        // 'current_page' => $headquarters->currentPage(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve headquarters',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get headquarters details
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            // $headquarters = Headquarters::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Headquarters retrieved successfully',
                'data' => [
                    // 'headquarters' => $headquarters,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Headquarters not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Create new headquarters
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:headquarters,name',
                'code' => 'required|string|max:50|unique:headquarters,code',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'pincode' => 'required|string|max:10',
                'address' => 'required|string',
                'contact_person' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'status' => 'required|in:active,inactive',
            ]);

            // $headquarters = Headquarters::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Headquarters created successfully',
                'data' => [
                    // 'headquarters' => $headquarters,
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
     * Update headquarters details
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255|unique:headquarters,name,' . $id,
                'city' => 'sometimes|string|max:255',
                'state' => 'sometimes|string|max:255',
                'pincode' => 'sometimes|string|max:10',
                'address' => 'sometimes|string',
                'contact_person' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'status' => 'sometimes|in:active,inactive',
            ]);

            // $headquarters = Headquarters::findOrFail($id);
            // $headquarters->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Headquarters updated successfully',
                'data' => [
                    // 'headquarters' => $headquarters,
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
     * Delete headquarters
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            // $headquarters = Headquarters::findOrFail($id);
            // Check if zones exist under this headquarters
            // if ($headquarters->zones()->count() > 0) {
            //     return response with conflict
            // }
            // $headquarters->delete();

            return response()->json([
                'success' => true,
                'message' => 'Headquarters deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete headquarters',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
