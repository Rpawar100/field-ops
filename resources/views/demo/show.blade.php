@extends('layouts.admin')

@section('title', 'Demo Lot Details')

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Demo Lot Details</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('demo.index') }}">Demos</a></li>
                    <li class="breadcrumb-item active">{{ $demo->lot_no ?? $demo->id ?? 'Details' }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('demo.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row">
    {{-- Lot Info Card --}}
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body text-center pt-4">
                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                     style="width:80px;height:80px;background:rgba(67,185,178,0.12);color:var(--theme-default);font-size:2rem;">
                    <i class="fas fa-flask"></i>
                </div>
                <h5 class="fw-semibold mb-1">Lot {{ $demo->lot_no ?? $demo->id ?? '' }}</h5>
                <p class="text-muted mb-2">{{ $demo->crop->name ?? '' }} - {{ $demo->product->sku_name ?? $demo->product_name ?? '' }}</p>
                @php
                    $statusClass = match(strtolower($demo->status ?? 'pending')) {
                        'completed', 'reconciled' => 'completed',
                        'in_progress', 'distributed', 'executing' => 'in-progress',
                        default => 'pending',
                    };
                @endphp
                <span class="badge-status {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $demo->status ?? 'Pending')) }}</span>
            </div>
            <hr class="my-0">
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-hashtag me-2"></i>Lot No</span>
                        <span class="fw-medium">{{ $demo->lot_no ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-seedling me-2"></i>Crop</span>
                        <span class="fw-medium">{{ $demo->crop->name ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-box me-2"></i>Product</span>
                        <span class="fw-medium">{{ $demo->product->sku_name ?? $demo->product_name ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-sort-numeric-up me-2"></i>Total Qty</span>
                        <span class="fw-medium">{{ $demo->total_quantity ?? '-' }} {{ $demo->quantity_unit ?? '' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-warehouse me-2"></i>Available Qty</span>
                        <span class="fw-medium">{{ $demo->available_quantity ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-users me-2"></i>Target Farmers</span>
                        <span class="fw-medium">{{ $demo->target_farmers ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-sun me-2"></i>Season / Year</span>
                        <span class="fw-medium">{{ $demo->season ?? '-' }} {{ $demo->year ?? '' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted"><i class="fas fa-calendar me-2"></i>Created</span>
                        <span class="fw-medium">{{ $demo->created_at ? $demo->created_at->format('d M Y') : '-' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        {{-- Farmer Assignments --}}
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold">Farmer Assignments</h6>
            </div>
            <div class="card-body">
                @if(isset($assignments) && count($assignments) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Farmer</th>
                                    <th>Qty Given</th>
                                    <th>Assignment Date</th>
                                    <th>Current Stage</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignments as $assignment)
                                <tr>
                                    <td>{{ $assignment->farmer->name ?? '-' }}</td>
                                    <td>{{ $assignment->quantity_given ?? '-' }} {{ $assignment->quantity_unit ?? '' }}</td>
                                    <td class="text-muted">{{ $assignment->assignment_date ?? '-' }}</td>
                                    <td><span class="badge bg-light text-dark">{{ $assignment->current_stage ?? '-' }} / {{ $assignment->total_stages ?? '-' }}</span></td>
                                    <td>
                                        @php
                                            $aStatusClass = match(strtolower($assignment->status ?? 'assigned')) {
                                                'completed', 'harvested' => 'completed',
                                                'in_progress', 'sowing', 'germination', 'vegetative', 'flowering' => 'in-progress',
                                                default => 'pending',
                                            };
                                        @endphp
                                        <span class="badge-status {{ $aStatusClass }}">{{ ucfirst(str_replace('_', ' ', $assignment->status ?? 'Assigned')) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3 mb-0">
                        <i class="fas fa-users d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>
                        No farmer assignments yet.
                    </p>
                @endif
            </div>
        </div>

        {{-- Stage Activities --}}
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold">Stage History</h6>
            </div>
            <div class="card-body">
                @if(isset($stages) && count($stages) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Stage</th>
                                    <th>Actual Date</th>
                                    <th>Observations</th>
                                    <th>Recorded By</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stages as $stage)
                                <tr>
                                    <td><span class="badge bg-light text-dark">{{ $stage->stage_name ?? '-' }}</span></td>
                                    <td class="text-muted">{{ $stage->actual_date ?? '-' }}</td>
                                    <td>{{ $stage->observations ?? '-' }}</td>
                                    <td>{{ $stage->recordedByUser->name ?? '-' }}</td>
                                    <td>
                                        @php
                                            $sClass = match(strtolower($stage->status ?? 'pending')) {
                                                'completed' => 'completed',
                                                'in_progress' => 'in-progress',
                                                default => 'pending',
                                            };
                                        @endphp
                                        <span class="badge-status {{ $sClass }}">{{ ucfirst(str_replace('_', ' ', $stage->status ?? 'Pending')) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3 mb-0">
                        <i class="fas fa-history d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>
                        No stage history recorded.
                    </p>
                @endif
            </div>
        </div>

        {{-- Photos --}}
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold">Demo Photos</h6>
            </div>
            <div class="card-body">
                @if(isset($photos) && count($photos) > 0)
                    <div class="row g-3">
                        @foreach($photos as $photo)
                        <div class="col-md-3 col-6">
                            <div class="border rounded overflow-hidden">
                                <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="{{ $photo->caption ?? 'Demo Photo' }}"
                                     class="img-fluid" style="width:100%;height:150px;object-fit:cover;">
                                <div class="p-2 small text-muted">{{ $photo->photo_type ?? '' }} {{ $photo->caption ?? '' }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-3 mb-0">
                        <i class="fas fa-camera d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>
                        No photos uploaded.
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
