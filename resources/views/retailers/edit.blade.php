@extends('layouts.admin')

@section('title', 'Edit Retailer')

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Edit Retailer</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('retailers.index') }}">Retailers</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold">Edit Retailer: {{ $retailer->shop_name ?? '' }}</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('retailers.update', $retailer) }}">
            @csrf
            @method('PUT')
            @include('retailers._form')
            <div class="mt-4">
                <button type="submit" class="btn btn-theme">
                    <i class="fas fa-save me-1"></i> Update Retailer
                </button>
                <a href="{{ route('retailers.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
