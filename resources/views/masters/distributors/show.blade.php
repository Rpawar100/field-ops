@extends('layouts.admin')
@section('title', 'Distributor Details')
@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Distributor Details</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('distributors.index') }}">Distributors</a></li>
                    <li class="breadcrumb-item active">{{ $distributor->name ?? '' }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('distributors.edit', $distributor) }}" class="btn btn-outline-theme me-1"><i class="fas fa-edit me-1"></i> Edit</a>
            <a href="{{ route('distributors.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
        </div>
    </div>
</div>

<div class="row">
    {{-- Basic Info --}}
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Basic Information</h6></div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Code</span><span class="badge bg-light text-dark">{{ $distributor->distributor_code ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Name</span><span class="fw-medium">{{ $distributor->name ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Contact Person</span><span class="fw-medium">{{ $distributor->contact_person ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Mobile</span><span class="fw-medium">{{ $distributor->mobile ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Email</span><span class="fw-medium">{{ $distributor->email ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Type</span>
                        <span class="badge bg-info">{{ ucwords(str_replace('_', ' ', $distributor->type ?? '-')) }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted">Status</span>
                        @if(($distributor->status ?? 'active') === 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Address & Business --}}
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Address & Business Details</h6></div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="py-2 border-bottom"><span class="text-muted d-block mb-1">Address</span><span class="fw-medium">{{ $distributor->address ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Village</span><span class="fw-medium">{{ $distributor->village->name ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Pincode</span><span class="fw-medium">{{ $distributor->pincode ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">ZRTH</span><span class="fw-medium">{{ $distributor->zrthHierarchy->name ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">GST Number</span><span class="fw-medium">{{ $distributor->gst_number ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">PAN Number</span><span class="fw-medium">{{ $distributor->pan_number ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Credit Limit</span><span class="fw-medium">{{ $distributor->credit_limit ? "\u{20B9}" . number_format($distributor->credit_limit, 2) : '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2"><span class="text-muted">Credit Days</span><span class="fw-medium">{{ $distributor->credit_days ?? '-' }}</span></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
