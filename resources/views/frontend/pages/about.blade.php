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
                    @if(!empty($settings['about_image']))
                        <img src="{{ asset('storage/' . $settings['about_image']) }}" class="img-fluid rounded shadow" alt="{{ app()->getLocale() == 'bn' ? 'সাতক্ষীরা' : 'Satkhira' }}">
                    @else
                        <img src="https://picsum.photos/seed/satkhira/600/400" class="img-fluid rounded shadow" alt="{{ app()->getLocale() == 'bn' ? 'সাতক্ষীরা' : 'Satkhira' }}">
                    @endif
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
                                    <h5 class="mb-0">{{ $settings['population'] ?? '২০ লক্ষ+' }}</h5>
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

    <!-- Upazila Moderators Section -->
    @if(isset($upazilaModerators) && $upazilaModerators->count() > 0)
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2>আমাদের উপজেলা মডারেটরস</h2>
                <p class="text-muted">Our Upazila Moderators</p>
                <div class="underline mx-auto" style="width: 80px; height: 4px; background: linear-gradient(90deg, #f39c12, #e67e22); border-radius: 2px;"></div>
            </div>
            
            <div class="row g-4 justify-content-center">
                @foreach($upazilaModerators as $moderator)
                    <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="team-card text-center moderator-card">
                            <div class="moderator-badge">
                                <i class="fas fa-user-shield"></i> মডারেটর
                            </div>
                            <div class="team-image-wrapper">
                                @if($moderator->avatar)
                                    <img src="{{ asset('storage/' . $moderator->avatar) }}" 
                                         alt="{{ $moderator->name }}" 
                                         class="team-image">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($moderator->name) }}&background=f39c12&color=fff&size=150" 
                                         alt="{{ $moderator->name }}" 
                                         class="team-image">
                                @endif
                            </div>
                            <div class="team-info">
                                <h5 class="team-name">{{ $moderator->name }}</h5>
                                <span class="team-role" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $moderator->upazila->name_bn ?? $moderator->upazila->name ?? 'N/A' }}
                                </span>
                                @if($moderator->phone)
                                    <div class="team-contact mt-2">
                                        <div class="contact-item">
                                            <i class="fas fa-phone-alt text-warning"></i>
                                            <a href="tel:{{ $moderator->phone }}" class="text-dark">{{ $moderator->phone }}</a>
                                        </div>
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
            <div class="text-center mb-5" data-aos="fade-up">
                <h2>নিজস্ব ব্যবসা মডারেটরস</h2>
                <p class="text-muted">Own Business Moderators</p>
                <div class="underline mx-auto" style="width: 80px; height: 4px; background: linear-gradient(90deg, #17a2b8, #138496); border-radius: 2px;"></div>
            </div>
            
            <div class="row g-4 justify-content-center">
                @foreach($ownBusinessModerators as $moderator)
                    <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="team-card text-center moderator-card">
                            <div class="moderator-badge" style="background: linear-gradient(135deg, #17a2b8, #138496);">
                                <i class="fas fa-store"></i> ব্যবসা মডারেটর
                            </div>
                            <div class="team-image-wrapper">
                                @if($moderator->avatar)
                                    <img src="{{ asset('storage/' . $moderator->avatar) }}" 
                                         alt="{{ $moderator->name }}" 
                                         class="team-image">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($moderator->name) }}&background=17a2b8&color=fff&size=150" 
                                         alt="{{ $moderator->name }}" 
                                         class="team-image">
                                @endif
                            </div>
                            <div class="team-info">
                                <h5 class="team-name">{{ $moderator->name }}</h5>
                                @if($moderator->approvedCategories->count() > 0)
                                    <span class="team-role" style="background: linear-gradient(135deg, #17a2b8, #138496);">
                                        <i class="fas fa-th-large me-1"></i>{{ $moderator->approvedCategories->pluck('name_bn')->first() ?? $moderator->approvedCategories->pluck('name')->first() }}
                                    </span>
                                @endif
                                @if($moderator->phone)
                                    <div class="team-contact mt-2">
                                        <div class="contact-item">
                                            <i class="fas fa-phone-alt text-info"></i>
                                            <a href="tel:{{ $moderator->phone }}" class="text-dark">{{ $moderator->phone }}</a>
                                        </div>
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
    /* Premium Team Card Styles */
    .team-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8fdf9 100%);
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(40, 167, 69, 0.08),
                    0 4px 12px rgba(0, 0, 0, 0.05),
                    inset 0 1px 0 rgba(255, 255, 255, 0.9);
        padding: 35px 25px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        height: 100%;
        position: relative;
        border: 1px solid rgba(40, 167, 69, 0.1);
        overflow: hidden;
    }
    
    .team-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #28a745, #20c997, #28a745);
        background-size: 200% 100%;
        /* animation disabled for performance */
    }
    
    /* @keyframes shimmer {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    } */
    
    .team-card:hover {
        transform: translateY(-12px) scale(1.02);
        box-shadow: 0 25px 60px rgba(40, 167, 69, 0.18),
                    0 8px 20px rgba(0, 0, 0, 0.08);
        border-color: rgba(40, 167, 69, 0.3);
    }
    
    .team-card::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(40, 167, 69, 0.03) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.4s ease;
        pointer-events: none;
    }
    
    .team-card:hover::after {
        opacity: 1;
    }
    
    .team-image-wrapper {
        width: 130px;
        height: 130px;
        margin: 0 auto 25px;
        position: relative;
    }
    
    .team-image-wrapper::before {
        content: '';
        position: absolute;
        top: -6px;
        left: -6px;
        right: -6px;
        bottom: -6px;
        background: linear-gradient(135deg, #28a745, #20c997, #28a745);
        background-size: 200% 200%;
        border-radius: 50%;
        z-index: 0;
        /* animation disabled for performance */
        box-shadow: 0 4px 20px rgba(40, 167, 69, 0.35);
    }
    
    /* @keyframes gradientRotate {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    } */
    
    .team-image-wrapper::after {
        content: '';
        position: absolute;
        top: -10px;
        left: -10px;
        right: -10px;
        bottom: -10px;
        border: 2px dashed rgba(40, 167, 69, 0.2);
        border-radius: 50%;
        /* animation disabled for performance */
    }
    
    /* @keyframes spin {
        100% { transform: rotate(360deg); }
    } */
    
    .team-image {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        object-fit: cover;
        position: relative;
        z-index: 1;
        border: 5px solid #fff;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }
    
    .team-card:hover .team-image {
        transform: scale(1.05);
    }
    
    .team-info {
        padding-top: 15px;
    }
    
    .team-name {
        font-weight: 800;
        color: #1a5f2a;
        margin-bottom: 8px;
        font-size: 1.2rem;
        letter-spacing: -0.5px;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }
    
    .team-role {
        display: inline-block;
        background: linear-gradient(135deg, #28a745 0%, #20c997 50%, #28a745 100%);
        background-size: 200% 100%;
        color: #fff;
        padding: 6px 16px;
        border-radius: 25px;
        font-size: 0.82rem;
        font-weight: 600;
        margin-bottom: 12px;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        /* animation disabled for performance */
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .team-designation {
        color: #555;
        font-size: 0.95rem;
        margin-bottom: 12px;
        font-weight: 500;
    }
    
    .team-bio {
        color: #777;
        font-size: 0.88rem;
        line-height: 1.6;
        margin-bottom: 18px;
    }
    
    .team-social {
        display: flex;
        justify-content: center;
        gap: 12px;
    }
    
    .social-link {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(145deg, #f5f5f5, #e8e8e8);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #666;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    }
    
    .social-link:hover {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: #fff;
        transform: scale(1.15) rotate(5deg);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
    }
    
    .social-link i {
        font-size: 1rem;
    }
    
    .team-contact {
        font-size: 0.88rem;
    }
    
    .team-contact .contact-item {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-bottom: 6px;
        padding: 5px 12px;
        background: rgba(40, 167, 69, 0.05);
        border-radius: 20px;
        transition: all 0.3s ease;
    }
    
    .team-contact .contact-item:hover {
        background: rgba(40, 167, 69, 0.12);
    }
    
    .team-contact .contact-item i {
        font-size: 0.85rem;
    }
    
    .team-contact .contact-item a {
        text-decoration: none;
        transition: color 0.3s;
        font-weight: 500;
    }
    
    .team-contact .contact-item a:hover {
        color: #28a745 !important;
    }
    
    /* Premium Moderator Card Styles */
    .moderator-card {
        position: relative;
        background: linear-gradient(145deg, #fffdfb 0%, #fff8f0 100%);
        border: 2px solid rgba(243, 156, 18, 0.3);
    }
    
    .moderator-card::before {
        background: linear-gradient(90deg, #f39c12, #e67e22, #f39c12);
        background-size: 200% 100%;
    }
    
    .moderator-card:hover {
        border-color: rgba(243, 156, 18, 0.5);
        box-shadow: 0 25px 60px rgba(243, 156, 18, 0.2),
                    0 8px 20px rgba(0, 0, 0, 0.08);
    }
    
    .moderator-card::after {
        background: radial-gradient(circle, rgba(243, 156, 18, 0.05) 0%, transparent 70%);
    }
    
    .moderator-card .team-image-wrapper::before {
        background: linear-gradient(135deg, #f39c12, #e67e22, #f39c12);
        background-size: 200% 200%;
        box-shadow: 0 4px 20px rgba(243, 156, 18, 0.4);
    }
    
    .moderator-card .team-image-wrapper::after {
        border-color: rgba(243, 156, 18, 0.25);
    }
    
    .moderator-card .team-name {
        color: #c17a00;
    }
    
    .moderator-card .team-role {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 50%, #f39c12 100%);
        box-shadow: 0 4px 15px rgba(243, 156, 18, 0.35);
    }
    
    .moderator-card .social-link:hover {
        background: linear-gradient(135deg, #f39c12, #e67e22);
        box-shadow: 0 6px 20px rgba(243, 156, 18, 0.4);
    }
    
    .moderator-card .team-contact .contact-item {
        background: rgba(243, 156, 18, 0.08);
    }
    
    .moderator-card .team-contact .contact-item:hover {
        background: rgba(243, 156, 18, 0.15);
    }
    
    .moderator-card .team-contact .contact-item a:hover {
        color: #e67e22 !important;
    }
    
    .moderator-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, #f39c12, #e67e22);
        color: #fff;
        padding: 6px 14px;
        border-radius: 25px;
        font-size: 0.78rem;
        font-weight: 700;
        box-shadow: 0 4px 15px rgba(243, 156, 18, 0.4);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        z-index: 10;
    }
    
    .moderator-badge i {
        margin-right: 5px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .team-card {
            padding: 25px 18px;
        }
        .team-image-wrapper,
        .team-image {
            width: 110px;
            height: 110px;
        }
        .team-name {
            font-size: 1.05rem;
        }
        .team-role {
            font-size: 0.75rem;
            padding: 5px 12px;
        }
    }
</style>
@endpush
