@extends('layouts.admin')

@section('title', 'Demo Report')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Demo Report</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
                    <li class="breadcrumb-item active">Demo</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('reports.export', ['type' => 'demo', 'from_date' => request('from_date'), 'to_date' => request('to_date'), 'status' => request('status'), 'crop_id' => request('crop_id')]) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-download me-1"></i> Export CSV
        </a>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('reports.demo') }}" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small">From Date</label>
                <input type="date" class="form-control form-control-sm" name="from_date" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small">To Date</label>
                <input type="date" class="form-control form-control-sm" name="to_date" value="{{ request('to_date') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Status</label>
                <select class="form-select form-select-sm" name="status">
                    <option value="">All</option>
                    <option value="assigned" {{ request('status') === 'assigned' ? 'selected' : '' }}>Assigned</option>
                    <option value="sowing_done" {{ request('status') === 'sowing_done' ? 'selected' : '' }}>Sowing Done</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="abandoned" {{ request('status') === 'abandoned' ? 'selected' : '' }}>Abandoned</option>
                    <option value="harvested" {{ request('status') === 'harvested' ? 'selected' : '' }}>Harvested</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Crop</label>
                <select class="form-select form-select-sm" name="crop_id">
                    <option value="">All Crops</option>
                    @foreach($crops ?? [] as $crop)
                        <option value="{{ $crop->id }}" {{ request('crop_id') == $crop->id ? 'selected' : '' }}>{{ $crop->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-theme btn-sm w-100"><i class="fas fa-filter me-1"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

{{-- DataTable --}}
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="demoTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Lot No</th>
                        <th>Farmer</th>
                        <th>Crop</th>
                        <th>Progress</th>
                        <th>Status</th>
                        <th>Assignment Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments ?? [] as $assignment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <span class="fw-medium">{{ $assignment->demoMaster->lot_no ?? $assignment->lot_no ?? '-' }}</span>
                        </td>
                        <td>{{ $assignment->farmer->name ?? '-' }}</td>
                        <td>
                            <span class="badge bg-light text-dark">{{ $assignment->demoMaster->crop->name ?? '-' }}</span>
                        </td>
                        <td>
                            @php
                                $current = $assignment->current_stage ?? 0;
                                $total = $assignment->total_stages ?? 1;
                                $pct = $total > 0 ? round(($current / $total) * 100) : 0;
                            @endphp
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height:6px;min-width:60px;">
                                    <div class="progress-bar" role="progressbar" style="width:{{ $pct }}%;background:var(--theme-default);"></div>
                                </div>
                                <span class="small fw-medium text-nowrap">{{ $current }}/{{ $total }}</span>
                            </div>
                        </td>
                        <td>
                            @php
                                $demoStatusClass = match($assignment->status ?? 'assigned') {
                                    'completed', 'harvested' => 'bg-success',
                                    'in_progress', 'sowing_done' => 'bg-primary',
                                    'assigned' => 'bg-warning text-dark',
                                    'failed' => 'bg-danger',
                                    'abandoned' => 'bg-secondary',
                                    default => 'bg-light text-dark',
                                };
                            @endphp
                            <span class="badge {{ $demoStatusClass }}">{{ ucfirst(str_replace('_', ' ', $assignment->status ?? 'Assigned')) }}</span>
                        </td>
                        <td class="text-muted">{{ $assignment->assignment_date ? \Carbon\Carbon::parse($assignment->assignment_date)->format('d M Y') : '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>
                            No demo assignment records found.
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
    $('#demoTable').DataTable({
        paging: true,
        pageLength: 25,
        info: true,
        searching: true,
        order: [[6, 'desc']],
        language: {
            emptyTable: "No demo assignment records found."
        }
    });
});
</script>
@endsection
