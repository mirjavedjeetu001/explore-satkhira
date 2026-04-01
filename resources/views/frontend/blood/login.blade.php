@extends('frontend.layouts.app')
@section('title', 'রক্তদাতা লগইন')

@push('styles')
<style>
    .login-hero { background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%); color: white; padding: 2rem 0; }
    .login-card { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
    .btn-blood { background: #dc3545; color: white; border: none; border-radius: 8px; }
    .btn-blood:hover { background: #a71d2a; color: white; }
</style>
@endpush

@section('content')
<section class="login-hero">
    <div class="container">
        <h2 class="fw-bold"><i class="fas fa-sign-in-alt me-2"></i>রক্তদাতা লগইন</h2>
        <p class="mb-0 opacity-90">আপনার তথ্য ম্যানেজ করতে লগইন করুন।</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card login-card">
                    <div class="card-body p-4">
                        @if($errors->any())
                            <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                        @endif

                        <form action="{{ route('blood.login.submit') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">ফোন নম্বর</label>
                                <input type="text" name="phone" class="form-control" required value="{{ old('phone') }}" placeholder="01XXXXXXXXX">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">সংগঠনের নাম <small class="text-muted">(সংগঠনের জন্য)</small></label>
                                <input type="text" name="organization_name" class="form-control" value="{{ old('organization_name') }}" placeholder="যদি সংগঠনের হয়ে লগইন করেন">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">পাসওয়ার্ড</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-blood w-100 mb-3"><i class="fas fa-sign-in-alt me-2"></i>লগইন</button>
                        </form>
                        <div class="text-center">
                            <a href="{{ route('blood.forgot-password') }}" class="text-muted small">পাসওয়ার্ড ভুলে গেছেন?</a>
                            <hr>
                            <span class="text-muted">একাউন্ট নেই?</span>
                            <a href="{{ route('blood.register') }}" class="text-danger fw-bold">নিবন্ধন করুন</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
