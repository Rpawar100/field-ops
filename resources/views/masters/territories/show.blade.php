@extends('layouts.admin')
@section('title', 'Territory Details')
@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Territory Details</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('territories.index') }}">Territories</a></li>
                    <li class="breadcrumb-item active">{{ $territory->name ?? '' }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('territories.edit', $territory) }}" class="btn btn-outline-theme me-1"><i class="fas fa-edit me-1"></i> Edit</a>
            <a href="{{ route('territories.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
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
                        <span class="fw-medium">{{ $territory->name ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Code</span>
                        <span class="fw-medium">{{ $territory->code ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Level</span>
                        <span class="fw-medium"><span class="badge bg-light text-dark">{{ ucfirst($territory->level ?? 'territory') }}</span></span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Region (Parent)</span>
                        <span class="fw-medium">{{ $territory->parent->name ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Zone</span>
                        <span class="fw-medium">{{ $territory->parent->parent->name ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Status</span>
                        @php $statusClass = ($territory->status ?? 'active') === 'active' ? 'completed' : 'pending'; @endphp
                        <span class="badge-status {{ $statusClass }}">{{ ucfirst($territory->status ?? 'Active') }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Headquarters</span>
                        <span class="fw-medium">{{ $territory->children ? $territory->children->count() : 0 }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted">Description</span>
                        <span class="fw-medium">{{ $territory->description ?? '-' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Headquarters in this Territory</h6></div>
            <div class="card-body">
                @if(isset($territory->children) && $territory->children->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead><tr><th>#</th><th>Code</th><th>HQ Name</th><th>Status</th></tr></thead>
                            <tbody>
                                @foreach($territory->children as $hq)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><span class="badge bg-light text-dark">{{ $hq->code ?? '-' }}</span></td>
                                    <td><a href="{{ route('headquarters.show', $hq) }}">{{ $hq->name }}</a></td>
                                    <td>
                                        @php $hClass = ($hq->status ?? 'active') === 'active' ? 'completed' : 'pending'; @endphp
                                        <span class="badge-status {{ $hClass }}">{{ ucfirst($hq->status ?? 'Active') }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3 mb-0">No headquarters in this territory.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
