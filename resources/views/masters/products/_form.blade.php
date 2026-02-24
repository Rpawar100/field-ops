{{-- Product Form Partial — matches products table schema --}}
<div class="row g-3">
    {{-- Row 1: SKU Code + SKU Name --}}
    <div class="col-md-4">
        <label for="sku_code" class="form-label">SKU Code <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('sku_code') is-invalid @enderror" id="sku_code" name="sku_code" value="{{ old('sku_code', $product->sku_code ?? '') }}" placeholder="Enter SKU code" required>
        @error('sku_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label for="sku_name" class="form-label">SKU Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('sku_name') is-invalid @enderror" id="sku_name" name="sku_name" value="{{ old('sku_name', $product->sku_name ?? '') }}" placeholder="Enter SKU name" required>
        @error('sku_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label for="display_name" class="form-label">Display Name</label>
        <input type="text" class="form-control @error('display_name') is-invalid @enderror" id="display_name" name="display_name" value="{{ old('display_name', $product->display_name ?? '') }}" placeholder="Enter display name">
        @error('display_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Row 2: Category + Product Type --}}
    <div class="col-md-6">
        <label for="category_id" class="form-label">Category</label>
        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
            <option value="">-- Select Category --</option>
            @foreach($categories ?? [] as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="product_type" class="form-label">Product Type <span class="text-danger">*</span></label>
        <select class="form-select @error('product_type') is-invalid @enderror" id="product_type" name="product_type" required>
            <option value="">-- Select Product Type --</option>
            @php $productTypes = ['seed' => 'Seed', 'pesticide' => 'Pesticide', 'bio_product' => 'Bio Product', 'fertilizer' => 'Fertilizer', 'equipment' => 'Equipment', 'other' => 'Other']; @endphp
            @foreach($productTypes as $value => $label)
                <option value="{{ $value }}" {{ old('product_type', $product->product_type ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('product_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Row 3: Brand + Variety + Crop --}}
    <div class="col-md-4">
        <label for="brand_id" class="form-label">Brand</label>
        <select class="form-select @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id">
            <option value="">-- Select Brand --</option>
            @foreach($brands ?? [] as $brand)
                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
            @endforeach
        </select>
        @error('brand_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label for="variety_id" class="form-label">Variety</label>
        <select class="form-select @error('variety_id') is-invalid @enderror" id="variety_id" name="variety_id">
            <option value="">-- Select Variety --</option>
            @foreach($varieties ?? [] as $variety)
                <option value="{{ $variety->id }}" {{ old('variety_id', $product->variety_id ?? '') == $variety->id ? 'selected' : '' }}>{{ $variety->name }}</option>
            @endforeach
        </select>
        @error('variety_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label for="crop_id" class="form-label">Crop</label>
        <select class="form-select @error('crop_id') is-invalid @enderror" id="crop_id" name="crop_id">
            <option value="">-- Select Crop --</option>
            @foreach($crops ?? [] as $crop)
                <option value="{{ $crop->id }}" {{ old('crop_id', $product->crop_id ?? '') == $crop->id ? 'selected' : '' }}>{{ $crop->name }}</option>
            @endforeach
        </select>
        @error('crop_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Row 4: Pack details --}}
    <div class="col-md-4">
        <label for="pack_size" class="form-label">Pack Size</label>
        <input type="text" class="form-control @error('pack_size') is-invalid @enderror" id="pack_size" name="pack_size" value="{{ old('pack_size', $product->pack_size ?? '') }}" placeholder="e.g. 500">
        @error('pack_size') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label for="pack_unit" class="form-label">Pack Unit</label>
        <input type="text" class="form-control @error('pack_unit') is-invalid @enderror" id="pack_unit" name="pack_unit" value="{{ old('pack_unit', $product->pack_unit ?? '') }}" placeholder="e.g. gm, ml, kg, L">
        @error('pack_unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label for="pack_quantity" class="form-label">Pack Quantity</label>
        <input type="number" class="form-control @error('pack_quantity') is-invalid @enderror" id="pack_quantity" name="pack_quantity" value="{{ old('pack_quantity', $product->pack_quantity ?? '') }}" placeholder="e.g. 1" min="1">
        @error('pack_quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Row 5: Pricing --}}
    <div class="col-md-4">
        <label for="mrp" class="form-label">MRP (INR) <span class="text-danger">*</span></label>
        <input type="number" step="0.01" class="form-control @error('mrp') is-invalid @enderror" id="mrp" name="mrp" value="{{ old('mrp', $product->mrp ?? '') }}" placeholder="Enter MRP" required>
        @error('mrp') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label for="selling_price" class="form-label">Selling Price (INR)</label>
        <input type="number" step="0.01" class="form-control @error('selling_price') is-invalid @enderror" id="selling_price" name="selling_price" value="{{ old('selling_price', $product->selling_price ?? '') }}" placeholder="Enter selling price">
        @error('selling_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label for="distributor_price" class="form-label">Distributor Price (INR)</label>
        <input type="number" step="0.01" class="form-control @error('distributor_price') is-invalid @enderror" id="distributor_price" name="distributor_price" value="{{ old('distributor_price', $product->distributor_price ?? '') }}" placeholder="Enter distributor price">
        @error('distributor_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Row 6: Tax --}}
    <div class="col-md-6">
        <label for="hsn_code" class="form-label">HSN Code</label>
        <input type="text" class="form-control @error('hsn_code') is-invalid @enderror" id="hsn_code" name="hsn_code" value="{{ old('hsn_code', $product->hsn_code ?? '') }}" placeholder="Enter HSN code">
        @error('hsn_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="gst_rate" class="form-label">GST Rate (%)</label>
        <input type="number" step="0.01" class="form-control @error('gst_rate') is-invalid @enderror" id="gst_rate" name="gst_rate" value="{{ old('gst_rate', $product->gst_rate ?? '') }}" placeholder="e.g. 18.00">
        @error('gst_rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Row 7: Description + Status --}}
    <div class="col-md-12">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="2" placeholder="Enter product description">{{ old('description', $product->description ?? '') }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
            <option value="active" {{ old('status', $product->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $product->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
