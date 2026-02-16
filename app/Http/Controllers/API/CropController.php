<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CropController extends Controller
{
    /**
     * List all crops
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $status = $request->get('status');
            $search = $request->get('search');
            $perPage = $request->get('per_page', 15);

            // $crops = Crop::query();
            // if ($status) {
            //     $crops->where('status', $status);
            // }
            // if ($search) {
            //     $crops->where('name', 'like', "%{$search}%");
            // }
            // $crops = $crops->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Crops retrieved successfully',
                'data' => [
                    // 'crops' => $crops->items(),
                    'pagination' => [
                        // 'total' => $crops->total(),
                        // 'per_page' => $crops->perPage(),
                        // 'current_page' => $crops->currentPage(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve crops',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single crop
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            // $crop = Crop::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Crop retrieved successfully',
                'data' => [
                    // 'crop' => $crop,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Crop not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Create new crop
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:crops,name',
                'code' => 'required|string|max:50|unique:crops,code',
                'description' => 'nullable|string',
                'season' => 'nullable|string',
                'status' => 'required|in:active,inactive',
            ]);

            // $crop = Crop::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Crop created successfully',
                'data' => [
                    // 'crop' => $crop,
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
     * Update crop
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255|unique:crops,name,' . $id,
                'description' => 'nullable|string',
                'season' => 'nullable|string',
                'status' => 'sometimes|in:active,inactive',
            ]);

            // $crop = Crop::findOrFail($id);
            // $crop->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Crop updated successfully',
                'data' => [
                    // 'crop' => $crop,
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
     * Delete crop
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            // $crop = Crop::findOrFail($id);
            // $crop->delete();

            return response()->json([
                'success' => true,
                'message' => 'Crop deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete crop',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
