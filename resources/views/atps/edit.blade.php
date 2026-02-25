@extends('layouts.admin')

@section('title', 'Edit Tour Plan')

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Edit Tour Plan</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('atps.index') }}">Tour Plans</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold">Edit Tour Plan: {{ $atp->month ?? '' }}</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('atps.update', $atp) }}">
            @csrf
            @method('PUT')
            @include('atps._form')
            <div class="mt-4">
                <button type="submit" class="btn btn-theme">
                    <i class="fas fa-save me-1"></i> Update Tour Plan
                </button>
                <a href="{{ route('atps.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
