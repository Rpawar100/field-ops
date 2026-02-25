@extends('layouts.admin')
@section('title', 'Beat Management')
@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endsection
@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Beat Management</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Beats</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('beats.create') }}" class="btn btn-theme"><i class="fas fa-plus me-1"></i> Add Beat</a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="dataTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Beat Code</th>
                        <th>Name</th>
                        <th>HQ</th>
                        <th>Beat Day</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($beats as $beat)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="badge bg-light text-dark">{{ $beat->beat_code ?? '-' }}</span></td>
                        <td><span class="fw-medium">{{ $beat->name }}</span></td>
                        <td>{{ $beat->zrthHierarchy->name ?? '-' }}</td>
                        <td>{{ ucfirst($beat->beat_day ?? '-') }}</td>
                        <td>
                            @if(($beat->status ?? 'active') === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('beats.show', $beat) }}" class="btn btn-sm btn-outline-info me-1" title="View"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('beats.edit', $beat) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('beats.destroy', $beat) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this beat?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>No beats found.
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
<script>$(document).ready(function() { $(''#dataTable'').DataTable({
        paging: false,
        info: false,
        searching: true,
        responsive: true,
        order: [[1, 'asc']],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search records..."
        }
    }); });</script>
@endsection
