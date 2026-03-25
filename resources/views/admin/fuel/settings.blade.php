@extends('admin.layouts.app')

@section('title', 'জ্বালানি তেল - সেটিংস')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-cog me-2"></i>জ্বালানি তেল সেটিংস</h1>
    <a href="{{ route('admin.fuel.reports') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> ফিরে যান
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <i class="fas fa-sliders-h me-2"></i>সেটিংস
    </div>
    <div class="card-body">
        <form action="{{ route('admin.fuel.settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row g-3">
                <div class="col-md-12">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_enabled" id="isEnabled" 
                               {{ $settings['is_enabled'] == '1' ? 'checked' : '' }} style="width: 3em; height: 1.5em;">
                        <label class="form-check-label fw-semibold ms-2" for="isEnabled">
                            ফিচার চালু/বন্ধ (হোমপেজে দেখাবে)
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">সেকশন শিরোনাম</label>
                    <input type="text" name="section_title" class="form-control" 
                           value="{{ $settings['section_title'] }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">সেকশন সাবটাইটেল</label>
                    <input type="text" name="section_subtitle" class="form-control" 
                           value="{{ $settings['section_subtitle'] }}">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> সংরক্ষণ করুন
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Quick Stats -->
<div class="card mt-4">
    <div class="card-header">
        <i class="fas fa-chart-bar me-2"></i>পরিসংখ্যান
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="text-center p-3 bg-light rounded">
                    <h3 class="text-primary mb-0">{{ \App\Models\FuelStation::count() }}</h3>
                    <small class="text-muted">মোট পাম্প</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-3 bg-light rounded">
                    <h3 class="text-success mb-0">{{ \App\Models\FuelReport::count() }}</h3>
                    <small class="text-muted">মোট রিপোর্ট</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-3 bg-light rounded">
                    <h3 class="text-info mb-0">{{ \App\Models\FuelReport::distinct('reporter_phone')->count() }}</h3>
                    <small class="text-muted">রিপোর্টকারী</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
