@extends('admin.layouts.app')

@section('title', 'Edit MP Profile')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-user-edit me-2"></i>Edit MP Profile</h1>
    <a href="{{ route('admin.mp-profiles.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to List
    </a>
</div>

<div class="admin-form">
    <form action="{{ route('admin.mp-profiles.update', $mpProfile) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.mp-profiles.form')
    </form>
</div>
@endsection
