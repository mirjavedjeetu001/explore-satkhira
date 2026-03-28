@extends('frontend.layouts.app')

@section('title', 'অ্যাপ ডাউনলোড করুন - Explore Satkhira')

@section('content')
<div class="app-download-page">
    <!-- Hero Section -->
    <section class="download-hero">
        <div class="container">
            <div class="row align-items-center min-vh-75">
                <div class="col-lg-6 text-center text-lg-start mb-5 mb-lg-0">
                    <div class="download-badge mb-3" data-aos="fade-up">
                        <span><i class="fas fa-shield-alt me-1"></i> Trusted & Verified App</span>
                    </div>
                    <h1 class="download-title" data-aos="fade-up" data-aos-delay="100">
                        এক্সপ্লোর সাতক্ষীরা
                        <span class="d-block text-accent">অ্যাপ ডাউনলোড করুন</span>
                    </h1>
                    <p class="download-subtitle" data-aos="fade-up" data-aos-delay="200">
                        সাতক্ষীরা জেলার সকল তথ্য এক জায়গায়। হোম টিউটর, টু-লেট, রেস্টুরেন্ট, হাসপাতাল, স্কুল, তেলের আপডেট এবং আরও অনেক কিছু আপনার হাতের মুঠোয়।
                    </p>
                    
                    <div class="download-stats mb-4" data-aos="fade-up" data-aos-delay="250">
                        <div class="stat-item">
                            <i class="fas fa-download"></i>
                            <div>
                                <strong id="downloadCount">{{ number_format($downloadCount) }}+</strong>
                                <small>ডাউনলোড</small>
                            </div>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-star"></i>
                            <div>
                                <strong>4.8</strong>
                                <small>রেটিং</small>
                            </div>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-weight-hanging"></i>
                            <div>
                                <strong>~1 MB</strong>
                                <small>সাইজ</small>
                            </div>
                        </div>
                    </div>

                    <div class="download-buttons" data-aos="fade-up" data-aos-delay="300">
                        <a href="{{ route('app.download.file') }}" class="btn-download-main" id="downloadBtn">
                            <i class="fab fa-android me-2"></i>
                            <div>
                                <small>ফ্রি ডাউনলোড</small>
                                <strong>Android APK</strong>
                            </div>
                        </a>
                        <a href="#" class="btn-download-playstore disabled-link" title="শীঘ্রই আসছে">
                            <i class="fab fa-google-play me-2"></i>
                            <div>
                                <small>শীঘ্রই আসছে</small>
                                <strong>Google Play</strong>
                            </div>
                        </a>
                    </div>

                    <div class="trust-badges mt-4" data-aos="fade-up" data-aos-delay="350">
                        <span><i class="fas fa-check-circle text-success me-1"></i> ভাইরাস মুক্ত</span>
                        <span><i class="fas fa-lock text-success me-1"></i> নিরাপদ</span>
                        <span><i class="fas fa-certificate text-success me-1"></i> ডিজিটাল সাইনড</span>
                    </div>
                </div>
                <div class="col-lg-6 text-center" data-aos="fade-left" data-aos-delay="200">
                    <div class="phone-mockup">
                        <div class="phone-frame">
                            <div class="phone-screen">
                                <img src="{{ asset('icons/icon-512x512.png') }}" alt="Explore Satkhira App" class="app-icon-large">
                                <h3>Explore Satkhira</h3>
                                <div class="phone-features">
                                    <div class="phone-feature-item">
                                        <i class="fas fa-gas-pump"></i>
                                        <span>তেলের দাম</span>
                                    </div>
                                    <div class="phone-feature-item">
                                        <i class="fas fa-home"></i>
                                        <span>টু-লেট</span>
                                    </div>
                                    <div class="phone-feature-item">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                        <span>টিউটর</span>
                                    </div>
                                    <div class="phone-feature-item">
                                        <i class="fas fa-utensils"></i>
                                        <span>রেস্টুরেন্ট</span>
                                    </div>
                                    <div class="phone-feature-item">
                                        <i class="fas fa-hospital"></i>
                                        <span>হাসপাতাল</span>
                                    </div>
                                    <div class="phone-feature-item">
                                        <i class="fas fa-bell"></i>
                                        <span>নোটিফিকেশন</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="download-features py-5">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">কেন এই অ্যাপ ব্যবহার করবেন?</h2>
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-bolt"></i></div>
                        <h5>দ্রুত ও হালকা</h5>
                        <p>মাত্র ১ MB সাইজের অ্যাপ, যেকোনো ফোনে দ্রুত চলে</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-bell"></i></div>
                        <h5>পুশ নোটিফিকেশন</h5>
                        <p>নতুন আপডেট সরাসরি আপনার ফোনে পেয়ে যান</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-wifi"></i></div>
                        <h5>অফলাইন সাপোর্ট</h5>
                        <p>ইন্টারনেট ছাড়াও আগের দেখা পেজ দেখতে পারবেন</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Install Guide -->
    <section class="install-guide py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">কিভাবে ইনস্টল করবেন?</h2>
            <div class="row g-4">
                <div class="col-md-3 text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="step-circle">১</div>
                    <h6 class="mt-3">ডাউনলোড করুন</h6>
                    <p class="text-muted small">উপরের বাটনে ক্লিক করে APK ডাউনলোড করুন</p>
                </div>
                <div class="col-md-3 text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="step-circle">২</div>
                    <h6 class="mt-3">অনুমতি দিন</h6>
                    <p class="text-muted small">"Unknown sources" বা "Install unknown apps" অনুমতি দিন</p>
                </div>
                <div class="col-md-3 text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="step-circle">৩</div>
                    <h6 class="mt-3">ইনস্টল করুন</h6>
                    <p class="text-muted small">ডাউনলোড করা ফাইলে ক্লিক করে Install বাটন চাপুন</p>
                </div>
                <div class="col-md-3 text-center" data-aos="fade-up" data-aos-delay="400">
                    <div class="step-circle">৪</div>
                    <h6 class="mt-3">ব্যবহার শুরু!</h6>
                    <p class="text-muted small">অ্যাপ খুলুন এবং সাতক্ষীরা সম্পর্কে সব তথ্য পান</p>
                </div>
            </div>

            <div class="text-center mt-4" data-aos="fade-up">
                <div class="alert alert-success d-inline-flex align-items-center">
                    <i class="fas fa-shield-alt me-2 fs-5"></i>
                    <div class="text-start">
                        <strong>এটি একটি বিশ্বস্ত অ্যাপ।</strong> এই APK ডিজিটাল সাইন করা এবং ভাইরাসমুক্ত। আপনার ডিভাইস সম্পূর্ণ নিরাপদ থাকবে। Google Play Protect এই অ্যাপকে verify করবে।
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.app-download-page {
    overflow-x: hidden;
}
.min-vh-75 {
    min-height: 75vh;
}
.download-hero {
    background: linear-gradient(135deg, #1a3c34 0%, #28a745 50%, #1a5f2a 100%);
    color: white;
    padding: 80px 0 60px;
    position: relative;
    overflow: hidden;
}
.download-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 60%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
    pointer-events: none;
}
.download-badge span {
    background: rgba(255,193,7,0.2);
    color: #ffc107;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    border: 1px solid rgba(255,193,7,0.3);
}
.download-title {
    font-size: 2.5rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 1rem;
}
.text-accent {
    color: #ffc107;
}
.download-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    line-height: 1.7;
}
.download-stats {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
}
.stat-item {
    display: flex;
    align-items: center;
    gap: 10px;
}
.stat-item i {
    font-size: 1.5rem;
    color: #ffc107;
}
.stat-item strong {
    display: block;
    font-size: 1.2rem;
}
.stat-item small {
    color: rgba(255,255,255,0.7);
    font-size: 0.8rem;
}
.download-buttons {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}
.btn-download-main {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #ffc107;
    color: #1a3c34;
    padding: 14px 28px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(255,193,7,0.3);
}
.btn-download-main:hover {
    background: #ffca28;
    color: #1a3c34;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255,193,7,0.4);
}
.btn-download-main i {
    font-size: 1.8rem;
}
.btn-download-main small {
    display: block;
    font-size: 0.7rem;
    opacity: 0.8;
}
.btn-download-main strong {
    font-size: 1.1rem;
}
.btn-download-playstore {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,0.15);
    color: white;
    padding: 14px 28px;
    border-radius: 12px;
    text-decoration: none;
    border: 1px solid rgba(255,255,255,0.2);
    transition: all 0.3s;
}
.btn-download-playstore.disabled-link {
    opacity: 0.6;
    cursor: not-allowed;
    pointer-events: none;
}
.btn-download-playstore i {
    font-size: 1.8rem;
}
.btn-download-playstore small {
    display: block;
    font-size: 0.7rem;
    opacity: 0.7;
}
.btn-download-playstore strong {
    font-size: 1.1rem;
}
.trust-badges {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    font-size: 0.85rem;
    opacity: 0.9;
}

