@extends('frontend.layouts.app')

@section('title', $settings->title ?? 'ঈদ গ্রিটিং কার্ড মেকার')
@section('meta_description', 'আপনার ছবি দিয়ে সুন্দর ঈদ গ্রিটিং কার্ড তৈরি করুন। বন্ধু-বান্ধব ও পরিবারকে ঈদের শুভেচ্ছা জানান!')

@section('content')
<div class="eid-card-page">
    <!-- Hero Section -->
    <div class="eid-hero">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="eid-icon mb-3">
                        <span class="moon">🌙</span>
                        <span class="star">⭐</span>
                        <span class="mosque">🕌</span>
                    </div>
                    <h1 class="display-4 fw-bold text-white mb-3">
                        {{ $settings->title ?? 'ঈদ গ্রিটিং কার্ড মেকার' }}
                    </h1>
                    <p class="lead text-white-50">
                        {{ $settings->description ?? 'আপনার ছবি দিয়ে সুন্দর ঈদ কার্ড তৈরি করুন!' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <!-- User Info Card -->
                <div id="userInfoCard" class="eid-card-box mb-4 {{ $hasSession ? 'd-none' : '' }}">
                    <div class="card-header-eid">
                        <i class="fas fa-user-circle"></i>
                        <span>আপনার তথ্য দিন</span>
                    </div>
                    <div class="card-body-eid">
                        <div class="text-center mb-4">
                            <p class="text-muted mb-0">
                                <i class="fas fa-info-circle me-1"></i>
                                একবার মোবাইল নম্বর দিলে পরবর্তীতে আর দিতে হবে না।
                            </p>
                        </div>
                        <form id="userInfoForm">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">আপনার নাম <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg" name="user_name" id="sessionUserName" 
                                           placeholder="যেমন: রহিম উদ্দিন" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">মোবাইল নম্বর <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control form-control-lg" name="phone" id="sessionPhone" 
                                           placeholder="যেমন: 01712345678" required pattern="[0-9]{11}">
                                </div>
                            </div>
                            <div class="mt-4 text-center">
                                <button type="submit" class="btn btn-eid-gold btn-lg px-5">
                                    <i class="fas fa-rocket me-2"></i>শুরু করুন
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Main Card Maker Section -->
                <div id="cardMakerSection" class="{{ !$hasSession ? 'd-none' : '' }}">
                    
                    <!-- User Welcome -->
                    <div class="welcome-card mb-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <span class="welcome-emoji">👋</span>
                                <div>
                                    <small class="text-muted">স্বাগতম</small>
                                    <h5 class="mb-0" id="welcomeName">{{ $savedName ?? 'অতিথি' }}</h5>
                                </div>
                            </div>
                            <button class="btn btn-outline-secondary btn-sm" id="logoutBtn">
                                <i class="fas fa-sign-out-alt me-1"></i> লগআউট
                            </button>
                        </div>
                    </div>

                    <div class="row g-4">
                        <!-- Card Creator Form -->
                        <div class="col-lg-5">
                            <div class="eid-card-box sticky-lg-top" style="top: 100px; z-index: 10;">
                                <div class="card-header-eid bg-gradient-gold">
                                    <i class="fas fa-magic"></i>
                                    <span>কার্ড তৈরি করুন</span>
                                </div>
                                <div class="card-body-eid">
                                    <form id="createCardForm" enctype="multipart/form-data">
                                        @csrf
                                        
                                        <!-- Photo Upload -->
                                        <div class="mb-4">
                                            <label class="form-label">আপনার ছবি <span class="text-danger">*</span></label>
                                            <div class="photo-upload-box" id="photoUploadBox">
                                                <input type="file" name="photo" id="photoInput" accept="image/*" required hidden>
                                                <div class="upload-placeholder" id="uploadPlaceholder">
                                                    <i class="fas fa-camera fa-3x text-muted mb-2"></i>
                                                    <p class="mb-0">ছবি আপলোড করতে ক্লিক করুন</p>
                                                    <small class="text-muted">JPG, PNG (সর্বোচ্চ 5MB)</small>
                                                </div>
                                                <img id="photoPreview" class="d-none" alt="Preview">
                                            </div>
                                        </div>
                                        
                                        <!-- Name -->
                                        <div class="mb-3">
                                            <label class="form-label">কার্ডে আপনার নাম <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" id="cardName" 
                                                   value="{{ $savedName ?? '' }}" placeholder="যেমন: মোঃ রহিম উদ্দিন" required>
                                        </div>
                                        
                                        <!-- Designation -->
                                        <div class="mb-3">
                                            <label class="form-label">পদবি / পরিচয় (ঐচ্ছিক)</label>
                                            <input type="text" class="form-control" name="designation" id="cardDesignation" 
                                                   placeholder="যেমন: শিক্ষক, ছাত্র, ব্যবসায়ী">
                                        </div>
                                        
                                        <!-- Custom Message -->
                                        <div class="mb-3">
                                            <label class="form-label">অতিরিক্ত বার্তা (ঐচ্ছিক)</label>
                                            <input type="text" class="form-control" name="custom_message" id="customMessage" 
                                                   placeholder="যেমন: সবাইকে ঈদের শুভেচ্ছা!" maxlength="100">
                                            <small class="text-muted">সর্বোচ্চ ১০০ অক্ষর</small>
                                        </div>
                                        
                                        <!-- Template Selection -->
                                        <div class="mb-4">
                                            <label class="form-label">টেমপ্লেট বাছুন <span class="text-danger">*</span></label>
                                            <div class="template-grid">
                                                <label class="template-card active" data-template="template1">
                                                    <input type="radio" name="template" value="template1" checked hidden>
                                                    <div class="template-thumb t1">
                                                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect fill='%23047857' width='100' height='100'/%3E%3Cpath d='M50 85 L30 65 L35 65 L35 50 L45 50 L45 40 L50 35 L55 40 L55 50 L65 50 L65 65 L70 65 Z' fill='%23fff' opacity='0.3'/%3E%3Ccircle cx='75' cy='20' r='12' fill='%23fcd34d' opacity='0.8'/%3E%3Ccircle cx='15' cy='15' r='3' fill='%23fff' opacity='0.5'/%3E%3Ccircle cx='85' cy='40' r='2' fill='%23fff' opacity='0.4'/%3E%3C/svg%3E" alt="">
                                                    </div>
                                                    <span class="template-label">মসজিদ ফ্রেম</span>
                                                    <i class="fas fa-check-circle template-check"></i>
                                                </label>
                                                <label class="template-card" data-template="template2">
                                                    <input type="radio" name="template" value="template2" hidden>
                                                    <div class="template-thumb t2">
                                                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect fill='%231e3a5f' width='100' height='100'/%3E%3Cpath d='M25 80 L25 40 L30 35 L30 25 L32 20 L30 25 L30 35 L35 40 L35 80 Z' fill='%23fcd34d' opacity='0.6'/%3E%3Cpath d='M65 80 L65 40 L70 35 L70 25 L72 20 L70 25 L70 35 L75 40 L75 80 Z' fill='%23fcd34d' opacity='0.6'/%3E%3Ccircle cx='50' cy='25' r='15' fill='none' stroke='%23fcd34d' stroke-width='2' opacity='0.8'/%3E%3Cpath d='M50 15 Q55 20 50 25 Q45 20 50 15' fill='%23fcd34d' opacity='0.8'/%3E%3C/svg%3E" alt="">
                                                    </div>
                                                    <span class="template-label">লণ্ঠন ফ্রেম</span>
                                                    <i class="fas fa-check-circle template-check"></i>
                                                </label>
                                                <label class="template-card" data-template="template3">
                                                    <input type="radio" name="template" value="template3" hidden>
                                                    <div class="template-thumb t3">
                                                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect fill='%23701a75' width='100' height='100'/%3E%3Ccircle cx='80' cy='20' r='18' fill='%23fcd34d' opacity='0.9'/%3E%3Ccircle cx='80' cy='20' r='12' fill='%23701a75'/%3E%3Ccircle cx='20' cy='30' r='3' fill='%23fff' opacity='0.6'/%3E%3Ccircle cx='30' cy='15' r='2' fill='%23fff' opacity='0.4'/%3E%3Ccircle cx='60' cy='80' r='2' fill='%23fff' opacity='0.5'/%3E%3Cpath d='M10 90 Q30 70 50 90 Q70 70 90 90' stroke='%23fcd34d' fill='none' stroke-width='2' opacity='0.5'/%3E%3C/svg%3E" alt="">
                                                    </div>
                                                    <span class="template-label">চাঁদ-তারা ফ্রেম</span>
                                                    <i class="fas fa-check-circle template-check"></i>
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-eid-gold btn-lg w-100" id="createCardBtn">
                                            <i class="fas fa-magic me-2"></i>কার্ড তৈরি করুন
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Live Preview & Download -->
                        <div class="col-lg-7">
                            <div class="eid-card-box">
                                <div class="card-header-eid d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-eye"></i>
                                        <span>প্রিভিউ</span>
                                    </div>
                                    <button class="btn btn-sm btn-warning" id="downloadCardBtn" disabled>
                                        <i class="fas fa-download me-1"></i> ডাউনলোড
                                    </button>
                                </div>
                                <div class="card-body-eid p-3">
                                    <div id="cardPreviewContainer" class="card-preview-container">
                                        <div class="preview-placeholder">
                                            <i class="fas fa-image fa-4x text-muted mb-3"></i>
                                            <h5 class="text-muted">কার্ড প্রিভিউ</h5>
                                            <p class="text-muted small">বাম দিকে তথ্য দিন, প্রিভিউ এখানে দেখাবে</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @if(count($previousCards) > 0)
                            <div class="eid-card-box mt-4">
                                <div class="card-header-eid bg-secondary">
                                    <i class="fas fa-history"></i>
                                    <span>আপনার আগের কার্ড</span>
                                    <small class="ms-2 opacity-75">(ক্লিক করে দেখুন)</small>
                                </div>
                                <div class="card-body-eid p-0">
                                    <div class="previous-cards-list" id="previousCardsList">
                                        @foreach($previousCards as $card)
                                        <div class="prev-card-item load-prev-card" 
                                             data-id="{{ $card->id }}"
                                             data-name="{{ $card->name }}"
                                             data-designation="{{ $card->designation }}"
                                             data-message="{{ $card->custom_message }}"
                                             data-template="{{ $card->template }}"
                                             data-photo="{{ $card->photo ? asset('storage/' . $card->photo) : '' }}"
                                             style="cursor: pointer;">
                                            <div class="prev-card-thumb">
                                                @if($card->photo)
                                                    <img src="{{ asset('storage/' . $card->photo) }}" alt="">
                                                @else
                                                    <i class="fas fa-image"></i>
                                                @endif
                                            </div>
                                            <div class="prev-card-info flex-grow-1">
                                                <h6>{{ $card->name }}</h6>
                                                <small class="text-muted">{{ $card->created_at->format('d M, Y') }} • {{ ucfirst($card->template == 'template1' ? 'মসজিদ' : ($card->template == 'template2' ? 'লণ্ঠন' : 'চাঁদ-তারা')) }}</small>
                                            </div>
                                            <button class="btn btn-sm btn-outline-primary me-1 view-card-btn" title="প্রিভিউ">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-prev-card" data-id="{{ $card->id }}" title="মুছুন">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Card Templates -->
<div id="cardTemplates" style="position: absolute; left: -9999px; top: 0;">
    
    <!-- Template 1: Mosque Frame -->
    <div id="template1Card" class="eid-template" style="width: 600px; height: 600px; font-family: 'Hind Siliguri', Arial, sans-serif; position: relative; overflow: hidden;">
        <div style="width: 100%; height: 100%; background: linear-gradient(180deg, #064e3b 0%, #047857 50%, #059669 100%); position: relative;">
            
            <!-- Top Mosque Silhouette -->
            <svg style="position: absolute; top: 0; left: 0; width: 100%;" viewBox="0 0 600 80" preserveAspectRatio="none">
                <path d="M0 80 L0 55 L40 55 L40 42 L50 32 L60 42 L60 55 L90 55 L90 35 L105 22 L120 35 L120 55 L180 55 L180 40 L200 27 L220 40 L220 55 L250 55 L250 28 L270 14 L300 5 L330 14 L350 28 L350 55 L380 55 L380 40 L400 27 L420 40 L420 55 L480 55 L480 35 L495 22 L510 35 L510 55 L540 55 L540 42 L550 32 L560 42 L560 55 L600 55 L600 80 Z" fill="rgba(255,255,255,0.12)"/>
                <circle cx="540" cy="32" r="20" fill="#fcd34d"/>
                <circle cx="548" cy="28" r="16" fill="#064e3b"/>
                <circle cx="70" cy="20" r="2" fill="#fff" opacity="0.8"/>
                <circle cx="450" cy="18" r="2" fill="#fff" opacity="0.7"/>
            </svg>
            
            <!-- Main Content -->
            <div style="padding: 70px 35px 35px; text-align: center; height: 100%; display: flex; flex-direction: column; box-sizing: border-box;">
                
                <!-- Arabic Eid Text -->
                <p style="color: #fcd34d; font-size: 58px; margin: 0; font-family: 'Traditional Arabic', serif; direction: rtl; line-height: 1; text-shadow: 2px 2px 8px rgba(0,0,0,0.3);">عيد مبارك</p>
                
                <!-- Bengali Eid Text -->
                <p style="color: white; font-size: 26px; margin: 8px 0 15px; letter-spacing: 3px; font-weight: 600;">ঈদ মোবারক</p>
                
                <!-- Photo Frame -->
                <div style="margin: 5px auto;">
                    <div style="width: 150px; height: 150px; border-radius: 50%; border: 5px solid #fcd34d; overflow: hidden; margin: 0 auto; background: #fff; box-shadow: 0 8px 30px rgba(0,0,0,0.4);">
                        <img class="template-user-photo" src="" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                </div>
                
                <!-- Name & Designation -->
                <div style="margin: 12px 0 8px;">
                    <h2 class="template-user-name" style="color: white; font-size: 32px; margin: 0; font-weight: bold; text-shadow: 2px 2px 6px rgba(0,0,0,0.4);"></h2>
                    <p class="template-user-designation" style="color: #a7f3d0; font-size: 18px; margin: 6px 0 0;"></p>
                </div>
                
                <!-- Custom Message -->
                <p class="template-custom-message" style="color: #fef3c7; font-size: 20px; margin: 10px 25px; font-style: italic;"></p>
                
                <!-- Auto Greeting -->
                <div style="margin-top: auto;">
                    <p style="color: rgba(255,255,255,0.95); font-size: 16px; margin: 0; background: rgba(0,0,0,0.25); padding: 12px 22px; border-radius: 25px; display: inline-block;">
                        🌙 সবার জীবনে শান্তি ও সমৃদ্ধি বয়ে আনুক এই ঈদ ✨
                    </p>
                </div>
                
                <!-- Watermark Footer -->
                <div style="margin-top: 10px;">
                    <p style="color: rgba(255,255,255,0.4); font-size: 12px; margin: 0; letter-spacing: 1px;">exploresatkhira.com</p>
                </div>
            </div>
            
            <!-- Side Lanterns -->
            <svg style="position: absolute; bottom: 100px; left: 12px; width: 30px; height: 50px; opacity: 0.3;">
                <path d="M15 0 L15 8 M8 8 L22 8 L22 12 L20 16 L20 38 Q20 45 15 45 Q10 45 10 38 L10 16 L8 12 Z" fill="#fcd34d"/>
            </svg>
            <svg style="position: absolute; bottom: 120px; right: 15px; width: 25px; height: 40px; opacity: 0.25;">
                <path d="M12 0 L12 6 M7 6 L17 6 L17 9 L15 12 L15 30 Q15 35 12 35 Q9 35 9 30 L9 12 L7 9 Z" fill="#fcd34d"/>
            </svg>
        </div>
    </div>
    
    <!-- Template 2: Lantern Frame -->
    <div id="template2Card" class="eid-template" style="width: 600px; height: 600px; font-family: 'Hind Siliguri', Arial, sans-serif; position: relative; overflow: hidden;">
        <div style="width: 100%; height: 100%; background: linear-gradient(180deg, #1e3a5f 0%, #2563eb 60%, #3b82f6 100%); position: relative;">
            
            <!-- Hanging Lanterns Top -->
            <svg style="position: absolute; top: 0; left: 0; width: 100%; height: 100px;">
                <line x1="70" y1="0" x2="70" y2="18" stroke="#fcd34d" stroke-width="2"/>
                <path d="M60 18 L80 18 L80 23 L77 27 L77 58 Q77 68 70 68 Q63 68 63 58 L63 27 L60 23 Z" fill="#fcd34d" opacity="0.8"/>
                <ellipse cx="70" cy="46" rx="5" ry="8" fill="#fff" opacity="0.3"/>
                
                <line x1="180" y1="0" x2="180" y2="12" stroke="#fcd34d" stroke-width="2"/>
                <path d="M172 12 L188 12 L188 16 L186 19 L186 45 Q186 53 180 53 Q174 53 174 45 L174 19 L172 16 Z" fill="#fcd34d" opacity="0.7"/>
                
                <line x1="420" y1="0" x2="420" y2="15" stroke="#fcd34d" stroke-width="2"/>
                <path d="M410 15 L430 15 L430 20 L427 24 L427 52 Q427 62 420 62 Q413 62 413 52 L413 24 L410 20 Z" fill="#fcd34d" opacity="0.75"/>
                
                <line x1="530" y1="0" x2="530" y2="20" stroke="#fcd34d" stroke-width="2"/>
                <path d="M520 20 L540 20 L540 25 L537 29 L537 62 Q537 73 530 73 Q523 73 523 62 L523 29 L520 25 Z" fill="#fcd34d" opacity="0.8"/>
                
                <circle cx="125" cy="40" r="2" fill="#fff" opacity="0.6"/>
                <circle cx="300" cy="28" r="2.5" fill="#fff" opacity="0.7"/>
            </svg>
            
            <!-- Main Content -->
            <div style="padding: 90px 35px 35px; text-align: center; height: 100%; display: flex; flex-direction: column; box-sizing: border-box;">
                
                <!-- Eid Text -->
                <div style="margin-bottom: 10px;">
                    <p style="color: #fcd34d; font-size: 22px; margin: 0; letter-spacing: 4px; font-weight: 600;">✨ ঈদ মোবারক ✨</p>
                    <p style="color: white; font-size: 48px; margin: 8px 0; font-family: 'Traditional Arabic', serif; direction: rtl; text-shadow: 2px 2px 6px rgba(0,0,0,0.3);">عيد الفطر مبارك</p>
                </div>
                
                <!-- Photo Frame -->
                <div style="margin: 8px auto;">
                    <div style="width: 145px; height: 145px; border-radius: 20px; border: 5px solid white; overflow: hidden; margin: 0 auto; background: #fff; box-shadow: 0 10px 40px rgba(0,0,0,0.4);">
                        <img class="template-user-photo" src="" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                </div>
                
                <!-- Name & Designation -->
                <div style="margin: 12px 0 8px;">
                    <h2 class="template-user-name" style="color: white; font-size: 32px; margin: 0; font-weight: bold; text-shadow: 2px 2px 6px rgba(0,0,0,0.3);"></h2>
                    <p class="template-user-designation" style="color: #93c5fd; font-size: 18px; margin: 6px 0 0;"></p>
                </div>
                
                <!-- Custom Message -->
                <p class="template-custom-message" style="color: #dbeafe; font-size: 20px; margin: 10px 30px; font-style: italic;"></p>
                
                <!-- Auto Greeting -->
                <div style="margin-top: auto;">
                    <p style="color: #fcd34d; font-size: 16px; margin: 0; font-weight: 500;">
                        🕌 আল্লাহ আমাদের সবাইকে ক্ষমা করুন ও কবুল করুন 🤲
                    </p>
                </div>
                
                <!-- Watermark Footer -->
                <div style="margin-top: 10px;">
                    <p style="color: rgba(255,255,255,0.35); font-size: 12px; margin: 0; letter-spacing: 1px;">exploresatkhira.com</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Template 3: Moon & Stars -->
    <div id="template3Card" class="eid-template" style="width: 600px; height: 600px; font-family: 'Hind Siliguri', Arial, sans-serif; position: relative; overflow: hidden;">
        <div style="width: 100%; height: 100%; background: linear-gradient(180deg, #581c87 0%, #7c3aed 50%, #a855f7 100%); position: relative;">
            
            <!-- Crescent Moon -->
            <svg style="position: absolute; top: 12px; right: 20px; width: 90px; height: 90px;">
                <defs>
                    <linearGradient id="moonGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#fef3c7"/>
                        <stop offset="100%" style="stop-color:#fcd34d"/>
                    </linearGradient>
                </defs>
                <circle cx="45" cy="45" r="38" fill="url(#moonGrad)"/>
                <circle cx="56" cy="38" r="32" fill="#581c87"/>
            </svg>
            
            <!-- Stars scattered -->
            <svg style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none;">
                <path d="M42 40 L44 45 L49 45 L45 48 L46 53 L42 50 L38 53 L39 48 L35 45 L40 45 Z" fill="#fcd34d" opacity="0.9"/>
                <path d="M120 25 L121 28 L124 28 L122 30 L123 33 L120 31 L117 33 L118 30 L115 28 L118 28 Z" fill="#fff" opacity="0.8"/>
                <circle cx="22" cy="75" r="2" fill="#fff" opacity="0.6"/>
                <circle cx="150" cy="60" r="2" fill="#fff" opacity="0.5"/>
                <circle cx="540" cy="120" r="2" fill="#fff" opacity="0.6"/>
                <circle cx="40" cy="320" r="2" fill="#fff" opacity="0.5"/>
                <circle cx="555" cy="380" r="2" fill="#fff" opacity="0.6"/>
            </svg>
            
            <!-- Bottom Decorative Pattern -->
            <svg style="position: absolute; bottom: 0; left: 0; width: 100%; height: 50px;">
                <defs>
                    <pattern id="islamicPattern" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M20 0 L25 10 L40 10 L27 18 L32 30 L20 22 L8 30 L13 18 L0 10 L15 10 Z" fill="#fcd34d" opacity="0.12"/>
                    </pattern>
                </defs>
                <rect x="0" y="0" width="100%" height="50" fill="url(#islamicPattern)"/>
                <path d="M0 50 Q75 35 150 50 Q225 35 300 50 Q375 35 450 50 Q525 35 600 50" stroke="#fcd34d" stroke-width="2" fill="none" opacity="0.4"/>
            </svg>
            
            <!-- Main Content -->
            <div style="padding: 50px 35px 60px; text-align: center; height: 100%; display: flex; flex-direction: column; justify-content: center; box-sizing: border-box;">
                
                <!-- Eid Text -->
                <div style="margin-bottom: 12px;">
                    <p style="color: #fcd34d; font-size: 20px; margin: 0; letter-spacing: 3px; font-weight: 600;">🌟 ঈদুল ফিতরের শুভেচ্ছা 🌟</p>
                    <p style="color: white; font-size: 55px; margin: 10px 0; font-family: 'Traditional Arabic', serif; direction: rtl; text-shadow: 3px 3px 8px rgba(0,0,0,0.4);">عيد مبارك</p>
                </div>
                
                <!-- Photo Frame with glow -->
                <div style="margin: 8px auto;">
                    <div style="width: 150px; height: 150px; border-radius: 50%; border: 5px solid #fcd34d; overflow: hidden; margin: 0 auto; background: #fff; box-shadow: 0 0 30px rgba(252,211,77,0.5), 0 10px 35px rgba(0,0,0,0.4);">
                        <img class="template-user-photo" src="" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                </div>
                
                <!-- Name & Designation -->
                <div style="margin: 12px 0 8px;">
                    <h2 class="template-user-name" style="color: white; font-size: 32px; margin: 0; font-weight: bold; text-shadow: 2px 2px 6px rgba(0,0,0,0.4);"></h2>
                    <p class="template-user-designation" style="color: #e9d5ff; font-size: 18px; margin: 6px 0 0;"></p>
                </div>
                
                <!-- Custom Message -->
                <p class="template-custom-message" style="color: #fef3c7; font-size: 20px; margin: 10px 30px; font-style: italic;"></p>
                
                <!-- Auto Greeting -->
                <div style="margin-top: auto;">
                    <p style="color: rgba(255,255,255,0.95); font-size: 15px; margin: 0; background: rgba(255,255,255,0.12); padding: 12px 22px; border-radius: 25px; display: inline-block;">
                        ✨ ঈদের আনন্দ সবার মাঝে ছড়িয়ে পড়ুক ✨
                    </p>
                </div>
                
                <!-- Watermark Footer -->
                <div style="margin-top: 10px;">
                    <p style="color: rgba(255,255,255,0.35); font-size: 12px; margin: 0; letter-spacing: 1px;">exploresatkhira.com</p>
                </div>
            </div>
        </div>
    </div>
</div>
                
                <!-- Custom Message -->
                <p class="template-custom-message" style="color: #fef3c7; font-size: 20px; margin: 10px 30px; font-style: italic;"></p>
                
                <!-- Auto Greeting -->
                <div style="margin-top: auto;">
                    <p style="color: rgba(255,255,255,0.95); font-size: 15px; margin: 0; background: rgba(255,255,255,0.12); padding: 12px 22px; border-radius: 25px; display: inline-block;">
                        ✨ ঈদের আনন্দ সবার মাঝে ছড়িয়ে পড়ুক ✨
                    </p>
                </div>
                
                <!-- Watermark Footer -->
                <div style="margin-top: 10px;">
                    <p style="color: rgba(255,255,255,0.35); font-size: 12px; margin: 0; letter-spacing: 1px;">exploresatkhira.com</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.eid-card-page {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 50%, #fef3c7 100%);
    min-height: 100vh;
}

.eid-hero {
    background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #10b981 100%);
    padding: 80px 0 120px;
    position: relative;
    overflow: hidden;
}

.eid-icon { font-size: 2.5rem; }
.eid-icon .moon { animation: float 3s ease-in-out infinite; }
.eid-icon .star { animation: twinkle 2s ease-in-out infinite; margin: 0 15px; }
.eid-icon .mosque { animation: float 3s ease-in-out infinite 0.5s; }

@keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
@keyframes twinkle { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: 0.5; transform: scale(0.8); } }

.eid-card-box {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    overflow: hidden;
}

.card-header-eid {
    background: linear-gradient(135deg, #047857 0%, #064e3b 100%);
    color: white;
    padding: 18px 25px;
    font-size: 1.1rem;
    font-weight: 600;
}

.card-header-eid.bg-gradient-gold { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
.card-header-eid.bg-secondary { background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); }
.card-header-eid i { margin-right: 10px; }
.card-body-eid { padding: 25px; }

.welcome-card {
    background: white;
    border-radius: 15px;
    padding: 20px 25px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.welcome-emoji { font-size: 2rem; }

.photo-upload-box {
    border: 3px dashed #d1d5db;
    border-radius: 15px;
    padding: 30px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    min-height: 180px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.photo-upload-box:hover { border-color: #047857; background: #f0fdf4; }
.photo-upload-box img { max-width: 100%; max-height: 180px; border-radius: 10px; object-fit: cover; }

/* Template Grid */
.template-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}

.template-card {
    cursor: pointer;
    position: relative;
    border: 3px solid transparent;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s;
}

.template-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
.template-card.active { border-color: #fcd34d; box-shadow: 0 0 20px rgba(252,211,77,0.4); }

.template-thumb {
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.template-thumb img { width: 100%; height: 100%; object-fit: cover; }
.template-thumb.t1 { background: linear-gradient(135deg, #064e3b 0%, #10b981 100%); }
.template-thumb.t2 { background: linear-gradient(135deg, #1e3a5f 0%, #3b82f6 100%); }
.template-thumb.t3 { background: linear-gradient(135deg, #581c87 0%, #a855f7 100%); }

.template-label {
    display: block;
    text-align: center;
    padding: 8px;
    font-size: 12px;
    font-weight: 600;
    color: #374151;
    background: #f3f4f6;
}

.template-check {
    position: absolute;
    top: 5px;
    right: 5px;
    color: #fcd34d;
    font-size: 18px;
    display: none;
    text-shadow: 0 0 3px rgba(0,0,0,0.5);
}

.template-card.active .template-check { display: block; }

.btn-eid-gold {
    background: linear-gradient(135deg, #fcd34d 0%, #f59e0b 100%);
    color: #78350f;
    border: none;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-eid-gold:hover {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    transform: scale(1.02);
}

.card-preview-container {
    min-height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f9fafb;
    border-radius: 10px;
}

.preview-placeholder { text-align: center; padding: 40px; }

#generatedCardPreview {
    max-width: 100%;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.previous-cards-list { max-height: 300px; overflow-y: auto; }

.prev-card-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
    transition: background 0.2s;
}

.prev-card-item:hover { background: #f9fafb; }
.prev-card-item:last-child { border-bottom: none; }

.prev-card-thumb {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    overflow: hidden;
    background: #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
}

.prev-card-thumb img { width: 100%; height: 100%; object-fit: cover; }
.prev-card-thumb i { color: #9ca3af; }

.prev-card-info { flex: 1; }
.prev-card-info h6 { margin: 0; font-size: 14px; }

.form-control, .form-select {
    border-radius: 10px;
    border: 2px solid #e5e7eb;
    padding: 12px 15px;
}

.form-control:focus, .form-select:focus {
    border-color: #047857;
    box-shadow: 0 0 0 3px rgba(4, 120, 87, 0.1);
}

.btn.loading { position: relative; color: transparent !important; }

.btn.loading::after {
    content: '';
    position: absolute;
    width: 20px; height: 20px;
    top: 50%; left: 50%;
    margin: -10px 0 0 -10px;
    border: 3px solid rgba(255,255,255,0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin { to { transform: rotate(360deg); } }

@media (max-width: 768px) {
    .eid-hero { padding: 60px 0 80px; }
    .template-grid { grid-template-columns: repeat(3, 1fr); gap: 8px; }
    .template-thumb { height: 60px; }
    .template-label { font-size: 10px; padding: 6px; }
}
</style>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const userInfoForm = document.getElementById('userInfoForm');
    const createCardForm = document.getElementById('createCardForm');
    const photoInput = document.getElementById('photoInput');
    const photoUploadBox = document.getElementById('photoUploadBox');
    const photoPreview = document.getElementById('photoPreview');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const cardPreviewContainer = document.getElementById('cardPreviewContainer');
    const downloadCardBtn = document.getElementById('downloadCardBtn');
    
    let selectedTemplate = 'template1';
    let currentPhotoDataUrl = null;
    
    photoUploadBox.addEventListener('click', () => photoInput.click());
    
    photoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                currentPhotoDataUrl = e.target.result;
                photoPreview.src = currentPhotoDataUrl;
                photoPreview.classList.remove('d-none');
                uploadPlaceholder.classList.add('d-none');
                updatePreview();
            };
            reader.readAsDataURL(file);
        }
    });
    
    document.querySelectorAll('.template-card').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('.template-card').forEach(o => o.classList.remove('active'));
            this.classList.add('active');
            this.querySelector('input').checked = true;
            selectedTemplate = this.dataset.template;
            updatePreview();
        });
    });
    
    ['cardName', 'cardDesignation', 'customMessage'].forEach(id => {
        document.getElementById(id)?.addEventListener('input', updatePreview);
    });
    
    function updatePreview() {
        const name = document.getElementById('cardName').value || 'আপনার নাম';
        const designation = document.getElementById('cardDesignation').value || '';
        const customMessage = document.getElementById('customMessage').value || '';
        
        if (!currentPhotoDataUrl) return;
        
        const templateCard = document.getElementById(selectedTemplate + 'Card');
        if (!templateCard) return;
        
        const clone = templateCard.cloneNode(true);
        clone.id = '';
        
        clone.querySelector('.template-user-photo').src = currentPhotoDataUrl;
        clone.querySelector('.template-user-name').textContent = name;
        
        const designationEl = clone.querySelector('.template-user-designation');
        if (designationEl) {
            designationEl.textContent = designation;
            designationEl.style.display = designation ? 'block' : 'none';
        }
        
        const messageEl = clone.querySelector('.template-custom-message');
        if (messageEl) {
            messageEl.textContent = customMessage;
            messageEl.style.display = customMessage ? 'block' : 'none';
        }
        
        cardPreviewContainer.innerHTML = '';
        cardPreviewContainer.appendChild(clone);
        clone.style.position = 'relative';
        clone.style.left = 'auto';
        clone.style.transform = 'scale(0.6)';
        clone.style.transformOrigin = 'top center';
        
        downloadCardBtn.disabled = false;
    }
    
    downloadCardBtn.addEventListener('click', function() {
        const templateCard = document.getElementById(selectedTemplate + 'Card');
        if (!templateCard || !currentPhotoDataUrl) return;
        
        const clone = templateCard.cloneNode(true);
        clone.id = 'tempCardForDownload';
        clone.style.position = 'absolute';
        clone.style.left = '-9999px';
        clone.style.top = '0';
        document.body.appendChild(clone);
        
        clone.querySelector('.template-user-photo').src = currentPhotoDataUrl;
        clone.querySelector('.template-user-name').textContent = document.getElementById('cardName').value || 'আপনার নাম';
        
        const designation = document.getElementById('cardDesignation').value;
        const designationEl = clone.querySelector('.template-user-designation');
        if (designationEl) {
            designationEl.textContent = designation;
            designationEl.style.display = designation ? 'block' : 'none';
        }
        
        const customMessage = document.getElementById('customMessage').value;
        const messageEl = clone.querySelector('.template-custom-message');
        if (messageEl) {
            messageEl.textContent = customMessage;
            messageEl.style.display = customMessage ? 'block' : 'none';
        }
        
        this.classList.add('loading');
        this.disabled = true;
        
        setTimeout(() => {
            html2canvas(clone, {
                scale: 2,
                useCORS: true,
                allowTaint: true,
                backgroundColor: null,
                width: 600,
                height: 600
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'eid-card-' + Date.now() + '.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
                
                clone.remove();
                this.classList.remove('loading');
                this.disabled = false;
            }).catch(err => {
                console.error('Error:', err);
                clone.remove();
                this.classList.remove('loading');
                this.disabled = false;
                alert('কার্ড ডাউনলোড করতে সমস্যা হয়েছে। আবার চেষ্টা করুন।');
            });
        }, 100);
    }.bind(downloadCardBtn));
    
    // User Info Form Submit
    userInfoForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const btn = this.querySelector('button[type="submit"]');
        btn.classList.add('loading');
        btn.disabled = true;
        
        fetch('{{ route("eid-card.start-session") }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('userInfoCard').classList.add('d-none');
                document.getElementById('cardMakerSection').classList.remove('d-none');
                document.getElementById('welcomeName').textContent = data.name || 'অতিথি';
                document.getElementById('cardName').value = data.name || '';
                if (data.has_previous_data) {
                    location.reload(); // Reload to show previous cards
                }
            } else {
                alert(data.message || 'কিছু সমস্যা হয়েছে');
            }
        })
        .catch(() => alert('সার্ভার এরর'))
        .finally(() => {
            btn.classList.remove('loading');
            btn.disabled = false;
        });
    });
    
    // Create Card Form Submit
    createCardForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const btn = document.getElementById('createCardBtn');
        btn.classList.add('loading');
        btn.disabled = true;
        
        fetch('{{ route("eid-card.create") }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Card saved
            } else {
                alert(data.message || 'কিছু সমস্যা হয়েছে');
            }
        })
        .catch(() => {})
        .finally(() => {
            btn.classList.remove('loading');
            btn.disabled = false;
        });
    });
    
    // Logout
    document.getElementById('logoutBtn')?.addEventListener('click', function() {
        if (confirm('লগআউট করতে চান?')) {
            fetch('{{ route("eid-card.reset") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(() => location.reload());
        }
    });
    
    // Load previous card into preview
    document.querySelectorAll('.load-prev-card').forEach(item => {
        item.addEventListener('click', function(e) {
            // Don't trigger if clicking on delete button
            if (e.target.closest('.delete-prev-card')) return;
            
            const name = this.dataset.name || '';
            const designation = this.dataset.designation || '';
            const message = this.dataset.message || '';
            const template = this.dataset.template || 'template1';
            const photo = this.dataset.photo;
            
            if (!photo) {
                alert('এই কার্ডের ছবি পাওয়া যায়নি।');
                return;
            }
            
            // Fill form fields
            document.getElementById('cardName').value = name;
            document.getElementById('cardDesignation').value = designation;
            document.getElementById('customMessage').value = message;
            
            // Select template
            document.querySelectorAll('.template-card').forEach(t => t.classList.remove('active'));
            const templateCard = document.querySelector(`.template-card[data-template="${template}"]`);
            if (templateCard) {
                templateCard.classList.add('active');
                templateCard.querySelector('input').checked = true;
                selectedTemplate = template;
            }
            
            // Load photo
            currentPhotoDataUrl = photo;
            photoPreview.src = photo;
            photoPreview.classList.remove('d-none');
            uploadPlaceholder.classList.add('d-none');
            
            // Update preview
            updatePreview();
            
            // Highlight selected card
            document.querySelectorAll('.prev-card-item').forEach(c => c.classList.remove('border-primary'));
            this.classList.add('border-primary');
            this.style.borderLeft = '4px solid #047857';
            
            // Scroll to preview
            cardPreviewContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    });
    
    // Delete previous card
    document.querySelectorAll('.delete-prev-card').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent triggering load
            if (confirm('এই কার্ডটি মুছে ফেলতে চান?')) {
                const cardId = this.dataset.id;
                fetch('{{ url("eid-card/card") }}/' + cardId, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.closest('.prev-card-item').remove();
                    }
                });
            }
        });
    });
});
</script>
@endpush
