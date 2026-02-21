<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>পেজ খুঁজে পাওয়া যায়নি - Explore Satkhira</title>
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
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
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
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
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
        
        .btn-back {
            background: white;
            color: #495057;
            padding: 14px 35px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            border: 2px solid #6c757d;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-back:hover {
            background: #6c757d;
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
            
            .btn-home, .btn-back {
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
                    <i class="fas fa-search"></i>
                </div>
                <div class="error-code">404</div>
                <h1 class="error-title">পেজ খুঁজে পাওয়া যায়নি</h1>
            </div>
            
            <div class="error-body">
                <p class="error-message">
                    <i class="fas fa-map-marker-alt text-danger me-2"></i>
                    দুঃখিত! আপনি যে পেজটি খুঁজছেন সেটি বিদ্যমান নেই।
                </p>
                <p class="error-submessage">
                    পেজটি সরানো হয়ে থাকতে পারে, নাম পরিবর্তন করা হয়েছে, 
                    <br>
                    অথবা সাময়িকভাবে অনুপলব্ধ।
                </p>
                
                <div class="error-actions">
                    <a href="{{ url('/') }}" class="btn-home">
                        <i class="fas fa-home"></i> হোমপেজে যান
                    </a>
                    <a href="javascript:history.back()" class="btn-back">
                        <i class="fas fa-arrow-left"></i> পেছনে যান
                    </a>
                </div>
                
                <div class="helpful-links">
                    <p><i class="fas fa-lightbulb me-1"></i> এখান থেকে শুরু করতে পারেন:</p>
                    <div class="link-list">
                        <a href="{{ route('listings.index') }}"><i class="fas fa-list me-1"></i>সকল তথ্য</a>
                        <a href="{{ route('about') }}"><i class="fas fa-info-circle me-1"></i>আমাদের সম্পর্কে</a>
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
