@extends('layouts.admin')

@section('title', 'Attendance Calendar')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<style>
    #attendanceCalendar {
        min-height: 600px;
    }
    .fc .fc-toolbar-title {
        font-size: 1.1rem;
        font-weight: 600;
    }
    .fc .fc-button-primary {
        background-color: var(--theme-default);
        border-color: var(--theme-default);
    }
    .fc .fc-button-primary:hover {
        background-color: #3aa5a0;
        border-color: #3aa5a0;
    }
    .fc .fc-button-primary:not(:disabled).fc-button-active {
        background-color: #2d8e89;
        border-color: #2d8e89;
    }
    .fc .fc-daygrid-event {
        border-radius: 4px;
        font-size: 0.75rem;
        padding: 2px 4px;
    }
</style>
@endsection

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Attendance Calendar</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('attendance.index') }}">Attendance</a></li>
                    <li class="breadcrumb-item active">Calendar</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('attendance.index') }}" class="btn btn-outline-theme me-1">
                <i class="fas fa-list me-1"></i> List View
            </a>
            <a href="{{ route('attendance.map') }}" class="btn btn-outline-secondary">
                <i class="fas fa-map-marker-alt me-1"></i> Map View
            </a>
        </div>
    </div>
</div>

{{-- Legend --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <div class="d-flex gap-4 flex-wrap align-items-center">
            <span class="small"><span class="badge bg-success me-1">&nbsp;&nbsp;</span> Present</span>
            <span class="small"><span class="badge bg-danger me-1">&nbsp;&nbsp;</span> Absent</span>
            <span class="small"><span class="badge bg-warning me-1">&nbsp;&nbsp;</span> Leave</span>
            <span class="small"><span class="badge bg-info me-1">&nbsp;&nbsp;</span> Half Day</span>
            <span class="small"><span class="badge bg-secondary me-1">&nbsp;&nbsp;</span> Holiday / Week Off</span>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div id="attendanceCalendar"></div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('attendanceCalendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek'
        },
        height: 'auto',
        events: {!! json_encode($events ?? []) !!},
        eventClick: function(info) {
            if (info.event.url) {
                window.location.href = info.event.url;
                info.jsEvent.preventDefault();
            }
        }
    });
    calendar.render();
});
</script>
@endsection
