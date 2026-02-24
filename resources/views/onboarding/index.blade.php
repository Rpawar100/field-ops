@extends('layouts.admin')

@section('title', 'FA Onboarding')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>FA Onboarding</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Onboarding</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('onboarding.create') }}" class="btn btn-theme">
            <i class="fas fa-plus me-1"></i> New Application
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
                        <th>Date of Joining</th>
                        <th>FA Type</th>
                        <th>KYC Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $app)
                    <tr>
                        <td>{{ $loop->iteration + (($applications->currentPage() - 1) * $applications->perPage()) }}</td>
                        <td><span class="text-muted">{{ $app->user_id ?? '-' }}</span></td>
                        <td><span class="fw-medium">{{ $app->name ?? '-' }}</span></td>
                        <td>{{ $app->mobile ?? '-' }}</td>
                        <td>{{ strtoupper($app->role ?? '-') }}</td>
                        <td class="text-muted">{{ $app->date_of_joining ? \Carbon\Carbon::parse($app->date_of_joining)->format('d M Y') : '-' }}</td>
                        <td>{{ $app->fa_type ? ucfirst($app->fa_type) : '-' }}</td>
                        <td>
                            @php
                                $kycStatus = optional($app->faOnboardingDetail)->kyc_status ?? 'pending';
                                $kycClass = match(strtolower($kycStatus)) {
                                    'verified' => 'completed',
                                    'pending' => 'pending',
                                    'rejected' => 'in-progress',
                                    default => 'pending',
                                };
                            @endphp
                            <span class="badge-status {{ $kycClass }}">{{ ucfirst(str_replace('_', ' ', $kycStatus)) }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('onboarding.show', $app) }}" class="btn btn-sm btn-outline-info me-1" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('onboarding.edit', $app) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('onboarding.destroy', $app) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete this application?');">
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
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>
                            No onboarding applications found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $applications->links() }}
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
        order: [[5, 'desc']],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search records..."
        }
    });
});
</script>
@endsection
