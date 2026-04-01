@extends('frontend.layouts.app')
@section('title', 'রক্তদাতা হিসেবে নিবন্ধন')

@push('styles')
<style>
    .register-hero { background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%); color: white; padding: 2rem 0; }
    .register-card { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
    .btn-blood { background: #dc3545; color: white; border: none; border-radius: 8px; }
    .btn-blood:hover { background: #a71d2a; color: white; }
    .form-label { font-weight: 500; font-size: 0.9rem; }
    .org-fields { display: none; }
</style>
@endpush

@section('content')
<section class="register-hero">
    <div class="container">
        <h2 class="fw-bold"><i class="fas fa-hand-holding-heart me-2"></i>রক্তদাতা হিসেবে নিবন্ধন</h2>
        <p class="mb-0 opacity-90">নিবন্ধন করুন এবং প্রয়োজনে অন্যের জীবন বাঁচান।</p>
    </div>
</section>

<section class="py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card register-card">
                    <div class="card-body p-4">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                            </div>
                        @endif

                        <form action="{{ route('blood.register.submit') }}" method="POST">
                            @csrf

                            <!-- Type Selection -->
                            <div class="mb-3">
                                <label class="form-label">নিবন্ধনের ধরন <span class="text-danger">*</span></label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type" value="individual" id="typeIndividual" {{ old('type', 'individual') == 'individual' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="typeIndividual"><i class="fas fa-user me-1"></i>ব্যক্তিগত</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type" value="organization" id="typeOrg" {{ old('type') == 'organization' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="typeOrg"><i class="fas fa-building me-1"></i>সংগঠন</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Organization Name -->
                            <div class="mb-3 org-fields" id="orgFields">
                                <label class="form-label">সংগঠনের নাম <span class="text-danger">*</span></label>
                                <input type="text" name="organization_name" class="form-control" value="{{ old('organization_name') }}" placeholder="সংগঠনের নাম লিখুন">
                            </div>

                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label class="form-label">নাম <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="আপনার পূর্ণ নাম">
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">রক্তের গ্রুপ <span class="text-danger blood-group-required">*</span></label>
                                    <select name="blood_group" class="form-select" id="bloodGroupSelect" required>
                                        <option value="">নির্বাচন করুন</option>
                                        @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                                            <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted org-hint" style="display:none;">সংগঠনের জন্য ঐচ্ছিক</small>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">ফোন নম্বর <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required placeholder="01XXXXXXXXX">
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">WhatsApp নম্বর</label>
                                    <input type="text" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number') }}" placeholder="01XXXXXXXXX">
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">ইমেইল <small class="text-muted">(পাসওয়ার্ড রিসেটের জন্য)</small></label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="email@example.com">
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">সর্বশেষ রক্তদানের তারিখ</label>
                                    <input type="date" name="last_donation_date" class="form-control" value="{{ old('last_donation_date') }}" max="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">উপজেলা <span class="text-danger">*</span></label>
                                    <select name="upazila_id" class="form-select" id="upazilaSelect" required>
                                        <option value="">নির্বাচন করুন</option>
                                        @foreach($upazilas as $up)
                                            <option value="{{ $up->id }}" {{ old('upazila_id') == $up->id ? 'selected' : '' }}>{{ $up->name_bn }}</option>
                                        @endforeach
                                        <option value="outside" {{ old('upazila_id') == 'outside' ? 'selected' : '' }}>🌍 সাতক্ষীরার বাহিরে</option>
                                    </select>
                                </div>
                                <div class="col-sm-6" id="outsideAreaField" style="display:none;">
                                    <label class="form-label">বর্তমান এলাকা/শহর <span class="text-danger">*</span></label>
                                    <input type="text" name="outside_area" class="form-control" value="{{ old('outside_area') }}" placeholder="যেমন: ঢাকা, চট্টগ্রাম, খুলনা...">
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">ঠিকানা</label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address') }}" placeholder="বিস্তারিত ঠিকানা">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">কোন কোন এলাকায় রক্ত দিতে পারবেন</label>
                                    <div class="row g-1">
                                        @foreach($upazilas as $up)
                                            <div class="col-6 col-sm-4 col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="available_areas[]" value="{{ $up->id }}" id="area{{ $up->id }}" {{ is_array(old('available_areas')) && in_array($up->id, old('available_areas')) ? 'checked' : '' }}>
                                                    <label class="form-check-label small" for="area{{ $up->id }}">{{ $up->name_bn }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="hide_phone" value="1" id="hidePhone" {{ old('hide_phone') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="hidePhone">ফোন নম্বর গোপন রাখুন</label>
                                    </div>
                                </div>
                                <div class="col-12" id="altContactField" style="display:none;">
                                    <label class="form-label">বিকল্প যোগাযোগ মাধ্যম</label>
                                    <input type="text" name="alternative_contact" class="form-control" value="{{ old('alternative_contact') }}" placeholder="যেমন: Facebook link, WhatsApp তে যোগাযোগ করুন ইত্যাদি">
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label">পাসওয়ার্ড <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control" required placeholder="কমপক্ষে ৪ অক্ষর">
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">পাসওয়ার্ড নিশ্চিত করুন <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control" required placeholder="আবার পাসওয়ার্ড দিন">
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-blood btn-lg w-100"><i class="fas fa-user-plus me-2"></i>নিবন্ধন করুন</button>
                            </div>

                            <div class="text-center mt-3">
                                <span class="text-muted">ইতিমধ্যে নিবন্ধন করেছেন?</span>
                                <a href="{{ route('blood.login') }}" class="text-danger fw-bold">লগইন করুন</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeRadios = document.querySelectorAll('input[name="type"]');
    const orgFields = document.getElementById('orgFields');
    const hidePhone = document.getElementById('hidePhone');
    const altContact = document.getElementById('altContactField');
    const upazilaSelect = document.getElementById('upazilaSelect');
    const outsideAreaField = document.getElementById('outsideAreaField');

    function toggleOrg() {
        const isOrg = document.getElementById('typeOrg').checked;
        orgFields.style.display = isOrg ? 'block' : 'none';
        const bgSelect = document.getElementById('bloodGroupSelect');
        const bgStar = document.querySelector('.blood-group-required');
        const orgHints = document.querySelectorAll('.org-hint');
        if (isOrg) {
            bgSelect.removeAttribute('required');
            if (bgStar) bgStar.style.display = 'none';
            orgHints.forEach(h => h.style.display = '');
        } else {
            bgSelect.setAttribute('required', 'required');
            if (bgStar) bgStar.style.display = '';
            orgHints.forEach(h => h.style.display = 'none');
        }
    }
    function toggleAlt() {
        altContact.style.display = hidePhone.checked ? 'block' : 'none';
    }
    function toggleOutsideArea() {
        outsideAreaField.style.display = upazilaSelect.value === 'outside' ? '' : 'none';
    }

    typeRadios.forEach(r => r.addEventListener('change', toggleOrg));
    hidePhone.addEventListener('change', toggleAlt);
    upazilaSelect.addEventListener('change', toggleOutsideArea);
    toggleOrg();
    toggleAlt();
    toggleOutsideArea();
});
</script>
@endpush
@endsection
