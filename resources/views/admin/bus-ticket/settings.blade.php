@extends('admin.layouts.app')

@section('title', 'বাস টিকেট রিসেল সেটিংস - Admin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-cog me-2"></i>বাস টিকেট রিসেল সেটিংস</h4>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Feature Toggle -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1">ফিচার স্ট্যাটাস</h5>
                                    <p class="text-muted mb-0">বাস টিকেট রিসেল ফিচার চালু বা বন্ধ করুন</p>
                                </div>
                                <form action="{{ route('admin.bus-ticket.toggle') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-{{ $settings->is_enabled ? 'success' : 'danger' }} btn-lg">
                                        <i class="fas fa-{{ $settings->is_enabled ? 'check' : 'times' }} me-2"></i>
                                        {{ $settings->is_enabled ? 'চালু আছে' : 'বন্ধ আছে' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Settings Form -->
                    <form method="POST" action="{{ route('admin.bus-ticket.settings.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">শিরোনাম</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title', $settings->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">বিবরণ</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                      rows="4">{{ old('description', $settings->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted">হোম পেজে দেখানো হবে</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>সেটিংস সংরক্ষণ
                            </button>
                            <a href="{{ route('admin.bus-ticket.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-arrow-left me-2"></i>ফিরে যান
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
