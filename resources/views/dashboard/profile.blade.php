@extends('layouts.admin')

@section('title', 'My Profile')

@section('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, var(--theme-default), #3aa5a0);
        border-radius: 12px;
        padding: 30px;
        color: #fff;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }
    .profile-header::after {
        content: '';
        position: absolute;
        top: -30%;
        right: -10%;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.06);
    }
    .profile-avatar-lg {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 700;
        color: #fff;
        border: 3px solid rgba(255, 255, 255, 0.3);
        flex-shrink: 0;
    }
    .profile-info-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-light-gray);
        margin-bottom: 4px;
    }
    .profile-info-value {
        font-size: 0.95rem;
        font-weight: 500;
        color: var(--body-font-color);
    }
</style>
@endsection

@section('content')

    {{-- Page Title --}}
    <div class="page-title-area">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h3>My Profile</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    {{-- Profile Header --}}
    <div class="profile-header">
        <div class="d-flex align-items-center gap-4 position-relative" style="z-index: 1;">
            <div class="profile-avatar-lg">
                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
            </div>
            <div>
                <h4 class="mb-1 fw-semibold">{{ Auth::user()->name ?? 'Unknown User' }}</h4>
                <p class="mb-0 opacity-75" style="font-size: 0.9rem;">
                    <i class="fas fa-briefcase me-1"></i>
                    {{ Auth::user()->designation ?? 'Field Agent' }}
                </p>
                @if(Auth::user()->user_id)
                    <p class="mb-0 opacity-50" style="font-size: 0.82rem;">
                        Emp Code: {{ Auth::user()->user_id }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    {{-- Profile Details --}}
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0 fw-semibold"><i class="fas fa-user me-2 text-theme"></i> Personal Information</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="profile-info-label">Full Name</div>
                            <div class="profile-info-value">{{ Auth::user()->name ?? 'N/A' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="profile-info-label">Email</div>
                            <div class="profile-info-value">{{ Auth::user()->email ?? 'N/A' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="profile-info-label">Mobile</div>
                            <div class="profile-info-value">{{ Auth::user()->mobile ?? 'N/A' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="profile-info-label">Designation</div>
                            <div class="profile-info-value">{{ Auth::user()->designation ?? 'N/A' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="profile-info-label">Employee Code</div>
                            <div class="profile-info-value">{{ Auth::user()->user_id ?? 'N/A' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="profile-info-label">Role</div>
                            <div class="profile-info-value">{{ strtoupper(Auth::user()->role ?? 'N/A') }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="profile-info-label">Date of Joining</div>
                            <div class="profile-info-value">{{ Auth::user()->date_of_joining ? \Carbon\Carbon::parse(Auth::user()->date_of_joining)->format('d M Y') : 'N/A' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="profile-info-label">Status</div>
                            <div class="profile-info-value">
                                @php
                                    $profileStatusClass = match(Auth::user()->status ?? 'active') {
                                        'active' => 'bg-success bg-opacity-10 text-success',
                                        'inactive' => 'bg-danger bg-opacity-10 text-danger',
                                        'pending' => 'bg-warning bg-opacity-10 text-warning',
                                        'suspended' => 'bg-secondary bg-opacity-10 text-secondary',
                                        default => 'bg-secondary bg-opacity-10 text-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $profileStatusClass }}" style="font-size: 0.78rem;">{{ ucfirst(Auth::user()->status ?? 'N/A') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0 fw-semibold"><i class="fas fa-info-circle me-2 text-theme"></i> Account Details</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="profile-info-label">Account Created</div>
                            <div class="profile-info-value">
                                {{ Auth::user()->created_at ? Auth::user()->created_at->format('d M Y') : 'N/A' }}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="profile-info-label">Last Updated</div>
                            <div class="profile-info-value">
                                {{ Auth::user()->updated_at ? Auth::user()->updated_at->format('d M Y') : 'N/A' }}
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="profile-info-label">Reporting Manager</div>
                            <div class="profile-info-value">
                                @php
                                    $manager = null;
                                    try {
                                        $manager = Auth::user()->reportingManager;
                                    } catch (\Throwable $e) {}
                                @endphp
                                {{ $manager ? $manager->name : 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0 fw-semibold"><i class="fas fa-cog me-2 text-theme"></i> Quick Actions</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('settings') }}" class="btn btn-outline-theme btn-sm me-2">
                        <i class="fas fa-key me-1"></i> Change Password
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection
