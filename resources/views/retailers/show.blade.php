@extends('layouts.admin')

@section('title', 'Retailer Details')

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Retailer Details</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('retailers.index') }}">Retailers</a></li>
                    <li class="breadcrumb-item active">{{ $retailer->shop_name ?? '' }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('retailers.edit', $retailer) }}" class="btn btn-outline-theme me-1">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <a href="{{ route('retailers.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>
</div>

<div class="row">
    {{-- Retailer Info --}}
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body text-center pt-4">
                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                     style="width:80px;height:80px;background:rgba(194,128,210,0.12);color:var(--theme-secondary);font-size:2rem;">
                    <i class="fas fa-store"></i>
                </div>
                <h5 class="fw-semibold mb-1">{{ $retailer->shop_name ?? '' }}</h5>
                <p class="text-muted mb-2">{{ ucfirst(str_replace('_', ' ', $retailer->business_type ?? '')) }}</p>
                @php
                    $kycClass = match(strtolower($retailer->kyc_status ?? 'pending')) {
                        'verified', 'approved' => 'completed',
                        'pending' => 'pending',
                        'rejected' => 'in-progress',
                        default => 'pending',
                    };
                @endphp
                <span class="badge-status {{ $kycClass }}">KYC: {{ ucfirst($retailer->kyc_status ?? 'Pending') }}</span>
            </div>
            <hr class="my-0">
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-id-card me-2"></i>Code</span>
                        <span class="fw-medium">{{ $retailer->retailer_code ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-user me-2"></i>Owner</span>
                        <span class="fw-medium">{{ $retailer->owner_name ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-building me-2"></i>Firm</span>
                        <span class="fw-medium">{{ $retailer->firm_name ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-phone me-2"></i>Mobile</span>
                        <span class="fw-medium">{{ $retailer->mobile ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-tag me-2"></i>Business Type</span>
                        <span class="fw-medium">{{ ucfirst(str_replace('_', ' ', $retailer->business_type ?? '-')) }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-star me-2"></i>Grade</span>
                        <span class="fw-medium">{{ $retailer->retailer_grade ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-map-pin me-2"></i>Village</span>
                        <span class="fw-medium">{{ $retailer->village->name ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-route me-2"></i>Beat</span>
                        <span class="fw-medium">{{ $retailer->beat->name ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-file-invoice me-2"></i>GST</span>
                        <span class="fw-medium">{{ $retailer->gst_number ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted"><i class="fas fa-calendar me-2"></i>Registered</span>
                        <span class="fw-medium">{{ $retailer->created_at ? $retailer->created_at->format('d M Y') : '-' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        {{-- Address & KYC Details --}}
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold">Address & KYC Details</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="text-muted small">Full Address</label>
                        <p class="fw-medium">{{ $retailer->address ?? 'No address recorded.' }}</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <label class="text-muted small">KYC Status</label>
                        <p class="fw-medium">
                            <span class="badge-status {{ $kycClass }}">{{ ucfirst($retailer->kyc_status ?? 'Pending') }}</span>
                        </p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">GST Number</label>
                        <p class="fw-medium">{{ $retailer->gst_number ?? 'Not provided' }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">PAN Number</label>
                        <p class="fw-medium">{{ $retailer->pan_number ?? 'Not provided' }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Verified At</label>
                        <p class="fw-medium">{{ $retailer->verified_at ?? 'Not yet verified' }}</p>
                    </div>
                </div>

                @if(strtolower($retailer->kyc_status ?? 'pending') === 'pending')
                <hr>
                <form method="POST" action="{{ route('retailers.kyc-update', $retailer) }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="kyc_status" value="verified">
                    <button type="submit" class="btn btn-sm btn-theme me-1"
                            onclick="return confirm('Approve KYC for this retailer?');">
                        <i class="fas fa-check me-1"></i> Approve KYC
                    </button>
                </form>
                <form method="POST" action="{{ route('retailers.kyc-update', $retailer) }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="kyc_status" value="rejected">
                    <button type="submit" class="btn btn-sm btn-outline-danger"
                            onclick="return confirm('Reject KYC for this retailer?');">
                        <i class="fas fa-times me-1"></i> Reject KYC
                    </button>
                </form>
                @endif
            </div>
        </div>

        {{-- Linked Farmers --}}
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold">Linked Farmers</h6>
            </div>
            <div class="card-body">
                @if(isset($farmers) && $farmers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Farmer Name</th>
                                    <th>Mobile</th>
                                    <th>Village</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($farmers as $farmer)
                                <tr>
                                    <td><a href="{{ route('farmers.show', $farmer) }}">{{ $farmer->name }}</a></td>
                                    <td>{{ $farmer->mobile ?? '-' }}</td>
                                    <td>{{ $farmer->village->name ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3 mb-0">
                        <i class="fas fa-leaf d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>
                        No farmers linked to this retailer.
                    </p>
                @endif
            </div>
        </div>

        {{-- Linked Distributors --}}
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold">Linked Distributors</h6>
            </div>
            <div class="card-body">
                @if(isset($retailer->distributors) && $retailer->distributors->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Type</th>
                                    <th>Primary</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($retailer->distributors as $distributor)
                                <tr>
                                    <td>{{ $distributor->name ?? '-' }}</td>
                                    <td>{{ $distributor->mobile ?? '-' }}</td>
                                    <td><span class="badge bg-light text-dark">{{ ucfirst(str_replace('_', ' ', $distributor->type ?? '-')) }}</span></td>
                                    <td>{{ $distributor->pivot->is_primary ? 'Yes' : 'No' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3 mb-0">
                        <i class="fas fa-truck d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>
                        No distributors linked to this retailer.
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
