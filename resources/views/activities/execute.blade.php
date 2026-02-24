@extends('layouts.admin')

@section('title', 'Execute Activity')

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Execute Activity</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('activities.index') }}">Activities</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('activities.show', $activity) }}">{{ $activity->activityType->name ?? 'Activity' }}</a></li>
                    <li class="breadcrumb-item active">Execute</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

{{-- Activity Summary --}}
<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <label class="text-muted small">Activity Type</label>
                <p class="fw-medium mb-0">{{ $activity->activityType->name ?? '-' }}</p>
            </div>
            <div class="col-md-3">
                <label class="text-muted small">User</label>
                <p class="fw-medium mb-0">{{ $activity->user->name ?? '-' }}</p>
            </div>
            <div class="col-md-3">
                <label class="text-muted small">Village</label>
                <p class="fw-medium mb-0">{{ $activity->village->name ?? '-' }}</p>
            </div>
            <div class="col-md-3">
                <label class="text-muted small">Execution Date</label>
                <p class="fw-medium mb-0">{{ $activity->execution_date ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Execution Form --}}
<div class="card">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold">Execution Details</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('activities.execute', $activity) }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="status" class="form-label">Execution Status <span class="text-danger">*</span></label>
                    <select class="form-select @error('status') is-invalid @enderror"
                            id="status" name="status" required>
                        <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="submitted" {{ old('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                        <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="start_time" class="form-label">Start Time</label>
                    <input type="time" class="form-control @error('start_time') is-invalid @enderror"
                           id="start_time" name="start_time"
                           value="{{ old('start_time') }}">
                    @error('start_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="end_time" class="form-label">End Time</label>
                    <input type="time" class="form-control @error('end_time') is-invalid @enderror"
                           id="end_time" name="end_time"
                           value="{{ old('end_time') }}">
                    @error('end_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label for="remarks" class="form-label">Remarks</label>
                    <textarea class="form-control @error('remarks') is-invalid @enderror"
                              id="remarks" name="remarks" rows="4"
                              placeholder="Enter execution notes, observations, outcomes...">{{ old('remarks') }}</textarea>
                    @error('remarks')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- GPS Coordinates --}}
                <div class="col-md-4">
                    <label for="gps_latitude" class="form-label">Latitude</label>
                    <input type="text" class="form-control @error('gps_latitude') is-invalid @enderror"
                           id="gps_latitude" name="gps_latitude"
                           value="{{ old('gps_latitude') }}" placeholder="e.g. 19.0760" readonly>
                    @error('gps_latitude')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="gps_longitude" class="form-label">Longitude</label>
                    <input type="text" class="form-control @error('gps_longitude') is-invalid @enderror"
                           id="gps_longitude" name="gps_longitude"
                           value="{{ old('gps_longitude') }}" placeholder="e.g. 72.8777" readonly>
                    @error('gps_longitude')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="gps_accuracy" class="form-label">GPS Accuracy (m)</label>
                    <input type="text" class="form-control @error('gps_accuracy') is-invalid @enderror"
                           id="gps_accuracy" name="gps_accuracy"
                           value="{{ old('gps_accuracy') }}" placeholder="Accuracy in meters" readonly>
                    @error('gps_accuracy')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <button type="button" class="btn btn-sm btn-outline-theme" id="captureGps">
                        <i class="fas fa-map-marker-alt me-1"></i> Capture GPS Location
                    </button>
                    <span id="gpsStatus" class="ms-2 small text-muted"></span>
                </div>

                {{-- Photo Upload --}}
                <div class="col-md-12">
                    <label for="photos" class="form-label">Upload Photos</label>
                    <input type="file" class="form-control @error('photos') is-invalid @enderror @error('photos.*') is-invalid @enderror"
                           id="photos" name="photos[]" multiple accept="image/*">
                    <small class="form-text text-muted">You can select multiple photos. Max 5MB each.</small>
                    @error('photos')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @error('photos.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-theme">
                    <i class="fas fa-check me-1"></i> Submit Execution
                </button>
                <a href="{{ route('activities.show', $activity) }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('captureGps').addEventListener('click', function() {
    var statusEl = document.getElementById('gpsStatus');
    statusEl.textContent = 'Capturing location...';

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            document.getElementById('gps_latitude').value = position.coords.latitude.toFixed(6);
            document.getElementById('gps_longitude').value = position.coords.longitude.toFixed(6);
            document.getElementById('gps_accuracy').value = position.coords.accuracy.toFixed(1);
            statusEl.textContent = 'Location captured successfully.';
            statusEl.classList.remove('text-danger');
            statusEl.classList.add('text-success');
        }, function(error) {
            statusEl.textContent = 'Error: ' + error.message;
            statusEl.classList.remove('text-success');
            statusEl.classList.add('text-danger');
        }, { enableHighAccuracy: true });
    } else {
        statusEl.textContent = 'Geolocation is not supported by this browser.';
        statusEl.classList.add('text-danger');
    }
});
</script>
@endsection
