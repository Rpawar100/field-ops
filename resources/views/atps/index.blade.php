@extends('layouts.admin')

@section('title', 'Tour Plans (ATP)')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Tour Plans (ATP)</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Tour Plans</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('atps.create') }}" class="btn btn-theme">
            <i class="fas fa-plus me-1"></i> Create Tour Plan
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
                        <th>Month</th>
                        <th>FA Name</th>
                        <th>Status</th>
                        <th>Beats Count</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($atps as $atp)
                    <tr>
                        <td>{{ $loop->iteration + (($atps->currentPage() - 1) * $atps->perPage()) }}</td>
                        <td><span class="fw-medium">{{ $atp->month ?? '-' }}</span></td>
                        <td>{{ $atp->user->name ?? '-' }}</td>
                        <td>
                            @php
                                $atpStatusClass = match(strtolower($atp->status ?? 'draft')) {
                                    'approved' => 'completed',
                                    'submitted' => 'in-progress',
                                    'rejected' => 'in-progress',
                                    default => 'pending',
                                };
                            @endphp
                            <span class="badge-status {{ $atpStatusClass }}">{{ ucfirst($atp->status ?? 'Draft') }}</span>
                        </td>
                        <td>{{ $atp->items_count ?? ($atp->items ? $atp->items->count() : 0) }}</td>
                        <td class="text-end">
                            <a href="{{ route('atps.show', $atp) }}" class="btn btn-sm btn-outline-info me-1" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('atps.edit', $atp) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('atps.destroy', $atp) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete this tour plan?');">
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
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>
                            No tour plans found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $atps->links() }}
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
        order: [[1, 'desc']],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search records..."
        }
    });
});
</script>
@endsection
