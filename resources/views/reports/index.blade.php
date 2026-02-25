@extends('layouts.admin')

@section('title', 'Reports Dashboard')

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Reports & Analytics</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Reports</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

{{-- 4 Report Cards --}}
<div class="row g-3">
    <div class="col-md-6 col-xl-3">
        <a href="{{ route('reports.activities') }}" class="card text-decoration-none h-100">
            <div class="card-body text-center py-4">
                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                     style="width:60px;height:60px;background:rgba(67,185,178,0.12);color:var(--theme-default);">
                    <i class="fas fa-clipboard-list" style="font-size:1.3rem;"></i>
                </div>
                <h6 class="fw-semibold mb-1">Activity Report</h6>
                <p class="text-muted small mb-2">View all activities by date, type, and user</p>
                <span class="badge bg-light text-dark">{{ $stats['total_activities'] ?? 0 }} total</span>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-xl-3">
        <a href="{{ route('reports.attendance') }}" class="card text-decoration-none h-100">
            <div class="card-body text-center py-4">
                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                     style="width:60px;height:60px;background:rgba(194,128,210,0.12);color:var(--theme-secondary);">
                    <i class="fas fa-fingerprint" style="font-size:1.3rem;"></i>
                </div>
                <h6 class="fw-semibold mb-1">Attendance Report</h6>
                <p class="text-muted small mb-2">Attendance trends and daily summaries</p>
                <span class="badge bg-light text-dark">{{ $stats['total_attendances'] ?? 0 }} records</span>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-xl-3">
        <a href="{{ route('reports.demo') }}" class="card text-decoration-none h-100">
            <div class="card-body text-center py-4">
                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                     style="width:60px;height:60px;background:rgba(25,135,84,0.12);color:#198754;">
                    <i class="fas fa-flask" style="font-size:1.3rem;"></i>
                </div>
                <h6 class="fw-semibold mb-1">Demo Report</h6>
                <p class="text-muted small mb-2">Demo lot distribution and execution tracking</p>
                <span class="badge bg-light text-dark">{{ $stats['total_demo_assignments'] ?? 0 }} assignments</span>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-xl-3">
        <a href="{{ route('reports.coverage') }}" class="card text-decoration-none h-100">
            <div class="card-body text-center py-4">
                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                     style="width:60px;height:60px;background:rgba(255,193,7,0.12);color:#e6ad00;">
                    <i class="fas fa-map-marked-alt" style="font-size:1.3rem;"></i>
                </div>
                <h6 class="fw-semibold mb-1">Coverage Report</h6>
                <p class="text-muted small mb-2">Territory coverage and beat completion</p>
                <span class="badge bg-light text-dark">{{ $stats['total_beats'] ?? 0 }} beats</span>
            </div>
        </a>
    </div>
</div>

{{-- Monthly Overview Stats + Export --}}
<div class="row g-3 mt-3">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-semibold">This Month Overview</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-center py-3 border rounded">
                            <div style="font-size:1.5rem;font-weight:700;color:var(--theme-default);">{{ $stats['monthly_activities'] ?? 0 }}</div>
                            <div class="small text-muted">Activities</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center py-3 border rounded">
                            <div style="font-size:1.5rem;font-weight:700;color:var(--theme-secondary);">{{ $stats['monthly_attendance_avg'] ?? '0%' }}</div>
                            <div class="small text-muted">Avg. Attendance</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center py-3 border rounded">
                            <div style="font-size:1.5rem;font-weight:700;color:#198754;">{{ $stats['monthly_demo_assignments'] ?? 0 }}</div>
                            <div class="small text-muted">Demo Assignments</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center py-3 border rounded">
                            <div style="font-size:1.5rem;font-weight:700;color:#e6ad00;">{{ $stats['monthly_beat_coverage'] ?? '0%' }}</div>
                            <div class="small text-muted">Beat Coverage</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Export Options</h6></div>
            <div class="card-body d-flex flex-column justify-content-center">
                <p class="text-muted mb-3">Download detailed reports for offline analysis.</p>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('reports.export', ['type' => 'activities']) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-download me-1"></i> Activities CSV
                    </a>
                    <a href="{{ route('reports.export', ['type' => 'attendance']) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-download me-1"></i> Attendance CSV
                    </a>
                    <a href="{{ route('reports.export', ['type' => 'demo']) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-download me-1"></i> Demo CSV
                    </a>
                    <a href="{{ route('reports.export', ['type' => 'coverage']) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-download me-1"></i> Coverage CSV
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
