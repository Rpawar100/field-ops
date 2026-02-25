<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Crop;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        try {
            $items = Product::with(['category', 'brand', 'crop'])
                ->orderBy('sku_name')
                ->paginate(15);
        } catch (\Throwable $e) {
            $items = new LengthAwarePaginator([], 0, 15);
        }

        $products = $items;
        return view('masters.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): \Illuminate\Contracts\View\View
    {
        $parentData = $this->getParentData();

        return view('masters.products.create', $parentData);
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'sku_code'               => 'required|string|max:50|unique:products,sku_code',
            'sku_name'               => 'required|string|max:255',
            'display_name'           => 'nullable|string|max:255',
            'category_id'            => 'required|exists:product_categories,id',
            'product_type'           => 'required|string|max:100',
            'brand_id'               => 'nullable|exists:brands,id',
            'variety_id'             => 'nullable|exists:varieties,id',
            'crop_id'                => 'nullable|exists:crops,id',
            'pack_size'              => 'nullable|string|max:50',
            'pack_unit'              => 'nullable|string|max:50',
            'pack_quantity'          => 'nullable|integer|min:1',
            'packs_per_case'         => 'nullable|integer|min:1',
            'mrp'                    => 'required|numeric|min:0',
            'selling_price'          => 'nullable|numeric|min:0',
            'distributor_price'      => 'nullable|numeric|min:0',
            'retailer_margin_percent' => 'nullable|numeric|min:0|max:100',
            'hsn_code'               => 'nullable|string|max:20',
            'gst_rate'               => 'nullable|numeric|min:0|max:100',
            'description'            => 'nullable|string',
            'usage_instructions'     => 'nullable|string',
            'safety_instructions'    => 'nullable|string',
            'image_path'             => 'nullable|string|max:500',
            'launch_date'            => 'nullable|date',
            'status'                 => 'required|string|in:active,inactive',
        ]);

        try {
            Product::create($validated);

            return redirect()->route('products.index')->with('success', 'Product created successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product): \Illuminate\Contracts\View\View
    {
        try {
            $product->load(['category', 'brand', 'variety', 'crop']);
        } catch (\Throwable $e) {
            // Continue with base product object
        }

        return view('masters.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): \Illuminate\Contracts\View\View
    {
        $parentData = $this->getParentData();

        return view('masters.products.edit', array_merge(['product' => $product], $parentData));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'sku_code'               => 'required|string|max:50|unique:products,sku_code,' . $product->id,
            'sku_name'               => 'required|string|max:255',
            'display_name'           => 'nullable|string|max:255',
            'category_id'            => 'required|exists:product_categories,id',
            'product_type'           => 'required|string|max:100',
            'brand_id'               => 'nullable|exists:brands,id',
            'variety_id'             => 'nullable|exists:varieties,id',
            'crop_id'                => 'nullable|exists:crops,id',
            'pack_size'              => 'nullable|string|max:50',
            'pack_unit'              => 'nullable|string|max:50',
            'pack_quantity'          => 'nullable|integer|min:1',
            'packs_per_case'         => 'nullable|integer|min:1',
            'mrp'                    => 'required|numeric|min:0',
            'selling_price'          => 'nullable|numeric|min:0',
            'distributor_price'      => 'nullable|numeric|min:0',
            'retailer_margin_percent' => 'nullable|numeric|min:0|max:100',
            'hsn_code'               => 'nullable|string|max:20',
            'gst_rate'               => 'nullable|numeric|min:0|max:100',
            'description'            => 'nullable|string',
            'usage_instructions'     => 'nullable|string',
            'safety_instructions'    => 'nullable|string',
            'image_path'             => 'nullable|string|max:500',
            'launch_date'            => 'nullable|date',
            'status'                 => 'required|string|in:active,inactive',
        ]);

        try {
            $product->update($validated);

            return redirect()->route('products.index')->with('success', 'Product updated successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified product (soft delete).
     */
    public function destroy(Product $product): \Illuminate\Http\RedirectResponse
    {
        try {
            $product->delete();

            return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('products.index')->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }

    /**
     * Load parent data for dropdowns.
     */
    private function getParentData(): array
    {
        try {
            $crops      = Crop::where('status', 'active')->orderBy('name')->get(['id', 'name']);
            $categories = \App\Models\ProductCategory::where('status', 'active')->orderBy('name')->get(['id', 'name']);
            $brands     = \App\Models\Brand::where('status', 'active')->orderBy('name')->get(['id', 'name']);
            $varieties  = \App\Models\Variety::where('status', 'active')->orderBy('name')->get(['id', 'name', 'crop_id']);
        } catch (\Throwable $e) {
            $crops = $categories = $brands = $varieties = collect();
        }

        return compact('crops', 'categories', 'brands', 'varieties');
    }
}
