@extends('layouts.admin')

@section('title', 'Attendance Log')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Daily Attendance Log</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Attendance</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('attendance.calendar') }}" class="btn btn-outline-theme me-1">
                <i class="fas fa-calendar me-1"></i> Calendar View
            </a>
            <a href="{{ route('attendance.map') }}" class="btn btn-outline-secondary">
                <i class="fas fa-map-marker-alt me-1"></i> Map View
            </a>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('attendance.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label for="attendance_date" class="form-label small">Date</label>
                <input type="date" class="form-control form-control-sm" id="attendance_date" name="attendance_date"
                       value="{{ request('attendance_date', date('Y-m-d')) }}">
            </div>
            <div class="col-md-3">
                <label for="user_id" class="form-label small">User</label>
                <select class="form-select form-select-sm" id="user_id" name="user_id">
                    <option value="">All Users</option>
                    @foreach($users ?? [] as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name ?? '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="attendance_type" class="form-label small">Type</label>
                <select class="form-select form-select-sm" id="attendance_type" name="attendance_type">
                    <option value="">All</option>
                    <option value="present" {{ request('attendance_type') === 'present' ? 'selected' : '' }}>Present</option>
                    <option value="absent" {{ request('attendance_type') === 'absent' ? 'selected' : '' }}>Absent</option>
                    <option value="leave" {{ request('attendance_type') === 'leave' ? 'selected' : '' }}>Leave</option>
                    <option value="half_day" {{ request('attendance_type') === 'half_day' ? 'selected' : '' }}>Half Day</option>
                    <option value="holiday" {{ request('attendance_type') === 'holiday' ? 'selected' : '' }}>Holiday</option>
                    <option value="week_off" {{ request('attendance_type') === 'week_off' ? 'selected' : '' }}>Week Off</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-theme btn-sm w-100">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="dataTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Date</th>
                        <th>Check-in Time</th>
                        <th>Check-out Time</th>
                        <th>Type</th>
                        <th>Working Min</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $att)
                    <tr>
                        <td>{{ $loop->iteration + (($attendances->currentPage() - 1) * $attendances->perPage()) }}</td>
                        <td>
                            <span class="fw-medium">{{ $att->user->name ?? '-' }}</span>
                        </td>
                        <td>{{ $att->attendance_date ?? '-' }}</td>
                        <td>
                            @if($att->check_in_time)
                                <span class="text-success"><i class="fas fa-sign-in-alt me-1"></i>{{ $att->check_in_time }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($att->check_out_time)
                                <span class="text-danger"><i class="fas fa-sign-out-alt me-1"></i>{{ $att->check_out_time }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $attStatusClass = match(strtolower($att->attendance_type ?? 'absent')) {
                                    'present' => 'completed',
                                    'leave' => 'in-progress',
                                    'half_day' => 'in-progress',
                                    'holiday', 'week_off' => 'in-progress',
                                    default => 'pending',
                                };
                            @endphp
                            <span class="badge-status {{ $attStatusClass }}">{{ ucfirst(str_replace('_', ' ', $att->attendance_type ?? 'Absent')) }}</span>
                        </td>
                        <td>{{ $att->total_working_minutes ?? '-' }}</td>
                        <td>
                            @if($att->check_in_latitude && $att->check_in_longitude)
                                <a href="https://www.google.com/maps?q={{ $att->check_in_latitude }},{{ $att->check_in_longitude }}"
                                   target="_blank" class="text-theme small">
                                    <i class="fas fa-map-marker-alt me-1"></i>View
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>
                            No attendance records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $attendances->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $(''#dataTable'').DataTable({
        paging: false,
        info: false,
        searching: true,
        responsive: true,
        order: [[2, 'desc']],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search records..."
        }
    });
});
</script>
@endsection
