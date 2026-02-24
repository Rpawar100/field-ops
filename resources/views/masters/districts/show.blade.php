@extends('layouts.admin')
@section('title', 'District Details')
@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>District Details</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('districts.index') }}">Districts</a></li>
                    <li class="breadcrumb-item active">{{ $district->name ?? '' }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('districts.edit', $district) }}" class="btn btn-outline-theme me-1"><i class="fas fa-edit me-1"></i> Edit</a>
            <a href="{{ route('districts.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Name</span><span class="fw-medium">{{ $district->name ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Code</span><span class="fw-medium">{{ $district->code ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">State</span><span class="fw-medium">{{ $district->state->name ?? '-' }}</span></li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Status</span>
                        @php $statusClass = ($district->status ?? 'active') === 'active' ? 'completed' : 'pending'; @endphp
                        <span class="badge-status {{ $statusClass }}">{{ ucfirst($district->status ?? 'Active') }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2"><span class="text-muted">Talukas</span><span class="fw-medium">{{ $district->talukas ? $district->talukas->count() : 0 }}</span></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Talukas in this District</h6></div>
            <div class="card-body">
                @if(isset($district->talukas) && $district->talukas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead><tr><th>#</th><th>Code</th><th>Taluka Name</th><th>Status</th></tr></thead>
                            <tbody>
                                @foreach($district->talukas as $taluka)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><span class="badge bg-light text-dark">{{ $taluka->code ?? '-' }}</span></td>
                                    <td><a href="{{ route('talukas.show', $taluka) }}">{{ $taluka->name }}</a></td>
                                    <td>
                                        @php $tClass = ($taluka->status ?? 'active') === 'active' ? 'completed' : 'pending'; @endphp
                                        <span class="badge-status {{ $tClass }}">{{ ucfirst($taluka->status ?? 'Active') }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3 mb-0">No talukas in this district.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
