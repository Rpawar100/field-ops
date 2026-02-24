@extends('layouts.admin')
@section('title', 'Product Management')
@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endsection
@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Product Management</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Products</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-theme"><i class="fas fa-plus me-1"></i> Add Product</a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="dataTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>SKU Code</th>
                        <th>SKU Name</th>
                        <th>Product Type</th>
                        <th>Brand</th>
                        <th>MRP</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="badge bg-light text-dark">{{ $product->sku_code ?? '-' }}</span></td>
                        <td><span class="fw-medium">{{ $product->sku_name }}</span></td>
                        <td>
                            @php
                                $ptColors = ['seed' => 'bg-success', 'pesticide' => 'bg-danger', 'bio_product' => 'bg-info', 'fertilizer' => 'bg-warning text-dark', 'equipment' => 'bg-primary', 'other' => 'bg-secondary'];
                                $ptBadge = $ptColors[$product->product_type ?? ''] ?? 'bg-secondary';
                            @endphp
                            <span class="badge {{ $ptBadge }}">{{ ucwords(str_replace('_', ' ', $product->product_type ?? '-')) }}</span>
                        </td>
                        <td>{{ $product->brand->name ?? '-' }}</td>
                        <td class="text-nowrap">{{ $product->mrp ? "\u{20B9}" . number_format($product->mrp, 2) : '-' }}</td>
                        <td>
                            @if(($product->status ?? 'active') === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-info me-1" title="View"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this product?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>No products found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>$(document).ready(function() { $(''#dataTable'').DataTable({
        paging: false,
        info: false,
        searching: true,
        responsive: true,
        order: [[1, 'asc']],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search records..."
        }
    }); });</script>
@endsection
