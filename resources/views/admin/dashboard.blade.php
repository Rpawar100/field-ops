@extends('layouts.admin')

@section('title', 'Dashboard')

@section('styles')
<style>
    /* ======================= KPI CARDS =============================== */
    .kpi-card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }
    .kpi-card .card-body {
        padding: 22px 24px;
    }
    .kpi-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
    .kpi-card .kpi-value {
        font-size: 1.75rem;
        font-weight: 700;
        line-height: 1.1;
        margin-bottom: 2px;
    }
    .kpi-card .kpi-label {
        font-size: 0.82rem;
        color: var(--text-light-gray);
        font-weight: 400;
    }
    .kpi-card .kpi-change {
        font-size: 0.75rem;
        font-weight: 500;
        margin-top: 6px;
    }
    .kpi-change.up   { color: #198754; }
    .kpi-change.down { color: #dc3545; }

    /* ======================= CHART PLACEHOLDER ======================= */
    .chart-placeholder {
        height: 280px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f8f9fe 0%, #f0f2f8 100%);
        border-radius: 10px;
        color: var(--text-light-gray);
        font-size: 0.9rem;
    }
    .chart-placeholder i {
        font-size: 2rem;
        margin-bottom: 10px;
        opacity: 0.4;
    }

    /* ======================= ACTIVITY TABLE ========================== */
    .activity-table thead th {
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-light-gray);
        border-bottom-width: 1px;
        padding: 12px 16px;
        white-space: nowrap;
    }
    .activity-table tbody td {
        padding: 12px 16px;
        font-size: 0.875rem;
        vertical-align: middle;
        border-bottom: 1px solid #f5f5f5;
    }
    .activity-table tbody tr:hover {
        background-color: rgba(67, 185, 178, 0.03);
    }
    .activity-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Status badges */
    .badge-status {
        font-size: 0.72rem;
        font-weight: 500;
        padding: 4px 10px;
        border-radius: 20px;
        letter-spacing: 0.3px;
    }
    .badge-status.completed {
        background-color: rgba(25, 135, 84, 0.1);
        color: #198754;
    }
    .badge-status.in-progress {
        background-color: rgba(255, 193, 7, 0.15);
        color: #b38600;
    }
    .badge-status.pending {
        background-color: rgba(108, 117, 125, 0.1);
        color: #6c757d;
    }

    /* ======================= QUICK STATS BAR ========================= */
    .quick-stat {
        text-align: center;
        padding: 16px 10px;
        border-right: 1px solid #f3f3f3;
    }
    .quick-stat:last-child {
        border-right: none;
    }
    .quick-stat .stat-value {
        font-size: 1.15rem;
        font-weight: 600;
        color: var(--body-font-color);
    }
    .quick-stat .stat-label {
        font-size: 0.75rem;
        color: var(--text-light-gray);
        margin-top: 2px;
    }
</style>
@endsection

@section('content')

    {{-- Page Title & Breadcrumb --}}
    <div class="page-title-area">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h3>Dashboard</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
            <div>
                <span class="text-muted" style="font-size: 0.85rem;">
                    <i class="fas fa-calendar-day me-1"></i>
                    {{ now()->format('l, d M Y') }}
                </span>
            </div>
        </div>
    </div>

    {{-- ============================================================
         KPI STAT CARDS
         ============================================================ --}}
    <div class="row g-3 mb-4">

        {{-- Total Users --}}
        <div class="col-xl-3 col-sm-6">
            <div class="card kpi-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="kpi-value">{{ number_format($stats['total_users']) }}</div>
                            <div class="kpi-label">Total Users</div>
                            <div class="kpi-change up">
                                <i class="fas fa-arrow-up fa-xs me-1"></i> Registered
                            </div>
                        </div>
                        <div class="kpi-icon" style="background: rgba(67, 185, 178, 0.12); color: var(--theme-default);">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Active Farmers --}}
        <div class="col-xl-3 col-sm-6">
            <div class="card kpi-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="kpi-value">{{ number_format($stats['active_farmers']) }}</div>
                            <div class="kpi-label">Active Farmers</div>
                            <div class="kpi-change up">
                                <i class="fas fa-arrow-up fa-xs me-1"></i> Active
                            </div>
                        </div>
                        <div class="kpi-icon" style="background: rgba(25, 135, 84, 0.12); color: #198754;">
                            <i class="fas fa-leaf"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Today's Activities --}}
        <div class="col-xl-3 col-sm-6">
            <div class="card kpi-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="kpi-value">{{ number_format($stats['todays_activities']) }}</div>
                            <div class="kpi-label">Today's Activities</div>
                            <div class="kpi-change up">
                                <i class="fas fa-arrow-up fa-xs me-1"></i> Today
                            </div>
                        </div>
                        <div class="kpi-icon" style="background: rgba(194, 128, 210, 0.12); color: var(--theme-secondary);">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Attendance % --}}
        <div class="col-xl-3 col-sm-6">
            <div class="card kpi-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="kpi-value">{{ $stats['attendance_percent'] }}%</div>
                            <div class="kpi-label">Attendance Today</div>
                            <div class="kpi-change {{ $stats['attendance_percent'] >= 50 ? 'up' : 'down' }}">
                                <i class="fas fa-{{ $stats['attendance_percent'] >= 50 ? 'arrow-up' : 'arrow-down' }} fa-xs me-1"></i>
                                {{ $stats['attendance_percent'] >= 50 ? 'On Track' : 'Below Target' }}
                            </div>
                        </div>
                        <div class="kpi-icon" style="background: rgba(255, 193, 7, 0.12); color: #e6ad00;">
                            <i class="fas fa-fingerprint"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ============================================================
         CHARTS ROW (Placeholder + Quick Stats)
         ============================================================ --}}
    <div class="row g-3 mb-4">

        {{-- Activity Trend Chart (Placeholder) --}}
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold">Activity Trend</h6>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary active">Weekly</button>
                        <button type="button" class="btn btn-outline-secondary">Monthly</button>
                        <button type="button" class="btn btn-outline-secondary">Yearly</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-placeholder">
                        <div class="text-center">
                            <i class="fas fa-chart-area d-block"></i>
                            <span>ApexCharts will render here</span>
                            <br>
                            <small class="text-muted">Integration pending — connect to activity data</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Stats Panel --}}
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0 fw-semibold">Quick Overview</h6>
                </div>
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-6">
                            <div class="quick-stat">
                                <div class="stat-value text-theme">{{ number_format($stats['total_users']) }}</div>
                                <div class="stat-label">Field Officers</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="quick-stat">
                                <div class="stat-value" style="color: var(--theme-secondary);">{{ number_format($stats['active_farmers']) }}</div>
                                <div class="stat-label">Farmers</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="quick-stat" style="border-top: 1px solid #f3f3f3;">
                                <div class="stat-value" style="color: #198754;">{{ number_format($stats['todays_activities']) }}</div>
                                <div class="stat-label">Activities Today</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="quick-stat" style="border-top: 1px solid #f3f3f3;">
                                <div class="stat-value" style="color: #e6ad00;">{{ $stats['attendance_percent'] }}%</div>
                                <div class="stat-label">Present Today</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Upcoming Actions --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0 fw-semibold">Upcoming Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:36px; height:36px; background: rgba(67,185,178,0.1); color: var(--theme-default); flex-shrink:0;">
                            <i class="fas fa-calendar-check fa-sm"></i>
                        </div>
                        <div>
                            <p class="mb-0 small fw-medium">Monthly ATP review deadline</p>
                            <small class="text-muted">Due in 3 days</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:36px; height:36px; background: rgba(194,128,210,0.1); color: var(--theme-secondary); flex-shrink:0;">
                            <i class="fas fa-flask fa-sm"></i>
                        </div>
                        <div>
                            <p class="mb-0 small fw-medium">Demo reconciliation — Batch #DL-2026</p>
                            <small class="text-muted">Due in 5 days</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:36px; height:36px; background: rgba(255,193,7,0.1); color: #e6ad00; flex-shrink:0;">
                            <i class="fas fa-user-plus fa-sm"></i>
                        </div>
                        <div>
                            <p class="mb-0 small fw-medium">3 FA onboarding approvals pending</p>
                            <small class="text-muted">Action required</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ============================================================
         RECENT ACTIVITIES TABLE
         ============================================================ --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold">Recent Activities</h6>
                    <a href="{{ url('/activities') }}" class="btn btn-sm btn-outline-theme">
                        View All <i class="fas fa-arrow-right ms-1 fa-xs"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table activity-table mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Activity</th>
                                    <th>Type</th>
                                    <th>Assigned To</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentActivities as $activity)
                                    <tr>
                                        <td class="text-muted">{{ $activity['id'] }}</td>
                                        <td>
                                            <span class="fw-medium">{{ $activity['title'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark" style="font-size: 0.75rem;">
                                                {{ $activity['type'] }}
                                            </span>
                                        </td>
                                        <td>{{ $activity['user'] }}</td>
                                        <td>
                                            @php
                                                $statusSlug = strtolower(str_replace(' ', '-', $activity['status']));
                                                $statusClass = match($statusSlug) {
                                                    'completed' => 'completed',
                                                    'in-progress' => 'in-progress',
                                                    default => 'pending',
                                                };
                                            @endphp
                                            <span class="badge-status {{ $statusClass }}">
                                                {{ $activity['status'] }}
                                            </span>
                                        </td>
                                        <td class="text-muted">
                                            <small>{{ $activity['created_at'] }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox d-block mb-2" style="font-size: 1.5rem; opacity: 0.3;"></i>
                                            No activities recorded yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    // ApexCharts stub — replace with real data when API is ready
    document.addEventListener('DOMContentLoaded', function () {
        // Chart integration will go here
        // Example:
        // var options = {
        //     chart: { type: 'area', height: 280 },
        //     series: [{ name: 'Activities', data: [31, 40, 28, 51, 42, 109, 100] }],
        //     xaxis: { categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] },
        //     colors: ['#43B9B2'],
        //     stroke: { curve: 'smooth', width: 2 },
        //     fill: { type: 'gradient', gradient: { opacityFrom: 0.4, opacityTo: 0.05 } }
        // };
        // var chart = new ApexCharts(document.querySelector("#activityChart"), options);
        // chart.render();
    });
</script>
@endsection
