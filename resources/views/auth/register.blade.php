<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ app()->getLocale() == 'bn' ? 'রেজিস্ট্রেশন - এক্সপ্লোর সাতক্ষীরা' : 'Register - Explore Satkhira' }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        * {
            font-family: 'Hind Siliguri', sans-serif;
        }
        
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0d5c2e 0%, #1a7f40 50%, #28a745 100%);
            position: relative;
            overflow-x: hidden;
            padding: 20px 0;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="none" stroke="rgba(255,255,255,0.03)" stroke-width="1"/></svg>') repeat;
            background-size: 100px 100px;
        }
        
        .register-container {
            max-width: 1100px;
            width: 100%;
            margin: 20px;
            display: flex;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 80px rgba(0,0,0,0.35);
            position: relative;
            z-index: 1;
        }
        
        .register-left {
            flex: 0 0 320px;
            background: linear-gradient(135deg, #0a4a24 0%, #0d5c2e 100%);
            padding: 40px 25px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .register-left::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 50%);
            animation: pulse 15s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }
        
        .register-left .brand {
            position: relative;
            z-index: 1;
        }
        
        .register-left .brand h1 {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .register-left .brand h1 i {
            color: #7bed9f;
        }
        
        .register-left .tagline {
            color: rgba(255,255,255,0.8);
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        
        .register-left .features {
            position: relative;
            z-index: 1;
        }
        
        .register-left .feature-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
            color: rgba(255,255,255,0.9);
        }
        
        .register-left .feature-item i {
            width: 32px;
            height: 32px;
            min-width: 32px;
            background: rgba(255,255,255,0.15);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-size: 0.85rem;
            color: #7bed9f;
        }
        
        .register-left .feature-item span {
            font-weight: 500;
            font-size: 0.85rem;
            line-height: 1.4;
        }
        
        .register-right {
            flex: 1;
            background: #fff;
            padding: 35px 40px;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .register-right::-webkit-scrollbar {
            width: 8px;
        }
        
        .register-right::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .register-right::-webkit-scrollbar-thumb {
            background: #28a745;
            border-radius: 4px;
        }
        
        .register-right::-webkit-scrollbar-thumb:hover {
            background: #1a5f2a;
        }
        
        .register-right h2 {
            color: #1a5f2a;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .register-right .subtitle {
            color: #6c757d;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }
        
        .section-title {
            color: #1a5f2a;
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 12px;
            padding-bottom: 6px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .section-title i {
            margin-right: 8px;
        }
        
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 4px rgba(40, 167, 69, 0.1);
        }
        
        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 5px;
            font-size: 0.85rem;
        }
        
        .form-label .required {
            color: #dc3545;
        }
        
        .form-row {
            display: flex;
            gap: 15px;
        }
        
        .form-row .col {
            flex: 1;
            min-width: 0;
        }
        
        .category-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .category-item {
            position: relative;
        }
        
        .category-item input[type="checkbox"] {
            position: absolute;
            opacity: 0;
        }
        
        .category-item label {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.85rem;
        }
        
        .category-item label i {
            margin-right: 8px;
            font-size: 1rem;
            color: #6c757d;
        }
        
        .category-item input[type="checkbox"]:checked + label {
            background: rgba(40, 167, 69, 0.1);
            border-color: #28a745;
            color: #1a5f2a;
        }
        
        .category-item input[type="checkbox"]:checked + label i {
            color: #28a745;
        }
        
        .category-item label:hover {
            border-color: #28a745;
        }
        
        .btn-register {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #1a7f40 0%, #28a745 100%);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-top: 10px;
        }
        
        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }
        
        .btn-register:hover::before {
            left: 100%;
        }
        
        .btn-register:hover {
            background: linear-gradient(135deg, #0d5c2e 0%, #1a7f40 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 15px 0;
            color: #adb5bd;
        }
        
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e9ecef;
        }
        
        .divider span {
            padding: 0 12px;
            font-size: 0.85rem;
        }
        
        .login-link {
            text-align: center;
            color: #6c757d;
        }
        
        .login-link a {
            color: #28a745;
            font-weight: 600;
            text-decoration: none;
        }
        
        .login-link a:hover {
            color: #1a5f2a;
            text-decoration: underline;
        }
        
        .alert {
            border-radius: 12px;
            margin-bottom: 20px;
        }
        
        .terms-text {
            font-size: 0.85rem;
            color: #6c757d;
            margin: 15px 0;
        }
        
        .terms-text a {
            color: #28a745;
            text-decoration: none;
        }
        
        .terms-text a:hover {
            text-decoration: underline;
        }
        
        .info-box {
            background: #e8f5e9;
            border-left: 4px solid #28a745;
            padding: 10px 14px;
            border-radius: 0 10px 10px 0;
            margin-bottom: 15px;
            font-size: 0.85rem;
            color: #2e7d32;
        }
        
        .info-box i {
            margin-right: 8px;
        }
        
        /* Avatar Upload Styles */
        .avatar-upload-wrapper {
            display: inline-block;
        }
        
        .avatar-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #f8f9fa;
            border: 3px dashed #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .avatar-preview:hover {
            border-color: #28a745;
        }
        
        .avatar-upload-btn {
            display: inline-block;
            padding: 6px 14px;
            background: linear-gradient(135deg, #1a7f40, #28a745);
            color: #fff;
            border-radius: 20px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .avatar-upload-btn:hover {
            background: linear-gradient(135deg, #0d5c2e, #1a7f40);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }
        
        .avatar-upload-btn i {
            margin-right: 5px;
        }
        
        @media (max-width: 900px) {
            body {
                overflow: auto;
                min-height: auto;
                padding: 15px 0;
            }
            
            .register-container {
                flex-direction: column;
                margin: 10px;
            }
            
            .register-left {
                flex: 0 0 auto;
                padding: 20px 18px;
            }
            
            .register-right {
                padding: 25px 20px;
                max-height: none;
            }
            
            .register-left .brand h1 {
                font-size: 1.3rem;
            }
            
            .register-left .tagline {
                font-size: 0.85rem;
                margin-bottom: 15px;
            }
            
            .register-left .feature-item {
                margin-bottom: 10px;
            }
            
            .register-left .feature-item span {
                font-size: 0.8rem;
            }
            
            .register-left .feature-item i {
                width: 30px;
                height: 30px;
                min-width: 30px;
                font-size: 0.8rem;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .category-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            }
            
            .register-right h2 {
                font-size: 1.4rem;
            }
        }
        
        @media (max-width: 576px) {
            body {
                padding: 10px 0;
            }
            
            .register-container {
                margin: 5px;
                border-radius: 16px;
            }
            
            .register-left {
                padding: 20px 15px;
            }
            
            .register-left .brand h1 {
                font-size: 1.2rem;
            }
            
            .register-left .tagline {
                font-size: 0.85rem;
            }
            
            .register-left .feature-item {
                font-size: 0.8rem;
                margin-bottom: 10px;
            }
            
            .register-left .feature-item i {
                width: 32px;
                height: 32px;
                font-size: 0.85rem;
                min-width: 32px;
            }
            
            .register-left .feature-item span {
                font-size: 0.8rem;
            }
            
            .register-right {
                padding: 20px 15px;
            }
            
            .register-right h2 {
                font-size: 1.3rem;
            }
            
            .register-right .subtitle {
                font-size: 0.85rem;
            }
            
            .info-box {
                font-size: 0.8rem;
                padding: 10px 12px;
            }
            
            .section-title {
                font-size: 0.9rem;
            }
            
            .form-control, .form-select {
                padding: 10px 12px;
                font-size: 0.9rem;
            }
            
            .form-label {
                font-size: 0.85rem;
            }
            
            .category-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
            }
            
            .category-item label {
                padding: 10px 12px;
                font-size: 0.8rem;
            }
            
            .category-item label i {
                font-size: 1rem;
                margin-right: 6px;
            }
            
            .avatar-preview {
                width: 100px;
                height: 100px;
            }
            
            .avatar-upload-btn {
                padding: 6px 12px;
                font-size: 0.8rem;
            }
            
            .btn-register {
                padding: 12px;
                font-size: 1rem;
            }
            
            .comment-only-option,
            .mp-question-option,
            .upazila-moderator-option,
            .own-business-option {
                padding: 12px 15px;
            }
            
            .comment-only-option label span,
            .mp-question-option label span,
            .upazila-moderator-option label span,
            .own-business-option label span {
                font-size: 14px;
            }
            
            .comment-only-option label i,
            .mp-question-option label i,
            .upazila-moderator-option label i,
            .own-business-option label i {
                font-size: 20px;
                margin-right: 10px;
            }
            
            .terms-text {
                font-size: 0.8rem;
            }
        }
        
        @media (max-width: 380px) {
            .register-container {
                margin: 3px;
            }
            
            .register-left, .register-right {
                padding: 15px 12px;
            }
            
            .register-left .brand h1 {
                font-size: 1.1rem;
            }
            
            .category-grid {
                grid-template-columns: 1fr 1fr;
            }
            
            .category-item label {
                padding: 8px 10px;
                font-size: 0.75rem;
            }
            
            .form-control, .form-select {
                padding: 8px 10px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-left">
            <div class="brand">
                <h1><i class="fas fa-leaf"></i> {{ app()->getLocale() == 'bn' ? 'এক্সপ্লোর সাতক্ষীরা' : 'Explore Satkhira' }}</h1>
                <p class="tagline">{{ app()->getLocale() == 'bn' ? 'সাতক্ষীরা জেলার সকল তথ্য এক জায়গায়' : 'All information of Satkhira district in one place' }}</p>
            </div>
            
            <div class="features">
                <h6 style="color: #7bed9f; margin-bottom: 15px; font-weight: 600;">
                    <i class="fas fa-clipboard-list me-2"></i>{{ app()->getLocale() == 'bn' ? 'রেজিস্ট্রেশনের নিয়মাবলী' : 'Registration Rules' }}
                </h6>
                
                <div class="feature-item">
                    <i class="fas fa-camera"></i>
                    <span>{{ app()->getLocale() == 'bn' ? 'প্রোফাইল ছবি আবশ্যক (JPG/PNG, সর্বোচ্চ 2MB)' : 'Profile picture required (JPG/PNG, max 2MB)' }}</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ app()->getLocale() == 'bn' ? 'উপজেলা ও পূর্ণ ঠিকানা দিতে হবে' : 'Upazila & full address required' }}</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-user-check"></i>
                    <span>{{ app()->getLocale() == 'bn' ? 'অ্যাডমিন অনুমোদন করলে লগইন করতে পারবেন' : 'Login after admin approval' }}</span>
                </div>
                
                <hr style="border-color: rgba(255,255,255,0.2); margin: 15px 0;">
                
                <h6 style="color: #7bed9f; margin-bottom: 15px; font-weight: 600;">
                    <i class="fas fa-th-large me-2"></i>{{ app()->getLocale() == 'bn' ? 'অপশন বুঝুন' : 'Understand Options' }}
                </h6>
                
                <div class="feature-item">
                    <i class="fas fa-comment-dots" style="color: #38ef7d;"></i>
                    <span>{{ app()->getLocale() == 'bn' ? 'শুধু মন্তব্য - তথ্যে মন্তব্য করতে পারবেন' : 'Comment only - can comment on listings' }}</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-comments" style="color: #764ba2;"></i>
                    <span>{{ app()->getLocale() == 'bn' ? 'সাংসদ প্রশ্ন - MP কে প্রশ্ন করতে পারবেন' : 'MP Question - can ask MP' }}</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-user-shield" style="color: #f5576c;"></i>
                    <span>{{ app()->getLocale() == 'bn' ? 'উপজেলা মডারেটর - সব ক্যাটাগরিতে তথ্য দিতে পারবেন' : 'Upazila Mod - all categories' }}</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-store" style="color: #fee140;"></i>
                    <span>{{ app()->getLocale() == 'bn' ? 'ব্যবসা মডারেটর - শুধু ১টি ক্যাটাগরি' : 'Business Mod - only 1 category' }}</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-th-list" style="color: #28a745;"></i>
                    <span>{{ app()->getLocale() == 'bn' ? 'সাধারণ - নির্বাচিত ক্যাটাগরিতে তথ্য দিন' : 'Normal - add in selected categories' }}</span>
                </div>
                
                <hr style="border-color: rgba(255,255,255,0.2); margin: 15px 0;">
                
                <div class="feature-item" style="background: rgba(255,255,255,0.1); padding: 10px; border-radius: 8px;">
                    <i class="fas fa-lightbulb" style="color: #ffc107;"></i>
                    <span style="font-size: 0.85rem;">{{ app()->getLocale() == 'bn' ? 'টিপস: মডারেটর না হলে শুধু ক্যাটাগরি সিলেক্ট করুন' : 'Tip: If not moderator, just select categories' }}</span>
                </div>
            </div>
        </div>
        
        <div class="register-right">
            <h2>{{ app()->getLocale() == 'bn' ? 'অ্যাকাউন্ট তৈরি করুন' : 'Create Account' }}</h2>
            <p class="subtitle">{{ app()->getLocale() == 'bn' ? 'নিচের তথ্য দিয়ে রেজিস্ট্রেশন সম্পন্ন করুন' : 'Fill in the details below to register' }}</p>
            
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                {{ app()->getLocale() == 'bn' ? 'রেজিস্ট্রেশনের পর অ্যাডমিন আপনার আবেদন পর্যালোচনা করবেন এবং অনুমোদন দিলে আপনি লগইন করতে পারবেন।' : 'After registration, admin will review your application and you can login once approved.' }}
            </div>
            
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            
            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf
                
                <!-- Personal Information -->
                <h5 class="section-title"><i class="fas fa-user"></i>{{ app()->getLocale() == 'bn' ? 'ব্যক্তিগত তথ্য' : 'Personal Information' }}</h5>
                
                <!-- Profile Picture Upload -->
                <div class="mb-4 text-center">
                    <label class="form-label d-block">{{ app()->getLocale() == 'bn' ? 'প্রোফাইল ছবি' : 'Profile Picture' }} <span class="required">*</span></label>
                    <div class="avatar-upload-wrapper">
                        <div class="avatar-preview" id="avatarPreview">
                            <i class="fas fa-user fa-3x text-muted"></i>
                        </div>
                        <label for="avatarInput" class="avatar-upload-btn">
                            <i class="fas fa-camera"></i> {{ app()->getLocale() == 'bn' ? 'ছবি আপলোড করুন' : 'Upload Photo' }}
                        </label>
                        <input type="file" name="avatar" id="avatarInput" class="d-none" accept="image/*">
                        <small class="d-block text-muted mt-1">JPG, PNG ({{ app()->getLocale() == 'bn' ? 'সর্বোচ্চ' : 'Max' }} 2MB)</small>
                    </div>
                    @error('avatar')
                        <div class="text-danger mt-1" style="font-size: 0.85rem;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-row">
                    <div class="col mb-3">
                        <label class="form-label">{{ app()->getLocale() == 'bn' ? 'পূর্ণ নাম' : 'Full Name' }} <span class="required">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               name="name" value="{{ old('name') }}" required placeholder="{{ app()->getLocale() == 'bn' ? 'আপনার নাম লিখুন' : 'Enter your name' }}">
                    </div>
                    <div class="col mb-3">
                        <label class="form-label">{{ app()->getLocale() == 'bn' ? 'ইমেইল অ্যাড্রেস' : 'Email Address' }} <span class="required">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required placeholder="example@email.com">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="col mb-3">
                        <label class="form-label">{{ app()->getLocale() == 'bn' ? 'মোবাইল নম্বর' : 'Mobile Number' }} <span class="required">*</span></label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                               name="phone" value="{{ old('phone') }}" required placeholder="01XXXXXXXXX">
                    </div>
                    <div class="col mb-3">
                        <label class="form-label">{{ app()->getLocale() == 'bn' ? 'জাতীয় পরিচয়পত্র নম্বর' : 'NID Number' }} ({{ app()->getLocale() == 'bn' ? 'ঐচ্ছিক' : 'Optional' }})</label>
                        <input type="text" class="form-control @error('nid_number') is-invalid @enderror" 
                               name="nid_number" value="{{ old('nid_number') }}" placeholder="{{ app()->getLocale() == 'bn' ? 'NID নম্বর' : 'NID Number' }}">
                    </div>
                </div>
                
                <!-- Location Information -->
                <h5 class="section-title"><i class="fas fa-map-marker-alt"></i>{{ app()->getLocale() == 'bn' ? 'অবস্থান' : 'Location' }}</h5>
                
                <div class="form-row">
                    <div class="col mb-3">
                        <label class="form-label">{{ app()->getLocale() == 'bn' ? 'উপজেলা' : 'Upazila' }} <span class="required">*</span></label>
                        <select name="upazila_id" class="form-select @error('upazila_id') is-invalid @enderror" required>
                            <option value="">{{ app()->getLocale() == 'bn' ? 'উপজেলা নির্বাচন করুন' : 'Select Upazila' }}</option>
                            @foreach($upazilas ?? [] as $upazila)
                                <option value="{{ $upazila->id }}" {{ old('upazila_id') == $upazila->id ? 'selected' : '' }}>
                                    {{ app()->getLocale() == 'bn' ? ($upazila->name_bn ?? $upazila->name) : $upazila->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">{{ app()->getLocale() == 'bn' ? 'পূর্ণ ঠিকানা' : 'Full Address' }} <span class="required">*</span></label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror" 
                           name="address" value="{{ old('address') }}" required placeholder="{{ app()->getLocale() == 'bn' ? 'গ্রাম/মহল্লা, পোস্ট অফিস' : 'Village/Area, Post Office' }}">
                </div>
                
                <!-- Category Selection -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="section-title mb-0"><i class="fas fa-th-large"></i>{{ app()->getLocale() == 'bn' ? 'আপনি কি করতে চান?' : 'What do you want to do?' }} <span class="required">*</span></h5>
                </div>
                <p class="text-muted mb-3" style="font-size: 0.85rem;">{{ app()->getLocale() == 'bn' ? 'আপনার উদ্দেশ্য অনুযায়ী নির্বাচন করুন' : 'Select based on your purpose' }}</p>
                
                <!-- Comment Only Option -->
                <div class="comment-only-option mb-3" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 12px; padding: 15px 20px; cursor: pointer; border: 2px solid transparent; transition: all 0.3s ease;">
                    <label for="comment_only" style="display: flex; align-items: center; cursor: pointer; margin: 0;">
                        <input type="checkbox" name="comment_only" value="1" 
                               id="comment_only"
                               style="width: 20px; height: 20px; margin-right: 12px; accent-color: #fff;"
                               {{ old('comment_only') ? 'checked' : '' }}>
                        <i class="fas fa-comment-dots" style="font-size: 24px; color: #fff; margin-right: 12px;"></i>
                        <span style="color: #fff; font-size: 16px; font-weight: 600;">{{ app()->getLocale() == 'bn' ? 'শুধু মন্তব্য করতে চাই' : 'I just want to comment' }}</span>
                    </label>
                </div>
                
                <!-- MP Question Option -->
                <div class="mp-question-option mb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; padding: 15px 20px; cursor: pointer; border: 2px solid transparent; transition: all 0.3s ease;">
                    <label for="mp_question_only" style="display: flex; align-items: center; cursor: pointer; margin: 0;">
                        <input type="checkbox" name="mp_question_only" value="1" 
                               id="mp_question_only"
                               style="width: 20px; height: 20px; margin-right: 12px; accent-color: #fff;"
                               {{ old('mp_question_only') ? 'checked' : '' }}>
                        <i class="fas fa-comments" style="font-size: 24px; color: #fff; margin-right: 12px;"></i>
                        <span style="color: #fff; font-size: 16px; font-weight: 600;">{{ app()->getLocale() == 'bn' ? 'সাংসদকে প্রশ্ন করতে চাই' : 'I want to ask questions to MP' }}</span>
                    </label>
                </div>
                
                <!-- Upazila Moderator Option -->
                <div class="upazila-moderator-option mb-3" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 12px; padding: 15px 20px; cursor: pointer; border: 2px solid transparent; transition: all 0.3s ease;">
                    <label for="wants_upazila_moderator" style="display: flex; align-items: center; cursor: pointer; margin: 0;">
                        <input type="checkbox" name="wants_upazila_moderator" value="1" 
                               id="wants_upazila_moderator"
                               style="width: 20px; height: 20px; margin-right: 12px; accent-color: #fff;"
                               {{ old('wants_upazila_moderator') ? 'checked' : '' }}>
                        <i class="fas fa-user-shield" style="font-size: 24px; color: #fff; margin-right: 12px;"></i>
                        <div>
                            <span style="color: #fff; font-size: 16px; font-weight: 600;">{{ app()->getLocale() == 'bn' ? 'উপজেলা মডারেটর হতে চাই' : 'I want to be Upazila Moderator' }}</span>
                            <small style="display: block; color: rgba(255,255,255,0.8); font-size: 12px;">{{ app()->getLocale() == 'bn' ? 'আপনার উপজেলার সকল তথ্য যোগ ও পরিচালনা করতে পারবেন' : 'You can add and manage all information of your upazila' }}</small>
                        </div>
                    </label>
                </div>
                
                <!-- Own Business Moderator Option -->
                <div class="own-business-option mb-3" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 12px; padding: 15px 20px; cursor: pointer; border: 2px solid transparent; transition: all 0.3s ease;">
                    <label for="wants_own_business_moderator" style="display: flex; align-items: center; cursor: pointer; margin: 0;">
                        <input type="checkbox" name="wants_own_business_moderator" value="1" 
                               id="wants_own_business_moderator"
                               style="width: 20px; height: 20px; margin-right: 12px; accent-color: #fff;"
                               {{ old('wants_own_business_moderator') ? 'checked' : '' }}>
                        <i class="fas fa-store" style="font-size: 24px; color: #fff; margin-right: 12px;"></i>
                        <div>
                            <span style="color: #fff; font-size: 16px; font-weight: 600;">{{ app()->getLocale() == 'bn' ? 'নিজের ব্যবসার মডারেটর হতে চাই' : 'I want to be Own Business Moderator' }}</span>
                            <small style="display: block; color: rgba(255,255,255,0.8); font-size: 12px;">{{ app()->getLocale() == 'bn' ? 'নিজের ব্যবসার তথ্য যোগ ও পরিচালনা করতে পারবেন' : 'You can add and manage your own business information' }}</small>
                        </div>
                    </label>
                </div>
                
                <!-- Category Selection (only for listing contributors) -->
                <div class="category-section p-3 mb-3" id="categorySection" style="background: #f8f9fa; border-radius: 12px; border: 1px solid #e9ecef;">
                    <p class="mb-3 fw-bold" style="font-size: 0.9rem;"><i class="fas fa-th-list me-2 text-success"></i><span id="categoryLabel">{{ app()->getLocale() == 'bn' ? 'তথ্য যোগ করতে ক্যাটাগরি নির্বাচন করুন:' : 'Select categories to add listings:' }}</span></p>
                    
                    <div class="category-grid">
                        @foreach($categories ?? [] as $category)
                            <div class="category-item">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" 
                                       id="cat_{{ $category->id }}"
                                       {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                                <label for="cat_{{ $category->id }}">
                                    <i class="{{ $category->icon ?: 'fas fa-folder' }}"></i>
                                    {{ app()->getLocale() == 'bn' ? ($category->name_bn ?? $category->name) : $category->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                @error('categories')
                    <div class="text-danger mb-3" style="font-size: 0.85rem;">{{ $message }}</div>
                @enderror
                @error('mp_question_only')
                    <div class="text-danger mb-3" style="font-size: 0.85rem;">{{ $message }}</div>
                @enderror
                
                <!-- Registration Purpose -->
                <h5 class="section-title"><i class="fas fa-question-circle"></i>{{ app()->getLocale() == 'bn' ? 'রেজিস্ট্রেশনের উদ্দেশ্য' : 'Registration Purpose' }}</h5>
                
                <div class="mb-3">
                    <label class="form-label">{{ app()->getLocale() == 'bn' ? 'কেন আপনি এই পোর্টালে তথ্য যোগ করতে চাইছেন?' : 'Why do you want to add information to this portal?' }} <span class="required">*</span></label>
                    <textarea class="form-control @error('registration_purpose') is-invalid @enderror" 
                              name="registration_purpose" rows="3" required 
                              placeholder="{{ app()->getLocale() == 'bn' ? 'উদাহরণ: আমি সাতক্ষীরা সদরে একটি দোকান চালাই এবং আমার ব্যবসার তথ্য পোর্টালে যোগ করতে চাই...' : 'Example: I run a shop in Satkhira Sadar and want to add my business info to the portal...' }}">{{ old('registration_purpose') }}</textarea>
                </div>
                
                <!-- Password -->
                <h5 class="section-title"><i class="fas fa-lock"></i>{{ app()->getLocale() == 'bn' ? 'পাসওয়ার্ড সেট করুন' : 'Set Password' }}</h5>
                
                <div class="form-row">
                    <div class="col mb-3">
                        <label class="form-label">{{ app()->getLocale() == 'bn' ? 'পাসওয়ার্ড' : 'Password' }} <span class="required">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" required placeholder="{{ app()->getLocale() == 'bn' ? 'কমপক্ষে ৮ অক্ষর' : 'At least 8 characters' }}">
                    </div>
                    
                    <div class="col mb-3">
                        <label class="form-label">{{ app()->getLocale() == 'bn' ? 'পাসওয়ার্ড নিশ্চিত করুন' : 'Confirm Password' }} <span class="required">*</span></label>
                        <input type="password" class="form-control" 
                               name="password_confirmation" required placeholder="{{ app()->getLocale() == 'bn' ? 'পাসওয়ার্ড আবার লিখুন' : 'Re-enter password' }}">
                    </div>
                </div>
                
                <div class="terms-text">
                    {{ app()->getLocale() == 'bn' ? 'রেজিস্ট্রেশন করে আপনি আমাদের' : 'By registering, you agree to our' }} <a href="#">{{ app()->getLocale() == 'bn' ? 'শর্তাবলী' : 'Terms' }}</a> {{ app()->getLocale() == 'bn' ? 'এবং' : 'and' }} <a href="#">{{ app()->getLocale() == 'bn' ? 'গোপনীয়তা নীতি' : 'Privacy Policy' }}</a>{{ app()->getLocale() == 'bn' ? 'তে সম্মত হচ্ছেন।' : '.' }}
                </div>
                
                <button type="submit" class="btn btn-register">
                    <i class="fas fa-paper-plane me-2"></i>{{ app()->getLocale() == 'bn' ? 'আবেদন জমা দিন' : 'Submit Application' }}
                </button>
            </form>
            
            <div class="divider"><span>{{ app()->getLocale() == 'bn' ? 'অথবা' : 'or' }}</span></div>
            
            <div class="login-link">
                {{ app()->getLocale() == 'bn' ? 'ইতিমধ্যে অ্যাকাউন্ট আছে?' : 'Already have an account?' }} <a href="{{ route('login') }}">{{ app()->getLocale() == 'bn' ? 'লগইন করুন' : 'Login' }}</a>
            </div>
            
            <div class="text-center mt-3">
                <a href="{{ route('home') }}" class="text-muted text-decoration-none">
                    <i class="fas fa-arrow-left me-1"></i> {{ app()->getLocale() == 'bn' ? 'হোমে ফিরে যান' : 'Back to Home' }}
                </a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Avatar Preview
        document.getElementById('avatarInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatarPreview');
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="Avatar Preview">';
                };
                reader.readAsDataURL(file);
            }
        });
        
        // Moderator type handling
        const upazilaModerator = document.getElementById('wants_upazila_moderator');
        const ownBusinessModerator = document.getElementById('wants_own_business_moderator');
        const categoryCheckboxes = document.querySelectorAll('input[name="categories[]"]');
        const categorySection = document.getElementById('categorySection');
        const categoryLabel = document.getElementById('categoryLabel');
        const commentOnly = document.getElementById('comment_only');
        const mpQuestionOnly = document.getElementById('mp_question_only');
        
        const bnLang = '{{ app()->getLocale() }}' === 'bn';
        
        function updateCategoryState() {
            const isUpazilaMod = upazilaModerator.checked;
            const isOwnBusinessMod = ownBusinessModerator.checked;
            const isCommentOnly = commentOnly.checked;
            const isMpQuestion = mpQuestionOnly.checked;
            
            // Hide category section if comment only or MP question only (without moderator options)
            if ((isCommentOnly || isMpQuestion) && !isUpazilaMod && !isOwnBusinessMod) {
                categorySection.style.display = 'none';
                categoryCheckboxes.forEach(cb => {
                    cb.checked = false;
                    cb.disabled = false;
                });
                return;
            }
            
            categorySection.style.display = 'block';
            
            if (isUpazilaMod) {
                // Upazila Moderator - select ALL options including comment and MP questions
                categoryLabel.textContent = bnLang 
                    ? 'উপজেলা মডারেটর হিসেবে সকল ক্যাটাগরিতে কাজ করতে পারবেন:' 
                    : 'As Upazila Moderator, you can work in all categories:';
                categoryCheckboxes.forEach(cb => {
                    cb.checked = true;
                    cb.disabled = true;
                });
                // Also check comment and MP question
                commentOnly.checked = true;
                mpQuestionOnly.checked = true;
                // Uncheck own business moderator
                ownBusinessModerator.checked = false;
            } else if (isOwnBusinessMod) {
                // Own Business Moderator - only check comment, allow ONE category selection
                categoryLabel.textContent = bnLang 
                    ? 'আপনার ব্যবসার ক্যাটাগরি নির্বাচন করুন (শুধু একটি):' 
                    : 'Select your business category (only one):';
                categoryCheckboxes.forEach(cb => {
                    cb.disabled = false;
                });
                // Only check comment (not MP question)
                commentOnly.checked = true;
                mpQuestionOnly.checked = false;
                // Ensure only one category is selected
                enforceOneCategory();
                // Uncheck upazila moderator
                upazilaModerator.checked = false;
            } else {
                // Normal mode - multiple categories allowed
                categoryLabel.textContent = bnLang 
                    ? 'তথ্য যোগ করতে ক্যাটাগরি নির্বাচন করুন:' 
                    : 'Select categories to add listings:';
                categoryCheckboxes.forEach(cb => {
                    cb.disabled = false;
                });
            }
        }
        
        function enforceOneCategory() {
            // Count checked categories
            const checkedCategories = Array.from(categoryCheckboxes).filter(cb => cb.checked);
            if (checkedCategories.length > 1) {
                // Uncheck all except the last one
                checkedCategories.slice(0, -1).forEach(cb => cb.checked = false);
            }
        }
        
        // Event listeners for moderator options
        upazilaModerator.addEventListener('change', function() {
            if (this.checked) {
                // Uncheck own business moderator
                ownBusinessModerator.checked = false;
            }
            updateCategoryState();
        });
        
        ownBusinessModerator.addEventListener('change', function() {
            if (this.checked) {
                // Uncheck upazila moderator
                upazilaModerator.checked = false;
            }
            updateCategoryState();
        });
        
        commentOnly.addEventListener('change', updateCategoryState);
        mpQuestionOnly.addEventListener('change', updateCategoryState);
        
        // Category checkbox handler for own business moderator
        categoryCheckboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                if (ownBusinessModerator.checked && this.checked) {
                    // If own business moderator, only allow one category
                    categoryCheckboxes.forEach(otherCb => {
                        if (otherCb !== this) {
                            otherCb.checked = false;
                        }
                    });
                }
            });
        });
        
        // Initialize state on page load
        updateCategoryState();
    </script>
</body>
</html>
