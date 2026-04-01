@extends('admin.layouts.app')

@section('title', 'ডোনর এডিট - ' . $donor->name)

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-edit me-2 text-warning"></i>ডোনর এডিট</h1>
    <a href="{{ route('admin.blood.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>ফিরে যান</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.blood.update', $donor->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">নাম <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $donor->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">ফোন <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $donor->phone) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">হোয়াটসঅ্যাপ নম্বর</label>
                    <input type="text" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', $donor->whatsapp_number) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">ইমেইল</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $donor->email) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">রক্তের গ্রুপ <span class="text-danger">*</span></label>
                    <select name="blood_group" class="form-select" required>
                        @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                            <option value="{{ $bg }}" {{ old('blood_group', $donor->blood_group) == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">ধরন</label>
                    <select name="type" class="form-select" id="donorType" onchange="toggleOrgField()">
                        <option value="individual" {{ old('type', $donor->type) == 'individual' ? 'selected' : '' }}>ব্যক্তিগত</option>
                        <option value="organization" {{ old('type', $donor->type) == 'organization' ? 'selected' : '' }}>সংগঠন</option>
                    </select>
                </div>
                <div class="col-md-4" id="orgField" style="{{ old('type', $donor->type) == 'organization' ? '' : 'display:none' }}">
                    <label class="form-label fw-semibold">সংগঠনের নাম</label>
                    <input type="text" name="organization_name" class="form-control" value="{{ old('organization_name', $donor->organization_name) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">উপজেলা <span class="text-danger">*</span></label>
                    <select name="upazila_id" class="form-select" id="editUpazilaSelect">
                        @foreach($upazilas as $u)
                            <option value="{{ $u->id }}" {{ old('upazila_id', $donor->upazila_id) == $u->id ? 'selected' : '' }}>{{ $u->name_bn ?? $u->name }}</option>
                        @endforeach
                        <option value="outside" {{ !$donor->upazila_id && $donor->outside_area ? 'selected' : '' }}>🌍 সাতক্ষীরার বাহিরে</option>
                    </select>
                </div>
                <div class="col-md-4" id="editOutsideField" style="{{ !$donor->upazila_id && $donor->outside_area ? '' : 'display:none' }}">
                    <label class="form-label fw-semibold">বর্তমান এলাকা/শহর</label>
                    <input type="text" name="outside_area" class="form-control" value="{{ old('outside_area', $donor->outside_area) }}" placeholder="যেমন: ঢাকা, চট্টগ্রাম...">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">সর্বশেষ রক্তদান</label>
                    <input type="date" name="last_donation_date" class="form-control" value="{{ old('last_donation_date', $donor->last_donation_date?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">স্ট্যাটাস <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="active" {{ old('status', $donor->status) == 'active' ? 'selected' : '' }}>সক্রিয়</option>
                        <option value="inactive" {{ old('status', $donor->status) == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয়</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">ঠিকানা</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $donor->address) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">বিকল্প যোগাযোগ</label>
                    <input type="text" name="alternative_contact" class="form-control" value="{{ old('alternative_contact', $donor->alternative_contact) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">নতুন পাসওয়ার্ড (ফাঁকা রাখলে আগেরটা থাকবে)</label>
                    <input type="text" name="new_password" class="form-control" placeholder="নতুন পাসওয়ার্ড">
                </div>

                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_available" id="isAvailable" value="1" {{ old('is_available', $donor->is_available) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isAvailable">Available</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="hide_phone" id="hidePhone" value="1" {{ old('hide_phone', $donor->hide_phone) ? 'checked' : '' }}>
                        <label class="form-check-label" for="hidePhone">ফোন নম্বর লুকান</label>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>আপডেট করুন</button>
                <a href="{{ route('admin.blood.show', $donor->id) }}" class="btn btn-outline-secondary ms-2">বিস্তারিত দেখুন</a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleOrgField() {
    document.getElementById('orgField').style.display = document.getElementById('donorType').value === 'organization' ? '' : 'none';
}
document.getElementById('editUpazilaSelect').addEventListener('change', function() {
    document.getElementById('editOutsideField').style.display = this.value === 'outside' ? '' : 'none';
});
</script>
@endsection
