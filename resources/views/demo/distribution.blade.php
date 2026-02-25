@extends('layouts.admin')

@section('title', 'Demo Distribution')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Demo Distribution</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('demo.index') }}">Demos</a></li>
                    <li class="breadcrumb-item active">Distribution</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

{{-- Distribution Form --}}
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold">New Distribution</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('demo.store-distribution') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="demo_master_id" class="form-label">Demo Lot <span class="text-danger">*</span></label>
                    <select class="form-select @error('demo_master_id') is-invalid @enderror"
                            id="demo_master_id" name="demo_master_id" required>
                        <option value="">-- Select Demo Lot --</option>
                        @foreach($demoMasters ?? [] as $master)
                            <option value="{{ $master->id }}" {{ old('demo_master_id') == $master->id ? 'selected' : '' }}>
                                {{ $master->lot_no }} - {{ $master->product->sku_name ?? $master->product_name ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('demo_master_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="to_user_id" class="form-label">Distribute To (User) <span class="text-danger">*</span></label>
                    <select class="form-select @error('to_user_id') is-invalid @enderror"
                            id="to_user_id" name="to_user_id" required>
                        <option value="">-- Select User --</option>
                        @foreach($users ?? [] as $user)
                            <option value="{{ $user->id }}" {{ old('to_user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name ?? '' }} ({{ strtoupper($user->role ?? '') }})
                            </option>
                        @endforeach
                    </select>
                    @error('to_user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                           id="quantity" name="quantity" value="{{ old('quantity') }}"
                           placeholder="Enter quantity" min="1" required>
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="distribution_date" class="form-label">Distribution Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('distribution_date') is-invalid @enderror"
                           id="distribution_date" name="distribution_date"
                           value="{{ old('distribution_date', date('Y-m-d')) }}" required>
                    @error('distribution_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="challan_number" class="form-label">Challan Number</label>
                    <input type="text" class="form-control @error('challan_number') is-invalid @enderror"
                           id="challan_number" name="challan_number"
                           value="{{ old('challan_number') }}" placeholder="Enter challan number">
                    @error('challan_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-theme w-100">
                        <i class="fas fa-plus me-1"></i> Create Distribution
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Distribution List --}}
<div class="card">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold">Distribution Records</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="dataTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Lot No</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Distributed To</th>
                        <th>Date</th>
                        <th>Acknowledged</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($distributions ?? [] as $dist)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="fw-medium">{{ $dist->demoMaster->lot_no ?? '-' }}</span></td>
                        <td>{{ $dist->demoMaster->product->sku_name ?? $dist->demoMaster->product_name ?? '-' }}</td>
                        <td>{{ $dist->quantity ?? '-' }} {{ $dist->quantity_unit ?? '' }}</td>
                        <td>{{ $dist->toUser->name ?? $dist->to_location_name ?? '-' }}</td>
                        <td class="text-muted">{{ $dist->distribution_date ?? '-' }}</td>
                        <td>
                            @if($dist->is_acknowledged)
                                <span class="badge bg-success bg-opacity-10 text-success">Yes</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning">No</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $dStatusClass = match(strtolower($dist->status ?? 'pending')) {
                                    'distributed', 'completed' => 'completed',
                                    'in_transit' => 'in-progress',
                                    default => 'pending',
                                };
                            @endphp
                            <span class="badge-status {{ $dStatusClass }}">{{ ucfirst(str_replace('_', ' ', $dist->status ?? 'Pending')) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>
                            No distribution records found.
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
    $('#dataTable').DataTable({ paging: false, info: false, searching: true });
});
</script>
@endsection
