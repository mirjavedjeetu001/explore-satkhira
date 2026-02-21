<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ app()->getLocale() == 'bn' ? 'লগইন - এক্সপ্লোর সাতক্ষীরা' : 'Login - Explore Satkhira' }}</title>
    
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
            overflow: hidden;
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
        
        .login-container {
            max-width: 1000px;
            width: 100%;
            margin: 20px;
            display: flex;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 80px rgba(0,0,0,0.35);
            position: relative;
            z-index: 1;
        }
        
        .login-left {
            flex: 1;
            background: linear-gradient(135deg, #0a4a24 0%, #0d5c2e 100%);
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-left::before {
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
        
        .login-left .brand {
            position: relative;
            z-index: 1;
        }
        
        .login-left .brand h1 {
            color: #fff;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .login-left .brand h1 i {
            color: #7bed9f;
        }
        
        .login-left .tagline {
            color: rgba(255,255,255,0.8);
            font-size: 1.1rem;
            margin-bottom: 40px;
        }
        
        .login-left .features {
            position: relative;
            z-index: 1;
        }
        
        .login-left .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            color: rgba(255,255,255,0.9);
        }
        
        .login-left .feature-item i {
            width: 45px;
            height: 45px;
            background: rgba(255,255,255,0.15);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.1rem;
            color: #7bed9f;
        }
        
        .login-left .feature-item span {
            font-weight: 500;
        }
        
        .login-right {
            flex: 1;
            background: #fff;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .login-right h2 {
            color: #1a5f2a;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .login-right .subtitle {
            color: #6c757d;
            margin-bottom: 30px;
        }
        
        .form-floating {
            margin-bottom: 20px;
        }
        
        .form-floating .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem 1rem 1rem 3rem;
            height: 60px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-floating .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 4px rgba(40, 167, 69, 0.1);
        }
        
        .form-floating label {
            padding-left: 3rem;
            color: #6c757d;
        }
        
        .form-floating .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
            font-size: 1.1rem;
            z-index: 5;
        }
        
        .form-floating .form-control:focus + label {
            color: #28a745;
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .form-check-input:checked {
            background-color: #28a745;
            border-color: #28a745;
        }
        
        .forgot-link {
            color: #28a745;
            text-decoration: none;
            font-weight: 500;
        }
        
        .forgot-link:hover {
            color: #1a5f2a;
            text-decoration: underline;
        }
        
        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #1a7f40 0%, #28a745 100%);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, #0d5c2e 0%, #1a7f40 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
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
        
        .register-link {
            text-align: center;
            color: #6c757d;
        }
        
        .register-link a {
            color: #28a745;
            font-weight: 600;
            text-decoration: none;
        }
        
        .register-link a:hover {
            color: #1a5f2a;
            text-decoration: underline;
        }
        
        .alert {
            border-radius: 12px;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            body {
                overflow: auto;
                min-height: auto;
                padding: 20px 0;
            }
            
            .login-container {
                flex-direction: column;
                margin: 10px;
            }
            
            .login-left {
                padding: 30px 20px;
            }
            
            .login-right {
                padding: 30px 20px;
            }
            
            .login-left .brand h1 {
                font-size: 1.6rem;
            }
            
            .login-left .tagline {
                font-size: 0.95rem;
                margin-bottom: 25px;
            }
            
            .login-left .feature-item {
                margin-bottom: 15px;
                font-size: 0.9rem;
            }
            
            .login-left .feature-item i {
                width: 38px;
                height: 38px;
                font-size: 1rem;
            }
            
            .login-right h2 {
                font-size: 1.6rem;
            }
            
            .form-floating .form-control {
                height: 55px;
            }
        }
        
        @media (max-width: 576px) {
            body {
                padding: 10px 0;
            }
            
            .login-container {
                margin: 5px;
                border-radius: 16px;
            }
            
            .login-left {
                padding: 25px 15px;
            }
            
            .login-right {
                padding: 25px 15px;
            }
            
            .login-left .brand h1 {
                font-size: 1.3rem;
            }
            
            .login-left .tagline {
                font-size: 0.85rem;
            }
            
            .login-left .feature-item {
                margin-bottom: 12px;
                font-size: 0.85rem;
            }
            
            .login-left .feature-item i {
                width: 35px;
                height: 35px;
                font-size: 0.9rem;
                min-width: 35px;
            }
            
            .login-right h2 {
                font-size: 1.4rem;
            }
            
            .login-right .subtitle {
                font-size: 0.9rem;
            }
            
            .form-floating .form-control {
                height: 50px;
                font-size: 0.95rem;
            }
            
            .form-floating label {
                font-size: 0.9rem;
            }
            
            .btn-login {
                padding: 12px;
                font-size: 1rem;
            }
            
            .alert {
                font-size: 0.85rem;
                padding: 12px;
            }
        }
        
        @media (max-width: 380px) {
            .login-container {
                margin: 3px;
            }
            
            .login-left, .login-right {
                padding: 20px 12px;
            }
            
            .login-left .brand h1 {
                font-size: 1.2rem;
            }
            
            .login-right h2 {
                font-size: 1.2rem;
            }
            
            .form-floating .form-control {
                height: 48px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <div class="brand">
                <h1><i class="fas fa-leaf"></i> {{ app()->getLocale() == 'bn' ? 'এক্সপ্লোর সাতক্ষীরা' : 'Explore Satkhira' }}</h1>
                <p class="tagline">{{ app()->getLocale() == 'bn' ? 'সাতক্ষীরা জেলার সকল তথ্য এক জায়গায়' : 'All information of Satkhira district in one place' }}</p>
            </div>
            
            <div class="features">
                <div class="feature-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ app()->getLocale() == 'bn' ? '৭টি উপজেলার সম্পূর্ণ তথ্য' : 'Complete info of 7 upazilas' }}</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-hospital"></i>
                    <span>{{ app()->getLocale() == 'bn' ? 'হাসপাতাল, শিক্ষা প্রতিষ্ঠান, সেবা' : 'Hospitals, Education, Services' }}</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-user-tie"></i>
                    <span>{{ app()->getLocale() == 'bn' ? 'সংসদ সদস্যকে সরাসরি প্রশ্ন করুন' : 'Ask questions directly to MP' }}</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-edit"></i>
                    <span>তথ্য যোগ করে সমাজে অবদান রাখুন</span>
                </div>
            </div>
        </div>
        
        <div class="login-right">
            <h2>{{ app()->getLocale() == 'bn' ? 'স্বাগতম!' : 'Welcome!' }}</h2>
            <p class="subtitle">{{ app()->getLocale() == 'bn' ? 'আপনার অ্যাকাউন্টে লগইন করুন' : 'Login to your account' }}</p>
            
            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="form-floating">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" class="form-control" id="email" name="email" placeholder="{{ app()->getLocale() == 'bn' ? 'ইমেইল' : 'Email' }}" value="{{ old('email') }}" required autofocus>
                    <label for="email">{{ app()->getLocale() == 'bn' ? 'ইমেইল অ্যাড্রেস' : 'Email Address' }}</label>
                </div>
                
                <div class="form-floating">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" class="form-control" id="password" name="password" placeholder="{{ app()->getLocale() == 'bn' ? 'পাসওয়ার্ড' : 'Password' }}" required>
                    <label for="password">{{ app()->getLocale() == 'bn' ? 'পাসওয়ার্ড' : 'Password' }}</label>
                </div>
                
                <div class="remember-forgot">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">{{ app()->getLocale() == 'bn' ? 'মনে রাখুন' : 'Remember me' }}</label>
                    </div>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">{{ app()->getLocale() == 'bn' ? 'পাসওয়ার্ড ভুলে গেছেন?' : 'Forgot password?' }}</a>
                    @endif
                </div>
                
                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>{{ app()->getLocale() == 'bn' ? 'লগইন করুন' : 'Login' }}
                </button>
            </form>
            
            <div class="divider"><span>{{ app()->getLocale() == 'bn' ? 'অথবা' : 'or' }}</span></div>
            
            <div class="register-link">
                {{ app()->getLocale() == 'bn' ? 'অ্যাকাউন্ট নেই?' : "Don't have an account?" }} <a href="{{ route('register') }}">{{ app()->getLocale() == 'bn' ? 'রেজিস্ট্রেশন করুন' : 'Register' }}</a>
            </div>
            
            <div class="text-center mt-3">
                <a href="{{ route('home') }}" class="text-muted text-decoration-none">
                    <i class="fas fa-arrow-left me-1"></i> {{ app()->getLocale() == 'bn' ? 'হোমে ফিরে যান' : 'Back to Home' }}
                </a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
