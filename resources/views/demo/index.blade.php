@extends('layouts.admin')

@section('title', 'Demo Management')

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Demo Overview</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Demos</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

{{-- Stats Cards --}}
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div style="font-size:1.75rem;font-weight:700;line-height:1.1;">{{ $stats['total_lots'] ?? 0 }}</div>
                        <div class="text-muted small">Total Demo Lots</div>
                    </div>
                    <div class="rounded d-flex align-items-center justify-content-center"
                         style="width:52px;height:52px;background:rgba(67,185,178,0.12);color:var(--theme-default);border-radius:14px;">
                        <i class="fas fa-flask" style="font-size:1.3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div style="font-size:1.75rem;font-weight:700;line-height:1.1;">{{ $stats['distributed'] ?? 0 }}</div>
                        <div class="text-muted small">Distributed</div>
                    </div>
                    <div class="rounded d-flex align-items-center justify-content-center"
                         style="width:52px;height:52px;background:rgba(194,128,210,0.12);color:var(--theme-secondary);border-radius:14px;">
                        <i class="fas fa-truck" style="font-size:1.3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div style="font-size:1.75rem;font-weight:700;line-height:1.1;">{{ $stats['executed'] ?? 0 }}</div>
                        <div class="text-muted small">Executed</div>
                    </div>
                    <div class="rounded d-flex align-items-center justify-content-center"
                         style="width:52px;height:52px;background:rgba(25,135,84,0.12);color:#198754;border-radius:14px;">
                        <i class="fas fa-check-circle" style="font-size:1.3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div style="font-size:1.75rem;font-weight:700;line-height:1.1;">{{ $stats['reconciled'] ?? 0 }}</div>
                        <div class="text-muted small">Reconciled</div>
                    </div>
                    <div class="rounded d-flex align-items-center justify-content-center"
                         style="width:52px;height:52px;background:rgba(255,193,7,0.12);color:#e6ad00;border-radius:14px;">
                        <i class="fas fa-balance-scale" style="font-size:1.3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <a href="{{ route('demo.distribution') }}" class="card text-decoration-none h-100">
            <div class="card-body text-center py-4">
                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                     style="width:60px;height:60px;background:rgba(194,128,210,0.12);color:var(--theme-secondary);">
                    <i class="fas fa-truck" style="font-size:1.3rem;"></i>
                </div>
                <h6 class="fw-semibold mb-1">Distribution</h6>
                <p class="text-muted small mb-0">Manage demo lot distribution to field assistants</p>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('demo.execution') }}" class="card text-decoration-none h-100">
            <div class="card-body text-center py-4">
                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                     style="width:60px;height:60px;background:rgba(25,135,84,0.12);color:#198754;">
                    <i class="fas fa-play-circle" style="font-size:1.3rem;"></i>
                </div>
                <h6 class="fw-semibold mb-1">Execution</h6>
                <p class="text-muted small mb-0">Track demo execution progress and stages</p>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('demo.reconciliation') }}" class="card text-decoration-none h-100">
            <div class="card-body text-center py-4">
                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                     style="width:60px;height:60px;background:rgba(255,193,7,0.12);color:#e6ad00;">
                    <i class="fas fa-balance-scale" style="font-size:1.3rem;"></i>
                </div>
                <h6 class="fw-semibold mb-1">Reconciliation</h6>
                <p class="text-muted small mb-0">Reconcile demo lots and generate reports</p>
            </div>
        </a>
    </div>
</div>

{{-- Recent Demo Masters --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="mb-0 fw-semibold">Recent Demo Masters</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Lot No</th>
                        <th>Crop</th>
                        <th>Product</th>
                        <th>Total Qty</th>
                        <th>Available Qty</th>
                        <th>Target Farmers</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($demoLots ?? [] as $lot)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="fw-medium">{{ $lot->lot_no ?? '-' }}</span></td>
                        <td>{{ $lot->crop->name ?? '-' }}</td>
                        <td>{{ $lot->product->sku_name ?? $lot->product_name ?? '-' }}</td>
                        <td>{{ $lot->total_quantity ?? '-' }} {{ $lot->quantity_unit ?? '' }}</td>
                        <td>{{ $lot->available_quantity ?? '-' }}</td>
                        <td>{{ $lot->target_farmers ?? '-' }}</td>
                        <td>
                            @php
                                $dStatusClass = match(strtolower($lot->status ?? 'pending')) {
                                    'completed', 'reconciled' => 'completed',
                                    'in_progress', 'distributed', 'executing' => 'in-progress',
                                    default => 'pending',
                                };
                            @endphp
                            <span class="badge-status {{ $dStatusClass }}">{{ ucfirst(str_replace('_', ' ', $lot->status ?? 'Pending')) }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('demo.show', $lot) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>
                            No demo lots found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
