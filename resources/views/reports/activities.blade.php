@extends('layouts.admin')

@section('title', 'Activity Report')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Activity Report</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
                    <li class="breadcrumb-item active">Activities</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('reports.export', ['type' => 'activities', 'from_date' => request('from_date'), 'to_date' => request('to_date'), 'activity_type_id' => request('activity_type_id'), 'user_id' => request('user_id'), 'status' => request('status')]) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-download me-1"></i> Export CSV
        </a>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('reports.activities') }}" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small">From Date</label>
                <input type="date" class="form-control form-control-sm" name="from_date" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small">To Date</label>
                <input type="date" class="form-control form-control-sm" name="to_date" value="{{ request('to_date') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Activity Type</label>
                <select class="form-select form-select-sm" name="activity_type_id">
                    <option value="">All Types</option>
                    @foreach($activityTypes ?? [] as $type)
                        <option value="{{ $type->id }}" {{ request('activity_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">User</label>
                <select class="form-select form-select-sm" name="user_id">
                    <option value="">All Users</option>
                    @foreach($users ?? [] as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Status</label>
                <select class="form-select form-select-sm" name="status">
                    <option value="">All</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-theme btn-sm w-100"><i class="fas fa-filter me-1"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="activitiesTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Execution Date</th>
                        <th>User</th>
                        <th>Activity Type</th>
                        <th>Farmer</th>
                        <th>Beat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities ?? [] as $activity)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-muted">{{ $activity->execution_date ? \Carbon\Carbon::parse($activity->execution_date)->format('d M Y') : '-' }}</td>
                        <td>
                            <span class="fw-medium">{{ $activity->user->name ?? '-' }}</span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">{{ $activity->activityType->name ?? '-' }}</span>
                        </td>
                        <td>{{ $activity->farmer->name ?? '-' }}</td>
                        <td>{{ $activity->beat->name ?? '-' }}</td>
                        <td>
                            @php
                                $statusClass = match($activity->status ?? 'draft') {
                                    'completed' => 'bg-success',
                                    'submitted' => 'bg-primary',
                                    'draft' => 'bg-warning text-dark',
                                    'cancelled' => 'bg-danger',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ ucfirst($activity->status ?? 'Draft') }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>
                            No activities match your filters.
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
    $('#activitiesTable').DataTable({
        paging: true,
        pageLength: 25,
        info: true,
        searching: true,
        order: [[1, 'desc']],
        language: {
            emptyTable: "No activities match your filters."
        }
    });
});
</script>
@endsection
