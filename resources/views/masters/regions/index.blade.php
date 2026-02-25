@extends('layouts.admin')
@section('title', 'Region Management')
@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endsection
@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Region Management</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">ZRTH Hierarchy</li>
                    <li class="breadcrumb-item active">Regions</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('regions.create') }}" class="btn btn-theme"><i class="fas fa-plus me-1"></i> Add Region</a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="dataTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Zone</th>
                        <th>Territories</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($regions as $region)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="badge bg-light text-dark">{{ $region->code ?? '-' }}</span></td>
                        <td><span class="fw-medium">{{ $region->name }}</span></td>
                        <td>{{ $region->parent->name ?? '-' }}</td>
                        <td>{{ $region->children_count ?? ($region->children ? $region->children->count() : 0) }}</td>
                        <td>
                            @php $statusClass = ($region->status ?? 'active') === 'active' ? 'completed' : 'pending'; @endphp
                            <span class="badge-status {{ $statusClass }}">{{ ucfirst($region->status ?? 'Active') }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('regions.show', $region) }}" class="btn btn-sm btn-outline-info me-1" title="View"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('regions.edit', $region) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('regions.destroy', $region) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this region?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted"><i class="fas fa-inbox d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>No regions found.</td></tr>
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
        order: [[0, 'asc']],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search records..."
        }
    }); });</script>
@endsection
