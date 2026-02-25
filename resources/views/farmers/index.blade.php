@extends('layouts.admin')

@section('title', 'Farmer Management')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <div class="page-title-area">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h3>Farmer Management</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Farmers</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('farmers.create') }}" class="btn btn-theme">
                <i class="fas fa-plus me-1"></i> Register Farmer
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
                            <th>Farmer Code</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Farmer Type</th>
                            <th>Village</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($farmers as $farmer)
                            <tr>
                                <td>{{ $loop->iteration + (($farmers->currentPage() - 1) * $farmers->perPage()) }}</td>
                                <td><span class="badge bg-light text-dark">{{ $farmer->farmer_code ?? '-' }}</span></td>
                                <td>
                                    <span class="fw-medium">{{ $farmer->name }}</span>
                                    @if($farmer->father_name)
                                        <br><small class="text-muted">S/o {{ $farmer->father_name }}</small>
                                    @endif
                                </td>
                                <td>{{ $farmer->mobile ?? '-' }}</td>
                                <td>
                                    @php
                                        $typeLabels = ['pda' => 'PDA', 'demo' => 'Demo', 'user' => 'User', 'non_user' => 'Non-User'];
                                    @endphp
                                    <span
                                        class="badge bg-light text-dark">{{ $typeLabels[$farmer->farmer_type] ?? ucfirst($farmer->farmer_type ?? '-') }}</span>
                                </td>
                                <td>{{ $farmer->village->name ?? '-' }}</td>
                                <td>
                                    @php
                                        $statusClass = match (strtolower($farmer->status ?? 'active')) {
                                            'active' => 'completed',
                                            'inactive' => 'pending',
                                            default => 'pending',
                                        };
                                    @endphp
                                    <span
                                        class="badge-status {{ $statusClass }}">{{ ucfirst($farmer->status ?? 'Active') }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('farmers.show', $farmer) }}" class="btn btn-sm btn-outline-info me-1"
                                        title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('farmers.edit', $farmer) }}" class="btn btn-sm btn-outline-warning me-1"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('farmers.destroy', $farmer) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this farmer?');">
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
                                    No farmers found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $farmers->links() }}
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
                order: [[0, 'asc']],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records..."
                }
            });
        });
    </script>
@endsection