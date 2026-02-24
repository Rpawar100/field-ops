@extends('layouts.admin')

@section('title', 'Activity Details')

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Activity Details</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('activities.index') }}">Activities</a></li>
                    <li class="breadcrumb-item active">{{ $activity->activityType->name ?? 'Details' }}</li>
                </ol>
            </nav>
        </div>
        <div>
            @if(strtolower($activity->status ?? '') !== 'completed')
            <a href="{{ route('activities.execute-form', $activity) }}" class="btn btn-theme me-1">
                <i class="fas fa-play me-1"></i> Execute
            </a>
            @endif
            <a href="{{ route('activities.edit', $activity) }}" class="btn btn-outline-theme me-1">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <a href="{{ route('activities.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>
</div>

<div class="row">
    {{-- Activity Info --}}
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body text-center pt-4">
                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                     style="width:80px;height:80px;background:rgba(67,185,178,0.12);color:var(--theme-default);font-size:2rem;">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h5 class="fw-semibold mb-1">{{ $activity->activityType->name ?? '' }}</h5>
                <p class="text-muted mb-2">{{ $activity->village->name ?? '' }}</p>
                @php
                    $statusClass = match(strtolower($activity->status ?? 'draft')) {
                        'completed' => 'completed',
                        'submitted' => 'in-progress',
                        'cancelled' => 'in-progress',
                        default => 'pending',
                    };
                @endphp
                <span class="badge-status {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $activity->status ?? 'Draft')) }}</span>
            </div>
            <hr class="my-0">
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-user me-2"></i>User</span>
                        <span class="fw-medium">{{ $activity->user->name ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-calendar me-2"></i>Execution Date</span>
                        <span class="fw-medium">{{ $activity->execution_date ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-map-marker-alt me-2"></i>Village</span>
                        <span class="fw-medium">{{ $activity->village->name ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-route me-2"></i>Beat</span>
                        <span class="fw-medium">{{ $activity->beat->name ?? '-' }}</span>
                    </li>
                    @if($activity->farmer)
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-leaf me-2"></i>Farmer</span>
                        <span class="fw-medium">{{ $activity->farmer->name ?? '-' }}</span>
                    </li>
                    @endif
                    @if($activity->retailer)
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-store me-2"></i>Retailer</span>
                        <span class="fw-medium">{{ $activity->retailer->shop_name ?? '-' }}</span>
                    </li>
                    @endif
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted"><i class="fas fa-clock me-2"></i>Created</span>
                        <span class="fw-medium">{{ $activity->created_at ? $activity->created_at->format('d M Y') : '-' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        {{-- Remarks --}}
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold">Remarks</h6>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $activity->remarks ?? 'No remarks provided.' }}</p>
            </div>
        </div>

        {{-- Execution Details --}}
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold">Execution Details</h6>
            </div>
            <div class="card-body">
                @if($activity->start_time ?? false)
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">Start Time</label>
                            <p class="fw-medium mb-0">{{ $activity->start_time ?? '-' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">End Time</label>
                            <p class="fw-medium mb-0">{{ $activity->end_time ?? '-' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">Duration</label>
                            <p class="fw-medium mb-0">{{ $activity->duration_minutes ? $activity->duration_minutes . ' min' : '-' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">GPS Coordinates</label>
                            <p class="fw-medium mb-0">
                                @if($activity->gps_latitude && $activity->gps_longitude)
                                    <a href="https://www.google.com/maps?q={{ $activity->gps_latitude }},{{ $activity->gps_longitude }}" target="_blank" class="text-theme">
                                        {{ $activity->gps_latitude }}, {{ $activity->gps_longitude }}
                                    </a>
                                    @if($activity->gps_accuracy)
                                        <br><small class="text-muted">Accuracy: {{ $activity->gps_accuracy }}m</small>
                                    @endif
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                @else
                    <p class="text-muted text-center py-3 mb-0">
                        <i class="fas fa-hourglass-half d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>
                        Activity not yet executed.
                    </p>
                @endif
            </div>
        </div>

        {{-- Photos --}}
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-semibold">Photos</h6>
            </div>
            <div class="card-body">
                @if(isset($activity->activityPhotos) && $activity->activityPhotos->count() > 0)
                    <div class="row g-3">
                        @foreach($activity->activityPhotos as $photo)
                        <div class="col-md-3 col-6">
                            <div class="border rounded overflow-hidden">
                                <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="{{ $photo->caption ?? 'Activity Photo' }}"
                                     class="img-fluid" style="width:100%;height:150px;object-fit:cover;">
                                @if($photo->caption)
                                    <div class="p-2 small text-muted">{{ $photo->caption }}</div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-3 mb-0">
                        <i class="fas fa-camera d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>
                        No photos uploaded.
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
