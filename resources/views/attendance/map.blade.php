@extends('layouts.admin')

@section('title', 'Attendance Map')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #attendanceMap {
        height: 600px;
        border-radius: 10px;
    }
</style>
@endsection

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Attendance Map View</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('attendance.index') }}">Attendance</a></li>
                    <li class="breadcrumb-item active">Map</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('attendance.index') }}" class="btn btn-outline-theme me-1">
                <i class="fas fa-list me-1"></i> List View
            </a>
            <a href="{{ route('attendance.calendar') }}" class="btn btn-outline-secondary">
                <i class="fas fa-calendar me-1"></i> Calendar View
            </a>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('attendance.map') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label for="attendance_date" class="form-label small">Date</label>
                <input type="date" class="form-control form-control-sm" id="attendance_date" name="attendance_date"
                       value="{{ request('attendance_date', date('Y-m-d')) }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-theme btn-sm w-100">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div id="attendanceMap"></div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('attendanceMap').setView([20.5937, 78.9629], 5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 18
    }).addTo(map);

    var locations = {!! json_encode($locations ?? []) !!};

    locations.forEach(function(loc) {
        if (loc.check_in_latitude && loc.check_in_longitude) {
            var marker = L.marker([loc.check_in_latitude, loc.check_in_longitude]).addTo(map);
            marker.bindPopup(
                '<strong>' + (loc.user_name || 'Unknown') + '</strong><br>' +
                'Check-in: ' + (loc.check_in_time || '-') + '<br>' +
                'Type: ' + (loc.attendance_type || '-')
            );
        }
    });

    if (locations.length > 0) {
        var validLocs = locations.filter(function(l) { return l.check_in_latitude && l.check_in_longitude; });
        if (validLocs.length > 0) {
            var bounds = L.latLngBounds(validLocs.map(function(l) { return [l.check_in_latitude, l.check_in_longitude]; }));
            map.fitBounds(bounds, { padding: [30, 30] });
        }
    }
});
</script>
@endsection
