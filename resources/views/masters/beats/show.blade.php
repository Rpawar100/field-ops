@extends('layouts.admin')
@section('title', 'Beat Details')
@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Beat Details</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('beats.index') }}">Beats</a></li>
                    <li class="breadcrumb-item active">{{ $beat->name ?? '' }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('beats.edit', $beat) }}" class="btn btn-outline-theme me-1"><i class="fas fa-edit me-1"></i> Edit</a>
            <a href="{{ route('beats.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
        </div>
    </div>
</div>

<div class="row">
    {{-- Beat Info Card --}}
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Beat Information</h6></div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Beat Code</span><span class="badge bg-light text-dark">{{ $beat->beat_code ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Name</span><span class="fw-medium">{{ $beat->name ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Headquarters</span><span class="fw-medium">{{ $beat->zrthHierarchy->name ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Primary Village</span><span class="fw-medium">{{ $beat->village->name ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Beat Day</span><span class="fw-medium">{{ ucfirst($beat->beat_day ?? '-') }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Visit Frequency</span><span class="fw-medium">{{ $beat->visit_frequency_days ? $beat->visit_frequency_days . ' days' : '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Status</span>
                        @if(($beat->status ?? 'active') === 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </li>
                    <li class="py-2 border-bottom"><span class="text-muted d-block mb-1">Description</span><span class="fw-medium">{{ $beat->description ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Total Farmers</span><span class="fw-medium">{{ $beat->total_farmers ?? 0 }}</span></li>
                    <li class="d-flex justify-content-between py-2"><span class="text-muted">Total Retailers</span><span class="fw-medium">{{ $beat->total_retailers ?? 0 }}</span></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        {{-- Assigned Users --}}
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Assigned Users</h6></div>
            <div class="card-body">
                @if(isset($beat->userAssignments) && $beat->userAssignments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead><tr><th>#</th><th>User</th><th>Primary</th><th>Assigned From</th><th>Status</th></tr></thead>
                            <tbody>
                                @foreach($beat->userAssignments as $assignment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $assignment->user->name ?? '-' }}</td>
                                    <td>
                                        @if($assignment->is_primary)
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                    <td>{{ $assignment->assigned_from ? \Carbon\Carbon::parse($assignment->assigned_from)->format('d M Y') : '-' }}</td>
                                    <td>
                                        @if(($assignment->status ?? 'active') === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3 mb-0">No users assigned to this beat.</p>
                @endif
            </div>
        </div>

        {{-- Farmers in Beat --}}
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Farmers in this Beat</h6></div>
            <div class="card-body">
                @if(isset($beat->beatFarmers) && $beat->beatFarmers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead><tr><th>#</th><th>Farmer Code</th><th>Name</th><th>Mobile</th><th>Primary</th><th>Status</th></tr></thead>
                            <tbody>
                                @foreach($beat->beatFarmers as $bf)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><span class="badge bg-light text-dark">{{ $bf->farmer->farmer_code ?? '-' }}</span></td>
                                    <td>{{ $bf->farmer->name ?? '-' }}</td>
                                    <td>{{ $bf->farmer->mobile ?? '-' }}</td>
                                    <td>
                                        @if($bf->is_primary)
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(($bf->status ?? 'active') === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3 mb-0">No farmers assigned to this beat.</p>
                @endif
            </div>
        </div>

        {{-- Retailers in Beat --}}
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Retailers in this Beat</h6></div>
            <div class="card-body">
                @if(isset($beat->beatRetailers) && $beat->beatRetailers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead><tr><th>#</th><th>Retailer Code</th><th>Shop Name</th><th>Mobile</th><th>Primary</th><th>Status</th></tr></thead>
                            <tbody>
                                @foreach($beat->beatRetailers as $br)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><span class="badge bg-light text-dark">{{ $br->retailer->retailer_code ?? '-' }}</span></td>
                                    <td>{{ $br->retailer->shop_name ?? '-' }}</td>
                                    <td>{{ $br->retailer->mobile ?? '-' }}</td>
                                    <td>
                                        @if($br->is_primary)
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(($br->status ?? 'active') === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3 mb-0">No retailers assigned to this beat.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
