@extends('layouts.admin')

@section('title', 'Retailer Management')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <div class="page-title-area">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h3>Retailer Management</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Retailers</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('retailers.create') }}" class="btn btn-theme">
                <i class="fas fa-plus me-1"></i> Register Retailer
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
                            <th>Retailer Code</th>
                            <th>Shop Name</th>
                            <th>Owner</th>
                            <th>Mobile</th>
                            <th>Business Type</th>
                            <th>KYC Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($retailers as $retailer)
                            <tr>
                                <td>{{ $loop->iteration + (($retailers->currentPage() - 1) * $retailers->perPage()) }}</td>
                                <td><span class="badge bg-light text-dark">{{ $retailer->retailer_code ?? '-' }}</span></td>
                                <td><span class="fw-medium">{{ $retailer->shop_name ?? '-' }}</span></td>
                                <td>{{ $retailer->owner_name ?? '-' }}</td>
                                <td>{{ $retailer->mobile ?? '-' }}</td>
                                <td><span
                                        class="badge bg-light text-dark">{{ ucfirst(str_replace('_', ' ', $retailer->business_type ?? '-')) }}</span>
                                </td>
                                <td>
                                    @php
                                        $kycClass = match (strtolower($retailer->kyc_status ?? 'pending')) {
                                            'verified', 'approved' => 'completed',
                                            'pending' => 'pending',
                                            'rejected' => 'in-progress',
                                            default => 'pending',
                                        };
                                    @endphp
                                    <span
                                        class="badge-status {{ $kycClass }}">{{ ucfirst($retailer->kyc_status ?? 'Pending') }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('retailers.show', $retailer) }}" class="btn btn-sm btn-outline-info me-1"
                                        title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('retailers.edit', $retailer) }}"
                                        class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('retailers.destroy', $retailer) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this retailer?');">
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
                                    No retailers found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $retailers->links() }}
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