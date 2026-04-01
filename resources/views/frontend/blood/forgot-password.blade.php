@extends('frontend.layouts.app')
@section('title', 'পাসওয়ার্ড রিসেট')

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
        <h2 class="fw-bold"><i class="fas fa-key me-2"></i>পাসওয়ার্ড রিসেট</h2>
        <p class="mb-0 opacity-90">আপনার ফোন নম্বর দিন, ইমেইলে নতুন পাসওয়ার্ড পাঠানো হবে।</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card login-card">
                    <div class="card-body p-4">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                        @endif

                        <form action="{{ route('blood.forgot-password.submit') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">রেজিস্টার্ড ফোন নম্বর</label>
                                <input type="text" name="phone" class="form-control" required value="{{ old('phone') }}" placeholder="01XXXXXXXXX">
                            </div>
                            <button type="submit" class="btn btn-blood w-100"><i class="fas fa-paper-plane me-2"></i>পাসওয়ার্ড পাঠান</button>
                        </form>
                        <div class="text-center mt-3">
                            <a href="{{ route('blood.login') }}" class="text-danger">লগইনে ফিরুন</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
