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

<!-- Our Team Section -->
@if(isset($teamMembers) && $teamMembers->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">আমাদের টিম</h2>
            <p class="text-muted">যারা এই পোর্টাল পরিচালনা করছেন</p>
            <div class="underline mx-auto" style="width: 80px; height: 4px; background: linear-gradient(90deg, #1a5f2a, #28a745); border-radius: 2px;"></div>
        </div>
        
        <div class="row g-4 justify-content-center">
            @foreach($teamMembers as $member)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm h-100 text-center">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                @if($member->user->avatar)
                                    <img src="{{ asset('storage/' . $member->user->avatar) }}" 
                                         alt="{{ $member->user->name }}" 
                                         class="rounded-circle"
                                         style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #28a745;">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($member->user->name) }}&background=28a745&color=fff&size=100" 
                                         alt="{{ $member->user->name }}" 
                                         class="rounded-circle"
                                         style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #28a745;">
                                @endif
                            </div>
                            <h5 class="fw-bold mb-1">{{ $member->user->name }}</h5>
                            <span class="badge bg-success mb-2">{{ $member->role_display }}</span>
                            @if($member->designation_display)
                                <p class="text-muted small mb-2">{{ $member->designation_display }}</p>
                            @endif
                            @if($member->phone || $member->email)
                                <div class="small">
                                    @if($member->phone)
                                        <div class="mb-1">
                                            <i class="fas fa-phone-alt text-success me-1"></i>
                                            <a href="tel:{{ $member->phone }}" class="text-decoration-none text-dark">{{ $member->phone }}</a>
                                        </div>
                                    @endif
                                    @if($member->email)
                                        <div>
                                            <i class="fas fa-envelope text-success me-1"></i>
                                            <a href="mailto:{{ $member->email }}" class="text-decoration-none text-dark small">{{ $member->email }}</a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Upazila Moderators Section -->
@if(isset($upazilaModerators) && $upazilaModerators->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">আমাদের উপজেলা মডারেটরস</h2>
            <p class="text-muted">Our Upazila Moderators</p>
            <div class="underline mx-auto" style="width: 80px; height: 4px; background: linear-gradient(90deg, #f39c12, #e67e22); border-radius: 2px;"></div>
        </div>
        
        <div class="row g-4 justify-content-center">
            @foreach($upazilaModerators as $moderator)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm h-100 text-center position-relative">
                        <div class="position-absolute top-0 start-50 translate-middle">
                            <span class="badge" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                                <i class="fas fa-user-shield me-1"></i> মডারেটর
                            </span>
                        </div>
                        <div class="card-body p-4 pt-5">
                            <div class="mb-3 mt-2">
                                @if($moderator->avatar)
                                    <img src="{{ asset('storage/' . $moderator->avatar) }}" 
                                         alt="{{ $moderator->name }}" 
                                         class="rounded-circle"
                                         style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #f39c12;">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($moderator->name) }}&background=f39c12&color=fff&size=100" 
                                         alt="{{ $moderator->name }}" 
                                         class="rounded-circle"
                                         style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #f39c12;">
                                @endif
                            </div>
                            <h5 class="fw-bold mb-1">{{ $moderator->name }}</h5>
                            <span class="badge mb-2" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $moderator->upazila->name_bn ?? $moderator->upazila->name ?? 'N/A' }}
                            </span>
                            @if($moderator->phone)
                                <div class="small mt-2">
                                    <i class="fas fa-phone-alt text-warning me-1"></i>
                                    <a href="tel:{{ $moderator->phone }}" class="text-decoration-none text-dark">{{ $moderator->phone }}</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Own Business Moderators Section -->
@if(isset($ownBusinessModerators) && $ownBusinessModerators->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">নিজস্ব ব্যবসা মডারেটরস</h2>
            <p class="text-muted">Own Business Moderators</p>
            <div class="underline mx-auto" style="width: 80px; height: 4px; background: linear-gradient(90deg, #17a2b8, #138496); border-radius: 2px;"></div>
        </div>
        
        <div class="row g-4 justify-content-center">
            @foreach($ownBusinessModerators as $moderator)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm h-100 text-center position-relative">
                        <div class="position-absolute top-0 start-50 translate-middle">
                            <span class="badge" style="background: linear-gradient(135deg, #17a2b8, #138496);">
                                <i class="fas fa-store me-1"></i> ব্যবসা মডারেটর
                            </span>
                        </div>
                        <div class="card-body p-4 pt-5">
                            <div class="mb-3 mt-2">
                                @if($moderator->avatar)
                                    <img src="{{ asset('storage/' . $moderator->avatar) }}" 
                                         alt="{{ $moderator->name }}" 
                                         class="rounded-circle"
                                         style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #17a2b8;">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($moderator->name) }}&background=17a2b8&color=fff&size=100" 
                                         alt="{{ $moderator->name }}" 
                                         class="rounded-circle"
                                         style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #17a2b8;">
                                @endif
                            </div>
                            <h5 class="fw-bold mb-1">{{ $moderator->name }}</h5>
                            @if($moderator->approvedCategories && $moderator->approvedCategories->count() > 0)
                                <div class="mb-2">
                                    @foreach($moderator->approvedCategories->take(2) as $cat)
                                        <span class="badge bg-info me-1">{{ $cat->name_bn ?? $cat->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                            @if($moderator->phone)
                                <div class="small mt-2">
                                    <i class="fas fa-phone-alt text-info me-1"></i>
                                    <a href="tel:{{ $moderator->phone }}" class="text-decoration-none text-dark">{{ $moderator->phone }}</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
