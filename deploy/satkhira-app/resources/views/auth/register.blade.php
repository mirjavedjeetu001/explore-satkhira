<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>রেজিস্ট্রেশন - Satkhira Portal</title>
    
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
            max-width: 1200px;
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
            flex: 0 0 350px;
            background: linear-gradient(135deg, #0a4a24 0%, #0d5c2e 100%);
            padding: 50px 35px;
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
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .register-left .brand h1 i {
            color: #7bed9f;
        }
        
        .register-left .tagline {
            color: rgba(255,255,255,0.8);
            font-size: 0.95rem;
            margin-bottom: 25px;
        }
        
        .register-left .features {
            position: relative;
            z-index: 1;
        }
        
        .register-left .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            color: rgba(255,255,255,0.9);
        }
        
        .register-left .feature-item i {
            width: 36px;
            height: 36px;
            background: rgba(255,255,255,0.15);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 0.9rem;
            color: #7bed9f;
        }
        
        .register-left .feature-item span {
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .register-right {
            flex: 1;
            background: #fff;
            padding: 40px 50px;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .register-right h2 {
            color: #1a5f2a;
            font-size: 1.6rem;
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
            font-size: 1rem;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .section-title i {
            margin-right: 8px;
        }
        
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 4px rgba(40, 167, 69, 0.1);
        }
        
        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 6px;
            font-size: 0.9rem;
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
        }
        
        .category-grid {
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
            padding: 12px 15px;
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .category-item label i {
            margin-right: 10px;
            font-size: 1.1rem;
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
            padding: 14px;
            background: linear-gradient(135deg, #1a7f40 0%, #28a745 100%);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-top: 15px;
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
            margin: 20px 0;
            color: #adb5bd;
        }
        
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e9ecef;
        }
        
        .divider span {
            padding: 0 15px;
            font-size: 0.9rem;
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
            padding: 12px 15px;
            border-radius: 0 10px 10px 0;
            margin-bottom: 20px;
            font-size: 0.9rem;
            color: #2e7d32;
        }
        
        .info-box i {
            margin-right: 8px;
        }
        
        @media (max-width: 900px) {
            .register-container {
                flex-direction: column;
            }
            
            .register-left {
                flex: 0 0 auto;
                padding: 30px 25px;
            }
            
            .register-right {
                padding: 30px 25px;
                max-height: none;
            }
            
            .register-left .brand h1 {
                font-size: 1.5rem;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .category-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-left">
            <div class="brand">
                <h1><i class="fas fa-leaf"></i> সাতক্ষীরা পোর্টাল</h1>
                <p class="tagline">সাতক্ষীরা জেলার সকল তথ্য এক জায়গায়</p>
            </div>
            
            <div class="features">
                <div class="feature-item">
                    <i class="fas fa-user-plus"></i>
                    <span>বিনামূল্যে অ্যাকাউন্ট তৈরি করুন</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-edit"></i>
                    <span>আপনার এলাকার তথ্য যোগ করুন</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-check-circle"></i>
                    <span>অ্যাডমিন অনুমোদনের পর অ্যাক্সেস</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-tags"></i>
                    <span>নির্দিষ্ট ক্যাটাগরিতে কাজ করুন</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-shield-alt"></i>
                    <span>নিরাপদ ও বিশ্বস্ত প্ল্যাটফর্ম</span>
                </div>
            </div>
        </div>
        
        <div class="register-right">
            <h2>অ্যাকাউন্ট তৈরি করুন</h2>
            <p class="subtitle">নিচের তথ্য দিয়ে রেজিস্ট্রেশন সম্পন্ন করুন</p>
            
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                রেজিস্ট্রেশনের পর অ্যাডমিন আপনার আবেদন পর্যালোচনা করবেন এবং অনুমোদন দিলে আপনি লগইন করতে পারবেন।
            </div>
            
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            
            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <!-- Personal Information -->
                <h5 class="section-title"><i class="fas fa-user"></i>ব্যক্তিগত তথ্য</h5>
                
                <div class="form-row">
                    <div class="col mb-3">
                        <label class="form-label">পূর্ণ নাম <span class="required">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               name="name" value="{{ old('name') }}" required placeholder="আপনার নাম লিখুন">
                    </div>
                    <div class="col mb-3">
                        <label class="form-label">ইমেইল অ্যাড্রেস <span class="required">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required placeholder="example@email.com">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="col mb-3">
                        <label class="form-label">মোবাইল নম্বর <span class="required">*</span></label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                               name="phone" value="{{ old('phone') }}" required placeholder="01XXXXXXXXX">
                    </div>
                    <div class="col mb-3">
                        <label class="form-label">জাতীয় পরিচয়পত্র নম্বর (ঐচ্ছিক)</label>
                        <input type="text" class="form-control @error('nid_number') is-invalid @enderror" 
                               name="nid_number" value="{{ old('nid_number') }}" placeholder="NID নম্বর">
                    </div>
                </div>
                
                <!-- Location Information -->
                <h5 class="section-title"><i class="fas fa-map-marker-alt"></i>অবস্থান</h5>
                
                <div class="form-row">
                    <div class="col mb-3">
                        <label class="form-label">উপজেলা <span class="required">*</span></label>
                        <select name="upazila_id" class="form-select @error('upazila_id') is-invalid @enderror" required>
                            <option value="">উপজেলা নির্বাচন করুন</option>
                            @foreach($upazilas ?? [] as $upazila)
                                <option value="{{ $upazila->id }}" {{ old('upazila_id') == $upazila->id ? 'selected' : '' }}>
                                    {{ $upazila->name_bn ?? $upazila->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">পূর্ণ ঠিকানা <span class="required">*</span></label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror" 
                           name="address" value="{{ old('address') }}" required placeholder="গ্রাম/মহল্লা, পোস্ট অফিস">
                </div>
                
                <!-- Category Selection -->
                <h5 class="section-title"><i class="fas fa-th-large"></i>কোন ক্যাটাগরিতে তথ্য যোগ করতে চান? <span class="required">*</span></h5>
                <p class="text-muted mb-3" style="font-size: 0.85rem;">আপনি যে ক্যাটাগরিতে তথ্য/লিস্টিং যোগ করতে চান সেগুলো নির্বাচন করুন (একাধিক নির্বাচন করতে পারবেন)</p>
                
                <div class="category-grid">
                    @foreach($categories ?? [] as $category)
                        <div class="category-item">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}" 
                                   id="cat_{{ $category->id }}"
                                   {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                            <label for="cat_{{ $category->id }}">
                                <i class="{{ $category->icon ?: 'fas fa-folder' }}"></i>
                                {{ $category->name_bn ?? $category->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('categories')
                    <div class="text-danger mb-3" style="font-size: 0.85rem;">{{ $message }}</div>
                @enderror
                
                <!-- Registration Purpose -->
                <h5 class="section-title"><i class="fas fa-question-circle"></i>রেজিস্ট্রেশনের উদ্দেশ্য</h5>
                
                <div class="mb-3">
                    <label class="form-label">কেন আপনি এই পোর্টালে তথ্য যোগ করতে চাইছেন? <span class="required">*</span></label>
                    <textarea class="form-control @error('registration_purpose') is-invalid @enderror" 
                              name="registration_purpose" rows="3" required 
                              placeholder="উদাহরণ: আমি সাতক্ষীরা সদরে একটি দোকান চালাই এবং আমার ব্যবসার তথ্য পোর্টালে যোগ করতে চাই...">{{ old('registration_purpose') }}</textarea>
                </div>
                
                <!-- Password -->
                <h5 class="section-title"><i class="fas fa-lock"></i>পাসওয়ার্ড সেট করুন</h5>
                
                <div class="form-row">
                    <div class="col mb-3">
                        <label class="form-label">পাসওয়ার্ড <span class="required">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" required placeholder="কমপক্ষে ৮ অক্ষর">
                    </div>
                    
                    <div class="col mb-3">
                        <label class="form-label">পাসওয়ার্ড নিশ্চিত করুন <span class="required">*</span></label>
                        <input type="password" class="form-control" 
                               name="password_confirmation" required placeholder="পাসওয়ার্ড আবার লিখুন">
                    </div>
                </div>
                
                <div class="terms-text">
                    রেজিস্ট্রেশন করে আপনি আমাদের <a href="#">শর্তাবলী</a> এবং <a href="#">গোপনীয়তা নীতি</a>তে সম্মত হচ্ছেন।
                </div>
                
                <button type="submit" class="btn btn-register">
                    <i class="fas fa-paper-plane me-2"></i>আবেদন জমা দিন
                </button>
            </form>
            
            <div class="divider"><span>অথবা</span></div>
            
            <div class="login-link">
                ইতিমধ্যে অ্যাকাউন্ট আছে? <a href="{{ route('login') }}">লগইন করুন</a>
            </div>
            
            <div class="text-center mt-3">
                <a href="{{ route('home') }}" class="text-muted text-decoration-none">
                    <i class="fas fa-arrow-left me-1"></i> হোমে ফিরে যান
                </a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
