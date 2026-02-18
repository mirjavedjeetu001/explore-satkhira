@extends('admin.layouts.app')

@section('title', 'Add MP Profile')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-user-plus me-2"></i>Add MP Profile</h1>
    <a href="{{ route('admin.mp-profiles.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to List
    </a>
</div>

<div class="admin-form">
    <form action="{{ route('admin.mp-profiles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.mp-profiles.form')
    </form>
</div>
@endsection
