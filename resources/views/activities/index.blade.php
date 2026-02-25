@extends('layouts.admin')

@section('title', 'Activity Management')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <div class="page-title-area">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h3>Activity Management</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Activities</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('activities.create') }}" class="btn btn-theme">
                <i class="fas fa-plus me-1"></i> Create Activity
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>User</th>
                            <th>Farmer / Retailer</th>
                            <th>Village</th>
                            <th>Status</th>
                            <th>Execution Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $activity)
                            <tr>
                                <td>{{ $loop->iteration + (($activities->currentPage() - 1) * $activities->perPage()) }}</td>
                                <td><span class="badge bg-light text-dark">{{ $activity->activityType->name ?? '-' }}</span>
                                </td>
                                <td>{{ $activity->user->name ?? '-' }}</td>
                                <td>
                                    @if($activity->farmer)
                                        <i class="fas fa-leaf text-success me-1"></i>{{ $activity->farmer->name }}
                                    @elseif($activity->retailer)
                                        <i class="fas fa-store text-info me-1"></i>{{ $activity->retailer->shop_name }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $activity->village->name ?? '-' }}</td>
                                <td>
                                    @php
                                        $statusClass = match (strtolower($activity->status ?? 'draft')) {
                                            'completed' => 'completed',
                                            'submitted' => 'in-progress',
                                            'cancelled' => 'in-progress',
                                            default => 'pending',
                                        };
                                    @endphp
                                    <span
                                        class="badge-status {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $activity->status ?? 'Draft')) }}</span>
                                </td>
                                <td class="text-muted">{{ $activity->execution_date ?? '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('activities.show', $activity) }}" class="btn btn-sm btn-outline-info me-1"
                                        title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(strtolower($activity->status ?? '') !== 'completed')
                                        <a href="{{ route('activities.execute-form', $activity) }}"
                                            class="btn btn-sm btn-outline-success me-1" title="Execute">
                                            <i class="fas fa-play"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('activities.edit', $activity) }}"
                                        class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('activities.destroy', $activity) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this activity?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>
                                    No activities found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $activities->links() }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable({
                paging: false,
                info: false,
                searching: true,
                responsive: true,
                order: [[6, 'desc']],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records..."
                }
            });
        });
    </script>
@endsection