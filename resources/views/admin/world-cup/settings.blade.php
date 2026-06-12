@extends('admin.layouts.app')

@section('title', 'World Cup 2026 - সেটিংস')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-futbol me-2"></i>World Cup 2026 সেটিংস</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
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
        <form action="{{ route('admin.world-cup.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-12">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_enabled" id="isEnabled"
                               {{ $settings['is_enabled'] == '1' ? 'checked' : '' }} style="width: 3em; height: 1.5em;">
                        <label class="form-check-label fw-semibold ms-2" for="isEnabled">
                            World Cup ফিচার চালু/বন্ধ (পুরো ফিচার)
                        </label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="show_on_homepage" id="showHomepage"
                               {{ $settings['show_on_homepage'] == '1' ? 'checked' : '' }} style="width: 3em; height: 1.5em;">
                        <label class="form-check-label fw-semibold ms-2" for="showHomepage">
                            হোমপেজে World Cup সেকশন দেখাবে
                        </label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="show_floating_button" id="showFloating"
                               {{ $settings['show_floating_button'] == '1' ? 'checked' : '' }} style="width: 3em; height: 1.5em;">
                        <label class="form-check-label fw-semibold ms-2" for="showFloating">
                            ফ্লোটিং বাটন দেখাবে (right corner)
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
                    <h3 class="text-success mb-0">{{ \Illuminate\Support\Facades\Cache::get('wc_page_visits', 0) }}</h3>
                    <small class="text-muted">World Cup পেজ ভিজিট</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
