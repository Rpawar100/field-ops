@extends('layouts.admin')

@section('title', 'Demo Execution')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Demo Execution Tracking</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('demo.index') }}">Demos</a></li>
                    <li class="breadcrumb-item active">Execution</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

{{-- Stage Pipeline (Kanban-style) --}}
<div class="row g-3 mb-4">
    @php
        $stages = ['Sowing', 'Germination', 'Vegetative', 'Flowering', 'Harvest', 'Completed'];
        $stageColors = ['#43B9B2', '#C280D2', '#198754', '#ffc107', '#0dcaf0', '#6c757d'];
    @endphp
    @foreach($stages as $i => $stage)
    <div class="col-md-2">
        <div class="card border-0" style="border-top: 3px solid {{ $stageColors[$i] }} !important;">
            <div class="card-body text-center py-3">
                <div style="font-size:1.5rem;font-weight:700;">{{ $stageCounts[$stage] ?? 0 }}</div>
                <div class="small text-muted">{{ $stage }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Execution List (demo_farmer_assignments) --}}
<div class="card">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold">Farmer Assignment Records</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="dataTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Lot No</th>
                        <th>Farmer</th>
                        <th>Crop</th>
                        <th>Current Stage</th>
                        <th>Sowing Date</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($executions ?? [] as $exec)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="fw-medium">{{ $exec->lot_no ?? $exec->demoMaster->lot_no ?? '-' }}</span></td>
                        <td>{{ $exec->farmer->name ?? '-' }}</td>
                        <td>{{ $exec->demoMaster->crop->name ?? '-' }}</td>
                        <td><span class="badge bg-light text-dark">{{ $exec->current_stage ?? '-' }} / {{ $exec->total_stages ?? '-' }}</span></td>
                        <td class="text-muted">{{ $exec->sowing_date ?? '-' }}</td>
                        <td>
                            @php
                                $eStatusClass = match(strtolower($exec->status ?? 'assigned')) {
                                    'completed', 'harvested' => 'completed',
                                    'in_progress', 'sowing', 'germination', 'vegetative', 'flowering' => 'in-progress',
                                    default => 'pending',
                                };
                            @endphp
                            <span class="badge-status {{ $eStatusClass }}">{{ ucfirst(str_replace('_', ' ', $exec->status ?? 'Assigned')) }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('demo.show', $exec->demoMaster ?? $exec) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>
                            No execution records found.
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
