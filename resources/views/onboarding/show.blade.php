@extends('layouts.admin')

@section('title', 'Onboarding Application Details')

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Application Details</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('onboarding.index') }}">Onboarding</a></li>
                    <li class="breadcrumb-item active">{{ $user->name ?? '' }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('onboarding.edit', $user) }}" class="btn btn-outline-theme me-1"><i class="fas fa-edit me-1"></i> Edit</a>
            <a href="{{ route('onboarding.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
        </div>
    </div>
</div>

<div class="row">
    {{-- Profile Card --}}
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body text-center pt-4">
                <div class="user-avatar mx-auto mb-3" style="width:80px;height:80px;font-size:2rem;">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                </div>
                <h5 class="fw-semibold mb-1">{{ $user->name ?? '' }}</h5>
                <p class="text-muted mb-1">{{ $user->designation ?? 'Field Agent' }}</p>
                <p class="text-muted mb-2" style="font-size:0.82rem;">{{ strtoupper($user->role ?? '') }} &middot; {{ $user->user_id ?? '' }}</p>
                @php
                    $userStatusClass = match(strtolower($user->status ?? 'pending')) {
                        'active' => 'completed',
                        'inactive' => 'in-progress',
                        'suspended' => 'in-progress',
                        default => 'pending',
                    };
                @endphp
                <span class="badge-status {{ $userStatusClass }}">{{ ucfirst($user->status ?? 'Pending') }}</span>
            </div>
            <hr class="my-0">
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-phone me-2"></i>Mobile</span>
                        <span class="fw-medium">{{ $user->mobile ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-envelope me-2"></i>Email</span>
                        <span class="fw-medium">{{ $user->email ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-id-badge me-2"></i>FA Type</span>
                        <span class="fw-medium">{{ $user->fa_type ? ucfirst($user->fa_type) : '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-calendar me-2"></i>Date of Joining</span>
                        <span class="fw-medium">{{ $user->date_of_joining ? \Carbon\Carbon::parse($user->date_of_joining)->format('d M Y') : '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted"><i class="fas fa-user-tie me-2"></i>Reporting Manager</span>
                        <span class="fw-medium">{{ $user->reportingManager->name ?? '-' }}</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Approve / Reject Actions --}}
        @if(in_array(strtolower($user->status ?? 'pending'), ['pending']))
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Actions</h6></div>
            <div class="card-body">
                <form method="POST" action="{{ route('onboarding.approve', $user) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-theme w-100 mb-2" onclick="return confirm('Approve this application?');">
                        <i class="fas fa-check me-1"></i> Approve Application
                    </button>
                </form>
                <form method="POST" action="{{ route('onboarding.reject', $user) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Reject this application?');">
                        <i class="fas fa-times me-1"></i> Reject Application
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>

    <div class="col-xl-8">
        {{-- KYC / Onboarding Details (from fa_onboarding_details) --}}
        @php $details = $user->onboardingDetails ?? null; @endphp
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-semibold">KYC & Banking Details</h6></div>
            <div class="card-body">
                @if($details)
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="text-muted small">PAN Number</label>
                        <p class="fw-medium mb-0">{{ $details->pan_number ?? '-' }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Aadhaar Number</label>
                        <p class="fw-medium mb-0">{{ $details->aadhaar_number ?? '-' }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Driving License</label>
                        <p class="fw-medium mb-0">{{ $details->driving_license_number ?? '-' }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Qualification</label>
                        <p class="fw-medium mb-0">{{ $details->qualification ?? '-' }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Experience</label>
                        <p class="fw-medium mb-0">{{ $details->experience_details ?? '-' }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">KYC Status</label>
                        <p class="fw-medium mb-0">
                            @php
                                $kycClass = match(strtolower($details->kyc_status ?? 'pending')) {
                                    'verified' => 'completed',
                                    'rejected' => 'in-progress',
                                    default => 'pending',
                                };
                            @endphp
                            <span class="badge-status {{ $kycClass }}">{{ ucfirst(str_replace('_', ' ', $details->kyc_status ?? 'Pending')) }}</span>
                        </p>
                    </div>
                    <div class="col-12"><hr class="my-2"></div>
                    <div class="col-md-3">
                        <label class="text-muted small">Bank Name</label>
                        <p class="fw-medium mb-0">{{ $details->bank_name ?? '-' }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Branch</label>
                        <p class="fw-medium mb-0">{{ $details->bank_branch ?? '-' }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Account Number</label>
                        <p class="fw-medium mb-0">{{ $details->bank_account_number ?? '-' }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">IFSC Code</label>
                        <p class="fw-medium mb-0">{{ $details->ifsc_code ?? '-' }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Proposed Salary</label>
                        <p class="fw-medium mb-0">{{ $details->proposed_salary ? number_format($details->proposed_salary, 2) : '-' }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Approved Salary</label>
                        <p class="fw-medium mb-0">{{ $details->approved_salary ? number_format($details->approved_salary, 2) : '-' }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Salary Status</label>
                        <p class="fw-medium mb-0">{{ $details->salary_status ? ucfirst($details->salary_status) : '-' }}</p>
                    </div>
                    @if($details->emergency_contact_name)
                    <div class="col-12"><hr class="my-2"></div>
                    <div class="col-md-4">
                        <label class="text-muted small">Emergency Contact</label>
                        <p class="fw-medium mb-0">{{ $details->emergency_contact_name }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Emergency Phone</label>
                        <p class="fw-medium mb-0">{{ $details->emergency_contact_phone ?? '-' }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Relation</label>
                        <p class="fw-medium mb-0">{{ $details->emergency_contact_relation ?? '-' }}</p>
                    </div>
                    @endif
                </div>
                @else
                <p class="text-muted text-center py-3 mb-0">
                    <i class="fas fa-id-card d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>
                    No onboarding details recorded yet.
                </p>
                @endif
            </div>
        </div>

        {{-- Documents Grid (from fa_documents) --}}
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Documents</h6></div>
            <div class="card-body">
                @if(isset($documents) && count($documents) > 0)
                    <div class="row g-3">
                        @foreach($documents as $doc)
                        <div class="col-md-4">
                            <div class="border rounded p-3 text-center">
                                <i class="fas fa-file-alt d-block mb-2" style="font-size:2rem;color:var(--theme-default);"></i>
                                <p class="small fw-medium mb-1">{{ $doc->document_type ?? 'Document' }}</p>
                                <p class="text-muted mb-2" style="font-size:0.75rem;">{{ $doc->document_name ?? '' }}</p>
                                @php
                                    $docStatusClass = match(strtolower($doc->status ?? 'pending')) {
                                        'verified', 'approved' => 'completed',
                                        'rejected' => 'in-progress',
                                        default => 'pending',
                                    };
                                @endphp
                                <span class="badge-status {{ $docStatusClass }} mb-2 d-inline-block">{{ ucfirst($doc->status ?? 'Pending') }}</span>
                                <br>
                                @if($doc->document_path)
                                <a href="{{ asset('storage/' . $doc->document_path) }}" target="_blank" class="btn btn-sm btn-outline-theme">
                                    <i class="fas fa-eye me-1"></i> View
                                </a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-3 mb-0">
                        <i class="fas fa-file d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>
                        No documents uploaded.
                    </p>
                @endif
            </div>
        </div>

        {{-- Address & Additional --}}
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-semibold">Additional Details</h6></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="text-muted small">Address</label>
                        <p class="fw-medium mb-0">{{ $user->address ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
