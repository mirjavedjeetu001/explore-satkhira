<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ app()->getLocale() == 'bn' ? 'পাসওয়ার্ড রিসেট - এক্সপ্লোর সাতক্ষীরা' : 'Password Reset - Explore Satkhira' }}</title>
    
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
        
        .password-container {
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
        
        .password-left {
            flex: 1;
            background: linear-gradient(135deg, #0a4a24 0%, #0d5c2e 100%);
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .password-left::before {
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
        
        .password-left .brand {
            position: relative;
            z-index: 1;
        }
        
        .password-left .brand h1 {
            color: #fff;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .password-left .brand h1 i {
            color: #7bed9f;
        }
        
        .password-left .tagline {
            color: rgba(255,255,255,0.8);
            font-size: 1.1rem;
            margin-bottom: 40px;
        }
        
        .password-left .features {
            position: relative;
            z-index: 1;
        }
        
        .password-left .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            color: rgba(255,255,255,0.9);
        }
        
        .password-left .feature-item i {
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
        
        .password-left .feature-item span {
            font-weight: 500;
        }
        
        .password-right {
            flex: 1;
            background: #fff;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .password-right h2 {
            color: #1a5f2a;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .password-right .subtitle {
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
        
        .btn-submit {
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
        
        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }
        
        .btn-submit:hover::before {
            left: 100%;
        }
        
        .btn-submit:hover {
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
        
        .back-link {
            text-align: center;
            color: #6c757d;
        }
        
        .back-link a {
            color: #28a745;
            font-weight: 600;
            text-decoration: none;
        }
        
        .back-link a:hover {
            color: #1a5f2a;
            text-decoration: underline;
        }
        
        .alert {
            border-radius: 12px;
            margin-bottom: 20px;
        }
        
        .icon-lock {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(40, 167, 69, 0.2) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
        }
        
        .icon-lock i {
            font-size: 2rem;
            color: #28a745;
        }
        
        @media (max-width: 768px) {
            body {
                overflow: auto;
                min-height: auto;
                padding: 20px 0;
            }
            
            .password-container {
                flex-direction: column;
                margin: 10px;
            }
            
            .password-left {
                padding: 30px 20px;
            }
            
            .password-right {
                padding: 30px 20px;
            }
            
            .password-left .brand h1 {
                font-size: 1.6rem;
            }
            
            .password-left .tagline {
                font-size: 0.95rem;
                margin-bottom: 25px;
            }
            
            .password-left .feature-item {
                margin-bottom: 15px;
                font-size: 0.9rem;
            }
            
            .password-left .feature-item i {
                width: 38px;
                height: 38px;
                font-size: 1rem;
            }
            
            .password-right h2 {
                font-size: 1.6rem;
            }
            
            .form-floating .form-control {
                height: 55px;
            }
        }
    </style>
</head>
<body>
    <div class="password-container">
        <div class="password-left">
            <div class="brand">
                <h1><i class="fas fa-leaf"></i> {{ app()->getLocale() == 'bn' ? 'এক্সপ্লোর সাতক্ষীরা' : 'Explore Satkhira' }}</h1>
                <p class="tagline">{{ app()->getLocale() == 'bn' ? 'সাতক্ষীরা জেলার সকল তথ্য এক জায়গায়' : 'All information of Satkhira district in one place' }}</p>
            </div>
            
            <div class="features">
                <div class="feature-item">
                    <i class="fas fa-shield-alt"></i>
                    <span>{{ app()->getLocale() == 'bn' ? 'আপনার অ্যাকাউন্ট সুরক্ষিত' : 'Your account is secure' }}</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-envelope"></i>
                    <span>{{ app()->getLocale() == 'bn' ? 'ইমেইলে রিসেট লিংক পাবেন' : 'Reset link sent to email' }}</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-clock"></i>
                    <span>{{ app()->getLocale() == 'bn' ? 'দ্রুত ও সহজ প্রক্রিয়া' : 'Quick & easy process' }}</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-headset"></i>
                    <span>{{ app()->getLocale() == 'bn' ? 'সমস্যায় সাহায্য নিন' : 'Get help if needed' }}</span>
                </div>
            </div>
        </div>
        
        <div class="password-right">
            <div class="icon-lock">
                <i class="fas fa-key"></i>
            </div>
            
            <h2 class="text-center">{{ app()->getLocale() == 'bn' ? 'পাসওয়ার্ড ভুলে গেছেন?' : 'Forgot Password?' }}</h2>
            <p class="subtitle text-center">{{ app()->getLocale() == 'bn' ? 'চিন্তা নেই! আপনার ইমেইল দিন, আমরা রিসেট লিংক পাঠাব' : 'No worries! Enter your email and we will send a reset link' }}</p>
            
            @if(session('status'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                
                <div class="form-floating">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" class="form-control" id="email" name="email" placeholder="{{ app()->getLocale() == 'bn' ? 'ইমেইল' : 'Email' }}" value="{{ old('email') }}" required autofocus>
                    <label for="email">{{ app()->getLocale() == 'bn' ? 'ইমেইল অ্যাড্রেস' : 'Email Address' }}</label>
                </div>
                
                <button type="submit" class="btn btn-submit">
                    <i class="fas fa-paper-plane me-2"></i>{{ app()->getLocale() == 'bn' ? 'রিসেট লিংক পাঠান' : 'Send Reset Link' }}
                </button>
            </form>
            
            <div class="divider"><span>{{ app()->getLocale() == 'bn' ? 'অথবা' : 'or' }}</span></div>
            
            <div class="back-link">
                <a href="{{ route('login') }}"><i class="fas fa-arrow-left me-2"></i>{{ app()->getLocale() == 'bn' ? 'লগইন পেজে ফিরে যান' : 'Back to Login' }}</a>
            </div>
            
            <div class="text-center mt-3">
                <a href="{{ route('home') }}" class="text-muted text-decoration-none">
                    <i class="fas fa-home me-1"></i> {{ app()->getLocale() == 'bn' ? 'হোমে ফিরে যান' : 'Back to Home' }}
                </a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
