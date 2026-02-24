@extends('layouts.admin')
@section('title', 'Add District')
@section('content')
<div class="page-title-area"><div><h3>Add District</h3><nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li><li class="breadcrumb-item"><a href="{{ route('districts.index') }}">Districts</a></li><li class="breadcrumb-item active">Add</li></ol></nav></div></div>
<div class="card"><div class="card-header"><h6 class="mb-0 fw-semibold">Create New District</h6></div><div class="card-body">
    <form method="POST" action="{{ route('districts.store') }}">@csrf @include('masters.districts._form')
        <div class="mt-4"><button type="submit" class="btn btn-theme"><i class="fas fa-save me-1"></i> Save</button><a href="{{ route('districts.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a></div>
    </form>
</div></div>
@endsection
