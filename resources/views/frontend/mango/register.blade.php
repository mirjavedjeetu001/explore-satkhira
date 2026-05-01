@extends('frontend.layouts.app')

@section('title', 'আম স্টোর খুলুন - সাতক্ষীরার আম')

@section('content')
<div class="mango-auth-page py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="text-center mb-4">
                    <div style="font-size:3rem;">🥭</div>
                    <h2 class="fw-bold mt-2">আম স্টোর খুলুন</h2>
                    <p class="text-muted">আপনার আমের ব্যবসা অনলাইনে তুলুন - সম্পূর্ণ বিনামূল্যে!</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <i class="fas fa-store me-2"></i>স্টোরের তথ্য দিন
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('mango.register.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <h6 class="fw-bold text-success mb-3"><i class="fas fa-user me-2"></i>ব্যক্তিগত তথ্য</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">আপনার নাম <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('owner_name') is-invalid @enderror"
                                           name="owner_name" value="{{ old('owner_name') }}" placeholder="মালিকের নাম" required>
                                    @error('owner_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">স্টোরের নাম <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('store_name') is-invalid @enderror"
                                           name="store_name" value="{{ old('store_name') }}" placeholder="যেমন: রহিমের আম বাগান" required>
                                    @error('store_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">মোবাইল নম্বর <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                           name="phone" value="{{ old('phone') }}" placeholder="01XXXXXXXXX" required>
                                    <div class="form-text">এটি আপনার লগইন নম্বর হবে।</div>
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">WhatsApp নম্বর</label>
                                    <input type="tel" class="form-control @error('whatsapp') is-invalid @enderror"
                                           name="whatsapp" value="{{ old('whatsapp') }}" placeholder="01XXXXXXXXX (না থাকলে খালি)">
                                    @error('whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <h6 class="fw-bold text-success mb-3"><i class="fas fa-lock me-2"></i>পাসওয়ার্ড</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">পাসওয়ার্ড <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           name="password" placeholder="কমপক্ষে ৬ অক্ষর" required>
                                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">পাসওয়ার্ড নিশ্চিত করুন <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password_confirmation" placeholder="আবার দিন" required>
                                </div>
                            </div>

                            <h6 class="fw-bold text-success mb-3"><i class="fas fa-map-marker-alt me-2"></i>অবস্থান</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">উপজেলা</label>
                                    <select name="upazila_id" class="form-select">
                                        <option value="">উপজেলা নির্বাচন করুন</option>
                                        @foreach($upazilas as $upazila)
                                            <option value="{{ $upazila->id }}" {{ old('upazila_id') == $upazila->id ? 'selected' : '' }}>
                                                {{ $upazila->name_bn ?? $upazila->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">ঠিকানা</label>
                                    <input type="text" class="form-control" name="address" value="{{ old('address') }}" placeholder="গ্রাম/মহল্লার নাম">
                                </div>
                            </div>

                            <h6 class="fw-bold text-success mb-3"><i class="fas fa-info-circle me-2"></i>অতিরিক্ত তথ্য</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <label class="form-label">স্টোর সম্পর্কে</label>
                                    <textarea class="form-control" name="description" rows="3"
                                              placeholder="আপনার আম বাগান / ব্যবসা সম্পর্কে কিছু লিখুন...">{{ old('description') }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">ডেলিভারি তথ্য</label>
                                    <textarea class="form-control" name="delivery_info" rows="3"
                                              placeholder="কোথায় ডেলিভারি দেন, কত দিনে, কোনো চার্জ আছে কিনা...">{{ old('delivery_info') }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Facebook পেজ লিংক</label>
                                    <input type="url" class="form-control @error('facebook_url') is-invalid @enderror"
                                           name="facebook_url" value="{{ old('facebook_url') }}" placeholder="https://facebook.com/...">
                                    @error('facebook_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">স্টোরের লোগো / ছবি</label>
                                    <input type="file" class="form-control @error('logo') is-invalid @enderror"
                                           name="logo" accept="image/*">
                                    <div class="form-text">JPG/PNG, সর্বোচ্চ ২ MB</div>
                                    @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-warning btn-lg fw-bold">
                                    <i class="fas fa-store me-2"></i>স্টোর তৈরি করুন
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <p class="text-muted">ইতিমধ্যে স্টোর আছে?
                        <a href="{{ route('mango.login') }}" class="text-success fw-semibold">লগইন করুন</a>
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
.mango-auth-page { background: #fffbf0; min-height: 60vh; }
</style>
@endsection
