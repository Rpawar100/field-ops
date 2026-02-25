@extends('layouts.admin')

@section('title', 'Edit Activity')

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Edit Activity</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('activities.index') }}">Activities</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold">Edit Activity: {{ $activity->activityType->name ?? 'Activity #'.$activity->id }}</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('activities.update', $activity) }}">
            @csrf
            @method('PUT')
            @include('activities._form')
            <div class="mt-4">
                <button type="submit" class="btn btn-theme">
                    <i class="fas fa-save me-1"></i> Update Activity
                </button>
                <a href="{{ route('activities.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