/* Phone Mockup */
.phone-mockup {
    display: flex;
    justify-content: center;
}
.phone-frame {
    width: 280px;
    height: 500px;
    background: #1a1a2e;
    border-radius: 36px;
    padding: 12px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    position: relative;
    border: 3px solid #333;
}
.phone-frame::before {
    content: '';
    position: absolute;
    top: 8px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 6px;
    background: #333;
    border-radius: 3px;
}
.phone-screen {
    width: 100%;
    height: 100%;
    background: linear-gradient(180deg, #28a745 0%, #1a5f2a 100%);
    border-radius: 26px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 30px 20px;
}
.app-icon-large {
    width: 90px;
    height: 90px;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    margin-bottom: 15px;
}
.phone-screen h3 {
    color: white;
    font-size: 1.1rem;
    margin-bottom: 25px;
    font-weight: 700;
}
.phone-features {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 15px;
    width: 100%;
}
.phone-feature-item {
    text-align: center;
    color: white;
}
.phone-feature-item i {
    display: block;
    font-size: 1.3rem;
    margin-bottom: 5px;
    color: #ffc107;
}
.phone-feature-item span {
    font-size: 0.65rem;
    opacity: 0.9;
}

/* Features */
.feature-card {
    background: white;
    border-radius: 16px;
    padding: 30px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: transform 0.3s;
    height: 100%;
}
.feature-card:hover {
    transform: translateY(-5px);
}
.feature-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #28a745, #1a5f2a);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    color: white;
    font-size: 1.5rem;
}
.feature-card h5 {
    font-weight: 700;
    margin-bottom: 10px;
}
.feature-card p {
    color: #666;
    font-size: 0.9rem;
    margin: 0;
}

/* Install Steps */
.step-circle {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #28a745, #1a5f2a);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    color: white;
    font-size: 1.3rem;
    font-weight: 700;
}

@media (max-width: 768px) {
    .download-hero {
        padding: 40px 0;
    }
    .download-title {
        font-size: 1.8rem;
    }
    .download-stats {
        gap: 15px;
    }
    .phone-frame {
        width: 220px;
        height: 400px;
    }
    .app-icon-large {
        width: 60px;
        height: 60px;
    }
    .phone-features {
        gap: 10px;
    }
    .download-buttons {
        justify-content: center;
    }
}
</style>
@endsection
