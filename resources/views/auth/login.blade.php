<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — Field Operations Management System</title>

    {{-- Google Fonts: Rubik (matches Edmin) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap 5.3 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome 6 --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --theme-default: #43B9B2;
            --theme-secondary: #C280D2;
            --body-font-color: #3D3D47;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Rubik', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1a1e2e 0%, #22262c 40%, #2a3040 100%);
            color: var(--body-font-color);
            position: relative;
            overflow: hidden;
        }

        /* Subtle background pattern */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(67, 185, 178, 0.08) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(194, 128, 210, 0.06) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 80%, rgba(67, 185, 178, 0.05) 0%, transparent 50%);
            pointer-events: none;
        }

        .login-container {
            width: 100%;
            max-width: 440px;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        .login-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.05);
            overflow: hidden;
        }

        /* Top teal accent bar */
        .login-card::before {
            content: '';
            display: block;
            height: 4px;
            background: linear-gradient(90deg, var(--theme-default), var(--theme-secondary));
        }

        .login-body {
            padding: 40px 36px 36px;
        }

        /* Logo area */
        .login-logo {
            text-align: center;
            margin-bottom: 28px;
        }

        .login-logo .logo-icon-wrapper {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--theme-default), #3aa5a0);
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
            box-shadow: 0 4px 15px rgba(67, 185, 178, 0.3);
        }

        .login-logo .logo-icon-wrapper i {
            color: #fff;
            font-size: 1.5rem;
        }

        .login-logo h4 {
            font-size: 1.35rem;
            font-weight: 600;
            color: var(--body-font-color);
            margin-bottom: 4px;
        }

        .login-logo p {
            font-size: 0.82rem;
            color: #9B9B9B;
            margin: 0;
        }

        /* Form styles */
        .form-label {
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--body-font-color);
            margin-bottom: 6px;
        }

        .input-group-icon {
            position: relative;
        }

        .input-group-icon .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #bbb;
            font-size: 0.9rem;
            z-index: 2;
            pointer-events: none;
        }

        .input-group-icon .form-control {
            padding-left: 42px;
        }

        .form-control {
            border: 1px solid #e8e8e8;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.9rem;
            color: var(--body-font-color);
            background: #fafbfc;
            transition: border-color 0.2s, box-shadow 0.2s;
            height: auto;
        }

        .form-control:focus {
            border-color: var(--theme-default);
            box-shadow: 0 0 0 3px rgba(67, 185, 178, 0.12);
            background: #fff;
        }

        .form-control::placeholder {
            color: #bbb;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.12);
        }

        .invalid-feedback {
            font-size: 0.78rem;
        }

        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, var(--theme-default), #3aa5a0);
            border: none;
            color: #fff;
            padding: 11px 20px;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 500;
            letter-spacing: 0.3px;
            transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
            cursor: pointer;
        }

        .btn-login:hover {
            opacity: 0.92;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(67, 185, 178, 0.3);
            color: #fff;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none;
        }

        /* Password toggle */
        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #bbb;
            cursor: pointer;
            font-size: 0.9rem;
            z-index: 2;
            padding: 0;
            line-height: 1;
        }

        .password-toggle:hover {
            color: var(--theme-default);
        }

        /* Alert styles */
        .alert-login {
            font-size: 0.82rem;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 18px;
        }

        /* Footer area */
        .login-footer {
            text-align: center;
            padding: 18px 36px 24px;
            border-top: 1px solid #f3f3f3;
            background: #fafbfc;
        }

        .login-footer p {
            font-size: 0.78rem;
            color: #9B9B9B;
            margin: 0;
        }

        .login-footer a {
            color: var(--theme-default);
            text-decoration: none;
            font-weight: 500;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 575.98px) {
            .login-container {
                padding: 16px;
            }
            .login-body {
                padding: 30px 24px 28px;
            }
            .login-footer {
                padding: 14px 24px 20px;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-card">
            <div class="login-body">

                {{-- Logo --}}
                <div class="login-logo">
                    <div class="logo-icon-wrapper">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <h4>Field Ops</h4>
                    <p>Field Operations Management System</p>
                </div>

                {{-- Error alert --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-login">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                @endif

                {{-- Login Form --}}
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    {{-- Mobile Number --}}
                    <div class="mb-3">
                        <label for="mobile" class="form-label">Mobile Number</label>
                        <div class="input-group-icon">
                            <i class="fas fa-phone input-icon"></i>
                            <input
                                type="tel"
                                id="mobile"
                                name="mobile"
                                class="form-control @error('mobile') is-invalid @enderror"
                                placeholder="Enter your mobile number"
                                value="{{ old('mobile') }}"
                                required
                                autofocus
                                autocomplete="tel"
                            >
                        </div>
                        @error('mobile')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group-icon" style="position: relative;">
                            <i class="fas fa-lock input-icon"></i>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Enter your password"
                                required
                                autocomplete="current-password"
                                style="padding-right: 42px;"
                            >
                            <button type="button" class="password-toggle" id="togglePassword" title="Show password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn btn-login" id="btnLogin">
                        <i class="fas fa-sign-in-alt me-1"></i> Sign In
                    </button>
                </form>
            </div>

            {{-- Footer --}}
            <div class="login-footer">
                <p>&copy; {{ date('Y') }} Field Operations Management System</p>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        (function () {
            'use strict';

            // Password visibility toggle
            var toggleBtn = document.getElementById('togglePassword');
            var passInput = document.getElementById('password');

            if (toggleBtn && passInput) {
                toggleBtn.addEventListener('click', function () {
                    var isPassword = passInput.type === 'password';
                    passInput.type = isPassword ? 'text' : 'password';
                    toggleBtn.querySelector('i').classList.toggle('fa-eye');
                    toggleBtn.querySelector('i').classList.toggle('fa-eye-slash');
                });
            }

            // Disable button on submit to prevent double-clicks
            var form = document.getElementById('loginForm');
            var btn = document.getElementById('btnLogin');

            if (form && btn) {
                form.addEventListener('submit', function () {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Signing in...';
                });
            }
        })();
    </script>
</body>
</html>
