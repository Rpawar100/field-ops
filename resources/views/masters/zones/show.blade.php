@extends('layouts.admin')

@section('title', 'Zone Details')

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Zone Details</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('zones.index') }}">Zones</a></li>
                    <li class="breadcrumb-item active">{{ $zone->name ?? '' }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('zones.edit', $zone) }}" class="btn btn-outline-theme me-1"><i class="fas fa-edit me-1"></i> Edit</a>
            <a href="{{ route('zones.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Name</span>
                        <span class="fw-medium">{{ $zone->name ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Code</span>
                        <span class="fw-medium">{{ $zone->code ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Level</span>
                        <span class="fw-medium"><span class="badge bg-light text-dark">{{ ucfirst($zone->level ?? 'zone') }}</span></span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Status</span>
                        @php $statusClass = ($zone->status ?? 'active') === 'active' ? 'completed' : 'pending'; @endphp
                        <span class="badge-status {{ $statusClass }}">{{ ucfirst($zone->status ?? 'Active') }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Regions</span>
                        <span class="fw-medium">{{ $zone->children ? $zone->children->count() : 0 }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted">Description</span>
                        <span class="fw-medium">{{ $zone->description ?? '-' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Regions in this Zone</h6></div>
            <div class="card-body">
                @if(isset($zone->children) && $zone->children->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead><tr><th>#</th><th>Code</th><th>Region Name</th><th>Status</th></tr></thead>
                            <tbody>
                                @foreach($zone->children as $region)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><span class="badge bg-light text-dark">{{ $region->code ?? '-' }}</span></td>
                                    <td><a href="{{ route('regions.show', $region) }}">{{ $region->name }}</a></td>
                                    <td>
                                        @php $rClass = ($region->status ?? 'active') === 'active' ? 'completed' : 'pending'; @endphp
                                        <span class="badge-status {{ $rClass }}">{{ ucfirst($region->status ?? 'Active') }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3 mb-0">No regions in this zone.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
