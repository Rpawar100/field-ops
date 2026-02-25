@extends('layouts.admin')
@section('title', 'Headquarters Details')
@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Headquarters Details</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('headquarters.index') }}">Headquarters</a></li>
                    <li class="breadcrumb-item active">{{ $headquarters->name ?? '' }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('headquarters.edit', $headquarters) }}" class="btn btn-outline-theme me-1"><i class="fas fa-edit me-1"></i> Edit</a>
            <a href="{{ route('headquarters.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
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
                        <span class="fw-medium">{{ $headquarters->name ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Code</span>
                        <span class="fw-medium">{{ $headquarters->code ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Level</span>
                        <span class="fw-medium"><span class="badge bg-light text-dark">{{ ucfirst($headquarters->level ?? 'hq') }}</span></span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Territory (Parent)</span>
                        <span class="fw-medium">{{ $headquarters->parent->name ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Region</span>
                        <span class="fw-medium">{{ $headquarters->parent->parent->name ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Zone</span>
                        <span class="fw-medium">{{ $headquarters->parent->parent->parent->name ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Status</span>
                        @php $statusClass = ($headquarters->status ?? 'active') === 'active' ? 'completed' : 'pending'; @endphp
                        <span class="badge-status {{ $statusClass }}">{{ ucfirst($headquarters->status ?? 'Active') }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted">Description</span>
                        <span class="fw-medium">{{ $headquarters->description ?? '-' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Beats in this Headquarters</h6></div>
            <div class="card-body">
                @if(isset($beats) && $beats->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead><tr><th>#</th><th>Beat Code</th><th>Beat Name</th><th>Day</th><th>Status</th></tr></thead>
                            <tbody>
                                @foreach($beats as $beat)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><span class="badge bg-light text-dark">{{ $beat->beat_code ?? '-' }}</span></td>
                                    <td><a href="{{ route('beats.show', $beat) }}">{{ $beat->name }}</a></td>
                                    <td>{{ ucfirst($beat->beat_day ?? '-') }}</td>
                                    <td>
                                        @php $bClass = ($beat->status ?? 'active') === 'active' ? 'completed' : 'pending'; @endphp
                                        <span class="badge-status {{ $bClass }}">{{ ucfirst($beat->status ?? 'Active') }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3 mb-0">No beats in this headquarters.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
