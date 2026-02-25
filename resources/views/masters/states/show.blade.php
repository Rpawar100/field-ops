@extends('layouts.admin')
@section('title', 'State Details')
@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>State Details</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('states.index') }}">States</a></li>
                    <li class="breadcrumb-item active">{{ $state->name ?? '' }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('states.edit', $state) }}" class="btn btn-outline-theme me-1"><i class="fas fa-edit me-1"></i> Edit</a>
            <a href="{{ route('states.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Name</span><span class="fw-medium">{{ $state->name ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Code</span><span class="fw-medium">{{ $state->code ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Status</span>
                        @php $statusClass = ($state->status ?? 'active') === 'active' ? 'completed' : 'pending'; @endphp
                        <span class="badge-status {{ $statusClass }}">{{ ucfirst($state->status ?? 'Active') }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2"><span class="text-muted">Districts</span><span class="fw-medium">{{ $state->districts ? $state->districts->count() : 0 }}</span></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Districts in this State</h6></div>
            <div class="card-body">
                @if(isset($state->districts) && $state->districts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead><tr><th>#</th><th>Code</th><th>District Name</th><th>Status</th></tr></thead>
                            <tbody>
                                @foreach($state->districts as $district)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><span class="badge bg-light text-dark">{{ $district->code ?? '-' }}</span></td>
                                    <td><a href="{{ route('districts.show', $district) }}">{{ $district->name }}</a></td>
                                    <td>
                                        @php $dClass = ($district->status ?? 'active') === 'active' ? 'completed' : 'pending'; @endphp
                                        <span class="badge-status {{ $dClass }}">{{ ucfirst($district->status ?? 'Active') }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3 mb-0">No districts in this state.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
