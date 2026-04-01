@extends('frontend.layouts.app')
@section('title', 'ডোনর যোগ করুন - ' . $org->organization_name)

@push('styles')
<style>
    .org-hero { background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%); color: white; padding: 2rem 0; }
    .btn-blood { background: #dc3545; color: white; border: none; border-radius: 8px; }
    .btn-blood:hover { background: #a71d2a; color: white; }
</style>
@endpush

@section('content')
<section class="org-hero">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h3 class="fw-bold mb-1"><i class="fas fa-user-plus me-2"></i>ডোনর যোগ করুন</h3>
                <p class="mb-0 opacity-75">{{ $org->organization_name }} - এ নতুন ডোনর যোগ করুন</p>
            </div>
            <a href="{{ route('blood.org-donors') }}" class="btn btn-light">
                <i class="fas fa-arrow-left me-1"></i>ডোনর তালিকা
            </a>
        </div>
    </div>
</section>

<section class="py-4">
    <div class="container">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <div class="card border-0 shadow-sm" style="max-width: 700px; margin: 0 auto;">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-tint text-danger me-2"></i>ডোনর তথ্য
            </div>
            <div class="card-body">
                <form action="{{ route('blood.org-store-donor') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">নাম <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">রক্তের গ্রুপ <span class="text-danger">*</span></label>
                            <select name="blood_group" class="form-select" required>
                                <option value="">নির্বাচন করুন</option>
                                @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $g)
                                    <option value="{{ $g }}" {{ old('blood_group') == $g ? 'selected' : '' }}>{{ $g }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ফোন নম্বর <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="01XXXXXXXXX" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">হোয়াটসঅ্যাপ</label>
                            <input type="text" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">উপজেলা</label>
                            <select name="upazila_id" class="form-select" id="upazilaSelect" onchange="toggleOutside()">
                                <option value="">নির্বাচন করুন</option>
                                @foreach($upazilas as $upazila)
                                    <option value="{{ $upazila->id }}" {{ old('upazila_id') == $upazila->id ? 'selected' : '' }}>
                                        {{ $upazila->name_bn ?? $upazila->name }}
                                    </option>
                                @endforeach
                                <option value="outside" {{ old('upazila_id') == 'outside' ? 'selected' : '' }}>🌍 সাতক্ষীরার বাহিরে</option>
                            </select>
                        </div>
                        <div class="col-md-6" id="outsideAreaDiv" style="display: none;">
                            <label class="form-label">বর্তমান এলাকা/শহর</label>
                            <input type="text" name="outside_area" class="form-control" value="{{ old('outside_area') }}" placeholder="যেমন: ঢাকা, চট্টগ্রাম">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">সর্বশেষ রক্তদানের তারিখ</label>
                            <input type="date" name="last_donation_date" class="form-control" value="{{ old('last_donation_date') }}" max="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-blood btn-lg">
                            <i class="fas fa-user-plus me-2"></i>ডোনর যোগ করুন
                        </button>
                    </div>
                    <small class="text-muted d-block mt-2"><i class="fas fa-info-circle me-1"></i>ডোনরের ডিফল্ট পাসওয়ার্ড হবে তার ফোন নম্বর।</small>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
function toggleOutside() {
    const v = document.getElementById('upazilaSelect').value;
    document.getElementById('outsideAreaDiv').style.display = v === 'outside' ? '' : 'none';
}
document.addEventListener('DOMContentLoaded', toggleOutside);
</script>
@endsection
