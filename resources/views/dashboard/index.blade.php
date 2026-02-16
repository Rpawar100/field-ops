@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Dashboard</h2>
            <p class="text-muted">Welcome to Field Operations Management System</p>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="kpi-card blue">
                <div class="text-center">
                    <h5 class="mb-2">Activities Today</h5>
                    <h2 class="mb-0">{{ $activitiesToday ?? 0 }}</h2>
                    <small>Planned & Executed</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="kpi-card green">
                <div class="text-center">
                    <h5 class="mb-2">Present Staff</h5>
                    <h2 class="mb-0">{{ $staffPresent ?? 0 }}</h2>
                    <small>Checked In</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="kpi-card orange">
                <div class="text-center">
                    <h5 class="mb-2">Registered Farmers</h5>
                    <h2 class="mb-0">{{ $totalFarmers ?? 0 }}</h2>
                    <small>Active & Inactive</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="kpi-card red">
                <div class="text-center">
                    <h5 class="mb-2">Retailers</h5>
                    <h2 class="mb-0">{{ $totalRetailers ?? 0 }}</h2>
                    <small>All Active</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Recent Activities -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="mb-0">Recent Activities</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Activity</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Demo Distribution - Lot #001</td>
                                    <td><span class="badge bg-success">Demo</span></td>
                                    <td><span class="badge bg-primary">Completed</span></td>
                                    <td>2024-02-16</td>
                                </tr>
                                <tr>
                                    <td>Farmer Registration Drive</td>
                                    <td><span class="badge bg-warning text-dark">PSA</span></td>
                                    <td><span class="badge bg-primary">In Progress</span></td>
                                    <td>2024-02-16</td>
                                </tr>
                                <tr>
                                    <td>Retailer Feedback Survey</td>
                                    <td><span class="badge bg-info">Other</span></td>
                                    <td><span class="badge bg-secondary">Pending</span></td>
                                    <td>2024-02-17</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('activities.index') }}" class="btn btn-sm btn-primary">View All Activities</a>
                </div>
            </div>
        </div>

        <!-- Attendance Summary -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="mb-0">Today's Attendance</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-4">
                        <div>
                            <h6 class="text-muted">Present</h6>
                            <h3 class="text-success">45</h3>
                        </div>
                        <div>
                            <h6 class="text-muted">Absent</h6>
                            <h3 class="text-danger">5</h3>
                        </div>
                        <div>
                            <h6 class="text-muted">Leave</h6>
                            <h3 class="text-warning">8</h3>
                        </div>
                        <div>
                            <h6 class="text-muted">Total</h6>
                            <h3 class="text-secondary">58</h3>
                        </div>
                    </div>
                    <div class="progress mb-3" style="height: 25px;">
                        <div class="progress-bar bg-success" style="width: 77%;" title="Present">77%</div>
                        <div class="progress-bar bg-danger" style="width: 9%;" title="Absent">9%</div>
                        <div class="progress-bar bg-warning" style="width: 14%;" title="Leave">14%</div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-primary">View Attendance</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Coverage Summary -->
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="mb-0">Territory Coverage Summary</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Zone</th>
                                    <th>Regions</th>
                                    <th>Territories</th>
                                    <th>Users</th>
                                    <th>Farmers</th>
                                    <th>Retailers</th>
                                    <th>Demo Lots</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>North Zone</strong></td>
                                    <td>3</td>
                                    <td>9</td>
                                    <td>25</td>
                                    <td>350</td>
                                    <td>145</td>
                                    <td>12</td>
                                    <td>
                                        <a href="" class="btn btn-sm btn-outline-primary">Details</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>South Zone</strong></td>
                                    <td>2</td>
                                    <td>6</td>
                                    <td>18</td>
                                    <td>280</td>
                                    <td>120</td>
                                    <td>8</td>
                                    <td>
                                        <a href="" class="btn btn-sm btn-outline-primary">Details</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>East Zone</strong></td>
                                    <td>2</td>
                                    <td>7</td>
                                    <td>20</td>
                                    <td>310</td>
                                    <td>130</td>
                                    <td>10</td>
                                    <td>
                                        <a href="" class="btn btn-sm btn-outline-primary">Details</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('farmers.create') }}" class="btn btn-outline-primary btn-lg w-100 mb-2">
                                <i class="fas fa-user-plus"></i> Register New Farmer
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('retailers.create') }}" class="btn btn-outline-primary btn-lg w-100 mb-2">
                                <i class="fas fa-store"></i> Register New Retailer
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('activities.create') }}" class="btn btn-outline-success btn-lg w-100 mb-2">
                                <i class="fas fa-plus-circle"></i> Create Activity
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('demos.create') }}" class="btn btn-outline-info btn-lg w-100 mb-2">
                                <i class="fas fa-flask"></i> Create Demo Distribution
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Auto-refresh dashboard every 30 seconds
    setInterval(() => {
        location.reload();
    }, 30000);
</script>
@endsection
@endsection
