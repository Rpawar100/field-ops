<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Field Operations Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        .sidebar {
            background-color: #2c3e50;
            min-height: 100vh;
            color: white;
            padding-top: 20px;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.7);
            padding: 12px 20px;
            border-left: 3px solid transparent;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: #3498db;
        }
        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .main-content {
            padding: 20px;
        }
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
        }
        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        .table {
            background-color: white;
        }
        .kpi-card {
            padding: 20px;
            border-radius: 8px;
            color: white;
            text-align: center;
        }
        .kpi-card.blue {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .kpi-card.green {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .kpi-card.orange {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .kpi-card.red {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="d-flex" style="min-height: 100vh;">
        <!-- Sidebar -->
        <nav class="sidebar" style="width: 250px;">
            <div class="px-3 mb-4">
                <h5 class="text-white mb-0">
                    <i class="fas fa-leaf"></i> Field Ops
                </h5>
                <small class="text-muted">Management System</small>
            </div>

            <div class="nav flex-column">
                <a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>

                <div class="nav-section">
                    <small class="text-muted px-3">MASTERS</small>
                    <a href="{{ route('users.index') }}" class="nav-link">
                        <i class="fas fa-users"></i> Users
                    </a>
                    <a href="{{ route('zones.index') }}" class="nav-link">
                        <i class="fas fa-map"></i> ZRTH Management
                    </a>
                    <a href="{{ route('farmers.index') }}" class="nav-link">
                        <i class="fas fa-leaf"></i> Farmers
                    </a>
                    <a href="{{ route('retailers.index') }}" class="nav-link">
                        <i class="fas fa-store"></i> Retailers
                    </a>
                    <a href="{{ route('beats.index') }}" class="nav-link">
                        <i class="fas fa-route"></i> Beats
                    </a>
                </div>

                <div class="nav-section mt-3">
                    <small class="text-muted px-3">OPERATIONS</small>
                    <a href="{{ route('activities.index') }}" class="nav-link">
                        <i class="fas fa-tasks"></i> Activities
                    </a>
                    <a href="{{ route('attendance.index') }}" class="nav-link">
                        <i class="fas fa-clock"></i> Attendance
                    </a>
                    <a href="{{ route('atps.index') }}" class="nav-link">
                        <i class="fas fa-calendar"></i> Tour Plans
                    </a>
                    <a href="{{ route('demo.index') }}" class="nav-link">
                        <i class="fas fa-flask"></i> Demo Tracking
                    </a>
                </div>

                <div class="nav-section mt-3">
                    <small class="text-muted px-3">ADMIN</small>
                    <a href="{{ route('onboarding.index') }}" class="nav-link">
                        <i class="fas fa-user-plus"></i> FA Onboarding
                    </a>
                    <a href="{{ route('reports.index') }}" class="nav-link">
                        <i class="fas fa-chart-bar"></i> Reports
                    </a>
                    <a href="{{ route('audit-logs.index') }}" class="nav-link">
                        <i class="fas fa-scroll"></i> Audit Logs
                    </a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div style="flex: 1;">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <div class="ms-auto d-flex align-items-center gap-3">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i>
                                {{ Auth::user()->name ?? 'User' }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile') }}">Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('settings') }}">Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="main-content">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    @yield('scripts')
</body>
</html>
