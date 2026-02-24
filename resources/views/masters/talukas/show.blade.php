@extends('layouts.admin')
@section('title', 'Taluka Details')
@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Taluka Details</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('talukas.index') }}">Talukas</a></li>
                    <li class="breadcrumb-item active">{{ $taluka->name ?? '' }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('talukas.edit', $taluka) }}" class="btn btn-outline-theme me-1"><i class="fas fa-edit me-1"></i> Edit</a>
            <a href="{{ route('talukas.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Name</span><span class="fw-medium">{{ $taluka->name ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Code</span><span class="fw-medium">{{ $taluka->code ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">District</span><span class="fw-medium">{{ $taluka->district->name ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">State</span><span class="fw-medium">{{ $taluka->district->state->name ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Status</span>
                        @php $statusClass = ($taluka->status ?? 'active') === 'active' ? 'completed' : 'pending'; @endphp
                        <span class="badge-status {{ $statusClass }}">{{ ucfirst($taluka->status ?? 'Active') }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2"><span class="text-muted">Villages</span><span class="fw-medium">{{ $taluka->villages ? $taluka->villages->count() : 0 }}</span></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Villages in this Taluka</h6></div>
            <div class="card-body">
                @if(isset($taluka->villages) && $taluka->villages->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead><tr><th>#</th><th>Code</th><th>Village Name</th><th>Pincode</th><th>Status</th></tr></thead>
                            <tbody>
                                @foreach($taluka->villages as $village)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><span class="badge bg-light text-dark">{{ $village->code ?? '-' }}</span></td>
                                    <td><a href="{{ route('villages.show', $village) }}">{{ $village->name }}</a></td>
                                    <td>{{ $village->pincode ?? '-' }}</td>
                                    <td>
                                        @php $vClass = ($village->status ?? 'active') === 'active' ? 'completed' : 'pending'; @endphp
                                        <span class="badge-status {{ $vClass }}">{{ ucfirst($village->status ?? 'Active') }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3 mb-0">No villages in this taluka.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
