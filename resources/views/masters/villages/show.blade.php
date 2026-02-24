@extends('layouts.admin')
@section('title', 'Village Details')
@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Village Details</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('villages.index') }}">Villages</a></li>
                    <li class="breadcrumb-item active">{{ $village->name ?? '' }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('villages.edit', $village) }}" class="btn btn-outline-theme me-1"><i class="fas fa-edit me-1"></i> Edit</a>
            <a href="{{ route('villages.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header"><h6 class="mb-0 fw-semibold">Village Information</h6></div>
    <div class="card-body">
        <ul class="list-unstyled mb-0">
            <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Code</span><span class="badge bg-light text-dark">{{ $village->code ?? '-' }}</span></li>
            <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Name</span><span class="fw-medium">{{ $village->name ?? '-' }}</span></li>
            <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Taluka</span><span class="fw-medium">{{ $village->taluka->name ?? '-' }}</span></li>
            <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">District</span><span class="fw-medium">{{ $village->taluka->district->name ?? '-' }}</span></li>
            <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">State</span><span class="fw-medium">{{ $village->taluka->district->state->name ?? '-' }}</span></li>
            <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Pincode</span><span class="fw-medium">{{ $village->pincode ?? '-' }}</span></li>
            <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Latitude</span><span class="fw-medium">{{ $village->latitude ?? '-' }}</span></li>
            <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Longitude</span><span class="fw-medium">{{ $village->longitude ?? '-' }}</span></li>
            <li class="d-flex justify-content-between py-2">
                <span class="text-muted">Status</span>
                @if(($village->status ?? 'active') === 'active')
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-secondary">Inactive</span>
                @endif
            </li>
        </ul>
    </div>
</div>
@endsection
