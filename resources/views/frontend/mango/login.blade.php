@extends('frontend.layouts.app')

@section('title', 'বিক্রেতা লগইন - সাতক্ষীরার আম')

@section('content')
<div class="mango-auth-page py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">

                <div class="text-center mb-4">
                    <div style="font-size:3rem;">🥭</div>
                    <h2 class="fw-bold mt-2">বিক্রেতা লগইন</h2>
                    <p class="text-muted">আপনার স্টোরে লগইন করুন</p>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('mango.login.submit') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">মোবাইল নম্বর</label>
                                <input type="tel" class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                       name="phone" value="{{ old('phone') }}" placeholder="01XXXXXXXXX" autofocus required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">পাসওয়ার্ড</label>
                                <input type="password" class="form-control form-control-lg"
                                       name="password" placeholder="পাসওয়ার্ড দিন" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-warning btn-lg fw-bold">
                                    <i class="fas fa-sign-in-alt me-2"></i>লগইন করুন
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <p class="text-muted">স্টোর নেই?
                        <a href="{{ route('mango.register') }}" class="text-success fw-semibold">এখনই খুলুন</a>
                    </p>
                    <a href="{{ route('mango.index') }}" class="text-muted small">
                        <i class="fas fa-arrow-left me-1"></i>সব স্টোর দেখুন
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
.mango-auth-page { background: #fffbf0; min-height: 60vh; }
</style>
@endsection
