@extends('layouts.admin')

@section('title', 'Tour Plan Details')

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Tour Plan Details</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('atps.index') }}">Tour Plans</a></li>
                    <li class="breadcrumb-item active">{{ $atp->month ?? '' }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('atps.edit', $atp) }}" class="btn btn-outline-theme me-1">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <a href="{{ route('atps.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body text-center pt-4">
                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                     style="width:80px;height:80px;background:rgba(67,185,178,0.12);color:var(--theme-default);font-size:2rem;">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h5 class="fw-semibold mb-1">{{ $atp->month ?? '' }}</h5>
                <p class="text-muted mb-2">{{ $atp->user->name ?? '' }}</p>
                @php
                    $atpStatusClass = match(strtolower($atp->status ?? 'draft')) {
                        'approved' => 'completed',
                        'submitted' => 'in-progress',
                        'rejected' => 'in-progress',
                        default => 'pending',
                    };
                @endphp
                <span class="badge-status {{ $atpStatusClass }}">{{ ucfirst($atp->status ?? 'Draft') }}</span>
            </div>
            <hr class="my-0">
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-user me-2"></i>Field Assistant</span>
                        <span class="fw-medium">{{ $atp->user->name ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-route me-2"></i>Beats Count</span>
                        <span class="fw-medium">{{ $atp->items ? $atp->items->count() : 0 }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted"><i class="fas fa-calendar me-2"></i>Created</span>
                        <span class="fw-medium">{{ $atp->created_at ? $atp->created_at->format('d M Y') : '-' }}</span>
                    </li>
                </ul>
            </div>
        </div>

        @if($atp->remarks)
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold">Notes</h6>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $atp->remarks }}</p>
            </div>
        </div>
        @endif
    </div>

    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold">Assigned Beats</h6>
            </div>
            <div class="card-body">
                @if($atp->items && $atp->items->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Beat Name</th>
                                    <th>Planned Time</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($atp->items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><span class="fw-medium">{{ $item->beat->name ?? '-' }}</span></td>
                                    <td>{{ $item->planned_time ? $item->planned_time->format('d M Y, h:i A') : '-' }}</td>
                                    <td>{{ $item->description ?? '-' }}</td>
                                    <td>
                                        <span class="badge-status {{ ($item->status ?? 'planned') === 'approved' ? 'completed' : 'pending' }}">
                                            {{ ucfirst($item->status ?? 'planned') }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3 mb-0">
                        <i class="fas fa-route d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>
                        No beats assigned to this tour plan.
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
