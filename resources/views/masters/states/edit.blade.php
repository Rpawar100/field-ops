@extends('layouts.admin')
@section('title', 'Edit State')
@section('content')
<div class="page-title-area"><div><h3>Edit State</h3><nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li><li class="breadcrumb-item"><a href="{{ route('states.index') }}">States</a></li><li class="breadcrumb-item active">Edit</li></ol></nav></div></div>
<div class="card"><div class="card-header"><h6 class="mb-0 fw-semibold">Edit State: {{ $state->name ?? '' }}</h6></div><div class="card-body">
    <form method="POST" action="{{ route('states.update', $state) }}">@csrf @method('PUT') @include('masters.states._form')
        <div class="mt-4"><button type="submit" class="btn btn-theme"><i class="fas fa-save me-1"></i> Update</button><a href="{{ route('states.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a></div>
    </form>
</div></div>
@endsection
