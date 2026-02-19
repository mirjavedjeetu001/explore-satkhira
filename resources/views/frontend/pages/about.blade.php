@extends('frontend.layouts.app')

@section('title', 'আমাদের সম্পর্কে')

@section('content')
    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <h1>আমাদের সম্পর্কে</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">হোম</a></li>
                    <li class="breadcrumb-item active">আমাদের সম্পর্কে</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- About Intro -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <img src="https://picsum.photos/seed/satkhira/600/400" class="img-fluid rounded shadow" alt="{{ app()->getLocale() == 'bn' ? 'সাতক্ষীরা' : 'Satkhira' }}">
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <h2 class="mb-4">{{ app()->getLocale() == 'bn' ? 'এক্সপ্লোর সাতক্ষীরা সম্পর্কে' : 'About Explore Satkhira' }}</h2>
                    <p class="lead">
                        {{ $settings['about_intro'] ?? (app()->getLocale() == 'bn' ? 'এক্সপ্লোর সাতক্ষীরা হলো সাতক্ষীরা জেলার সকল তথ্যের একটি সমন্বিত ডিজিটাল প্ল্যাটফর্ম।' : 'Explore Satkhira is a comprehensive digital platform for all information about Satkhira district.') }}
                    </p>
                    <p class="text-muted">
                        {{ $settings['about_description'] ?? (app()->getLocale() == 'bn' ? 'এই পোর্টালের মাধ্যমে আপনি সাতক্ষীরা জেলার ৭টি উপজেলার সকল গুরুত্বপূর্ণ তথ্য, হাসপাতাল, শিক্ষা প্রতিষ্ঠান, সরকারি অফিস, ব্যবসা প্রতিষ্ঠান এবং অন্যান্য সেবা সম্পর্কে জানতে পারবেন।' : 'Through this portal, you can learn about all the important information of 7 upazilas of Satkhira district, including hospitals, educational institutions, government offices, businesses and other services.') }}
                    </p>
                    <div class="row mt-4">
                        <div class="col-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success text-white rounded-circle p-3 me-3">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ app()->getLocale() == 'bn' ? '৭' : '7' }}</h5>
                                    <small class="text-muted">{{ app()->getLocale() == 'bn' ? 'উপজেলা' : 'Upazilas' }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success text-white rounded-circle p-3 me-3">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">২০ লক্ষ+</h5>
                                    <small class="text-muted">জনসংখ্যা</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6" data-aos="fade-up">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-success text-white rounded p-3 me-3">
                                    <i class="fas fa-bullseye fa-2x"></i>
                                </div>
                                <h3 class="mb-0">আমাদের লক্ষ্য</h3>
                            </div>
                            <p>{{ $settings['mission'] ?? 'সাতক্ষীরা জেলার সকল নাগরিকদের জন্য একটি সহজ ও সুলভ তথ্য সেবা প্রদান করা। স্থানীয় ব্যবসা, সেবা ও প্রতিষ্ঠানগুলোকে ডিজিটাল প্ল্যাটফর্মে নিয়ে আসা এবং জনগণ ও সংসদ সদস্যের মধ্যে সেতুবন্ধন তৈরি করা।' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-success text-white rounded p-3 me-3">
                                    <i class="fas fa-eye fa-2x"></i>
                                </div>
                                <h3 class="mb-0">আমাদের দৃষ্টিভঙ্গি</h3>
                            </div>
                            <p>{{ $settings['vision'] ?? 'সাতক্ষীরা জেলাকে ডিজিটাল দিক থেকে দেশের একটি অগ্রণী জেলা হিসেবে গড়ে তোলা। প্রতিটি নাগরিকের হাতের মুঠোয় প্রয়োজনীয় তথ্য পৌঁছে দেওয়া এবং স্বচ্ছ ও জবাবদিহিমূলক সেবা নিশ্চিত করা।' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2>আমাদের সেবাসমূহ</h2>
                <div class="underline mx-auto" style="width: 80px; height: 4px; background: linear-gradient(90deg, #1a5f2a, #28a745); border-radius: 2px;"></div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">
                        <div class="bg-success text-white rounded-circle mx-auto mb-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-database fa-2x"></i>
                        </div>
                        <h5>তথ্য ভান্ডার</h5>
                        <p class="text-muted">হাসপাতাল, শিক্ষা প্রতিষ্ঠান, সরকারি অফিস, ব্যবসা প্রতিষ্ঠান সহ সকল গুরুত্বপূর্ণ তথ্য এক জায়গায়</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">
                        <div class="bg-success text-white rounded-circle mx-auto mb-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-comments fa-2x"></i>
                        </div>
                        <h5>MP প্রশ্নোত্তর</h5>
                        <p class="text-muted">মাননীয় সংসদ সদস্যকে সরাসরি প্রশ্ন করুন এবং উত্তর পান</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">
                        <div class="bg-success text-white rounded-circle mx-auto mb-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user-plus fa-2x"></i>
                        </div>
                        <h5>তথ্য যোগ করুন</h5>
                        <p class="text-muted">নিবন্ধন করে আপনার এলাকার তথ্য যোগ করুন এবং সমাজে অবদান রাখুন</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Team Section -->
    @if(isset($teamMembers) && $teamMembers->count() > 0)
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2>আমাদের টিম</h2>
                <p class="text-muted">যারা এই পোর্টাল পরিচালনা করছেন</p>
                <div class="underline mx-auto" style="width: 80px; height: 4px; background: linear-gradient(90deg, #1a5f2a, #28a745); border-radius: 2px;"></div>
            </div>
            
            <div class="row g-4 justify-content-center">
                @foreach($teamMembers as $member)
                    <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="team-card text-center">
                            <div class="team-image-wrapper">
                                @if($member->user->avatar)
                                    <img src="{{ asset('storage/' . $member->user->avatar) }}" 
                                         alt="{{ $member->user->name }}" 
                                         class="team-image">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($member->user->name) }}&background=28a745&color=fff&size=150" 
                                         alt="{{ $member->user->name }}" 
                                         class="team-image">
                                @endif
                            </div>
                            <div class="team-info">
                                <h5 class="team-name">{{ $member->user->name }}</h5>
                                <span class="team-role">{{ $member->role_display }}</span>
                                @if($member->designation_display)
                                    <p class="team-designation">{{ $member->designation_display }}</p>
                                @endif
                                @if($member->bio_display)
                                    <p class="team-bio">{{ Str::limit($member->bio_display, 100) }}</p>
                                @endif
                                @if($member->phone || $member->email)
                                    <div class="team-contact mb-2">
                                        @if($member->phone)
                                            <div class="contact-item">
                                                <i class="fas fa-phone-alt text-success"></i>
                                                <a href="tel:{{ $member->phone }}" class="text-dark">{{ $member->phone }}</a>
                                            </div>
                                        @endif
                                        @if($member->email)
                                            <div class="contact-item">
                                                <i class="fas fa-envelope text-success"></i>
                                                <a href="mailto:{{ $member->email }}" class="text-dark">{{ $member->email }}</a>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                <div class="team-social">
                                    @if($member->facebook_url)
                                        <a href="{{ $member->facebook_url }}" target="_blank" class="social-link">
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                    @endif
                                    @if($member->twitter_url)
                                        <a href="{{ $member->twitter_url }}" target="_blank" class="social-link">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                    @endif
                                    @if($member->linkedin_url)
                                        <a href="{{ $member->linkedin_url }}" target="_blank" class="social-link">
                                            <i class="fab fa-linkedin-in"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Contact CTA -->
    <section class="py-5 bg-success text-white">
        <div class="container text-center" data-aos="fade-up">
            <h2 class="mb-3">আপনার মতামত জানান</h2>
            <p class="lead mb-4">পোর্টাল সম্পর্কে কোন প্রশ্ন বা পরামর্শ থাকলে আমাদের জানান</p>
            <a href="{{ route('contact') }}" class="btn btn-warning btn-lg">
                <i class="fas fa-envelope me-2"></i>যোগাযোগ করুন
            </a>
        </div>
    </section>
@endsection

@push('styles')
<style>
    /* Team Card Styles */
    .team-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        padding: 30px 20px;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .team-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 50px rgba(40, 167, 69, 0.15);
    }
    
    .team-image-wrapper {
        width: 120px;
        height: 120px;
        margin: 0 auto 20px;
        position: relative;
    }
    
    .team-image-wrapper::before {
        content: '';
        position: absolute;
        top: -5px;
        left: -5px;
        right: -5px;
        bottom: -5px;
        background: linear-gradient(135deg, #28a745, #20c997);
        border-radius: 50%;
        z-index: 0;
    }
    
    .team-image {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        position: relative;
        z-index: 1;
        border: 4px solid #fff;
    }
    
    .team-info {
        padding-top: 10px;
    }
    
    .team-name {
        font-weight: 700;
        color: #1a5f2a;
        margin-bottom: 5px;
        font-size: 1.1rem;
    }
    
    .team-role {
        display: inline-block;
        background: linear-gradient(135deg, #28a745, #20c997);
        color: #fff;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        margin-bottom: 10px;
    }
    
    .team-designation {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 10px;
    }
    
    .team-bio {
        color: #888;
        font-size: 0.85rem;
        line-height: 1.5;
        margin-bottom: 15px;
    }
    
    .team-social {
        display: flex;
        justify-content: center;
        gap: 10px;
    }
    
    .social-link {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #666;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .social-link:hover {
        background: #28a745;
        color: #fff;
        transform: scale(1.1);
    }
    
    .social-link i {
        font-size: 0.9rem;
    }
    
    .team-contact {
        font-size: 0.85rem;
    }
    
    .team-contact .contact-item {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        margin-bottom: 4px;
    }
    
    .team-contact .contact-item i {
        font-size: 0.8rem;
    }
    
    .team-contact .contact-item a {
        text-decoration: none;
        transition: color 0.3s;
    }
    
    .team-contact .contact-item a:hover {
        color: #28a745 !important;
    }
</style>
@endpush
