@extends('layouts.admin')

@section('title', 'Demo Reconciliation')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Demo Reconciliation</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('demo.index') }}">Demos</a></li>
                    <li class="breadcrumb-item active">Reconciliation</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div style="font-size:2rem;font-weight:700;color:var(--theme-default);">{{ $summary['pending'] ?? 0 }}</div>
                <div class="text-muted small">Pending Reconciliation</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div style="font-size:2rem;font-weight:700;color:#198754;">{{ $summary['reconciled'] ?? 0 }}</div>
                <div class="text-muted small">Reconciled</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div style="font-size:2rem;font-weight:700;color:#dc3545;">{{ $summary['discrepancies'] ?? 0 }}</div>
                <div class="text-muted small">Discrepancies Found</div>
            </div>
        </div>
    </div>
</div>

{{-- Reconciliation Table --}}
<div class="card">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold">Reconciliation Records</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="dataTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Lot No</th>
                        <th>Product</th>
                        <th>Total Qty</th>
                        <th>Available Qty</th>
                        <th>Assigned Farmers</th>
                        <th>Target Farmers</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reconciliations ?? [] as $rec)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="fw-medium">{{ $rec->lot_no ?? '-' }}</span></td>
                        <td>{{ $rec->product->sku_name ?? $rec->product_name ?? '-' }}</td>
                        <td>{{ $rec->total_quantity ?? '-' }}</td>
                        <td>{{ $rec->available_quantity ?? '-' }}</td>
                        <td>{{ $rec->assigned_farmers ?? '-' }}</td>
                        <td>{{ $rec->target_farmers ?? '-' }}</td>
                        <td>
                            @php
                                $rStatusClass = match(strtolower($rec->status ?? 'pending')) {
                                    'reconciled', 'completed' => 'completed',
                                    'discrepancy' => 'in-progress',
                                    default => 'pending',
                                };
                            @endphp
                            <span class="badge-status {{ $rStatusClass }}">{{ ucfirst(str_replace('_', ' ', $rec->status ?? 'Pending')) }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('demo.show', $rec) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>
                            No reconciliation records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#dataTable').DataTable({ paging: false, info: false, searching: true });
});
</script>
@endsection
