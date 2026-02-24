@extends('layouts.admin')

@section('title', 'Register Farmer')

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>Register Farmer</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('farmers.index') }}">Farmers</a></li>
                    <li class="breadcrumb-item active">Register</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold">Register New Farmer</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('farmers.store') }}">
            @csrf
            @include('farmers._form')
            <div class="mt-4">
                <button type="submit" class="btn btn-theme">
                    <i class="fas fa-save me-1"></i> Save Farmer
                </button>
                <a href="{{ route('farmers.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
