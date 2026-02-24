@extends('layouts.admin')

@section('title', 'User Management')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <div class="page-title-area">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h3>User Management</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('users.create') }}" class="btn btn-theme">
                <i class="fas fa-plus me-1"></i> Add User
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
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Role</th>
                            <th>Designation</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration + (($users->currentPage() - 1) * $users->perPage()) }}</td>
                                <td><span class="badge bg-light text-dark">{{ $user->user_id ?? '-' }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="user-avatar" style="width:32px;height:32px;font-size:0.75rem;">
                                            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="fw-medium">{{ $user->name ?? '' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->mobile ?? '-' }}</td>
                                <td><span class="badge bg-light text-dark">{{ strtoupper($user->role ?? '-') }}</span></td>
                                <td>{{ $user->designation ?? '-' }}</td>
                                <td>
                                    @php
                                        $statusClass = match (strtolower($user->status ?? 'active')) {
                                            'active' => 'completed',
                                            'inactive' => 'pending',
                                            'pending' => 'pending',
                                            'suspended' => 'in-progress',
                                            default => 'pending',
                                        };
                                    @endphp
                                    <span
                                        class="badge-status {{ $statusClass }}">{{ ucfirst($user->status ?? 'Active') }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-info me-1"
                                        title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-warning me-1"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this user?');">
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
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $users->links() }}
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