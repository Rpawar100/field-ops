@extends('layouts.admin')

@section('title', 'Attendance Report')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Attendance Report</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
                    <li class="breadcrumb-item active">Attendance</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('reports.export', ['type' => 'attendance', 'from_date' => request('from_date'), 'to_date' => request('to_date'), 'user_id' => request('user_id'), 'attendance_type' => request('attendance_type')]) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-download me-1"></i> Export CSV
        </a>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('reports.attendance') }}" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small">From Date</label>
                <input type="date" class="form-control form-control-sm" name="from_date" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small">To Date</label>
                <input type="date" class="form-control form-control-sm" name="to_date" value="{{ request('to_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small">User</label>
                <select class="form-select form-select-sm" name="user_id">
                    <option value="">All Users</option>
                    @foreach($users ?? [] as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Attendance Type</label>
                <select class="form-select form-select-sm" name="attendance_type">
                    <option value="">All Types</option>
                    @foreach($attendanceTypes ?? [] as $aType)
                        <option value="{{ $aType }}" {{ request('attendance_type') === $aType ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $aType)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-theme btn-sm w-100"><i class="fas fa-filter me-1"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <div style="font-size:1.5rem;font-weight:700;color:#198754;">{{ $summary['present'] ?? 0 }}</div>
                <div class="small text-muted">Present</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <div style="font-size:1.5rem;font-weight:700;color:#dc3545;">{{ $summary['absent'] ?? 0 }}</div>
                <div class="small text-muted">Absent</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <div style="font-size:1.5rem;font-weight:700;color:#ffc107;">{{ $summary['on_leave'] ?? 0 }}</div>
                <div class="small text-muted">On Leave</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <div style="font-size:1.5rem;font-weight:700;color:var(--theme-default);">{{ $summary['percentage'] ?? '0%' }}</div>
                <div class="small text-muted">Attendance %</div>
            </div>
        </div>
    </div>
</div>

{{-- DataTable --}}
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="attendanceTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>User</th>
                        <th>Type</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Working (min)</th>
                        <th>Activities</th>
                        <th>GPS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances ?? [] as $att)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-muted">{{ $att->attendance_date ? \Carbon\Carbon::parse($att->attendance_date)->format('d M Y') : '-' }}</td>
                        <td><span class="fw-medium">{{ $att->user->name ?? '-' }}</span></td>
                        <td>
                            @php
                                $typeClass = match($att->attendance_type ?? '') {
                                    'present', 'field_work', 'office' => 'bg-success',
                                    'leave', 'sick_leave' => 'bg-warning text-dark',
                                    'absent' => 'bg-danger',
                                    'half_day' => 'bg-info',
                                    'holiday' => 'bg-secondary',
                                    default => 'bg-light text-dark',
                                };
                            @endphp
                            <span class="badge {{ $typeClass }}">{{ ucfirst(str_replace('_', ' ', $att->attendance_type ?? '-')) }}</span>
                        </td>
                        <td>{{ $att->check_in_time ? \Carbon\Carbon::parse($att->check_in_time)->format('h:i A') : '-' }}</td>
                        <td>{{ $att->check_out_time ? \Carbon\Carbon::parse($att->check_out_time)->format('h:i A') : '-' }}</td>
                        <td>
                            @if($att->total_working_minutes)
                                <span class="fw-medium">{{ $att->total_working_minutes }}</span>
                                <small class="text-muted">({{ floor($att->total_working_minutes / 60) }}h {{ $att->total_working_minutes % 60 }}m)</small>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($att->activity_count)
                                <span class="badge bg-light text-dark">{{ $att->activity_count }}</span>
                            @else
                                <span class="text-muted">0</span>
                            @endif
                        </td>
                        <td>
                            @if($att->check_in_latitude && $att->check_in_longitude)
                                <a href="https://maps.google.com/?q={{ $att->check_in_latitude }},{{ $att->check_in_longitude }}" target="_blank" class="btn btn-sm btn-outline-theme" title="View on Map">
                                    <i class="fas fa-map-marker-alt"></i>
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>
                            No attendance records found.
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
    $('#attendanceTable').DataTable({
        paging: true,
        pageLength: 25,
        info: true,
        searching: true,
        order: [[1, 'desc']],
        language: {
            emptyTable: "No attendance records found."
        }
    });
});
</script>
@endsection
