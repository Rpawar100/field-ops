<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * List products with filters
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $filters = $request->only(['crop_id', 'category', 'status', 'search', 'page', 'per_page']);
            $perPage = $filters['per_page'] ?? 15;

            // $products = Product::query();
            // if ($filters['crop_id'] ?? null) {
            //     $products->where('crop_id', $filters['crop_id']);
            // }
            // if ($filters['category'] ?? null) {
            //     $products->where('category', $filters['category']);
            // }
            // if ($filters['status'] ?? null) {
            //     $products->where('status', $filters['status']);
            // }
            // if ($filters['search'] ?? null) {
            //     $products->where('name', 'like', "%{$filters['search']}%");
            // }
            // $products = $products->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Products retrieved successfully',
                'data' => [
                    // 'products' => $products->items(),
                    'pagination' => [
                        // 'total' => $products->total(),
                        // 'per_page' => $products->perPage(),
                        // 'current_page' => $products->currentPage(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single product
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            // $product = Product::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Product retrieved successfully',
                'data' => [
                    // 'product' => $product,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Create new product/SKU
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'sku' => 'required|string|max:50|unique:products,sku',
                'crop_id' => 'required|exists:crops,id',
                'category' => 'required|string|max:255',
                'description' => 'nullable|string',
                'mrp' => 'required|numeric|min:0',
                'status' => 'required|in:active,inactive',
            ]);

            // $product = Product::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => [
                    // 'product' => $product,
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
     * Update product details
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
                'mrp' => 'sometimes|numeric|min:0',
                'status' => 'sometimes|in:active,inactive',
            ]);

            // $product = Product::findOrFail($id);
            // $product->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => [
                    // 'product' => $product,
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
     * Delete product
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            // $product = Product::findOrFail($id);
            // $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get SKU history
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHistory($id)
    {
        try {
            // $product = Product::findOrFail($id);
            // $history = $product->history()->paginate(15);

            return response()->json([
                'success' => true,
                'message' => 'Product history retrieved successfully',
                'data' => [
                    // 'history' => $history->items(),
                    'pagination' => [
                        // 'total' => $history->total(),
                        // 'current_page' => $history->currentPage(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve history',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
