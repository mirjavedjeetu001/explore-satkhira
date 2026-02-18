@extends('frontend.layouts.app')

@section('title', 'যোগাযোগ - Satkhira Portal')

@section('content')
<!-- Page Header -->
<section class="page-header py-5" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center text-white">
                <h1 class="display-5 fw-bold">যোগাযোগ করুন</h1>
                <p class="lead mb-0">আমাদের সাথে যোগাযোগ করুন</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Contact Info -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h4 class="mb-4"><i class="fas fa-info-circle text-success me-2"></i>যোগাযোগের তথ্য</h4>
                        
                        @php
                            $settings = \App\Models\SiteSetting::pluck('value', 'key')->toArray();
                        @endphp
                        
                        <div class="mb-4">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                        <i class="fas fa-map-marker-alt text-success"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">ঠিকানা</h6>
                                    <p class="text-muted mb-0">{{ $settings['contact_address'] ?? 'সাতক্ষীরা, বাংলাদেশ' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                        <i class="fas fa-phone text-success"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">ফোন</h6>
                                    <p class="text-muted mb-0">
                                        <a href="tel:{{ $settings['contact_phone'] ?? '' }}" class="text-decoration-none">
                                            {{ $settings['contact_phone'] ?? '+880 XXXXXXXXXX' }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                        <i class="fas fa-envelope text-success"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">ইমেইল</h6>
                                    <p class="text-muted mb-0">
                                        <a href="mailto:{{ $settings['contact_email'] ?? '' }}" class="text-decoration-none">
                                            {{ $settings['contact_email'] ?? 'info@satkhira.com' }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Social Links -->
                        <hr>
                        <h6 class="mb-3">সামাজিক যোগাযোগ</h6>
                        <div class="d-flex gap-2">
                            @if(!empty($settings['facebook']))
                                <a href="{{ $settings['facebook'] }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            @endif
                            @if(!empty($settings['twitter']))
                                <a href="{{ $settings['twitter'] }}" target="_blank" class="btn btn-outline-info btn-sm">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            @endif
                            @if(!empty($settings['youtube']))
                                <a href="{{ $settings['youtube'] }}" target="_blank" class="btn btn-outline-danger btn-sm">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="mb-4"><i class="fas fa-paper-plane text-success me-2"></i>মেসেজ পাঠান</h4>
                        
                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">আপনার নাম <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name') }}" required placeholder="আপনার সম্পূর্ণ নাম">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">ইমেইল <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email') }}" required placeholder="your@email.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">ফোন নম্বর</label>
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone') }}" placeholder="01XXXXXXXXX">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">বিষয় <span class="text-danger">*</span></label>
                                    <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" 
                                           value="{{ old('subject') }}" required placeholder="মেসেজের বিষয়">
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">মেসেজ <span class="text-danger">*</span></label>
                                    <textarea name="message" class="form-control @error('message') is-invalid @enderror" 
                                              rows="5" required placeholder="আপনার মেসেজ লিখুন...">{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-paper-plane me-2"></i>মেসেজ পাঠান
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Map -->
        @if(!empty($settings['google_map']))
        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="ratio ratio-21x9">
                            <iframe src="{{ $settings['google_map'] }}" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
