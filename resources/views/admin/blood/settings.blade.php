@extends('admin.layouts.app')

@section('title', '🩸 Explore Blood - সেটিংস')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-cog me-2"></i>Explore Blood সেটিংস</h1>
    <a href="{{ route('admin.blood.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>ফিরে যান</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold"><i class="fas fa-toggle-on me-2 text-success"></i>ফিচার নিয়ন্ত্রণ</div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="mb-1">Explore Blood</h5>
                        <p class="text-muted mb-0 small">চালু করলে ফ্রন্টেন্ডে রক্তদাতা তালিকা দেখাবে</p>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="featureToggle" 
                               {{ $settings['is_enabled'] == '1' ? 'checked' : '' }} 
                               style="width: 3em; height: 1.5em; cursor: pointer;"
                               onchange="toggleFeature()">
                    </div>
                </div>
                <div id="toggleStatus">
                    <span class="badge {{ $settings['is_enabled'] == '1' ? 'bg-success' : 'bg-secondary' }} fs-6">
                        {{ $settings['is_enabled'] == '1' ? '✅ চালু আছে' : '❌ বন্ধ আছে' }}
                    </span>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">🏠 হোমপেজে দেখান</h6>
                        <p class="text-muted mb-0 small">চালু করলে হোমপেজে টপ রক্তদাতাদের সেকশন দেখাবে</p>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="homepageToggle" 
                               {{ $settings['show_on_homepage'] == '1' ? 'checked' : '' }} 
                               style="width: 3em; height: 1.5em; cursor: pointer;"
                               onchange="toggleHomepage()">
                    </div>
                </div>
                <div id="homepageStatus" class="mt-2">
                    <span class="badge {{ $settings['show_on_homepage'] == '1' ? 'bg-success' : 'bg-secondary' }}">
                        {{ $settings['show_on_homepage'] == '1' ? '✅ হোমপেজে দেখাচ্ছে' : '❌ হোমপেজে বন্ধ' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold"><i class="fas fa-sliders-h me-2 text-primary"></i>সেটিংস</div>
            <div class="card-body">
                <form action="{{ route('admin.blood.settings.update') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">কুলডাউন পিরিয়ড (দিন)</label>
                        <input type="number" name="cooldown_days" class="form-control" value="{{ $settings['cooldown_days'] }}" min="1" max="365" required>
                        <div class="form-text">রক্তদানের পর কতদিন ডোনর অনুপলব্ধ থাকবে (ডিফল্ট: ৯০)</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Not Reachable থ্রেশোল্ড</label>
                        <input type="number" name="not_reachable_threshold" class="form-control" value="{{ $settings['not_reachable_threshold'] }}" min="1" max="100" required>
                        <div class="form-text">কতটি রিপোর্ট পেলে ডোনর স্বয়ংক্রিয়ভাবে অনুপলব্ধ হবে (ডিফল্ট: ১০)</div>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>সংরক্ষণ করুন</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFeature() {
    fetch('{{ route("admin.blood.toggle") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' }
    }).then(r => r.json()).then(d => {
        const badge = document.getElementById('toggleStatus').querySelector('.badge');
        if (d.enabled) {
            badge.className = 'badge bg-success fs-6';
            badge.textContent = '✅ চালু আছে';
        } else {
            badge.className = 'badge bg-secondary fs-6';
            badge.textContent = '❌ বন্ধ আছে';
        }
    }).catch(() => alert('সমস্যা হয়েছে'));
}

function toggleHomepage() {
    fetch('{{ route("admin.blood.toggle-homepage") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' }
    }).then(r => r.json()).then(d => {
        const badge = document.getElementById('homepageStatus').querySelector('.badge');
        if (d.enabled) {
            badge.className = 'badge bg-success';
            badge.textContent = '✅ হোমপেজে দেখাচ্ছে';
        } else {
            badge.className = 'badge bg-secondary';
            badge.textContent = '❌ হোমপেজে বন্ধ';
        }
    }).catch(() => alert('সমস্যা হয়েছে'));
}
</script>
@endsection
