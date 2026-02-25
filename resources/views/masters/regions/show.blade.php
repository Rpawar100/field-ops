@extends('layouts.admin')
@section('title', 'Region Details')
@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Region Details</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('regions.index') }}">Regions</a></li>
                    <li class="breadcrumb-item active">{{ $region->name ?? '' }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('regions.edit', $region) }}" class="btn btn-outline-theme me-1"><i class="fas fa-edit me-1"></i> Edit</a>
            <a href="{{ route('regions.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
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
                        <span class="fw-medium">{{ $region->name ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Code</span>
                        <span class="fw-medium">{{ $region->code ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Level</span>
                        <span class="fw-medium"><span class="badge bg-light text-dark">{{ ucfirst($region->level ?? 'region') }}</span></span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Zone (Parent)</span>
                        <span class="fw-medium">{{ $region->parent->name ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Status</span>
                        @php $statusClass = ($region->status ?? 'active') === 'active' ? 'completed' : 'pending'; @endphp
                        <span class="badge-status {{ $statusClass }}">{{ ucfirst($region->status ?? 'Active') }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Territories</span>
                        <span class="fw-medium">{{ $region->children ? $region->children->count() : 0 }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted">Description</span>
                        <span class="fw-medium">{{ $region->description ?? '-' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Territories in this Region</h6></div>
            <div class="card-body">
                @if(isset($region->children) && $region->children->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead><tr><th>#</th><th>Code</th><th>Territory Name</th><th>Status</th></tr></thead>
                            <tbody>
                                @foreach($region->children as $territory)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><span class="badge bg-light text-dark">{{ $territory->code ?? '-' }}</span></td>
                                    <td><a href="{{ route('territories.show', $territory) }}">{{ $territory->name }}</a></td>
                                    <td>
                                        @php $tClass = ($territory->status ?? 'active') === 'active' ? 'completed' : 'pending'; @endphp
                                        <span class="badge-status {{ $tClass }}">{{ ucfirst($territory->status ?? 'Active') }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3 mb-0">No territories in this region.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
