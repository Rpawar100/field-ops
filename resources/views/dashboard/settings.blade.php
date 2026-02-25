@extends('layouts.admin')

@section('title', 'Settings')

@section('styles')
    <style>
        .settings-section .card {
            border-radius: 12px;
        }

        .settings-section .form-label {
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--body-font-color);
        }

        .settings-section .form-control {
            border-radius: 8px;
            border: 1px solid #e8e8e8;
            padding: 10px 14px;
            font-size: 0.9rem;
            background: #fafbfc;
        }

        .settings-section .form-control:focus {
            border-color: var(--theme-default);
            box-shadow: 0 0 0 3px rgba(67, 185, 178, 0.12);
        }

        .settings-section .form-text {
            font-size: 0.75rem;
        }
    </style>
@endsection

@section('content')

    {{-- Page Title --}}
    <div class="page-title-area">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h3>Settings</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Settings</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="settings-section">
        <div class="row g-3">

            {{-- Display Settings --}}
            <div class="col-lg-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0 fw-semibold"><i class="fas fa-moon me-2 text-theme"></i> Display Settings</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-1" style="font-size: 0.9rem;">Dark Mode</h6>
                                <p class="text-muted mb-0" style="font-size: 0.8rem;">Switch between light and dark themes.
                                </p>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="darkModeSwitch"
                                    onchange="window.toggleDarkMode()">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Change Password --}}
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0 fw-semibold"><i class="fas fa-key me-2 text-theme"></i> Change Password</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ url('/settings/password') }}" id="passwordForm">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                    id="current_password" name="current_password" placeholder="Enter current password"
                                    required>
                                @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                                    id="new_password" name="new_password" placeholder="Enter new password" required
                                    minlength="8">
                                @error('new_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <div class="form-text text-muted">Minimum 8 characters.</div>
                            </div>

                            <div class="mb-4">
                                <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password"
                                    class="form-control @error('new_password_confirmation') is-invalid @enderror"
                                    id="new_password_confirmation" name="new_password_confirmation"
                                    placeholder="Re-enter new password" required>
                                @error('new_password_confirmation') <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-theme" disabled
                                title="Password change endpoint not yet implemented">
                                <i class="fas fa-save me-1"></i> Update Password
                            </button>
                            <span class="text-muted ms-2" style="font-size: 0.78rem;">
                                <i class="fas fa-info-circle me-1"></i> Endpoint not yet implemented
                            </span>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Notification Preferences --}}
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0 fw-semibold"><i class="fas fa-bell me-2 text-theme"></i> Notification Preferences
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="notifEmail" checked disabled>
                            <label class="form-check-label" for="notifEmail" style="font-size: 0.9rem;">
                                Email Notifications
                            </label>
                            <div class="form-text text-muted">Receive email alerts for important events.</div>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="notifPush" checked disabled>
                            <label class="form-check-label" for="notifPush" style="font-size: 0.9rem;">
                                Push Notifications
                            </label>
                            <div class="form-text text-muted">Mobile push notifications for real-time updates.</div>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="notifSMS" disabled>
                            <label class="form-check-label" for="notifSMS" style="font-size: 0.9rem;">
                                SMS Notifications
                            </label>
                            <div class="form-text text-muted">Text message alerts for critical actions.</div>
                        </div>

                        <div class="mt-3">
                            <span class="text-muted" style="font-size: 0.78rem;">
                                <i class="fas fa-info-circle me-1"></i> Preference saving not yet implemented
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Account Info (Read-only) --}}
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0 fw-semibold"><i class="fas fa-shield-alt me-2 text-theme"></i> Account Security</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div>
                                <div class="fw-medium" style="font-size: 0.9rem;">Two-Factor Authentication</div>
                                <div class="text-muted" style="font-size: 0.78rem;">Add extra security to your account</div>
                            </div>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary">Not Available</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-medium" style="font-size: 0.9rem;">Active Sessions</div>
                                <div class="text-muted" style="font-size: 0.78rem;">Manage your login sessions</div>
                            </div>
                            <span class="badge bg-success bg-opacity-10 text-success">1 Active</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection