<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>রেজিস্ট্রেশন সম্পন্ন - Satkhira Portal</title>
    
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
            padding: 20px;
        }
        
        .success-container {
            max-width: 600px;
            width: 100%;
            background: #fff;
            border-radius: 24px;
            padding: 50px;
            text-align: center;
            box-shadow: 0 25px 80px rgba(0,0,0,0.35);
        }
        
        .success-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #28a745 0%, #1a7f40 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            animation: pulse 2s infinite;
        }
        
        .success-icon i {
            font-size: 50px;
            color: #fff;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        h1 {
            color: #1a5f2a;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .message {
            color: #6c757d;
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 30px;
        }
        
        .steps {
            background: #f8f9fa;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 30px;
            text-align: left;
        }
        
        .steps h5 {
            color: #1a5f2a;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .step-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
        }
        
        .step-item:last-child {
            margin-bottom: 0;
        }
        
        .step-number {
            width: 28px;
            height: 28px;
            background: #28a745;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 600;
            margin-right: 12px;
            flex-shrink: 0;
        }
        
        .step-item span {
            color: #495057;
            font-size: 0.95rem;
            line-height: 1.6;
        }
        
        .btn-home {
            padding: 14px 40px;
            background: linear-gradient(135deg, #1a7f40 0%, #28a745 100%);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .btn-home:hover {
            background: linear-gradient(135deg, #0d5c2e 0%, #1a7f40 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
            color: #fff;
        }
        
        .login-link {
            margin-top: 20px;
            color: #6c757d;
        }
        
        .login-link a {
            color: #28a745;
            font-weight: 600;
            text-decoration: none;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>
        
        <h1>রেজিস্ট্রেশন সফল হয়েছে!</h1>
        
        <p class="message">
            আপনার রেজিস্ট্রেশন সফলভাবে সম্পন্ন হয়েছে। আপনার আবেদন এখন অ্যাডমিনের কাছে পর্যালোচনার জন্য অপেক্ষায় আছে।
        </p>
        
        <div class="steps">
            <h5><i class="fas fa-info-circle me-2"></i>পরবর্তী ধাপসমূহ:</h5>
            <div class="step-item">
                <div class="step-number">১</div>
                <span>অ্যাডমিন আপনার তথ্য যাচাই করবেন</span>
            </div>
            <div class="step-item">
                <div class="step-number">২</div>
                <span>আপনি কোন কোন ক্যাটাগরিতে তথ্য যোগ করতে পারবেন সে অনুমতি দেওয়া হবে</span>
            </div>
            <div class="step-item">
                <div class="step-number">৩</div>
                <span>অনুমোদন পেলে আপনার ইমেইলে নোটিফিকেশন পাবেন</span>
            </div>
            <div class="step-item">
                <div class="step-number">৪</div>
                <span>এরপর লগইন করে আপনার এলাকার তথ্য যোগ করতে পারবেন</span>
            </div>
        </div>
        
        <a href="{{ route('home') }}" class="btn-home">
            <i class="fas fa-home me-2"></i>হোমে ফিরে যান
        </a>
        
        <div class="login-link">
            ইতিমধ্যে অনুমোদন পেয়েছেন? <a href="{{ route('login') }}">লগইন করুন</a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
