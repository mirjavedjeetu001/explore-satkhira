<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>সার্ভার ত্রুটি - Explore Satkhira</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <style>
        :root {
            --primary-color: #1a3c34;
            --secondary-color: #28a745;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e9f0 100%);
        }
        
        .error-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        
        .error-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            max-width: 550px;
            width: 100%;
            text-align: center;
            overflow: hidden;
        }
        
        .error-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            padding: 40px 30px;
            color: white;
        }
        
        .error-icon {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 50px;
            animation: shake 0.5s ease-in-out infinite;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .error-code {
            font-size: 72px;
            font-weight: bold;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .error-title {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }
        
        .error-body {
            padding: 40px 30px;
        }
        
        .error-message {
            font-size: 18px;
            color: #555;
            margin-bottom: 15px;
            line-height: 1.8;
        }
        
        .error-submessage {
            font-size: 15px;
            color: #888;
            margin-bottom: 30px;
        }
        
        .error-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-home {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 14px 35px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(40, 167, 69, 0.35);
            color: white;
        }
        
        .btn-refresh {
            background: white;
            color: #dc3545;
            padding: 14px 35px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            border: 2px solid #dc3545;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-refresh:hover {
            background: #dc3545;
            color: white;
            transform: translateY(-3px);
        }
        
        .helpful-links {
            margin-top: 35px;
            padding-top: 25px;
            border-top: 1px solid #eee;
        }
        
        .helpful-links p {
            color: #888;
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .link-list {
            display: flex;
            justify-content: center;
            gap: 25px;
            flex-wrap: wrap;
        }
        
        .link-list a {
            color: var(--secondary-color);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }
        
        .link-list a:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }
        
        .footer {
            text-align: center;
            padding: 20px;
            color: #888;
            font-size: 13px;
        }
        
        .footer a {
            color: var(--secondary-color);
            text-decoration: none;
        }
        
        @media (max-width: 576px) {
            .error-code {
                font-size: 56px;
            }
            
            .error-title {
                font-size: 20px;
            }
            
            .error-message {
                font-size: 16px;
            }
            
            .error-actions {
                flex-direction: column;
            }
            
            .btn-home, .btn-refresh {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-card">
            <div class="error-header">
                <div class="error-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="error-code">500</div>
                <h1 class="error-title">সার্ভার ত্রুটি</h1>
            </div>
            
            <div class="error-body">
                <p class="error-message">
                    <i class="fas fa-cogs text-warning me-2"></i>
                    দুঃখিত! আমাদের সার্ভারে একটি সমস্যা হয়েছে।
                </p>
                <p class="error-submessage">
                    আমরা এই সমস্যাটি সমাধান করতে কাজ করছি।
                    <br>
                    অনুগ্রহ করে কিছুক্ষণ পরে আবার চেষ্টা করুন।
                </p>
                
                <div class="error-actions">
                    <a href="{{ url('/') }}" class="btn-home">
                        <i class="fas fa-home"></i> হোমপেজে যান
                    </a>
                    <a href="javascript:location.reload()" class="btn-refresh">
                        <i class="fas fa-redo"></i> রিফ্রেশ করুন
                    </a>
                </div>
                
                <div class="helpful-links">
                    <p><i class="fas fa-headset me-1"></i> সমস্যা হলে যোগাযোগ করুন:</p>
                    <div class="link-list">
                        <a href="{{ route('contact') }}"><i class="fas fa-envelope me-1"></i>যোগাযোগ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="footer">
        © {{ date('Y') }} <a href="{{ url('/') }}">Explore Satkhira</a> | সাতক্ষীরা জেলার সকল তথ্য এক প্ল্যাটফর্মে
    </div>
</body>
</html>
