@extends('layouts.admin')
@section('title', 'Crop Details')
@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Crop Details</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('crops.index') }}">Crops</a></li>
                    <li class="breadcrumb-item active">{{ $crop->name ?? '' }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('crops.edit', $crop) }}" class="btn btn-outline-theme me-1"><i class="fas fa-edit me-1"></i> Edit</a>
            <a href="{{ route('crops.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header"><h6 class="mb-0 fw-semibold">Crop Information</h6></div>
    <div class="card-body">
        <ul class="list-unstyled mb-0">
            <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Code</span><span class="badge bg-light text-dark">{{ $crop->code ?? '-' }}</span></li>
            <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Name</span><span class="fw-medium">{{ $crop->name ?? '-' }}</span></li>
            <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Local Name</span><span class="fw-medium">{{ $crop->local_name ?? '-' }}</span></li>
            <li class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Crop Type</span>
                @php
                    $typeColors = ['kharif' => 'bg-success', 'rabi' => 'bg-info', 'zaid' => 'bg-warning text-dark', 'perennial' => 'bg-primary'];
                    $badgeClass = $typeColors[$crop->crop_type ?? ''] ?? 'bg-secondary';
                @endphp
                <span class="badge {{ $badgeClass }}">{{ ucfirst($crop->crop_type ?? '-') }}</span>
            </li>
            <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Typical Duration</span><span class="fw-medium">{{ $crop->typical_duration_days ? $crop->typical_duration_days . ' days' : '-' }}</span></li>
            <li class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Status</span>
                @if(($crop->status ?? 'active') === 'active')
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-secondary">Inactive</span>
                @endif
            </li>
            <li class="py-2"><span class="text-muted d-block mb-1">Description</span><span class="fw-medium">{{ $crop->description ?? '-' }}</span></li>
        </ul>
    </div>
</div>
@endsection
