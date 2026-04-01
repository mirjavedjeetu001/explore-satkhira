@extends('admin.layouts.app')

@section('title', 'নতুন ডোনর যুক্ত করুন')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-plus me-2 text-success"></i>নতুন ডোনর যুক্ত করুন</h1>
    <a href="{{ route('admin.blood.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>ফিরে যান</a>
</div>

@if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.blood.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">নাম <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">ফোন <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">হোয়াটসঅ্যাপ নম্বর</label>
                    <input type="text" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">ইমেইল</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">রক্তের গ্রুপ <span class="text-danger">*</span></label>
                    <select name="blood_group" class="form-select" required>
                        <option value="">নির্বাচন করুন</option>
                        @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                            <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">ধরন <span class="text-danger">*</span></label>
                    <select name="type" class="form-select" id="donorType" onchange="toggleOrgField()" required>
                        <option value="individual" {{ old('type') == 'individual' ? 'selected' : '' }}>ব্যক্তিগত</option>
                        <option value="organization" {{ old('type') == 'organization' ? 'selected' : '' }}>সংগঠন</option>
                    </select>
                </div>
                <div class="col-md-4" id="orgField" style="{{ old('type') == 'organization' ? '' : 'display:none' }}">
                    <label class="form-label fw-semibold">সংগঠনের নাম</label>
                    <input type="text" name="organization_name" class="form-control" value="{{ old('organization_name') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">উপজেলা <span class="text-danger">*</span></label>
                    <select name="upazila_id" class="form-select" id="createUpazilaSelect" required>
                        <option value="">নির্বাচন করুন</option>
                        @foreach($upazilas as $u)
                            <option value="{{ $u->id }}" {{ old('upazila_id') == $u->id ? 'selected' : '' }}>{{ $u->name_bn ?? $u->name }}</option>
                        @endforeach
                        <option value="outside" {{ old('upazila_id') == 'outside' ? 'selected' : '' }}>🌍 সাতক্ষীরার বাহিরে</option>
                    </select>
                </div>
                <div class="col-md-4" id="createOutsideField" style="{{ old('upazila_id') == 'outside' ? '' : 'display:none' }}">
                    <label class="form-label fw-semibold">বর্তমান এলাকা/শহর</label>
                    <input type="text" name="outside_area" class="form-control" value="{{ old('outside_area') }}" placeholder="যেমন: ঢাকা, চট্টগ্রাম...">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">সর্বশেষ রক্তদান</label>
                    <input type="date" name="last_donation_date" class="form-control" value="{{ old('last_donation_date') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">পাসওয়ার্ড <span class="text-danger">*</span></label>
                    <input type="text" name="password" class="form-control" value="{{ old('password') }}" required minlength="4" placeholder="কমপক্ষে ৪ অক্ষর">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">ঠিকানা</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">বিকল্প যোগাযোগ</label>
                    <input type="text" name="alternative_contact" class="form-control" value="{{ old('alternative_contact') }}" placeholder="ফেসবুক, অন্য নম্বর ইত্যাদি">
                </div>
                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="hide_phone" id="hidePhone" value="1" {{ old('hide_phone') ? 'checked' : '' }}>
                        <label class="form-check-label" for="hidePhone">ফোন নম্বর লুকান</label>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success"><i class="fas fa-plus me-1"></i>ডোনর যুক্ত করুন</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleOrgField() {
    document.getElementById('orgField').style.display = document.getElementById('donorType').value === 'organization' ? '' : 'none';
}
document.getElementById('createUpazilaSelect').addEventListener('change', function() {
    document.getElementById('createOutsideField').style.display = this.value === 'outside' ? '' : 'none';
});
</script>
@endsection
