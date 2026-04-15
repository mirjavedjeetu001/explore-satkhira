<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="MpDysAtFM8ui1Ken2ZbOg_JFGLQQVu_hCE2VQPT6XDk">
    
    @php
        $localeSiteName = app()->getLocale() === 'bn' 
            ? ($siteSettings['site_name_bn'] ?? __('messages.site_name'))
            : ($siteSettings['site_name'] ?? __('messages.site_name'));
        
        $defaultDescription = app()->getLocale() === 'bn' 
            ? 'সাতক্ষীরা জেলার সকল তথ্য এক জায়গায়। হোম টিউটর, টু-লেট, রেস্টুরেন্ট, হাসপাতাল, স্কুল, কলেজ, ডাক্তার, ফার্মেসি, ব্যাংক, সরকারি অফিস, পর্যটন স্পট এবং আরও অনেক কিছু খুঁজুন সাতক্ষীরা সদর, কালীগঞ্জ, শ্যামনগর, আশাশুনি, দেবহাটা, কলারোয়া, তালা উপজেলায়।'
            : 'Explore Satkhira - Complete district directory of Satkhira, Bangladesh. Find home tutors, rentals, restaurants, hospitals, schools, colleges, doctors, pharmacies, banks, government offices, tourist spots in Satkhira Sadar, Kaliganj, Shyamnagar, Assasuni, Debhata, Kalaroa, Tala.';
        
        $defaultKeywords = app()->getLocale() === 'bn'
            ? 'সাতক্ষীরা, এক্সপ্লোর সাতক্ষীরা, সাতক্ষীরা জেলা, হোম টিউটর সাতক্ষীরা, টু-লেট সাতক্ষীরা, রেস্টুরেন্ট সাতক্ষীরা, হাসপাতাল সাতক্ষীরা, স্কুল সাতক্ষীরা, কলেজ সাতক্ষীরা, ডাক্তার সাতক্ষীরা, ফার্মেসি সাতক্ষীরা, ব্যাংক সাতক্ষীরা, সরকারি অফিস সাতক্ষীরা, পর্যটন স্পট সাতক্ষীরা, শপিং সেন্টার সাতক্ষীরা, সাতক্ষীরা সদর, কালীগঞ্জ, শ্যামনগর, আশাশুনি, দেবহাটা, কলারোয়া, তালা'
            : 'Satkhira, Explore Satkhira, Satkhira District, Home Tutor Satkhira, To-Let Satkhira, Restaurant Satkhira, Hospital Satkhira, School Satkhira, College Satkhira, Doctor Satkhira, Pharmacy Satkhira, Bank Satkhira, Government Office Satkhira, Tourist Spots Satkhira, Shopping Center Satkhira, Satkhira Sadar, Kaliganj, Shyamnagar, Assasuni, Debhata, Kalaroa, Tala, Bangladesh';
    @endphp
    
    <title>@hasSection('title')@yield('title') - {{ $localeSiteName }}@else{{ $localeSiteName }}@endif</title>
    
    <meta name="description" content="@yield('meta_description', $defaultDescription)">
    <meta name="keywords" content="@yield('meta_keywords', $defaultKeywords)">
    <meta name="author" content="Explore Satkhira">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">
    <meta name="geo.region" content="BD-E">
    <meta name="geo.placename" content="Satkhira, Bangladesh">
    <link rel="canonical" href="{{ url()->current() }}">
    
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@hasSection('title')@yield('title') - {{ $localeSiteName }}@else{{ $localeSiteName }}@endif">
    <meta property="og:description" content="@yield('meta_description', $defaultDescription)">
    <meta property="og:image" content="@yield('og_image', asset('images/og-image.png'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="{{ $localeSiteName }}">
    <meta property="og:locale" content="{{ app()->getLocale() === 'bn' ? 'bn_BD' : 'en_US' }}">
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@hasSection('title')@yield('title') - {{ $localeSiteName }}@else{{ $localeSiteName }}@endif">
    <meta name="twitter:description" content="@yield('meta_description', $defaultDescription)">
    <meta name="twitter:image" content="@yield('og_image', asset('images/og-image.png'))">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="512x512" href="{{ asset('icons/icon-512x512.png') }}">
    
    <!-- PWA -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#28a745">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Explore Satkhira">
    <meta name="mobile-web-app-capable" content="yes">
    
    @yield('structured_data')
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- AOS Animation - Disabled for performance -->
    <!-- <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet"> -->
    
    <style>
        /* Disable AOS animations - show everything immediately */
        [data-aos] {
            opacity: 1 !important;
            transform: none !important;
            transition: none !important;
        }
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
            z-index: 1050;
            position: relative;
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
        
        /* Mega Dropdown Menu for Categories */
        .dropdown-mega {
            position: relative;
        }
        
        .dropdown-mega .dropdown-menu-mega {
            min-width: 400px;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            left: 50% !important;
            transform: translateX(-50%);
        }
        
        .dropdown-mega .dropdown-menu-mega .category-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 5px;
        }
        
        .dropdown-mega .dropdown-menu-mega .dropdown-item {
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .dropdown-mega .dropdown-menu-mega .dropdown-item:hover {
            background-color: rgba(40, 167, 69, 0.1);
        }
        
        @media (max-width: 768px) {
            .dropdown-mega .dropdown-menu-mega {
                min-width: 100%;
                left: 0 !important;
                transform: none;
                position: absolute;
            }
            
            .dropdown-mega .dropdown-menu-mega .category-grid {
                grid-template-columns: repeat(2, 1fr);
            }
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
        
        /* New MP Cards Grid */
        .mp-card-new {
            background: #fff;
            border-radius: 20px;
            padding: 30px 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        
        .mp-card-new:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        .mp-image-wrapper {
            position: relative;
            display: inline-block;
        }
        
        .mp-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #28a745;
            box-shadow: 0 5px 20px rgba(40, 167, 69, 0.3);
        }
        
        .mp-card-new .badge {
            font-weight: 500;
            padding: 6px 15px;
            border-radius: 20px;
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
                padding: 20px;
            }
            
            .section-header h2 {
                font-size: 1.5rem;
            }
            
            .category-card, .upazila-card, .listing-card {
                margin-bottom: 15px;
            }
            
            .navbar-custom .navbar-brand {
                font-size: 1.2rem;
            }
            
            .footer h5 {
                font-size: 1.1rem;
            }
            
            .btn-lg {
                padding: 10px 20px;
                font-size: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            .hero-slider .carousel-item {
                height: 280px;
            }
            
            .hero-slider .carousel-caption {
                padding: 10px;
            }
            
            .hero-slider .carousel-caption h2 {
                font-size: 1.3rem;
                margin-bottom: 8px;
            }
            
            .hero-slider .carousel-caption p {
                font-size: 0.85rem;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            
            .search-box {
                padding: 15px;
                margin-top: 15px;
            }
            
            .search-box .form-select-lg,
            .search-box .form-control-lg {
                font-size: 0.9rem;
                padding: 10px 12px;
            }
            
            .search-box .btn-lg {
                padding: 10px 15px;
            }
            
            .section-header {
                margin-bottom: 20px;
            }
            
            .section-header h2 {
                font-size: 1.3rem;
            }
            
            .section-header p {
                font-size: 0.85rem;
            }
            
            .category-card {
                padding: 15px;
            }
            
            .category-card h5 {
                font-size: 0.9rem;
            }
            
            .category-card .icon-wrapper {
                width: 50px;
                height: 50px;
            }
            
            .category-card .icon-wrapper i {
                font-size: 1.2rem;
            }
            
            .listing-card .card-title {
                font-size: 0.95rem;
            }
            
            .listing-card .card-body {
                padding: 12px;
            }
            
            .container {
                padding-left: 12px;
                padding-right: 12px;
            }
            
            .py-5 {
                padding-top: 2rem !important;
                padding-bottom: 2rem !important;
            }
            
            .navbar-custom .navbar-brand {
                font-size: 1.1rem;
            }
            
            .navbar-toggler {
                padding: 4px 8px;
            }
            
            .footer {
                padding: 30px 0 15px;
            }
            
            .footer h5 {
                font-size: 1rem;
                margin-bottom: 15px;
            }
            
            .footer p, .footer a {
                font-size: 0.85rem;
            }
            
            .btn {
                font-size: 0.9rem;
            }
            
            .btn-lg {
                padding: 8px 16px;
                font-size: 0.95rem;
            }
            
            .mp-card-new {
                padding: 15px;
            }
            
            .mp-avatar {
                width: 80px;
                height: 80px;
            }
            
            .mp-card-new h5 {
                font-size: 1rem;
            }
        }
        
        @media (max-width: 380px) {
            .hero-slider .carousel-item {
                height: 240px;
            }
            
            .hero-slider .carousel-caption h2 {
                font-size: 1.1rem;
            }
            
            .hero-slider .carousel-caption p {
                font-size: 0.8rem;
            }
            
            .hero-slider .carousel-caption .btn {
                padding: 6px 12px;
                font-size: 0.85rem;
            }
            
            .section-header h2 {
                font-size: 1.1rem;
            }
            
            .navbar-custom .navbar-brand {
                font-size: 1rem;
            }
            
            .category-card {
                padding: 12px;
            }
            
            .category-card h5 {
                font-size: 0.85rem;
            }
            
            .container {
                padding-left: 10px;
                padding-right: 10px;
            }
        }
        
        /* Preloader - Force full screen on all devices */
        html.preloader-active,
        body.preloader-active {
            overflow: hidden !important;
            height: 100% !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100vw !important;
            height: 100vh !important;
            height: 100dvh !important; /* Dynamic viewport height for mobile */
            min-height: 100% !important;
            background: linear-gradient(135deg, #1a3c34 0%, #28a745 100%);
            z-index: 999999 !important;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            transition: opacity 0.5s ease, visibility 0.5s ease;
            overflow: hidden !important;
            -webkit-overflow-scrolling: touch;
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
    <script>
    // Skip preloader in standalone/TWA mode to avoid double loading
    var isTWA = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone;
    if (!isTWA) {
        document.documentElement.classList.add('preloader-active');
    }
    </script>
</head>
<body>
    <script>if (!isTWA) document.body.classList.add('preloader-active');</script>
    <!-- Preloader (hidden in TWA/standalone mode) -->
    <div class="preloader" id="preloader" style="display:none;">
    <script>if (!isTWA) document.getElementById('preloader').style.display='';</script>
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
                    <li class="nav-item dropdown dropdown-mega">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="fas fa-th-large me-1"></i> {{ __('messages.categories') }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-mega">
                            <div class="category-grid">
                                @foreach(\App\Models\Category::active()->parentCategories()->inMenu()->ordered()->get() as $category)
                                    <a class="dropdown-item" href="{{ route('categories.show', $category) }}">
                                        <i class="{{ $category->icon ?? 'fas fa-folder' }} me-2" style="color: {{ $category->color }}"></i>
                                        {{ app()->getLocale() == 'bn' ? ($category->name_bn ?? $category->name) : $category->name }}
                                    </a>
                                @endforeach
                            </div>
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
                document.body.classList.remove('preloader-active');
                document.documentElement.classList.remove('preloader-active');
                setTimeout(function() {
                    preloader.style.display = 'none';
                }, 500);
            }
        });
        
        // Fallback: hide preloader after 1.5 seconds even if load event doesn't fire
        setTimeout(function() {
            const preloader = document.getElementById('preloader');
            if (preloader && !preloader.classList.contains('fade-out')) {
                preloader.classList.add('fade-out');
                document.body.classList.remove('preloader-active');
                document.documentElement.classList.remove('preloader-active');
                setTimeout(function() {
                    preloader.style.display = 'none';
                }, 500);
            }
        }, 1500);
        
        // AOS animations disabled for better mobile performance
        // AOS.init({
        //     duration: 400,
        //     once: true,
        //     offset: 50
        // });
        
        // Auto dismiss alerts
        setTimeout(function() {
            document.querySelectorAll('.alert-floating').forEach(function(alert) {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 150);
            });
        }, 5000);
    </script>
    
    @stack('scripts')
    
    <!-- Floating Salami Calculator Button (Eid Feature) -->
    @php
        $salamiEnabled = \App\Models\SalamiSetting::first()?->is_enabled ?? false;
    @endphp
    @if($salamiEnabled && !request()->routeIs('salami.*'))
    <a href="{{ route('salami.index') }}" class="salami-float-btn" title="ঈদ সালামি ক্যালকুলেটর">
        <span class="salami-icon">🌙</span>
        <span class="salami-text">সালামি হিসাব</span>
    </a>
    <style>
        .salami-float-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, #28a745 0%, #1a5f2a 100%);
            color: white;
            padding: 15px 25px;
            border-radius: 50px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 5px 25px rgba(40, 167, 69, 0.4);
            z-index: 1000;
            transition: all 0.3s ease;
            animation: salamiPulse 2s infinite;
        }
        .salami-float-btn:hover {
            transform: scale(1.05) translateY(-3px);
            box-shadow: 0 8px 30px rgba(40, 167, 69, 0.5);
            color: white;
        }
        .salami-icon {
            font-size: 1.5rem;
            animation: moonGlow 3s ease-in-out infinite;
        }
        .salami-text {
            font-weight: 600;
            font-size: 0.95rem;
        }
        @keyframes salamiPulse {
            0%, 100% { box-shadow: 0 5px 25px rgba(40, 167, 69, 0.4); }
            50% { box-shadow: 0 5px 35px rgba(40, 167, 69, 0.6); }
        }
        @keyframes moonGlow {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(10deg); }
        }
        @media (max-width: 576px) {
            .salami-float-btn {
                bottom: 20px;
                right: 20px;
                padding: 12px 18px;
            }
            .salami-text {
                font-size: 0.85rem;
            }
        }
    </style>
    @endif

    <!-- Floating Survey Button -->
    @php
        $activeSurvey = \App\Models\Survey::getActiveSurvey();
    @endphp
    @if($activeSurvey && !request()->routeIs('survey.*'))
    <a href="{{ route('survey.show', $activeSurvey->id) }}" class="survey-float-btn" title="{{ $activeSurvey->title }}">
        <span class="survey-float-icon">📊</span>
        <span class="survey-float-text">সার্ভে</span>
        @if($activeSurvey->is_live)
            <span class="survey-live-dot"></span>
        @endif
    </a>
    <style>
        .survey-float-btn {
            position: fixed;
            @php
                $surveyBaseBottom = ($salamiEnabled ?? false) ? 170 : (($eidCardEnabled ?? false) ? 100 : 30);
                $surveyBottom = ($fuelEnabled ?? false) ? ($surveyBaseBottom + 70) : $surveyBaseBottom;
                $surveyBottom = (\App\Models\BloodSetting::isEnabled()) ? ($surveyBottom + 70) : $surveyBottom;
            @endphp
            bottom: {{ $surveyBottom }}px;
            right: 30px;
            background: linear-gradient(135deg, #1a237e 0%, #3949ab 100%);
            color: white;
            padding: 15px 25px;
            border-radius: 50px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 5px 25px rgba(26, 35, 126, 0.4);
            z-index: 1000;
            transition: all 0.3s ease;
            animation: surveyPulse 2s infinite;
            position: relative;
        }
        .survey-float-btn:hover {
            transform: scale(1.05) translateY(-3px);
            box-shadow: 0 8px 30px rgba(26, 35, 126, 0.5);
            color: white;
        }
        .survey-float-icon { font-size: 1.5rem; }
        .survey-float-text { font-weight: 600; font-size: 0.95rem; }
        .survey-live-dot {
            width: 10px; height: 10px;
            background: #e53935;
            border-radius: 50%;
            position: absolute;
            top: 8px; right: 8px;
            animation: dotBlink 1s infinite;
        }
        @keyframes surveyPulse {
            0%, 100% { box-shadow: 0 5px 25px rgba(26, 35, 126, 0.4); }
            50% { box-shadow: 0 5px 35px rgba(26, 35, 126, 0.6); }
        }
        @keyframes dotBlink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        @media (max-width: 576px) {
            .survey-float-btn {
                @php
                    $surveyBaseBottomMobile = ($salamiEnabled ?? false) ? 160 : (($eidCardEnabled ?? false) ? 90 : 20);
                    $surveyBottomMobile = ($fuelEnabled ?? false) ? ($surveyBaseBottomMobile + 60) : $surveyBaseBottomMobile;
                    $surveyBottomMobile = (\App\Models\BloodSetting::isEnabled()) ? ($surveyBottomMobile + 60) : $surveyBottomMobile;
                @endphp
                bottom: {{ $surveyBottomMobile }}px;
                right: 20px;
                padding: 12px 18px;
            }
            .survey-float-text { font-size: 0.85rem; }
        }
    </style>
    @endif

    <!-- Floating Fuel Tracker Button -->
    @php
        $fuelEnabled = \App\Models\FuelSetting::isEnabled();
    @endphp
    @if($fuelEnabled && !request()->routeIs('fuel.*'))
    <a href="{{ route('fuel.index') }}" class="fuel-float-btn" title="জ্বালানি তেল আপডেট">
        <span class="fuel-icon">⛽</span>
        <span class="fuel-text">তেল আপডেট</span>
    </a>
    <style>
        .fuel-float-btn {
            position: fixed;
            bottom: {{ ($salamiEnabled ?? false) ? '170px' : (($eidCardEnabled ?? false) ? '100px' : '30px') }};
            right: 30px;
            background: linear-gradient(135deg, #e65100 0%, #ff6d00 100%);
            color: white;
            padding: 15px 25px;
            border-radius: 50px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 5px 25px rgba(230, 81, 0, 0.4);
            z-index: 1000;
            transition: all 0.3s ease;
            animation: fuelPulse 2s infinite;
        }
        .fuel-float-btn:hover {
            transform: scale(1.05) translateY(-3px);
            box-shadow: 0 8px 30px rgba(230, 81, 0, 0.5);
            color: white;
        }
        .fuel-icon {
            font-size: 1.5rem;
        }
        .fuel-text {
            font-weight: 600;
            font-size: 0.95rem;
        }
        @keyframes fuelPulse {
            0%, 100% { box-shadow: 0 5px 25px rgba(230, 81, 0, 0.4); }
            50% { box-shadow: 0 5px 35px rgba(230, 81, 0, 0.6); }
        }
        @media (max-width: 576px) {
            .fuel-float-btn {
                bottom: {{ ($salamiEnabled ?? false) ? '160px' : (($eidCardEnabled ?? false) ? '90px' : '20px') }};
                right: 20px;
                padding: 12px 18px;
            }
            .fuel-text {
                font-size: 0.85rem;
            }
        }
    </style>
    @endif

    <!-- Floating Explore Blood Button -->
    @php
        $bloodEnabled = \App\Models\BloodSetting::isEnabled();
    @endphp
    @if($bloodEnabled && !request()->routeIs('blood.*'))
    <a href="{{ route('blood.index') }}" class="blood-float-btn" title="এক্সপ্লোর রক্তদাতা">
        <span class="blood-float-icon">🩸</span>
        <span class="blood-float-text">এক্সপ্লোর রক্তদাতা</span>
    </a>
    <style>
        .blood-float-btn {
            position: fixed;
            @php
                // Stack above fuel button (fuel is ~60px tall, so add 70px gap)
                $fuelBottom = ($salamiEnabled ?? false) ? 170 : (($eidCardEnabled ?? false) ? 100 : 30);
                $bloodBottom = ($fuelEnabled ?? false) ? ($fuelBottom + 70) : $fuelBottom;
            @endphp
            bottom: {{ $bloodBottom }}px;
            right: 30px;
            background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%);
            color: white;
            padding: 15px 25px;
            border-radius: 50px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 5px 25px rgba(220, 53, 69, 0.4);
            z-index: 1000;
            transition: all 0.3s ease;
            animation: bloodPulse 2s infinite;
        }
        .blood-float-btn:hover {
            transform: scale(1.05) translateY(-3px);
            box-shadow: 0 8px 30px rgba(220, 53, 69, 0.5);
            color: white;
        }
        .blood-float-icon {
            font-size: 1.5rem;
        }
        .blood-float-text {
            font-weight: 600;
            font-size: 0.95rem;
        }
        @keyframes bloodPulse {
            0%, 100% { box-shadow: 0 5px 25px rgba(220, 53, 69, 0.4); }
            50% { box-shadow: 0 5px 35px rgba(220, 53, 69, 0.6); }
        }
        @media (max-width: 576px) {
            .blood-float-btn {
                @php
                    $fuelBottomMobile = ($salamiEnabled ?? false) ? 160 : (($eidCardEnabled ?? false) ? 90 : 20);
                    $bloodBottomMobile = ($fuelEnabled ?? false) ? ($fuelBottomMobile + 60) : $fuelBottomMobile;
                @endphp
                bottom: {{ $bloodBottomMobile }}px;
                right: 20px;
                padding: 12px 18px;
            }
            .blood-float-text {
                font-size: 0.85rem;
            }
        }
    </style>
    @endif

    <!-- Floating Eid Card Maker Button -->
    @php
        $eidCardEnabled = \App\Models\EidCardSetting::first()?->is_enabled ?? false;
    @endphp
    @if($eidCardEnabled && !request()->routeIs('eid-card.*'))
    <a href="{{ route('eid-card.index') }}" class="eid-card-float-btn" title="ঈদ গ্রিটিং কার্ড মেকার">
        <span class="eid-card-icon">✨</span>
        <span class="eid-card-text">ঈদ কার্ড</span>
    </a>
    <style>
        .eid-card-float-btn {
            position: fixed;
            bottom: {{ ($salamiEnabled ?? false) ? '100px' : ($fuelEnabled ? '100px' : '30px') }};
            right: 30px;
            background: linear-gradient(135deg, #1a237e 0%, #3949ab 100%);
            color: white;
            padding: 15px 25px;
            border-radius: 50px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 5px 25px rgba(26, 35, 126, 0.4);
            z-index: 1000;
            transition: all 0.3s ease;
            animation: eidCardPulse 2s infinite;
        }
        .eid-card-float-btn:hover {
            transform: scale(1.05) translateY(-3px);
            box-shadow: 0 8px 30px rgba(26, 35, 126, 0.5);
            color: white;
        }
        .eid-card-icon {
            font-size: 1.5rem;
            animation: sparkle 2s ease-in-out infinite;
        }
        .eid-card-text {
            font-weight: 600;
            font-size: 0.95rem;
        }
        @keyframes eidCardPulse {
            0%, 100% { box-shadow: 0 5px 25px rgba(26, 35, 126, 0.4); }
            50% { box-shadow: 0 5px 35px rgba(26, 35, 126, 0.6); }
        }
        @keyframes sparkle {
            0%, 100% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.1) rotate(15deg); }
        }
        @media (max-width: 576px) {
            .eid-card-float-btn {
                bottom: {{ $salamiEnabled ? '90px' : '20px' }};
                right: 20px;
                padding: 12px 18px;
            }
            .eid-card-text {
                font-size: 0.85rem;
            }
        }
    </style>
    @endif

    <!-- Service Worker + Push Notification + App Install -->
    <script>
        const VAPID_PUBLIC_KEY = '{{ config("services.vapid.public_key") }}';
        let swRegistration = null;
        
        // Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', async function() {
                try {
                    swRegistration = await navigator.serviceWorker.register('/sw.js');
                    
                    if ('PushManager' in window && 'Notification' in window && VAPID_PUBLIC_KEY) {
                        if (Notification.permission === 'granted') {
                            // Already granted - subscribe silently
                            subscribeToPush(swRegistration);
                        } else if (Notification.permission === 'default') {
                            // Auto-ask on first user interaction (tap/click/scroll)
                            const askOnce = async () => {
                                document.removeEventListener('click', askOnce, true);
                                document.removeEventListener('touchstart', askOnce, true);
                                document.removeEventListener('scroll', askOnce, true);
                                try {
                                    const perm = await Notification.requestPermission();
                                    if (perm === 'granted' && swRegistration) {
                                        subscribeToPush(swRegistration);
                                    }
                                } catch(e) {}
                            };
                            document.addEventListener('click', askOnce, true);
                            document.addEventListener('touchstart', askOnce, true);
                            document.addEventListener('scroll', askOnce, { once: true });
                        }
                    }
                } catch(e) {}
            });
        }
        
        async function subscribeToPush(reg) {
            try {
                if (!('Notification' in window) || Notification.permission !== 'granted') return;
                
                let sub = await reg.pushManager.getSubscription();
                if (sub) return; // Already subscribed
                
                const key = urlBase64ToUint8Array(VAPID_PUBLIC_KEY);
                sub = await reg.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: key
                });
                
                const ua = navigator.userAgent;
                let deviceType = 'desktop';
                if (/android/i.test(ua)) deviceType = 'android';
                else if (/iphone|ipad/i.test(ua)) deviceType = 'ios';
                else if (/mobile/i.test(ua)) deviceType = 'mobile';
                
                let browser = 'unknown';
                if (/chrome/i.test(ua) && !/edg/i.test(ua)) browser = 'Chrome';
                else if (/firefox/i.test(ua)) browser = 'Firefox';
                else if (/safari/i.test(ua) && !/chrome/i.test(ua)) browser = 'Safari';
                else if (/edg/i.test(ua)) browser = 'Edge';
                
                await fetch('/push/subscribe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        endpoint: sub.endpoint,
                        keys: {
                            p256dh: btoa(String.fromCharCode(...new Uint8Array(sub.getKey('p256dh')))),
                            auth: btoa(String.fromCharCode(...new Uint8Array(sub.getKey('auth'))))
                        },
                        device_type: deviceType,
                        browser: browser
                    })
                });
            } catch(e) {}
        }
        
        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
            const rawData = atob(base64);
            return Uint8Array.from([...rawData].map(c => c.charCodeAt(0)));
        }
        
        // Fuel Pump Subscription Toggle
        async function getPushEndpoint() {
            if (!('serviceWorker' in navigator)) return null;
            try {
                const reg = await navigator.serviceWorker.ready;
                const sub = await reg.pushManager.getSubscription();
                return sub ? sub.endpoint : null;
            } catch(e) { return null; }
        }

        function getFuelSubsLocal() {
            try { return JSON.parse(localStorage.getItem('fuelSubs') || '[]'); } catch(e) { return []; }
        }
        function saveFuelSubsLocal(arr) {
            localStorage.setItem('fuelSubs', JSON.stringify(arr));
        }
        function markBtnSubscribed(btn) {
            btn.classList.add('subscribed');
            btn.querySelector('i').className = 'fas fa-bell';
            btn.querySelector('span').textContent = '🔕 নোটিফিকেশন বন্ধ করুন';
        }
        function markBtnUnsubscribed(btn) {
            btn.classList.remove('subscribed');
            btn.querySelector('i').className = 'far fa-bell';
            btn.querySelector('span').textContent = '🔔 আপডেট নোটিফিকেশন পান';
        }

        function applyFuelSubsFromLocal() {
            const subs = getFuelSubsLocal();
            document.querySelectorAll('.fuel-subscribe-btn').forEach(btn => {
                const stationId = parseInt(btn.dataset.stationId);
                if (subs.includes(stationId)) {
                    markBtnSubscribed(btn);
                } else {
                    markBtnUnsubscribed(btn);
                }
            });
        }

        async function syncFuelSubsFromServer() {
            try {
                const endpoint = await getPushEndpoint();
                if (!endpoint) return;
                const res = await fetch('/fuel/subscriptions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ endpoint: endpoint })
                });
                const data = await res.json();
                const serverSubs = data.station_ids || [];
                saveFuelSubsLocal(serverSubs);
                applyFuelSubsFromLocal();
            } catch(e) {}
        }

        async function toggleFuelSubscription(btn) {
            const stationId = parseInt(btn.dataset.stationId);
            const isSubscribed = btn.classList.contains('subscribed');
            
            // Check notification permission first
            if (!isSubscribed) {
                if (!('Notification' in window) || !('PushManager' in window)) {
                    alert('আপনার ব্রাউজার নোটিফিকেশন সাপোর্ট করে না।');
                    return;
                }
                if (Notification.permission === 'denied') {
                    alert('নোটিফিকেশন ব্লক করা আছে। ব্রাউজার সেটিংস থেকে অনুমতি দিন।');
                    return;
                }
                if (Notification.permission === 'default') {
                    const perm = await Notification.requestPermission();
                    if (perm !== 'granted') return;
                    if (swRegistration) await subscribeToPush(swRegistration);
                    await new Promise(r => setTimeout(r, 1500));
                }
            }

            const endpoint = await getPushEndpoint();
            if (!endpoint) {
                alert('পুশ নোটিফিকেশন সাবস্ক্রাইব করা হয়নি। পেজ রিলোড করে আবার চেষ্টা করুন।');
                return;
            }

            const url = isSubscribed ? '/fuel/unsubscribe' : '/fuel/subscribe';
            btn.disabled = true;

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ endpoint: endpoint, fuel_station_id: stationId })
                });
                const data = await res.json();
                if (data.success) {
                    let subs = getFuelSubsLocal();
                    if (isSubscribed) {
                        subs = subs.filter(id => id !== stationId);
                        markBtnUnsubscribed(btn);
                    } else {
                        if (!subs.includes(stationId)) subs.push(stationId);
                        markBtnSubscribed(btn);
                    }
                    saveFuelSubsLocal(subs);
                }
            } catch(e) {
                alert('সমস্যা হয়েছে, আবার চেষ্টা করুন।');
            }
            btn.disabled = false;
        }

        // On page load: instantly apply from localStorage, then sync from server
        if (document.querySelector('.fuel-subscribe-btn')) {
            applyFuelSubsFromLocal();
            window.addEventListener('load', function() {
                setTimeout(syncFuelSubsFromServer, 3000);
            });
        }

        // App Install Banner

        // TWA/APK In-App Update Checker
        (function() {
            var isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone;
            if (!isStandalone) return;
            var currentVersion = localStorage.getItem('appVersion') || '1.0.0';
            var dismissedVersion = localStorage.getItem('appUpdateDismissed');
            fetch('/api/app-version')
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (!data.version || data.version <= currentVersion) return;
                    if (data.version === dismissedVersion) return;
                    // Styles
                    var s = document.createElement('style');
                    s.textContent = '@keyframes slideDown{from{transform:translateY(-100%)}to{transform:translateY(0)}}@keyframes fadeOutUp{to{opacity:0;transform:translateY(-100%)}}';
                    document.head.appendChild(s);
                    // Banner
                    var banner = document.createElement('div');
                    banner.id = 'app-update-banner';
                    banner.innerHTML = '<div style="display:flex;align-items:center;gap:10px;max-width:600px;margin:0 auto;">' +
                        '<div style="flex:0 0 40px;width:40px;height:40px;background:linear-gradient(135deg,#28a745,#20c997);border-radius:50%;display:flex;align-items:center;justify-content:center;">' +
                        '<i class="fas fa-arrow-up" style="color:#fff;font-size:16px;"></i></div>' +
                        '<div style="flex:1;line-height:1.3;">' +
                        '<strong style="display:block;font-size:13px;color:#1a3c34;">নতুন আপডেট! v' + data.version + '</strong>' +
                        '<small style="color:#6c757d;font-size:11px;">' + (data.releaseNotes || 'নতুন ফিচার ও বাগ ফিক্স') + '</small></div>' +
                        '<a id="appUpdateBtn" href="' + data.url + '" style="background:linear-gradient(135deg,#28a745,#20c997);color:#fff;border:none;padding:7px 16px;border-radius:20px;font-weight:600;font-size:12px;text-decoration:none;white-space:nowrap;">আপডেট</a>' +
                        '<button id="appUpdateClose" style="background:none;border:none;font-size:22px;color:#999;cursor:pointer;padding:0 4px;line-height:1;">&times;</button>' +
                        '</div>';
                    banner.style.cssText = 'position:fixed;top:0;left:0;right:0;background:#fff;box-shadow:0 2px 10px rgba(0,0,0,0.15);z-index:99999;padding:10px 14px;animation:slideDown 0.3s ease;';
                    document.body.appendChild(banner);
                    function closeBanner() {
                        banner.style.animation = 'fadeOutUp 0.3s ease forwards';
                        setTimeout(function() { banner.remove(); }, 300);
                    }
                    document.getElementById('appUpdateClose').addEventListener('click', function() {
                        localStorage.setItem('appUpdateDismissed', data.version);
                        closeBanner();
                    });
                    document.getElementById('appUpdateBtn').addEventListener('click', function() {
                        localStorage.setItem('appVersion', data.version);
                        localStorage.removeItem('appUpdateDismissed');
                        closeBanner();
                    });
                })
                .catch(function() {});
        })();
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            showInstallBanner();
        });
        
        function showInstallBanner() {
            if (document.querySelector('.app-install-banner')) return;
            if (localStorage.getItem('installBannerDismissed')) return;
            
            const banner = document.createElement('div');
            banner.className = 'app-install-banner';
            banner.innerHTML = `
                <div class="install-banner-content">
                    <img src="/icons/icon-96x96.png" alt="App Icon" class="install-banner-icon">
                    <div class="install-banner-text">
                        <strong>Explore Satkhira অ্যাপ</strong>
                        <small>দ্রুত অ্যাক্সেসের জন্য ইনস্টল করুন</small>
                    </div>
                    <button class="install-banner-btn" onclick="installApp()">ইনস্টল</button>
                    <button class="install-banner-close" onclick="dismissInstallBanner()">&times;</button>
                </div>
            `;
            document.body.appendChild(banner);
        }
        
        function installApp() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then(() => { deferredPrompt = null; });
            }
            dismissInstallBanner();
        }
        
        function dismissInstallBanner() {
            const banner = document.querySelector('.app-install-banner');
            if (banner) banner.remove();
            localStorage.setItem('installBannerDismissed', Date.now());
        }
    </script>
    
    <style>
        .app-install-banner {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #fff;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.15);
            z-index: 9999;
            padding: 12px 16px;
            animation: slideUp 0.3s ease;
        }
        @keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
        @keyframes slideUpBanner { from { transform: translateY(100%); } to { transform: translateY(0); } }
        .install-banner-content {
            display: flex;
            align-items: center;
            gap: 12px;
            max-width: 600px;
            margin: 0 auto;
        }
        .install-banner-icon { width: 48px; height: 48px; border-radius: 10px; }
        .install-banner-text { flex: 1; line-height: 1.3; }
        .install-banner-text strong { display: block; font-size: 14px; color: #1a3c34; }
        .install-banner-text small { color: #6c757d; font-size: 12px; }
        .install-banner-btn {
            background: #28a745; color: #fff; border: none; padding: 8px 20px;
            border-radius: 20px; font-weight: 600; font-size: 14px; cursor: pointer;
            white-space: nowrap;
        }
        .install-banner-close {
            background: none; border: none; font-size: 24px; color: #999;
            cursor: pointer; padding: 0 4px; line-height: 1;
        }
    </style>
    
    <!-- Source Code Protection -->
    <script>
    document.addEventListener('contextmenu', function(e) { e.preventDefault(); });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J' || e.key === 'C')) || (e.ctrlKey && e.key === 'u') || (e.ctrlKey && e.key === 'U')) {
            e.preventDefault();
        }
    });
    </script>
</body>
</html>
