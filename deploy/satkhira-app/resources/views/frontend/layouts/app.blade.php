<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @php
        $localeSiteName = app()->getLocale() === 'bn' 
            ? ($siteSettings['site_name_bn'] ?? __('messages.site_name'))
            : ($siteSettings['site_name'] ?? __('messages.site_name'));
    @endphp
    <title>@yield('title', $localeSiteName) - {{ $localeSiteName }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #28a745;
            --secondary-color: #1a5f2a;
            --accent-color: #ffc107;
            --dark-color: #1a3c34;
            --light-bg: #f8f9fa;
        }
        
        * {
            font-family: 'Hind Siliguri', sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
        }
        
        /* Navbar Styles */
        .navbar-custom {
            background: linear-gradient(135deg, #1a5f2a 0%, #28a745 100%);
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        
        .navbar-custom .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #fff !important;
        }
        
        .navbar-custom .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        
        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link.active {
            color: #fff !important;
            background: rgba(255,255,255,0.1);
            border-radius: 5px;
        }
        
        /* Top Bar */
        .top-bar {
            background: #1a3c34;
            padding: 8px 0;
            font-size: 0.9rem;
            color: #fff;
        }
        
        .top-bar a {
            color: #fff;
            text-decoration: none;
        }
        
        .top-bar a:hover {
            color: rgba(255,255,255,0.8);
        }
        
        /* Hero Section */
        .hero-slider {
            position: relative;
            overflow: hidden;
        }
        
        .hero-slider .carousel-item {
            height: 500px;
            background-size: cover;
            background-position: center;
        }
        
        .hero-slider .carousel-caption {
            bottom: 50%;
            transform: translateY(50%);
        }
        
        .hero-slider .carousel-caption h2 {
            font-size: 3rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        
        /* Category Cards */
        .category-card {
            background: #fff;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            height: 100%;
        }
        
        .category-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        
        .category-card .icon-wrapper {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 2rem;
            color: #fff;
        }
        
        .category-card h5 {
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .category-card .listing-count {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        /* Upazila Cards */
        .upazila-card {
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .upazila-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        
        .upazila-card .card-body {
            padding: 20px;
        }
        
        .upazila-card h5 {
            font-weight: 600;
            color: var(--dark-color);
        }
        
        /* Listing Cards */
        .listing-card {
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            height: 100%;
        }
        
        .listing-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        
        .listing-card .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        
        .listing-card .category-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .listing-card .featured-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--accent-color);
            color: #000;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        /* Section Headers */
        .section-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .section-header h2 {
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 10px;
        }
        
        .section-header .underline {
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary-color), var(--primary-color));
            margin: 0 auto;
            border-radius: 2px;
        }
        
        /* MP Section */
        .mp-section {
            background: linear-gradient(135deg, #1a3c34 0%, #28a745 100%);
            color: #fff;
            padding: 60px 0;
        }
        
        .mp-card {
            background: rgba(255,255,255,0.1);
            border-radius: 15px;
            padding: 30px;
            backdrop-filter: blur(10px);
        }
        
        .mp-card img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid rgba(255,255,255,0.3);
        }
        
        /* Footer */
        .footer {
            background: #1a3c34;
            color: rgba(255,255,255,0.8);
            padding: 60px 0 20px;
        }
        
        .footer h5 {
            color: #fff;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .footer a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer a:hover {
            color: #fff;
            padding-left: 5px;
        }
        
        .footer .social-links a {
            display: inline-flex;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            transition: all 0.3s ease;
        }
        
        .footer .social-links a:hover {
            background: var(--primary-color);
            transform: translateY(-3px);
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 20px;
            margin-top: 40px;
        }
        
        /* Buttons */
        .btn-primary-custom {
            background: linear-gradient(135deg, #28a745 0%, #1a5f2a 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }
        
        /* Search Box */
        .search-box {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-top: -60px;
            position: relative;
            z-index: 100;
        }
        
        /* Breadcrumb */
        .breadcrumb-section {
            background: linear-gradient(135deg, #1a3c34 0%, #28a745 100%);
            padding: 40px 0;
            color: #fff;
        }
        
        .breadcrumb-section h1 {
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .breadcrumb-section .breadcrumb {
            background: transparent;
            margin: 0;
            padding: 0;
        }
        
        .breadcrumb-section .breadcrumb-item a {
            color: rgba(255,255,255,0.8);
        }
        
        .breadcrumb-section .breadcrumb-item.active {
            color: #fff;
        }
        
        /* Alert Messages */
        .alert-floating {
            position: fixed;
            top: 100px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-slider .carousel-item {
                height: 350px;
            }
            
            .hero-slider .carousel-caption h2 {
                font-size: 1.8rem;
            }
            
            .search-box {
                margin-top: 20px;
            }
        }
        
        /* Preloader */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #1a3c34 0%, #28a745 100%);
            z-index: 99999;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            transition: opacity 0.5s ease, visibility 0.5s ease;
            overflow: hidden;
        }
        
        .preloader.fade-out {
            opacity: 0;
            visibility: hidden;
        }
        
        .preloader-inner {
            text-align: center;
            width: 100%;
            max-width: 90vw;
            padding: 0 15px;
            box-sizing: border-box;
        }
        
        .preloader-logo {
            font-size: 2.5rem;
            color: #fff;
            font-weight: 700;
            margin-bottom: 30px;
            animation: pulse 1.5s ease-in-out infinite;
            word-break: break-word;
        }
        
        .preloader-logo i {
            margin-right: 10px;
            color: #ffc107;
        }
        
        .preloader-spinner {
            width: 60px;
            height: 60px;
            position: relative;
            margin: 0 auto;
        }
        
        .preloader-spinner .spinner-ring {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 4px solid transparent;
            animation: spin 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
        }
        
        .preloader-spinner .spinner-ring:nth-child(1) {
            border-top-color: #fff;
            animation-delay: -0.45s;
        }
        
        .preloader-spinner .spinner-ring:nth-child(2) {
            border-right-color: #ffc107;
            animation-delay: -0.3s;
        }
        
        .preloader-spinner .spinner-ring:nth-child(3) {
            border-bottom-color: #fff;
            animation-delay: -0.15s;
        }
        
        .preloader-spinner .spinner-ring:nth-child(4) {
            border-left-color: #ffc107;
        }
        
        .preloader-text {
            color: rgba(255,255,255,0.8);
            margin-top: 20px;
            font-size: 1rem;
            letter-spacing: 2px;
        }
        
        .preloader-dots {
            display: inline-block;
        }
        
        .preloader-dots span {
            animation: dots 1.5s infinite;
            opacity: 0;
        }
        
        .preloader-dots span:nth-child(1) { animation-delay: 0s; }
        .preloader-dots span:nth-child(2) { animation-delay: 0.2s; }
        .preloader-dots span:nth-child(3) { animation-delay: 0.4s; }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }
        
        @keyframes dots {
            0%, 20% { opacity: 0; }
            50% { opacity: 1; }
            100% { opacity: 0; }
        }
        
        /* Mobile Responsive Preloader */
        @media (max-width: 768px) {
            .preloader-inner {
                max-width: 85vw;
                padding: 0 10px;
            }
            
            .preloader-logo {
                font-size: 1.4rem;
                margin-bottom: 20px;
            }
            
            .preloader-logo i {
                margin-right: 8px;
            }
            
            .preloader-spinner {
                width: 45px;
                height: 45px;
            }
            
            .preloader-spinner .spinner-ring {
                border-width: 3px;
            }
            
            .preloader-text {
                font-size: 0.85rem;
                margin-top: 15px;
                letter-spacing: 1px;
            }
        }
        
        @media (max-width: 480px) {
            .preloader-inner {
                max-width: 90vw;
                padding: 0 8px;
            }
            
            .preloader-logo {
                font-size: 1.1rem;
                margin-bottom: 15px;
            }
            
            .preloader-spinner {
                width: 35px;
                height: 35px;
            }
            
            .preloader-spinner .spinner-ring {
                border-width: 2px;
            }
            
            .preloader-text {
                font-size: 0.7rem;
                margin-top: 12px;
            }
        }
        
        @media (max-width: 360px) {
            .preloader-logo {
                font-size: 1rem;
            }
            
            .preloader-spinner {
                width: 30px;
                height: 30px;
            }
            
            .preloader-text {
                font-size: 0.65rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Preloader -->
    <div class="preloader" id="preloader">
        <div class="preloader-inner">
            <div class="preloader-logo">
                <i class="fas fa-leaf"></i>{{ __('messages.site_name') }}
            </div>
            <div class="preloader-spinner">
                <div class="spinner-ring"></div>
                <div class="spinner-ring"></div>
                <div class="spinner-ring"></div>
                <div class="spinner-ring"></div>
            </div>
            <div class="preloader-text">
                {{ app()->getLocale() == 'bn' ? 'লোড হচ্ছে' : 'Loading' }}<span class="preloader-dots"><span>.</span><span>.</span><span>.</span></span>
            </div>
        </div>
    </div>
    
    <!-- Top Bar -->
    <div class="top-bar d-none d-lg-block">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <i class="fas fa-phone-alt me-2"></i> {{ \App\Models\SiteSetting::get('contact_phone', '+880 1700-000000') }}
                    <span class="mx-3">|</span>
                    <i class="fas fa-envelope me-2"></i> {{ \App\Models\SiteSetting::get('contact_email', 'info@satkhira.com') }}
                </div>
                <div class="col-md-6 text-end">
                    <!-- Language Switcher -->
                    <div class="d-inline-block me-3">
                        <a href="{{ route('language.switch', 'bn') }}" class="me-1 {{ app()->getLocale() == 'bn' ? 'fw-bold text-warning' : '' }}">বাংলা</a>
                        <span>|</span>
                        <a href="{{ route('language.switch', 'en') }}" class="ms-1 {{ app()->getLocale() == 'en' ? 'fw-bold text-warning' : '' }}">English</a>
                    </div>
                    <span class="me-3">|</span>
                    @guest
                        <a href="{{ route('login') }}" class="me-3"><i class="fas fa-sign-in-alt me-1"></i> {{ __('messages.login') }}</a>
                        <a href="{{ route('register') }}"><i class="fas fa-user-plus me-1"></i> {{ __('messages.register') }}</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="me-3"><i class="fas fa-tachometer-alt me-1"></i> {{ __('messages.dashboard') }}</a>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <a href="#" onclick="event.preventDefault(); this.closest('form').submit();"><i class="fas fa-sign-out-alt me-1"></i> {{ __('messages.logout') }}</a>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-leaf me-2"></i>{{ __('messages.site_name') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i> {{ __('messages.home') }}
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="fas fa-map-marker-alt me-1"></i> {{ __('messages.upazilas') }}
                        </a>
                        <ul class="dropdown-menu">
                            @foreach(\App\Models\Upazila::active()->ordered()->get() as $upazila)
                                <li><a class="dropdown-item" href="{{ route('upazilas.show', $upazila) }}">{{ app()->getLocale() == 'bn' ? ($upazila->name_bn ?? $upazila->name) : $upazila->name }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="fas fa-th-large me-1"></i> {{ __('messages.categories') }}
                        </a>
                        <ul class="dropdown-menu">
                            @foreach(\App\Models\Category::active()->parentCategories()->inMenu()->ordered()->get() as $category)
                                <li><a class="dropdown-item" href="{{ route('categories.show', $category) }}">
                                    <i class="fas {{ $category->icon }} me-2" style="color: {{ $category->color }}"></i>
                                    {{ app()->getLocale() == 'bn' ? ($category->name_bn ?? $category->name) : $category->name }}
                                </a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('mp.*') ? 'active' : '' }}" href="{{ route('mp.index') }}">
                            <i class="fas fa-user-tie me-1"></i> {{ __('messages.mp') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}" href="{{ route('news.index') }}">
                            <i class="fas fa-newspaper me-1"></i> {{ __('messages.news') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                            <i class="fas fa-info-circle me-1"></i> {{ __('messages.about') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                            <i class="fas fa-phone me-1"></i> {{ __('messages.contact') }}
                        </a>
                    </li>
                    @auth
                        @if(auth()->user()->isActive())
                            <li class="nav-item">
                                <a class="nav-link btn btn-warning btn-sm ms-2 text-dark px-3" href="{{ route('dashboard.listings.create') }}">
                                    <i class="fas fa-plus me-1"></i> {{ __('messages.add_listing') }}
                                </a>
                            </li>
                        @endif
                    @endauth
                    
                    <!-- Mobile Only Items -->
                    <li class="nav-item d-lg-none">
                        <hr class="dropdown-divider" style="border-color: rgba(255,255,255,0.2); margin: 10px 0;">
                    </li>
                    <li class="nav-item d-lg-none">
                        <span class="nav-link text-white-50" style="font-size: 0.85rem;">
                            <i class="fas fa-phone-alt me-2"></i>{{ \App\Models\SiteSetting::get('contact_phone', '+880 1700-000000') }}
                        </span>
                    </li>
                    <li class="nav-item d-lg-none">
                        <div class="nav-link">
                            <a href="{{ route('language.switch', 'bn') }}" class="me-2 {{ app()->getLocale() == 'bn' ? 'text-warning fw-bold' : 'text-white' }}" style="text-decoration: none;">বাংলা</a>
                            <span class="text-white-50">|</span>
                            <a href="{{ route('language.switch', 'en') }}" class="ms-2 {{ app()->getLocale() == 'en' ? 'text-warning fw-bold' : 'text-white' }}" style="text-decoration: none;">English</a>
                        </div>
                    </li>
                    @guest
                        <li class="nav-item d-lg-none">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i> {{ __('messages.login') }}
                            </a>
                        </li>
                        <li class="nav-item d-lg-none">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i> {{ __('messages.register') }}
                            </a>
                        </li>
                    @else
                        <li class="nav-item d-lg-none">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-1"></i> {{ __('messages.dashboard') }}
                            </a>
                        </li>
                        <li class="nav-item d-lg-none">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="nav-link" href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="fas fa-sign-out-alt me-1"></i> {{ __('messages.logout') }}
                                </a>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-floating alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-floating alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5><i class="fas fa-leaf me-2"></i>{{ __('messages.site_name') }}</h5>
                    <p>{{ __('messages.site_tagline') }}</p>
                    <div class="social-links mt-3">
                        @if(\App\Models\SiteSetting::get('facebook'))
                            <a href="{{ \App\Models\SiteSetting::get('facebook') }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if(\App\Models\SiteSetting::get('youtube'))
                            <a href="{{ \App\Models\SiteSetting::get('youtube') }}" target="_blank"><i class="fab fa-youtube"></i></a>
                        @endif
                        @if(\App\Models\SiteSetting::get('twitter'))
                            <a href="{{ \App\Models\SiteSetting::get('twitter') }}" target="_blank"><i class="fab fa-twitter"></i></a>
                        @endif
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h5>{{ __('messages.quick_links') }}</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('home') }}"><i class="fas fa-chevron-right me-2"></i>{{ __('messages.home') }}</a></li>
                        <li class="mb-2"><a href="{{ route('upazilas.index') }}"><i class="fas fa-chevron-right me-2"></i>{{ __('messages.upazilas') }}</a></li>
                        <li class="mb-2"><a href="{{ route('categories.index') }}"><i class="fas fa-chevron-right me-2"></i>{{ __('messages.categories') }}</a></li>
                        <li class="mb-2"><a href="{{ route('mp.index') }}"><i class="fas fa-chevron-right me-2"></i>{{ __('messages.mp') }}</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4 mb-4">
                    <h5>{{ __('messages.categories') }}</h5>
                    <ul class="list-unstyled">
                        @foreach(\App\Models\Category::active()->take(5)->get() as $category)
                            <li class="mb-2">
                                <a href="{{ route('categories.show', $category) }}">
                                    <i class="fas fa-chevron-right me-2"></i>{{ app()->getLocale() == 'bn' ? ($category->name_bn ?? $category->name) : $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4 mb-4">
                    <h5>{{ __('messages.contact') }}</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i>{{ \App\Models\SiteSetting::get('contact_address', 'Satkhira, Bangladesh') }}</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i>{{ \App\Models\SiteSetting::get('contact_phone') }}</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i>{{ \App\Models\SiteSetting::get('contact_email') }}</li>
                        @if(\App\Models\SiteSetting::get('whatsapp'))
                            <li class="mb-2"><i class="fab fa-whatsapp me-2"></i>{{ \App\Models\SiteSetting::get('whatsapp') }}</li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="footer-bottom text-center">
                <p class="mb-0">{{ \App\Models\SiteSetting::get('footer_text', '© ' . date('Y') . ' Explore Satkhira. All rights reserved.') }}</p>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Hide preloader when page is loaded
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            if (preloader) {
                preloader.classList.add('fade-out');
                setTimeout(function() {
                    preloader.style.display = 'none';
                }, 500);
            }
        });
        
        // Fallback: hide preloader after 3 seconds even if load event doesn't fire
        setTimeout(function() {
            const preloader = document.getElementById('preloader');
            if (preloader && !preloader.classList.contains('fade-out')) {
                preloader.classList.add('fade-out');
                setTimeout(function() {
                    preloader.style.display = 'none';
                }, 500);
            }
        }, 1500);
        
        AOS.init({
            duration: 400,
            once: true,
            offset: 50
        });
        
        // Auto dismiss alerts
        setTimeout(function() {
            document.querySelectorAll('.alert-floating').forEach(function(alert) {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 150);
            });
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>
