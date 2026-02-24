@extends('layouts.admin')
@section('title', 'Product Details')
@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Product Details</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                    <li class="breadcrumb-item active">{{ $product->sku_name ?? '' }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-theme me-1"><i class="fas fa-edit me-1"></i> Edit</a>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
        </div>
    </div>
</div>

<div class="row">
    {{-- Product Info --}}
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Basic Information</h6></div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">SKU Code</span><span class="badge bg-light text-dark">{{ $product->sku_code ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">SKU Name</span><span class="fw-medium">{{ $product->sku_name ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Display Name</span><span class="fw-medium">{{ $product->display_name ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Category</span><span class="fw-medium">{{ $product->category->name ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Product Type</span>
                        @php
                            $ptColors = ['seed' => 'bg-success', 'pesticide' => 'bg-danger', 'bio_product' => 'bg-info', 'fertilizer' => 'bg-warning text-dark', 'equipment' => 'bg-primary', 'other' => 'bg-secondary'];
                            $ptBadge = $ptColors[$product->product_type ?? ''] ?? 'bg-secondary';
                        @endphp
                        <span class="badge {{ $ptBadge }}">{{ ucwords(str_replace('_', ' ', $product->product_type ?? '-')) }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Brand</span><span class="fw-medium">{{ $product->brand->name ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Variety</span><span class="fw-medium">{{ $product->variety->name ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Crop</span><span class="fw-medium">{{ $product->crop->name ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted">Status</span>
                        @if(($product->status ?? 'active') === 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Pack + Pricing --}}
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Packaging & Pricing</h6></div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Pack Size</span><span class="fw-medium">{{ $product->pack_size ?? '-' }} {{ $product->pack_unit ?? '' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Pack Quantity</span><span class="fw-medium">{{ $product->pack_quantity ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">MRP</span><span class="fw-medium text-success">{{ $product->mrp ? "\u{20B9}" . number_format($product->mrp, 2) : '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Selling Price</span><span class="fw-medium">{{ $product->selling_price ? "\u{20B9}" . number_format($product->selling_price, 2) : '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Distributor Price</span><span class="fw-medium">{{ $product->distributor_price ? "\u{20B9}" . number_format($product->distributor_price, 2) : '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">HSN Code</span><span class="fw-medium">{{ $product->hsn_code ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2"><span class="text-muted">GST Rate</span><span class="fw-medium">{{ $product->gst_rate ? $product->gst_rate . '%' : '-' }}</span></li>
                </ul>
            </div>
        </div>

        @if($product->description)
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Description</h6></div>
            <div class="card-body">
                <p class="mb-0">{{ $product->description }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
