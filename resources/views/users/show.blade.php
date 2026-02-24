@extends('layouts.admin')

@section('title', 'User Profile')

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>User Profile</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                    <li class="breadcrumb-item active">{{ $user->name ?? '' }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-theme me-1">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
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
                <p class="text-muted mb-1">{{ $user->designation ?? '' }}</p>
                <p class="text-muted mb-2"><span class="badge bg-light text-dark">{{ strtoupper($user->role ?? '') }}</span></p>
                @php
                    $statusClass = match(strtolower($user->status ?? 'active')) {
                        'active' => 'completed',
                        'inactive' => 'pending',
                        'pending' => 'pending',
                        'suspended' => 'in-progress',
                        default => 'pending',
                    };
                @endphp
                <span class="badge-status {{ $statusClass }}">{{ ucfirst($user->status ?? 'Active') }}</span>
            </div>
            <hr class="my-0">
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-id-card me-2"></i>Employee ID</span>
                        <span class="fw-medium">{{ $user->user_id ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-phone me-2"></i>Mobile</span>
                        <span class="fw-medium">{{ $user->mobile ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-envelope me-2"></i>Email</span>
                        <span class="fw-medium">{{ $user->email ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-user-shield me-2"></i>Role</span>
                        <span class="fw-medium">{{ strtoupper($user->role ?? '-') }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-id-badge me-2"></i>Designation</span>
                        <span class="fw-medium">{{ $user->designation ?? '-' }}</span>
                    </li>
                    @if($user->role === 'fa' && $user->fa_type)
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-tag me-2"></i>FA Type</span>
                        <span class="fw-medium">{{ ucfirst($user->fa_type) }}</span>
                    </li>
                    @endif
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-user-tie me-2"></i>Reporting Manager</span>
                        <span class="fw-medium">{{ $user->reportingManager->name ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-calendar me-2"></i>Date of Joining</span>
                        <span class="fw-medium">{{ $user->date_of_joining ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-mobile-alt me-2"></i>App Access</span>
                        <span class="fw-medium">
                            @if($user->app_access_enabled)
                                <span class="badge bg-success bg-opacity-10 text-success">Enabled</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger">Disabled</span>
                            @endif
                        </span>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted"><i class="fas fa-clock me-2"></i>Last Login</span>
                        <span class="fw-medium">{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d M Y H:i') : '-' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Details --}}
    <div class="col-xl-8">
        {{-- ZRTH Assignments --}}
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold">ZRTH Assignments</h6>
            </div>
            <div class="card-body">
                @if(isset($user->zrthAssignments) && $user->zrthAssignments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Level</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Primary</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->zrthAssignments as $assignment)
                                <tr>
                                    <td><span class="badge bg-light text-dark">{{ ucfirst($assignment->zrthHierarchy->level ?? '-') }}</span></td>
                                    <td>{{ $assignment->zrthHierarchy->name ?? '-' }}</td>
                                    <td>{{ $assignment->zrthHierarchy->code ?? '-' }}</td>
                                    <td>{{ $assignment->is_primary ? 'Yes' : 'No' }}</td>
                                    <td><span class="badge-status {{ $assignment->status === 'active' ? 'completed' : 'pending' }}">{{ ucfirst($assignment->status ?? '-') }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3 mb-0">
                        <i class="fas fa-map-marker-alt d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>
                        No ZRTH assignments yet.
                    </p>
                @endif
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold">Recent Activities</h6>
            </div>
            <div class="card-body">
                @if(isset($activities) && $activities->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Execution Date</th>
                                    <th>Status</th>
                                    <th>Village</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activities as $activity)
                                <tr>
                                    <td><span class="badge bg-light text-dark">{{ $activity->activityType->name ?? '-' }}</span></td>
                                    <td class="text-muted">{{ $activity->execution_date ?? '-' }}</td>
                                    <td>
                                        @php
                                            $aStatusClass = match(strtolower($activity->status ?? 'draft')) {
                                                'completed' => 'completed',
                                                'submitted' => 'in-progress',
                                                'cancelled' => 'in-progress',
                                                default => 'pending',
                                            };
                                        @endphp
                                        <span class="badge-status {{ $aStatusClass }}">{{ ucfirst(str_replace('_', ' ', $activity->status ?? 'Draft')) }}</span>
                                    </td>
                                    <td>{{ $activity->village->name ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3 mb-0">
                        <i class="fas fa-clipboard d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>
                        No recent activities.
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
