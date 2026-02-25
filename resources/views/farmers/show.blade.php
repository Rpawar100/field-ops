@extends('layouts.admin')

@section('title', 'Farmer Profile')

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Farmer Profile</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('farmers.index') }}">Farmers</a></li>
                    <li class="breadcrumb-item active">{{ $farmer->name ?? '' }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('farmers.edit', $farmer) }}" class="btn btn-outline-theme me-1">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <a href="{{ route('farmers.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>
</div>

<div class="row">
    {{-- Farmer Info Card --}}
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body text-center pt-4">
                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                     style="width:80px;height:80px;background:rgba(25,135,84,0.12);color:#198754;font-size:2rem;">
                    <i class="fas fa-leaf"></i>
                </div>
                <h5 class="fw-semibold mb-1">{{ $farmer->name ?? '' }}</h5>
                @if($farmer->father_name)
                    <p class="text-muted mb-1">S/o {{ $farmer->father_name }}</p>
                @endif
                <p class="text-muted mb-2">{{ $farmer->village->name ?? '' }}</p>
                @php
                    $statusClass = match(strtolower($farmer->status ?? 'active')) {
                        'active' => 'completed',
                        'inactive' => 'pending',
                        default => 'pending',
                    };
                @endphp
                <span class="badge-status {{ $statusClass }}">{{ ucfirst($farmer->status ?? 'Active') }}</span>
                @if($farmer->verification_status)
                    @php
                        $verClass = match(strtolower($farmer->verification_status)) {
                            'verified' => 'completed',
                            'pending' => 'pending',
                            'rejected' => 'in-progress',
                            default => 'pending',
                        };
                    @endphp
                    <span class="badge-status {{ $verClass }} ms-1">{{ ucfirst($farmer->verification_status) }}</span>
                @endif
            </div>
            <hr class="my-0">
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-id-card me-2"></i>Farmer Code</span>
                        <span class="fw-medium">{{ $farmer->farmer_code ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-phone me-2"></i>Mobile</span>
                        <span class="fw-medium">{{ $farmer->mobile ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-venus-mars me-2"></i>Gender</span>
                        <span class="fw-medium">{{ ucfirst($farmer->gender ?? '-') }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-birthday-cake me-2"></i>Date of Birth</span>
                        <span class="fw-medium">{{ $farmer->date_of_birth ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-tag me-2"></i>Farmer Type</span>
                        @php $typeLabels = ['pda' => 'PDA', 'demo' => 'Demo', 'user' => 'User', 'non_user' => 'Non-User']; @endphp
                        <span class="fw-medium">{{ $typeLabels[$farmer->farmer_type] ?? ucfirst($farmer->farmer_type ?? '-') }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-map-pin me-2"></i>Village</span>
                        <span class="fw-medium">{{ $farmer->village->name ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-map me-2"></i>Taluka</span>
                        <span class="fw-medium">{{ $farmer->village->taluka->name ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-globe me-2"></i>District</span>
                        <span class="fw-medium">{{ $farmer->village->taluka->district->name ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-route me-2"></i>Beat</span>
                        <span class="fw-medium">{{ $farmer->beat->name ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-ruler-combined me-2"></i>Total Landholding</span>
                        <span class="fw-medium">{{ $farmer->total_landholding_acres ?? '-' }} acres</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-seedling me-2"></i>Cultivable Land</span>
                        <span class="fw-medium">{{ $farmer->cultivable_land_acres ?? '-' }} acres</span>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted"><i class="fas fa-calendar me-2"></i>Registered</span>
                        <span class="fw-medium">{{ $farmer->created_at ? $farmer->created_at->format('d M Y') : '-' }}</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Crops Card --}}
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold">Crops Grown</h6>
            </div>
            <div class="card-body">
                @if(isset($farmer->farmerCrops) && $farmer->farmerCrops->count() > 0)
                    @foreach($farmer->farmerCrops as $farmerCrop)
                        <span class="badge bg-light text-dark me-1 mb-1 px-3 py-2">
                            <i class="fas fa-seedling me-1 text-theme"></i> {{ $farmerCrop->crop->name ?? 'Unknown' }}
                        </span>
                    @endforeach
                @else
                    <p class="text-muted text-center mb-0">No crops recorded.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        {{-- Linked Activities --}}
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-semibold">Linked Activities</h6>
            </div>
            <div class="card-body">
                @if(isset($activities) && $activities->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Execution Date</th>
                                    <th>Status</th>
                                    <th>Village</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activities as $activity)
                                <tr>
                                    <td><a href="{{ route('activities.show', $activity) }}"><span class="badge bg-light text-dark">{{ $activity->activityType->name ?? '-' }}</span></a></td>
                                    <td class="text-muted">{{ $activity->execution_date ?? '-' }}</td>
                                    <td>
                                        @php
                                            $aStatusClass = match(strtolower($activity->status ?? 'draft')) {
                                                'completed' => 'completed',
                                                'submitted' => 'in-progress',
                                                'cancelled' => 'in-progress',
                                                default => 'pending',
                                            };
                                        @endphp
                                        <span class="badge-status {{ $aStatusClass }}">{{ ucfirst(str_replace('_', ' ', $activity->status ?? 'Draft')) }}</span>
                                    </td>
                                    <td>{{ $activity->village->name ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3 mb-0">
                        <i class="fas fa-clipboard d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>
                        No activities linked to this farmer.
                    </p>
                @endif
            </div>
        </div>

        {{-- Linked Retailers --}}
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-semibold">Linked Retailers</h6>
            </div>
            <div class="card-body">
                @if(isset($retailers) && $retailers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Shop Name</th>
                                    <th>Owner</th>
                                    <th>Mobile</th>
                                    <th>Business Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($retailers as $retailer)
                                <tr>
                                    <td><a href="{{ route('retailers.show', $retailer) }}">{{ $retailer->shop_name ?? '-' }}</a></td>
                                    <td>{{ $retailer->owner_name ?? '-' }}</td>
                                    <td>{{ $retailer->mobile ?? '-' }}</td>
                                    <td><span class="badge bg-light text-dark">{{ ucfirst(str_replace('_', ' ', $retailer->business_type ?? '-')) }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3 mb-0">
                        <i class="fas fa-store d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>
                        No retailers linked to this farmer.
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
