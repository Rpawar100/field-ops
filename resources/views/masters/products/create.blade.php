@extends('layouts.admin')
@section('title', 'Add Product')
@section('content')
<div class="page-title-area">
    <div>
        <h3>Add Product</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                <li class="breadcrumb-item active">Add</li>
            </ol>
        </nav>
    </div>
</div>
<div class="card">
    <div class="card-header"><h6 class="mb-0 fw-semibold">Create New Product</h6></div>
    <div class="card-body">
        <form method="POST" action="{{ route('products.store') }}">
            @csrf
            @include('masters.products._form')
            <div class="mt-4">
                <button type="submit" class="btn btn-theme"><i class="fas fa-save me-1"></i> Save</button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
