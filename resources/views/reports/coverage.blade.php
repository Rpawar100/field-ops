@extends('layouts.admin')

@section('title', 'Coverage Report')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<style>
    .coverage-group-header {
        background-color: rgba(67, 185, 178, 0.06);
        font-weight: 600;
    }
    .coverage-group-header td {
        border-bottom: 2px solid var(--theme-default) !important;
    }
</style>
@endsection

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Coverage Report</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
                    <li class="breadcrumb-item active">Coverage</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('reports.export', ['type' => 'coverage', 'from_date' => request('from_date'), 'to_date' => request('to_date')]) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-download me-1"></i> Export CSV
        </a>
    </div>
</div>

{{-- Date Range Filter --}}
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('reports.coverage') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small">From Date</label>
                <input type="date" class="form-control form-control-sm" name="from_date" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small">To Date</label>
                <input type="date" class="form-control form-control-sm" name="to_date" value="{{ request('to_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Territory / HQ</label>
                <select class="form-select form-select-sm" name="zrth_id">
                    <option value="">All</option>
                    @foreach($territories ?? [] as $territory)
                        <option value="{{ $territory->id }}" {{ request('zrth_id') == $territory->id ? 'selected' : '' }}>{{ $territory->name }} ({{ ucfirst($territory->level) }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-theme btn-sm w-100"><i class="fas fa-filter me-1"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

{{-- Coverage Breakdown by Territory --}}
<div class="card">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold"><i class="fas fa-map-marked-alt me-2 text-theme"></i>Territory Coverage Breakdown</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="coverageTable">
                <thead>
                    <tr>
                        <th>Territory / HQ</th>
                        <th>Level</th>
                        <th>Total Beats</th>
                        <th>Planned Visits</th>
                        <th>Completed Visits</th>
                        <th>Coverage %</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coverageData ?? [] as $territoryName => $rows)
                        {{-- Territory group header --}}
                        <tr class="coverage-group-header">
                            <td colspan="6">
                                <i class="fas fa-layer-group me-1 text-theme"></i> {{ $territoryName }}
                            </td>
                        </tr>
                        @foreach($rows as $row)
                        <tr>
                            <td>
                                <span class="fw-medium ps-3">{{ $row['name'] ?? '-' }}</span>
                            </td>
                            <td>
                                @php
                                    $levelClass = match($row['level'] ?? '') {
                                        'zone' => 'bg-dark',
                                        'region' => 'bg-primary',
                                        'territory' => 'bg-info',
                                        'hq' => 'bg-secondary',
                                        default => 'bg-light text-dark',
                                    };
                                @endphp
                                <span class="badge {{ $levelClass }}">{{ ucfirst($row['level'] ?? '-') }}</span>
                            </td>
                            <td>{{ $row['total_beats'] ?? 0 }}</td>
                            <td>{{ $row['planned_visits'] ?? 0 }}</td>
                            <td>{{ $row['completed_visits'] ?? 0 }}</td>
                            <td>
                                @php
                                    $coveragePct = $row['coverage_percent'] ?? 0;
                                    $barColor = $coveragePct >= 80 ? '#198754' : ($coveragePct >= 50 ? '#ffc107' : '#dc3545');
                                @endphp
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height:8px;min-width:80px;">
                                        <div class="progress-bar" role="progressbar" style="width:{{ $coveragePct }}%;background:{{ $barColor }};"></div>
                                    </div>
                                    <span class="small fw-medium">{{ $coveragePct }}%</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @empty
                        {{-- Fallback: flat list if not grouped --}}
                        @forelse($coverage ?? [] as $row)
                        <tr>
                            <td><span class="fw-medium">{{ $row['name'] ?? '-' }}</span></td>
                            <td>
                                @php
                                    $levelClass = match($row['level'] ?? '') {
                                        'zone' => 'bg-dark',
                                        'region' => 'bg-primary',
                                        'territory' => 'bg-info',
                                        'hq' => 'bg-secondary',
                                        default => 'bg-light text-dark',
                                    };
                                @endphp
                                <span class="badge {{ $levelClass }}">{{ ucfirst($row['level'] ?? '-') }}</span>
                            </td>
                            <td>{{ $row['total_beats'] ?? 0 }}</td>
                            <td>{{ $row['planned_visits'] ?? 0 }}</td>
                            <td>{{ $row['completed_visits'] ?? 0 }}</td>
                            <td>
                                @php
                                    $coveragePct = $row['coverage_percent'] ?? 0;
                                    $barColor = $coveragePct >= 80 ? '#198754' : ($coveragePct >= 50 ? '#ffc107' : '#dc3545');
                                @endphp
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height:8px;min-width:80px;">
                                        <div class="progress-bar" role="progressbar" style="width:{{ $coveragePct }}%;background:{{ $barColor }};"></div>
                                    </div>
                                    <span class="small fw-medium">{{ $coveragePct }}%</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>
                                No coverage data available.
                            </td>
                        </tr>
                        @endforelse
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
    $('#coverageTable').DataTable({
        paging: true,
        pageLength: 50,
        info: true,
        searching: true,
        ordering: true,
        language: {
            emptyTable: "No coverage data available."
        }
    });
});
</script>
@endsection
