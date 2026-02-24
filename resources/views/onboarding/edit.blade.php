@extends('layouts.admin')

@section('title', 'Edit Onboarding Application')

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Edit Onboarding Application</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('onboarding.index') }}">Onboarding</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold">Edit Application: {{ $user->name ?? '' }}</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('onboarding.update', $user) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- User Details (from users table) --}}
            <h6 class="fw-semibold text-muted mb-3">Personal Information</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="user_id_field" class="form-label">Employee ID</label>
                    <input type="text" class="form-control" id="user_id_field"
                           value="{{ $user->user_id ?? '' }}" readonly disabled>
                </div>
                <div class="col-md-6">
                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name"
                           value="{{ old('name', $user->name ?? '') }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="mobile" class="form-label">Mobile <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('mobile') is-invalid @enderror"
                           id="mobile" name="mobile"
                           value="{{ old('mobile', $user->mobile ?? '') }}" required maxlength="15">
                    @error('mobile') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                           id="email" name="email"
                           value="{{ old('email', $user->email ?? '') }}">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                        <option value="">-- Select Role --</option>
                        @php $roles = ['nsm' => 'NSM', 'zm' => 'ZM', 'rm' => 'RM', 'tsl' => 'TSL', 'fa' => 'FA']; @endphp
                        @foreach($roles as $val => $lbl)
                            <option value="{{ $val }}" {{ old('role', $user->role ?? '') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                    @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="designation" class="form-label">Designation</label>
                    <input type="text" class="form-control @error('designation') is-invalid @enderror"
                           id="designation" name="designation"
                           value="{{ old('designation', $user->designation ?? '') }}"
                           placeholder="Enter designation" maxlength="100">
                    @error('designation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="fa_type" class="form-label">FA Type</label>
                    <select class="form-select @error('fa_type') is-invalid @enderror" id="fa_type" name="fa_type">
                        <option value="">-- Select FA Type --</option>
                        <option value="permanent" {{ old('fa_type', $user->fa_type ?? '') === 'permanent' ? 'selected' : '' }}>Permanent</option>
                        <option value="temporary" {{ old('fa_type', $user->fa_type ?? '') === 'temporary' ? 'selected' : '' }}>Temporary</option>
                    </select>
                    @error('fa_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="date_of_joining" class="form-label">Date of Joining</label>
                    <input type="date" class="form-control @error('date_of_joining') is-invalid @enderror"
                           id="date_of_joining" name="date_of_joining"
                           value="{{ old('date_of_joining', $user->date_of_joining ?? '') }}">
                    @error('date_of_joining') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                        <option value="pending" {{ old('status', $user->status ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="active" {{ old('status', $user->status ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $user->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ old('status', $user->status ?? '') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-12">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control @error('address') is-invalid @enderror"
                              id="address" name="address" rows="2">{{ old('address', $user->address ?? '') }}</textarea>
                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- KYC Details (from fa_onboarding_details) --}}
            <hr>
            <h6 class="fw-semibold text-muted mb-3">KYC & Documents</h6>
            @php $details = $user->onboardingDetails ?? null; @endphp
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="pan_number" class="form-label">PAN Number</label>
                    <input type="text" class="form-control @error('pan_number') is-invalid @enderror"
                           id="pan_number" name="pan_number"
                           value="{{ old('pan_number', $details->pan_number ?? '') }}" maxlength="10">
                    @error('pan_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="aadhaar_number" class="form-label">Aadhaar Number</label>
                    <input type="text" class="form-control @error('aadhaar_number') is-invalid @enderror"
                           id="aadhaar_number" name="aadhaar_number"
                           value="{{ old('aadhaar_number', $details->aadhaar_number ?? '') }}" maxlength="12">
                    @error('aadhaar_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="driving_license_number" class="form-label">Driving License Number</label>
                    <input type="text" class="form-control @error('driving_license_number') is-invalid @enderror"
                           id="driving_license_number" name="driving_license_number"
                           value="{{ old('driving_license_number', $details->driving_license_number ?? '') }}">
                    @error('driving_license_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="qualification" class="form-label">Qualification</label>
                    <input type="text" class="form-control @error('qualification') is-invalid @enderror"
                           id="qualification" name="qualification"
                           value="{{ old('qualification', $details->qualification ?? '') }}">
                    @error('qualification') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Banking --}}
                <div class="col-md-6">
                    <label for="bank_name" class="form-label">Bank Name</label>
                    <input type="text" class="form-control @error('bank_name') is-invalid @enderror"
                           id="bank_name" name="bank_name"
                           value="{{ old('bank_name', $details->bank_name ?? '') }}">
                    @error('bank_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="bank_branch" class="form-label">Branch</label>
                    <input type="text" class="form-control @error('bank_branch') is-invalid @enderror"
                           id="bank_branch" name="bank_branch"
                           value="{{ old('bank_branch', $details->bank_branch ?? '') }}">
                    @error('bank_branch') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="bank_account_number" class="form-label">Account Number</label>
                    <input type="text" class="form-control @error('bank_account_number') is-invalid @enderror"
                           id="bank_account_number" name="bank_account_number"
                           value="{{ old('bank_account_number', $details->bank_account_number ?? '') }}">
                    @error('bank_account_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="ifsc_code" class="form-label">IFSC Code</label>
                    <input type="text" class="form-control @error('ifsc_code') is-invalid @enderror"
                           id="ifsc_code" name="ifsc_code"
                           value="{{ old('ifsc_code', $details->ifsc_code ?? '') }}" maxlength="11">
                    @error('ifsc_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- KYC Status --}}
                <div class="col-md-6">
                    <label for="kyc_status" class="form-label">KYC Status</label>
                    <select class="form-select @error('kyc_status') is-invalid @enderror" id="kyc_status" name="kyc_status">
                        <option value="pending" {{ old('kyc_status', $details->kyc_status ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="verified" {{ old('kyc_status', $details->kyc_status ?? '') === 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="rejected" {{ old('kyc_status', $details->kyc_status ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    @error('kyc_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-theme">
                    <i class="fas fa-save me-1"></i> Update Application
                </button>
                <a href="{{ route('onboarding.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
