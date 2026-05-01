@extends('frontend.layouts.app')

{{-- Title not set here so homepage shows only site name in browser tab --}}

@section('structured_data')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "WebSite",
    "name": "এক্সপ্লোর সাতক্ষীরা - Explore Satkhira",
    "alternateName": "Explore Satkhira",
    "url": "{{ url('/') }}",
    "description": "সাতক্ষীরা জেলার সকল তথ্য এক জায়গায়। হোম টিউটর, টু-লেট, রেস্টুরেন্ট, হাসপাতাল, স্কুল, কলেজ, ডাক্তার, ফার্মেসি, ব্যাংক, সরকারি অফিস, পর্যটন স্পট খুঁজুন।",
    "inLanguage": ["bn", "en"],
    "potentialAction": {
        "@@type": "SearchAction",
        "target": "{{ url('/listings') }}?search={search_term_string}",
        "query-input": "required name=search_term_string"
    }
}
</script>
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Organization",
    "name": "এক্সপ্লোর সাতক্ষীরা",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('icons/icon-512x512.png') }}",
    "sameAs": [],
    "contactPoint": {
        "@@type": "ContactPoint",
        "contactType": "customer service",
        "areaServed": "BD",
        "availableLanguage": ["Bengali", "English"]
    },
    "address": {
        "@@type": "PostalAddress",
        "addressLocality": "Satkhira",
        "addressRegion": "Khulna",
        "addressCountry": "BD",
        "postalCode": "9400"
    }
}
</script>
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [{
        "@@type": "ListItem",
        "position": 1,
        "name": "হোম",
        "item": "{{ url('/') }}"
    }]
}
</script>
@endsection

@section('content')
    <!-- Hero Slider -->
    <section class="hero-slider">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @forelse($sliders as $index => $slider)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" style="background-image: linear-gradient(rgba(0,0,0,0.35), rgba(0,0,0,0.25)), url('{{ $slider->image ? asset('storage/' . $slider->image) . '?v=' . $slider->updated_at->timestamp : 'https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?w=1920' }}')">
                        <div class="carousel-caption">
                            <h2 data-aos="fade-up">{{ app()->getLocale() == 'bn' ? ($slider->title_bn ?? $slider->title ?? __('messages.hero_title')) : ($slider->title ?? __('messages.hero_title')) }}</h2>
                            <p data-aos="fade-up" data-aos-delay="100">{{ $slider->subtitle ?? __('messages.hero_subtitle') }}</p>
                            @if($slider->link)
                                <a href="{{ $slider->link }}" class="btn btn-primary-custom btn-lg mt-3" data-aos="fade-up" data-aos-delay="200">
                                    {{ $slider->button_text ?? __('messages.view_details') }} <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="carousel-item active" style="background-image: linear-gradient(rgba(0,0,0,0.35), rgba(0,0,0,0.25)), url('https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?w=1920')">
                        <div class="carousel-caption">
                            <h2>{{ __('messages.hero_title') }}</h2>
                            <p>{{ __('messages.hero_subtitle') }}</p>
                            <a href="{{ route('upazilas.index') }}" class="btn btn-primary-custom btn-lg mt-3">
                                {{ __('messages.view_all') }} <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                @endforelse
                <!-- App Download Promo Slide -->
                <div class="carousel-item" style="background-image: linear-gradient(135deg, rgba(16,65,40,0.92), rgba(26,107,60,0.88)), url('/icons/icon-512x512.png'); background-size: cover; background-position: center;">
                    <div class="carousel-caption">
                        <div class="d-flex align-items-center justify-content-center mb-3" data-aos="fade-up">
                            <img src="/icons/app-logo-96.png" alt="App Icon" style="width: 64px; height: 64px; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);" class="me-3">
                            <h2 class="mb-0" style="font-size: 1.8rem;">📱 আমাদের অ্যাপ ডাউনলোড করুন!</h2>
                        </div>
                        <p data-aos="fade-up" data-aos-delay="100">এক্সপ্লোর সাতক্ষীরা অ্যাপ দিয়ে সবকিছু হাতের মুঠোয়। জ্বালানি আপডেট, লিস্টিং, সংবাদ — সব এক জায়গায়!</p>
                        <a href="{{ route('app.download') }}" class="btn btn-warning btn-lg mt-3" data-aos="fade-up" data-aos-delay="200" style="font-weight: 700;">
                            <i class="fas fa-download me-2"></i>এখনই ডাউনলোড করুন
                        </a>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </section>

    <!-- Search Box -->
    <div class="container">
        <div class="search-box" data-aos="fade-up">
            <form action="{{ route('listings.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <select name="upazila" class="form-select form-select-lg">
                            <option value="">{{ __('messages.select_upazila') }}</option>
                            @foreach($upazilas as $upazila)
                                <option value="{{ $upazila->id }}">{{ app()->getLocale() == 'bn' ? ($upazila->name_bn ?? $upazila->name) : $upazila->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="category" class="form-select form-select-lg">
                            <option value="">{{ __('messages.select_category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ app()->getLocale() == 'bn' ? ($category->name_bn ?? $category->name) : $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control form-control-lg" placeholder="{{ __('messages.search_placeholder') }}">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Ramadan Sehri Iftar Section - Auto hidden after Ramadan -->
    @php
        // Ramadan 2026: February 19 - March 20
        $ramadanStart = '2026-02-19';
        $ramadanEnd = '2026-03-20';
        $today = date('Y-m-d');
        $isRamadan = ($today >= $ramadanStart && $today <= $ramadanEnd);
        
        // Sehri & Iftar times for Satkhira - Ramadan 2026
        $ramadanSchedule = [
            '2026-02-19' => ['day' => 1, 'sehri' => '5:14', 'iftar' => '6:06', 'bar' => 'বৃহস্পতি'],
            '2026-02-20' => ['day' => 2, 'sehri' => '5:13', 'iftar' => '6:06', 'bar' => 'শুক্র'],
            '2026-02-21' => ['day' => 3, 'sehri' => '5:12', 'iftar' => '6:07', 'bar' => 'শনি'],
            '2026-02-22' => ['day' => 4, 'sehri' => '5:11', 'iftar' => '6:07', 'bar' => 'রবি'],
            '2026-02-23' => ['day' => 5, 'sehri' => '5:10', 'iftar' => '6:08', 'bar' => 'সোম'],
            '2026-02-24' => ['day' => 6, 'sehri' => '5:10', 'iftar' => '6:08', 'bar' => 'মঙ্গল'],
            '2026-02-25' => ['day' => 7, 'sehri' => '5:09', 'iftar' => '6:09', 'bar' => 'বুধ'],
            '2026-02-26' => ['day' => 8, 'sehri' => '5:08', 'iftar' => '6:09', 'bar' => 'বৃহস্পতি'],
            '2026-02-27' => ['day' => 9, 'sehri' => '5:08', 'iftar' => '6:10', 'bar' => 'শুক্র'],
            '2026-02-28' => ['day' => 10, 'sehri' => '5:07', 'iftar' => '6:10', 'bar' => 'শনি'],
            '2026-03-01' => ['day' => 11, 'sehri' => '5:06', 'iftar' => '6:11', 'bar' => 'রবি'],
            '2026-03-02' => ['day' => 12, 'sehri' => '5:06', 'iftar' => '6:11', 'bar' => 'সোম'],
            '2026-03-03' => ['day' => 13, 'sehri' => '5:05', 'iftar' => '6:11', 'bar' => 'মঙ্গল'],
            '2026-03-04' => ['day' => 14, 'sehri' => '5:04', 'iftar' => '6:12', 'bar' => 'বুধ'],
            '2026-03-05' => ['day' => 15, 'sehri' => '5:03', 'iftar' => '6:12', 'bar' => 'বৃহস্পতি'],
            '2026-03-06' => ['day' => 16, 'sehri' => '5:02', 'iftar' => '6:13', 'bar' => 'শুক্র'],
            '2026-03-07' => ['day' => 17, 'sehri' => '5:02', 'iftar' => '6:13', 'bar' => 'শনি'],
            '2026-03-08' => ['day' => 18, 'sehri' => '5:01', 'iftar' => '6:14', 'bar' => 'রবি'],
            '2026-03-09' => ['day' => 19, 'sehri' => '5:00', 'iftar' => '6:14', 'bar' => 'সোম'],
            '2026-03-10' => ['day' => 20, 'sehri' => '4:59', 'iftar' => '6:15', 'bar' => 'মঙ্গল'],
            '2026-03-11' => ['day' => 21, 'sehri' => '4:58', 'iftar' => '6:15', 'bar' => 'বুধ'],
            '2026-03-12' => ['day' => 22, 'sehri' => '4:57', 'iftar' => '6:16', 'bar' => 'বৃহস্পতি'],
            '2026-03-13' => ['day' => 23, 'sehri' => '4:56', 'iftar' => '6:16', 'bar' => 'শুক্র'],
            '2026-03-14' => ['day' => 24, 'sehri' => '4:55', 'iftar' => '6:17', 'bar' => 'শনি'],
            '2026-03-15' => ['day' => 25, 'sehri' => '4:54', 'iftar' => '6:17', 'bar' => 'রবি'],
            '2026-03-16' => ['day' => 26, 'sehri' => '4:53', 'iftar' => '6:18', 'bar' => 'সোম'],
            '2026-03-17' => ['day' => 27, 'sehri' => '4:52', 'iftar' => '6:18', 'bar' => 'মঙ্গল'],
            '2026-03-18' => ['day' => 28, 'sehri' => '4:51', 'iftar' => '6:19', 'bar' => 'বুধ'],
            '2026-03-19' => ['day' => 29, 'sehri' => '4:50', 'iftar' => '6:19', 'bar' => 'বৃহস্পতি'],
            '2026-03-20' => ['day' => 30, 'sehri' => '4:49', 'iftar' => '6:20', 'bar' => 'শুক্র'],
        ];
        
        $todaySchedule = $ramadanSchedule[$today] ?? null;
    @endphp
    
    @if($isRamadan && $todaySchedule)
    <section class="ramadan-section py-4">
        <div class="container">
            <div class="ramadan-banner" data-aos="fade-up">
                <div class="ramadan-bg-pattern"></div>
                <div class="row align-items-center">
                    <div class="col-lg-4 text-center text-lg-start mb-3 mb-lg-0">
                        <div class="ramadan-title">
                            <span class="ramadan-arabic">رمضان مبارك</span>
                            <h3 class="mb-1">রমজান মোবারক</h3>
                            <p class="mb-0 ramadan-day">{{ $todaySchedule['day'] }} রমজান, ১৪৪৭ হিজরী</p>
                            <div class="current-time-display mt-2">
                                <i class="fas fa-clock me-1"></i>
                                <span>এখন সময়: </span>
                                <span id="currentTime" class="current-time-value">--:--:--</span>
                            </div>
                            <small class="location-note d-block mt-2"><i class="fas fa-map-marker-alt me-1"></i>সাতক্ষীরা ও পার্শ্ববর্তী এলাকার জন্য</small>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="time-card sehri-card">
                                    <div class="time-icon">
                                        <i class="fas fa-moon"></i>
                                    </div>
                                    <div class="time-info">
                                        <span class="time-label">সেহরির শেষ সময়</span>
                                        <span class="time-value" id="sehriTime">{{ $todaySchedule['sehri'] }} AM</span>
                                        <span class="countdown-label" id="sehriCountdown"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="time-card iftar-card">
                                    <div class="time-icon">
                                        <i class="fas fa-sun"></i>
                                    </div>
                                    <div class="time-info">
                                        <span class="time-label">ইফতারের সময়</span>
                                        <span class="time-value" id="iftarTime">{{ $todaySchedule['iftar'] }} PM</span>
                                        <span class="countdown-label" id="iftarCountdown"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 text-center text-lg-end mt-3 mt-lg-0">
                        <div class="d-flex flex-column flex-lg-row gap-2 justify-content-center justify-content-lg-end flex-wrap">
                            <button type="button" class="btn btn-ramadan" data-bs-toggle="modal" data-bs-target="#ramadanScheduleModal">
                                <i class="fas fa-calendar-alt me-2"></i>পুরা মাসের সময়সূচী
                            </button>
                            <button type="button" class="btn btn-ramadan-share" onclick="generateShareImage()">
                                <i class="fas fa-download me-2"></i>শেয়ার করুন
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Hidden canvas for image generation -->
    <canvas id="shareCanvas" style="display: none;" width="800" height="550"></canvas>

    <!-- Ramadan Full Schedule Modal -->
    <div class="modal fade" id="ramadanScheduleModal" tabindex="-1" aria-labelledby="ramadanScheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content ramadan-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="ramadanScheduleModalLabel">
                        <i class="fas fa-moon me-2"></i>রমজান ১৪৪৭ - সেহরি ও ইফতার
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="ramadan-today-highlight mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center">
                                <div class="today-badge">আজকের সময়সূচী</div>
                                <h4 class="mb-0">{{ $todaySchedule['day'] }} রমজান</h4>
                                <small>{{ $todaySchedule['bar'] }}, {{ date('d F Y') }}</small>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="today-time">
                                    <i class="fas fa-moon"></i>
                                    <span>সেহরি: <strong>{{ $todaySchedule['sehri'] }} AM</strong></span>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="today-time">
                                    <i class="fas fa-sun"></i>
                                    <span>ইফতার: <strong>{{ $todaySchedule['iftar'] }} PM</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-ramadan">
                            <thead>
                                <tr>
                                    <th>রমজান</th>
                                    <th>তারিখ</th>
                                    <th>বার</th>
                                    <th>সেহরি</th>
                                    <th>ইফতার</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ramadanSchedule as $date => $schedule)
                                    <tr data-ramadan-day="{{ $schedule['day'] }}" data-date="{{ $date }}">
                                        <td><strong>{{ $schedule['day'] }}</strong></td>
                                        <td>{{ date('d M', strtotime($date)) }}</td>
                                        <td>{{ $schedule['bar'] }}</td>
                                        <td><i class="fas fa-moon text-primary me-1"></i>{{ $schedule['sehri'] }}</td>
                                        <td><i class="fas fa-sun text-warning me-1"></i>{{ $schedule['iftar'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="ramadan-dua mt-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="dua-card">
                                    <h6><i class="fas fa-hands-praying me-2"></i>রোজার নিয়ত</h6>
                                    <p class="arabic-text">نَوَيْتُ اَنْ اَصُوْمَ غَدًا مِنْ شَهْرِ رَمَضَانَ الْمُبٰرَكِ فَرْضًا لَكَ يَا اَللهُ فَتَقَبَّلْ مِنِّىْ اِنَّكَ اَنْتَ السَّمِيْعُ الْعَلِيْم</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="dua-card">
                                    <h6><i class="fas fa-utensils me-2"></i>ইফতারের দোয়া</h6>
                                    <p class="arabic-text">اَللّٰهُمَّ لَكَ صُمْتُ وَعَلٰى رِزْقِكَ اَفْطَرْتُ</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <small class="text-muted">সাতক্ষীরা ও পার্শ্ববর্তী এলাকার জন্য প্রযোজ্য</small>
                </div>
            </div>
        </div>
    </div>

    <style>
    /* Ramadan Section Styles */
    .ramadan-section {
        background: linear-gradient(135deg, #1a472a 0%, #2d5016 50%, #1a472a 100%);
        position: relative;
        overflow: hidden;
    }
    
    .ramadan-banner {
        background: linear-gradient(135deg, rgba(26, 71, 42, 0.9), rgba(45, 80, 22, 0.9));
        border-radius: 20px;
        padding: 25px 30px;
        position: relative;
        border: 2px solid rgba(212, 175, 55, 0.3);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    }
    
    .ramadan-bg-pattern {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cpath d='M30 5 L35 25 L55 30 L35 35 L30 55 L25 35 L5 30 L25 25 Z' fill='rgba(212,175,55,0.05)'/%3E%3C/svg%3E");
        opacity: 0.5;
        border-radius: 20px;
        pointer-events: none;
    }
    
    .ramadan-banner .row {
        position: relative;
        z-index: 2;
    }
    
    .ramadan-title {
        position: relative;
        z-index: 1;
        color: #fff;
    }
    
    .ramadan-arabic {
        font-family: 'Amiri', serif;
        font-size: 1.8rem;
        color: #d4af37;
        display: block;
        margin-bottom: 5px;
    }
    
    .ramadan-title h3 {
        color: #fff;
        font-weight: 700;
        font-size: 1.5rem;
    }
    
    .ramadan-day {
        color: #d4af37;
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .current-time-display {
        background: rgba(255, 255, 255, 0.15);
        padding: 8px 15px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #fff;
        font-size: 0.95rem;
    }
    
    .current-time-value {
        font-weight: 700;
        font-size: 1.1rem;
        color: #d4af37;
        font-family: monospace;
    }
    
    .location-note {
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .location-note i {
        color: #d4af37;
    }
    
    .time-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 15px;
        display: flex;
        align-items: center;
        gap: 15px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: transform 0.3s ease;
    }
    
    .time-card:hover {
        transform: translateY(-3px);
    }
    
    .sehri-card {
        background: linear-gradient(135deg, rgba(63, 81, 181, 0.3), rgba(48, 63, 159, 0.3));
    }
    
    .iftar-card {
        background: linear-gradient(135deg, rgba(255, 152, 0, 0.3), rgba(245, 124, 0, 0.3));
    }
    
    .time-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .sehri-card .time-icon {
        background: linear-gradient(135deg, #3f51b5, #303f9f);
        color: #fff;
    }
    
    .iftar-card .time-icon {
        background: linear-gradient(135deg, #ff9800, #f57c00);
        color: #fff;
    }
    
    .time-info {
        display: flex;
        flex-direction: column;
    }
    
    .time-label {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.8);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .time-value {
        font-size: 1.4rem;
        font-weight: 700;
        color: #fff;
    }
    
    .countdown-label {
        font-size: 0.8rem;
        color: #d4af37;
        font-weight: 500;
    }
    
    .btn-ramadan {
        background: linear-gradient(135deg, #d4af37, #c9a227);
        color: #1a472a;
        border: none;
        padding: 12px 25px;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4);
    }
    
    .btn-ramadan:hover {
        background: linear-gradient(135deg, #e5c158, #d4af37);
        color: #1a472a;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(212, 175, 55, 0.5);
    }
    
    .btn-ramadan-share {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        border: 2px solid #d4af37;
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-ramadan-share:hover {
        background: #d4af37;
        color: #1a472a;
        transform: translateY(-2px);
    }
    
    @media (min-width: 992px) {
        .btn-ramadan {
            padding: 10px 18px;
            font-size: 0.9rem;
        }
        
        .btn-ramadan-share {
            padding: 8px 15px;
            font-size: 0.9rem;
        }
    }
    
    /* Modal Styles */
    .ramadan-modal .modal-content {
        background: #fff;
        border: 2px solid #1a472a;
    }
    
    .ramadan-modal .modal-header {
        background: linear-gradient(135deg, #1a472a, #2d5016);
        border-bottom: 2px solid #d4af37;
        color: #fff;
        padding: 1rem 1.5rem;
    }
    
    .ramadan-modal .modal-header .modal-title {
        color: #d4af37;
        font-size: 1rem;
        line-height: 1.4;
        white-space: normal;
        word-wrap: break-word;
    }
    
    @media (min-width: 576px) {
        .ramadan-modal .modal-header .modal-title {
            font-size: 1.15rem;
        }
    }
    
    .ramadan-modal .modal-body {
        color: #333;
        background: #fefefe;
        padding: 1rem;
    }
    
    .ramadan-modal .table-responsive {
        margin: 0 -0.5rem;
        padding: 0 0.5rem;
    }
    
    .ramadan-modal .modal-footer {
        border-top: 1px solid #ddd;
        background: #f8f9fa;
    }
    
    .ramadan-today-highlight {
        background: linear-gradient(135deg, #1a472a, #2d5016);
        border-radius: 15px;
        padding: 20px;
        border: 2px solid #d4af37;
        color: #fff;
    }
    
    .ramadan-today-highlight h4 {
        color: #fff;
    }
    
    .ramadan-today-highlight small {
        color: rgba(255, 255, 255, 0.8);
    }
    
    .today-badge {
        background: #d4af37;
        color: #1a472a;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 10px;
    }
    
    .today-time {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-size: 1.1rem;
        color: #fff;
    }
    
    .today-time i {
        font-size: 1.5rem;
        color: #d4af37;
    }
    
    .table-ramadan {
        color: #333;
    }
    
    .table-ramadan thead th {
        background: #1a472a;
        color: #fff;
        border-color: #1a472a;
        font-weight: 600;
        padding: 12px 10px;
    }
    
    .table-ramadan thead th:first-child {
        padding-left: 15px;
    }
    
    .table-ramadan tbody tr {
        background: #fff;
    }
    
    .table-ramadan tbody tr:nth-child(even) {
        background: #f8f9fa;
    }
    
    .table-ramadan tbody td {
        border-color: #dee2e6;
        vertical-align: middle;
        color: #333;
        padding: 10px;
    }
    
    .table-ramadan tbody td:first-child {
        padding-left: 15px;
        min-width: 60px;
    }
    
    .table-ramadan .today-row {
        background: #fff3cd !important;
    }
    
    .table-ramadan .today-row td {
        color: #1a472a;
        font-weight: 700;
    }
    
    .dua-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        border: 1px solid #1a472a;
    }
    
    .dua-card h6 {
        color: #1a472a;
        margin-bottom: 10px;
        font-weight: 600;
    }
    
    .arabic-text {
        font-family: 'Amiri', serif;
        font-size: 1.1rem;
        line-height: 2;
        text-align: right;
        direction: rtl;
        margin: 0;
        color: #333;
    }
    
    @media (max-width: 768px) {
        .ramadan-banner {
            padding: 20px;
        }
        
        .ramadan-arabic {
            font-size: 1.4rem;
        }
        
        .ramadan-title h3 {
            font-size: 1.2rem;
        }
        
        .time-value {
            font-size: 1.1rem;
        }
        
        .time-icon {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }
        
        .btn-ramadan {
            padding: 10px 20px;
            font-size: 0.9rem;
        }
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Today's times from server
        const sehriTime = '{{ $todaySchedule['sehri'] ?? '5:00' }}';
        const iftarTime = '{{ $todaySchedule['iftar'] ?? '6:00' }}';
        const serverDate = '{{ date('Y-m-d') }}';
        
        // Full Ramadan schedule for auto-update
        const ramadanSchedule = {
            '2026-02-19': {day: 1, sehri: '5:14', iftar: '6:06', bar: 'বৃহস্পতি'},
            '2026-02-20': {day: 2, sehri: '5:13', iftar: '6:06', bar: 'শুক্র'},
            '2026-02-21': {day: 3, sehri: '5:12', iftar: '6:07', bar: 'শনি'},
            '2026-02-22': {day: 4, sehri: '5:11', iftar: '6:07', bar: 'রবি'},
            '2026-02-23': {day: 5, sehri: '5:10', iftar: '6:08', bar: 'সোম'},
            '2026-02-24': {day: 6, sehri: '5:10', iftar: '6:08', bar: 'মঙ্গল'},
            '2026-02-25': {day: 7, sehri: '5:09', iftar: '6:09', bar: 'বুধ'},
            '2026-02-26': {day: 8, sehri: '5:08', iftar: '6:09', bar: 'বৃহস্পতি'},
            '2026-02-27': {day: 9, sehri: '5:08', iftar: '6:10', bar: 'শুক্র'},
            '2026-02-28': {day: 10, sehri: '5:07', iftar: '6:10', bar: 'শনি'},
            '2026-03-01': {day: 11, sehri: '5:06', iftar: '6:11', bar: 'রবি'},
            '2026-03-02': {day: 12, sehri: '5:06', iftar: '6:11', bar: 'সোম'},
            '2026-03-03': {day: 13, sehri: '5:05', iftar: '6:11', bar: 'মঙ্গল'},
            '2026-03-04': {day: 14, sehri: '5:04', iftar: '6:12', bar: 'বুধ'},
            '2026-03-05': {day: 15, sehri: '5:03', iftar: '6:12', bar: 'বৃহস্পতি'},
            '2026-03-06': {day: 16, sehri: '5:02', iftar: '6:13', bar: 'শুক্র'},
            '2026-03-07': {day: 17, sehri: '5:02', iftar: '6:13', bar: 'শনি'},
            '2026-03-08': {day: 18, sehri: '5:01', iftar: '6:14', bar: 'রবি'},
            '2026-03-09': {day: 19, sehri: '5:00', iftar: '6:14', bar: 'সোম'},
            '2026-03-10': {day: 20, sehri: '4:59', iftar: '6:15', bar: 'মঙ্গল'},
            '2026-03-11': {day: 21, sehri: '4:58', iftar: '6:15', bar: 'বুধ'},
            '2026-03-12': {day: 22, sehri: '4:57', iftar: '6:16', bar: 'বৃহস্পতি'},
            '2026-03-13': {day: 23, sehri: '4:56', iftar: '6:16', bar: 'শুক্র'},
            '2026-03-14': {day: 24, sehri: '4:55', iftar: '6:17', bar: 'শনি'},
            '2026-03-15': {day: 25, sehri: '4:54', iftar: '6:17', bar: 'রবি'},
            '2026-03-16': {day: 26, sehri: '4:53', iftar: '6:18', bar: 'সোম'},
            '2026-03-17': {day: 27, sehri: '4:52', iftar: '6:18', bar: 'মঙ্গল'},
            '2026-03-18': {day: 28, sehri: '4:51', iftar: '6:19', bar: 'বুধ'},
            '2026-03-19': {day: 29, sehri: '4:50', iftar: '6:19', bar: 'বৃহস্পতি'},
            '2026-03-20': {day: 30, sehri: '4:49', iftar: '6:20', bar: 'শুক্র'},
        };
        
        // Track if we've already updated today
        let hasUpdatedToday = false;
        
        // Get current date string in YYYY-MM-DD format
        function getCurrentDateString() {
            const now = new Date();
            return now.getFullYear() + '-' + 
                   String(now.getMonth() + 1).padStart(2, '0') + '-' + 
                   String(now.getDate()).padStart(2, '0');
        }
        
        // Update the display without page reload
        function updateRamadanDisplay() {
            const currentDate = getCurrentDateString();
            const schedule = ramadanSchedule[currentDate];
            
            if (schedule) {
                // Update Ramadan day
                const dayEl = document.querySelector('.ramadan-day');
                if (dayEl) {
                    dayEl.textContent = schedule.day + ' রমজান, ১৪৪৭ হিজরী';
                }
                
                // Update Sehri time
                const sehriEl = document.getElementById('sehriTime');
                if (sehriEl) {
                    sehriEl.textContent = schedule.sehri + ' AM';
                }
                
                // Update Iftar time
                const iftarEl = document.getElementById('iftarTime');
                if (iftarEl) {
                    iftarEl.textContent = schedule.iftar + ' PM';
                }
                
                // Update modal today highlight
                const modalDayEl = document.querySelector('.ramadan-today-highlight h4');
                if (modalDayEl) {
                    modalDayEl.innerHTML = '<i class="fas fa-star text-warning me-2"></i>আজ: ' + schedule.day + ' রমজান (' + schedule.bar + ')';
                }
                
                // Update today's times in modal
                const modalSehriEl = document.querySelector('.ramadan-today-highlight .row .col-6:first-child h5');
                if (modalSehriEl) {
                    modalSehriEl.textContent = schedule.sehri + ' AM';
                }
                const modalIftarEl = document.querySelector('.ramadan-today-highlight .row .col-6:last-child h5');
                if (modalIftarEl) {
                    modalIftarEl.textContent = schedule.iftar + ' PM';
                }
                
                // Update table row highlighting
                updateTableHighlight(schedule.day);
                
                hasUpdatedToday = true;
                console.log('রমজান দিন আপডেট হয়েছে: ' + schedule.day + ' রমজান');
            }
        }
        
        // Update table row highlighting
        function updateTableHighlight(ramadanDay) {
            // Remove existing highlight
            document.querySelectorAll('.table-ramadan tbody tr').forEach(row => {
                row.classList.remove('today-row');
            });
            // Add highlight to current day
            const currentRow = document.querySelector('.table-ramadan tbody tr[data-ramadan-day="' + ramadanDay + '"]');
            if (currentRow) {
                currentRow.classList.add('today-row');
            }
        }
        
        // Initial table highlight on page load
        function initTableHighlight() {
            const now = new Date();
            const currentDate = getCurrentDateString();
            
            // Check if we're past Iftar+10min - show tomorrow
            const [iftarHour, iftarMin] = iftarTime.split(':').map(Number);
            const iftarPlusTen = new Date();
            iftarPlusTen.setHours(iftarHour + 12, iftarMin + 10, 0, 0);
            
            let dayToHighlight;
            if (now >= iftarPlusTen) {
                const tomorrow = new Date(now);
                tomorrow.setDate(tomorrow.getDate() + 1);
                const tomorrowStr = tomorrow.getFullYear() + '-' + 
                               String(tomorrow.getMonth() + 1).padStart(2, '0') + '-' + 
                               String(tomorrow.getDate()).padStart(2, '0');
                const tomorrowSchedule = ramadanSchedule[tomorrowStr];
                dayToHighlight = tomorrowSchedule ? tomorrowSchedule.day : ramadanSchedule[currentDate]?.day;
            } else {
                dayToHighlight = ramadanSchedule[currentDate]?.day;
            }
            
            if (dayToHighlight) {
                updateTableHighlight(dayToHighlight);
            }
        }
        
        // Check if we need to update to next day
        function checkForDayChange() {
            const now = new Date();
            const currentDate = getCurrentDateString();
            
            // If server date is different from current date, update display
            if (serverDate !== currentDate && !hasUpdatedToday) {
                updateRamadanDisplay();
                return;
            }
            
            // Check if it's 10 minutes after Iftar
            const [iftarHour, iftarMin] = iftarTime.split(':').map(Number);
            const iftarPlusTen = new Date();
            iftarPlusTen.setHours(iftarHour + 12, iftarMin + 10, 0, 0); // PM so add 12
            
            // If current time is past Iftar + 10 mins, and we haven't updated yet
            if (now >= iftarPlusTen && !hasUpdatedToday) {
                // Get tomorrow's date
                const tomorrow = new Date(now);
                tomorrow.setDate(tomorrow.getDate() + 1);
                const tomorrowStr = tomorrow.getFullYear() + '-' + 
                                   String(tomorrow.getMonth() + 1).padStart(2, '0') + '-' + 
                                   String(tomorrow.getDate()).padStart(2, '0');
                
                const tomorrowSchedule = ramadanSchedule[tomorrowStr];
                if (tomorrowSchedule) {
                    // Update display to tomorrow's times
                    const dayEl = document.querySelector('.ramadan-day');
                    if (dayEl) {
                        dayEl.textContent = tomorrowSchedule.day + ' রমজান, ১৪৪৭ হিজরী';
                    }
                    
                    const sehriEl = document.getElementById('sehriTime');
                    if (sehriEl) {
                        sehriEl.textContent = tomorrowSchedule.sehri + ' AM';
                    }
                    
                    const iftarEl = document.getElementById('iftarTime');
                    if (iftarEl) {
                        iftarEl.textContent = tomorrowSchedule.iftar + ' PM';
                    }
                    
                    // Update countdown label
                    const sehriCountdownEl = document.getElementById('sehriCountdown');
                    if (sehriCountdownEl) {
                        sehriCountdownEl.textContent = 'আগামীকালের সেহরি';
                    }
                    const iftarCountdownEl = document.getElementById('iftarCountdown');
                    if (iftarCountdownEl) {
                        iftarCountdownEl.textContent = 'আগামীকালের ইফতার';
                    }
                    
                    // Update table row highlighting
                    updateTableHighlight(tomorrowSchedule.day);
                    
                    hasUpdatedToday = true;
                    console.log('ইফতারের পর আপডেট: ' + tomorrowSchedule.day + ' রমজান');
                }
            }
            
            // Also check at midnight - reload page to get fresh data
            if (now.getHours() === 0 && now.getMinutes() === 0) {
                location.reload();
            }
        }
        
        // Update current time display
        function updateCurrentTime() {
            const now = new Date();
            const hours = now.getHours();
            const mins = now.getMinutes();
            const secs = now.getSeconds();
            const ampm = hours >= 12 ? 'PM' : 'AM';
            const displayHours = hours % 12 || 12;
            const timeString = displayHours.toString().padStart(2, '0') + ':' + 
                              mins.toString().padStart(2, '0') + ':' + 
                              secs.toString().padStart(2, '0') + ' ' + ampm;
            document.getElementById('currentTime').textContent = timeString;
            
            // Check for day change every second
            checkForDayChange();
        }
        
        function updateCountdown() {
            const now = new Date();
            const currentDate = getCurrentDateString();
            
            // Get current schedule (might be tomorrow after iftar)
            let scheduleDate = currentDate;
            const [iftarHour, iftarMin] = iftarTime.split(':').map(Number);
            const iftarPlusTen = new Date();
            iftarPlusTen.setHours(iftarHour + 12, iftarMin + 10, 0, 0);
            
            if (now >= iftarPlusTen) {
                // Use tomorrow's schedule for countdown
                const tomorrow = new Date(now);
                tomorrow.setDate(tomorrow.getDate() + 1);
                scheduleDate = tomorrow.getFullYear() + '-' + 
                              String(tomorrow.getMonth() + 1).padStart(2, '0') + '-' + 
                              String(tomorrow.getDate()).padStart(2, '0');
            }
            
            const schedule = ramadanSchedule[scheduleDate] || ramadanSchedule[currentDate];
            if (!schedule) return;
            
            const today = now.toDateString();
            
            // Parse sehri time (AM) - for tomorrow if after iftar
            const [sehriHour, sehriMin] = schedule.sehri.split(':').map(Number);
            let sehriDate = new Date(today + ' ' + sehriHour + ':' + sehriMin + ':00');
            if (now >= iftarPlusTen) {
                sehriDate.setDate(sehriDate.getDate() + 1);
            }
            
            // Parse iftar time (PM - add 12 hours)
            const [scheduleIftarHour, scheduleIftarMin] = schedule.iftar.split(':').map(Number);
            let iftarDate = new Date(today + ' ' + (scheduleIftarHour + 12) + ':' + scheduleIftarMin + ':00');
            if (now >= iftarPlusTen) {
                iftarDate.setDate(iftarDate.getDate() + 1);
            }
            
            const sehriCountdownEl = document.getElementById('sehriCountdown');
            const iftarCountdownEl = document.getElementById('iftarCountdown');
            
            // Sehri countdown
            if (now < sehriDate) {
                const diff = sehriDate - now;
                const hours = Math.floor(diff / (1000 * 60 * 60));
                const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                sehriCountdownEl.textContent = 'বাকি ' + hours + ' ঘণ্টা ' + mins + ' মিনিট';
            } else if (now >= iftarPlusTen) {
                const diff = sehriDate - now;
                const hours = Math.floor(diff / (1000 * 60 * 60));
                const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                sehriCountdownEl.textContent = 'বাকি ' + hours + ' ঘণ্টা ' + mins + ' মিনিট';
            } else {
                sehriCountdownEl.textContent = 'সেহরি শেষ';
            }
            
            // Iftar countdown
            if (now < iftarDate && now < iftarPlusTen) {
                const diff = iftarDate - now;
                const hours = Math.floor(diff / (1000 * 60 * 60));
                const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                iftarCountdownEl.textContent = 'বাকি ' + hours + ' ঘণ্টা ' + mins + ' মিনিট';
            } else if (now >= iftarPlusTen) {
                const diff = iftarDate - now;
                const hours = Math.floor(diff / (1000 * 60 * 60));
                const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                iftarCountdownEl.textContent = 'বাকি ' + hours + ' ঘণ্টা ' + mins + ' মিনিট';
            } else {
                iftarCountdownEl.textContent = 'ইফতার শেষ';
            }
        }
        
        // Initial updates
        updateCurrentTime();
        updateCountdown();
        checkForDayChange();
        initTableHighlight();
        
        // Update current time every second (also checks for day change)
        setInterval(updateCurrentTime, 1000);
        // Update countdown every minute
        setInterval(updateCountdown, 60000);
    });
    
    // Generate shareable image
    function generateShareImage() {
        const canvas = document.getElementById('shareCanvas');
        canvas.width = 800;
        canvas.height = 550;
        const ctx = canvas.getContext('2d');
        
        // Canvas dimensions
        const width = 800;
        const height = 550;
        
        // Get current displayed schedule from the page
        const ramadanDayEl = document.querySelector('.ramadan-day');
        const sehriEl = document.getElementById('sehriTime');
        const iftarEl = document.getElementById('iftarTime');
        
        // Parse current day from display
        let ramadanDay = '{{ $todaySchedule['day'] ?? 1 }}';
        let todayBar = '{{ $todaySchedule['bar'] ?? 'রবি' }}';
        let sehriTime = '{{ $todaySchedule['sehri'] ?? '5:00' }}';
        let iftarTime = '{{ $todaySchedule['iftar'] ?? '6:00' }}';
        
        // Update from displayed values if available
        if (ramadanDayEl) {
            const dayText = ramadanDayEl.textContent;
            const match = dayText.match(/(\d+)/);
            if (match) ramadanDay = match[1];
        }
        if (sehriEl) {
            sehriTime = sehriEl.textContent.replace(' AM', '');
        }
        if (iftarEl) {
            iftarTime = iftarEl.textContent.replace(' PM', '');
        }
        
        // Get bar from schedule
        const barMap = {1:'বৃহস্পতি',2:'শুক্র',3:'শনি',4:'রবি',5:'সোম',6:'মঙ্গল',7:'বুধ',8:'বৃহস্পতি',9:'শুক্র',10:'শনি',11:'রবি',12:'সোম',13:'মঙ্গল',14:'বুধ',15:'বৃহস্পতি',16:'শুক্র',17:'শনি',18:'রবি',19:'সোম',20:'মঙ্গল',21:'বুধ',22:'বৃহস্পতি',23:'শুক্র',24:'শনি',25:'রবি',26:'সোম',27:'মঙ্গল',28:'বুধ',29:'বৃহস্পতি',30:'শুক্র'};
        todayBar = barMap[parseInt(ramadanDay)] || todayBar;
        
        // Create gradient background
        const gradient = ctx.createLinearGradient(0, 0, width, height);
        gradient.addColorStop(0, '#0d3320');
        gradient.addColorStop(0.3, '#1a472a');
        gradient.addColorStop(0.7, '#1a472a');
        gradient.addColorStop(1, '#0d3320');
        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, width, height);
        
        // Add decorative pattern overlay
        ctx.fillStyle = 'rgba(212, 175, 55, 0.03)';
        for (let i = 0; i < 20; i++) {
            const x = Math.random() * width;
            const y = Math.random() * height;
            ctx.beginPath();
            ctx.arc(x, y, Math.random() * 30 + 10, 0, Math.PI * 2);
            ctx.fill();
        }
        
        // Outer golden border with shadow effect
        ctx.shadowColor = 'rgba(212, 175, 55, 0.5)';
        ctx.shadowBlur = 15;
        ctx.strokeStyle = '#d4af37';
        ctx.lineWidth = 6;
        roundRect(ctx, 15, 15, width - 30, height - 30, 15);
        ctx.stroke();
        ctx.shadowBlur = 0;
        
        // Inner decorative border
        ctx.strokeStyle = 'rgba(212, 175, 55, 0.4)';
        ctx.lineWidth = 1;
        roundRect(ctx, 30, 30, width - 60, height - 60, 10);
        ctx.stroke();
        
        // Draw crescent moon
        ctx.fillStyle = '#d4af37';
        ctx.beginPath();
        ctx.arc(130, 75, 30, 0, Math.PI * 2);
        ctx.fill();
        ctx.fillStyle = '#1a472a';
        ctx.beginPath();
        ctx.arc(145, 70, 28, 0, Math.PI * 2);
        ctx.fill();
        
        // Draw stars
        ctx.fillStyle = '#d4af37';
        drawStar(ctx, 90, 55, 5, 8, 4);
        drawStar(ctx, 710, 60, 5, 10, 5);
        drawStar(ctx, 680, 90, 5, 6, 3);
        drawStar(ctx, 100, 100, 5, 5, 2.5);
        
        // Arabic text - Ramadan Mubarak (centered)
        ctx.fillStyle = '#d4af37';
        ctx.font = 'bold 40px Amiri, serif';
        ctx.textAlign = 'center';
        ctx.fillText('رمضان مبارك', width / 2, 85);
        
        // Bengali title
        ctx.fillStyle = '#ffffff';
        ctx.font = 'bold 30px Hind Siliguri, sans-serif';
        ctx.fillText('রমজান মোবারক ১৪৪৭ হিজরী', width / 2, 130);
        
        // Day badge background
        ctx.fillStyle = 'rgba(212, 175, 55, 0.2)';
        roundRect(ctx, width/2 - 100, 145, 200, 35, 17);
        ctx.fill();
        ctx.strokeStyle = '#d4af37';
        ctx.lineWidth = 1;
        roundRect(ctx, width/2 - 100, 145, 200, 35, 17);
        ctx.stroke();
        
        ctx.fillStyle = '#d4af37';
        ctx.font = 'bold 20px Hind Siliguri, sans-serif';
        ctx.fillText(ramadanDay + ' রমজান - ' + todayBar, width / 2, 170);
        
        // Decorative line with dots
        ctx.strokeStyle = '#d4af37';
        ctx.lineWidth = 2;
        ctx.beginPath();
        ctx.moveTo(150, 200);
        ctx.lineTo(350, 200);
        ctx.stroke();
        ctx.beginPath();
        ctx.moveTo(450, 200);
        ctx.lineTo(650, 200);
        ctx.stroke();
        
        // Center diamond
        ctx.fillStyle = '#d4af37';
        ctx.beginPath();
        ctx.moveTo(400, 193);
        ctx.lineTo(410, 200);
        ctx.lineTo(400, 207);
        ctx.lineTo(390, 200);
        ctx.closePath();
        ctx.fill();
        
        // Time cards
        const boxY = 225;
        const boxHeight = 115;
        const boxWidth = 300;
        
        // Sehri card with gradient
        const sehriGrad = ctx.createLinearGradient(70, boxY, 70, boxY + boxHeight);
        sehriGrad.addColorStop(0, 'rgba(63, 81, 181, 0.5)');
        sehriGrad.addColorStop(1, 'rgba(48, 63, 159, 0.5)');
        ctx.fillStyle = sehriGrad;
        roundRect(ctx, 70, boxY, boxWidth, boxHeight, 15);
        ctx.fill();
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.3)';
        ctx.lineWidth = 2;
        roundRect(ctx, 70, boxY, boxWidth, boxHeight, 15);
        ctx.stroke();
        
        // Moon icon for Sehri
        ctx.fillStyle = '#fff';
        ctx.beginPath();
        ctx.arc(110, boxY + 40, 18, 0, Math.PI * 2);
        ctx.fill();
        ctx.fillStyle = sehriGrad;
        ctx.beginPath();
        ctx.arc(120, boxY + 35, 16, 0, Math.PI * 2);
        ctx.fill();
        
        // Sehri text
        ctx.fillStyle = 'rgba(255, 255, 255, 0.85)';
        ctx.font = '16px Hind Siliguri, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('সেহরির শেষ সময়', 220, boxY + 35);
        ctx.fillStyle = '#ffffff';
        ctx.font = 'bold 42px Hind Siliguri, sans-serif';
        ctx.fillText(sehriTime + ' AM', 220, boxY + 85);
        
        // Iftar card with gradient
        const iftarGrad = ctx.createLinearGradient(430, boxY, 430, boxY + boxHeight);
        iftarGrad.addColorStop(0, 'rgba(255, 152, 0, 0.5)');
        iftarGrad.addColorStop(1, 'rgba(245, 124, 0, 0.5)');
        ctx.fillStyle = iftarGrad;
        roundRect(ctx, 430, boxY, boxWidth, boxHeight, 15);
        ctx.fill();
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.3)';
        ctx.lineWidth = 2;
        roundRect(ctx, 430, boxY, boxWidth, boxHeight, 15);
        ctx.stroke();
        
        // Sun icon for Iftar
        ctx.fillStyle = '#fff';
        ctx.beginPath();
        ctx.arc(470, boxY + 40, 15, 0, Math.PI * 2);
        ctx.fill();
        // Sun rays
        for (let i = 0; i < 8; i++) {
            const angle = (i * Math.PI) / 4;
            ctx.strokeStyle = '#fff';
            ctx.lineWidth = 3;
            ctx.beginPath();
            ctx.moveTo(470 + Math.cos(angle) * 20, boxY + 40 + Math.sin(angle) * 20);
            ctx.lineTo(470 + Math.cos(angle) * 28, boxY + 40 + Math.sin(angle) * 28);
            ctx.stroke();
        }
        
        // Iftar text
        ctx.fillStyle = 'rgba(255, 255, 255, 0.85)';
        ctx.font = '16px Hind Siliguri, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('ইফতারের সময়', 580, boxY + 35);
        ctx.fillStyle = '#ffffff';
        ctx.font = 'bold 42px Hind Siliguri, sans-serif';
        ctx.fillText(iftarTime + ' PM', 580, boxY + 85);
        
        // Location with icon
        ctx.fillStyle = 'rgba(255, 255, 255, 0.8)';
        ctx.font = '16px Hind Siliguri, sans-serif';
        ctx.fillText('📍 সাতক্ষীরা ও পার্শ্ববর্তী এলাকার জন্য প্রযোজ্য', width / 2, 380);
        
        // Website URL box
        ctx.fillStyle = 'rgba(212, 175, 55, 0.15)';
        roundRect(ctx, width/2 - 140, 400, 280, 45, 22);
        ctx.fill();
        ctx.strokeStyle = '#d4af37';
        ctx.lineWidth = 2;
        roundRect(ctx, width/2 - 140, 400, 280, 45, 22);
        ctx.stroke();
        
        ctx.fillStyle = '#d4af37';
        ctx.font = 'bold 22px Hind Siliguri, sans-serif';
        ctx.fillText('🌐 exploresatkhira.com', width / 2, 430);
        
        // Date
        const today = new Date();
        const dateStr = today.toLocaleDateString('bn-BD', { day: 'numeric', month: 'long', year: 'numeric' });
        ctx.fillStyle = 'rgba(255, 255, 255, 0.5)';
        ctx.font = '14px Hind Siliguri, sans-serif';
        ctx.fillText(dateStr, width / 2, 475);
        
        // Bottom decorative line
        ctx.strokeStyle = 'rgba(212, 175, 55, 0.3)';
        ctx.lineWidth = 1;
        ctx.beginPath();
        ctx.moveTo(200, 495);
        ctx.lineTo(600, 495);
        ctx.stroke();
        
        // Download the image
        const link = document.createElement('a');
        link.download = 'ramadan-sehri-iftar-' + ramadanDay + '-satkhira.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
    }
    
    // Helper function to draw star
    function drawStar(ctx, cx, cy, spikes, outerRadius, innerRadius) {
        let rot = Math.PI / 2 * 3;
        let x = cx;
        let y = cy;
        const step = Math.PI / spikes;
        
        ctx.beginPath();
        ctx.moveTo(cx, cy - outerRadius);
        for (let i = 0; i < spikes; i++) {
            x = cx + Math.cos(rot) * outerRadius;
            y = cy + Math.sin(rot) * outerRadius;
            ctx.lineTo(x, y);
            rot += step;
            
            x = cx + Math.cos(rot) * innerRadius;
            y = cy + Math.sin(rot) * innerRadius;
            ctx.lineTo(x, y);
            rot += step;
        }
        ctx.lineTo(cx, cy - outerRadius);
        ctx.closePath();
        ctx.fill();
    }
    
    // Helper function to draw rounded rectangle
    function roundRect(ctx, x, y, width, height, radius) {
        ctx.beginPath();
        ctx.moveTo(x + radius, y);
        ctx.lineTo(x + width - radius, y);
        ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
        ctx.lineTo(x + width, y + height - radius);
        ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
        ctx.lineTo(x + radius, y + height);
        ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
        ctx.lineTo(x, y + radius);
        ctx.quadraticCurveTo(x, y, x + radius, y);
        ctx.closePath();
    }
    </script>
    @endif

    <!-- Fuel Availability Section -->
    @if($fuelEnabled && count($fuelReports) > 0)
    <section class="py-5 fuel-section">
        <div class="container">
            <div class="section-header text-center mb-4" data-aos="fade-up">
                <h2><i class="fas fa-gas-pump me-2 text-warning"></i>⛽ জ্বালানি তেল আপডেট</h2>
                <p class="text-muted">সাতক্ষীরার পেট্রোল পাম্পে তেলের বর্তমান অবস্থা</p>
                <div class="underline" style="background: linear-gradient(90deg, #e65100, #ff6d00);"></div>
            </div>
            
            <div class="row g-3 mobile-scroll-row">
                @foreach($fuelReports as $report)
                    @php
                        $hasAnyFuel = $report->petrol_available || $report->diesel_available || $report->octane_available;
                        $isVerified = $report->is_verified || ($report->correct_votes >= 3 && $report->correct_votes > $report->incorrect_votes);
                        $isToday = $report->created_at->isToday();
                        $isLocked = $report->fuelStation->is_locked ?? false;
                        $reportImage = null;
                        if ($report->images && count($report->images) > 0) {
                            $reportImage = $report->images[0];
                        } elseif ($report->image) {
                            $reportImage = $report->image;
                        }
                    @endphp
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                        <a href="{{ route('fuel.station', $report->fuel_station_id) }}" class="text-decoration-none">
                        <div class="card h-100 fuel-card {{ $isLocked ? 'border-secondary' : ($hasAnyFuel ? 'border-success' : 'border-danger') }}" style="border-width: 2px;">
                            <div class="card-header {{ $isLocked ? 'bg-secondary' : ($hasAnyFuel ? 'bg-success' : 'bg-danger') }} text-white py-2">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-0 fw-bold"><i class="fas fa-gas-pump me-1"></i>{{ $report->fuelStation->name }}</h6>
                                        <small><i class="fas fa-map-marker-alt me-1"></i>{{ $report->fuelStation->upazila->name }}</small>
                                    </div>
                                    <div class="text-end">
                                        @if($isLocked)
                                            <span class="badge bg-dark"><i class="fas fa-lock"></i> বন্ধ</span>
                                        @elseif($isVerified)
                                            <span class="badge bg-warning text-dark" title="যাচাইকৃত তথ্য">
                                                <i class="fas fa-check-circle"></i> যাচাইকৃত
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-body py-2">
                                @if($isLocked)
                                    <div class="text-center text-muted py-2">
                                        <i class="fas fa-lock fa-2x mb-1"></i>
                                        <p class="mb-0 fw-bold text-danger small">অ্যাডমিন বন্ধ রেখেছে</p>
                                        <p class="mb-0 small">যোগাযোগ: 01811480222</p>
                                    </div>
                                @else
                                    {{-- Image thumbnail --}}
                                    @if($reportImage)
                                        <div class="text-center mb-2">
                                            <img src="{{ asset('uploads/fuel/' . $reportImage) }}" alt="ছবি" class="img-fluid rounded" style="max-height: 100px; object-fit: cover; width: 100%;">
                                        </div>
                                    @endif
                                    
                                    {{-- No update today --}}
                                    @if(!$isToday)
                                        <div class="alert alert-warning py-1 px-2 mb-2 text-center small">
                                            <i class="fas fa-exclamation-triangle me-1"></i>আজ কোন আপডেট করা হয়নি
                                        </div>
                                    @endif

                                    <div class="row g-1 mb-2">
                                        <div class="col-4 text-center">
                                            <div class="p-2 rounded {{ $report->petrol_available ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                                                <span class="d-block fw-bold small">পেট্রোল</span>
                                                <span class="badge {{ $report->petrol_available ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $report->petrol_available ? 'আছে' : 'নেই' }}
                                                </span>
                                                @if($report->petrol_price)
                                                    <small class="d-block text-muted">৳{{ number_format($report->petrol_price, 0) }}/লি.</small>
                                                @endif
                                                @if($report->petrol_selling_price)
                                                    <small class="d-block fw-bold text-primary">৳{{ number_format($report->petrol_selling_price, 0) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <div class="p-2 rounded {{ $report->diesel_available ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                                                <span class="d-block fw-bold small">ডিজেল</span>
                                                <span class="badge {{ $report->diesel_available ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $report->diesel_available ? 'আছে' : 'নেই' }}
                                                </span>
                                                @if($report->diesel_price)
                                                    <small class="d-block text-muted">৳{{ number_format($report->diesel_price, 0) }}/লি.</small>
                                                @endif
                                                @if($report->diesel_selling_price)
                                                    <small class="d-block fw-bold text-primary">৳{{ number_format($report->diesel_selling_price, 0) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <div class="p-2 rounded {{ $report->octane_available ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                                                <span class="d-block fw-bold small">অকটেন</span>
                                                <span class="badge {{ $report->octane_available ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $report->octane_available ? 'আছে' : 'নেই' }}
                                                </span>
                                                @if($report->octane_price)
                                                    <small class="d-block text-muted">৳{{ number_format($report->octane_price, 0) }}/লি.</small>
                                                @endif
                                                @if($report->octane_selling_price)
                                                    <small class="d-block fw-bold text-primary">৳{{ number_format($report->octane_selling_price, 0) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="badge bg-{{ $report->queue_status == 'none' ? 'success' : ($report->queue_status == 'short' ? 'info' : ($report->queue_status == 'medium' ? 'warning' : 'danger')) }}">
                                            <i class="fas fa-users me-1"></i>{{ $report->queue_status_bangla }}
                                        </span>
                                        @if($report->fixed_amount)
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-hand-holding-usd me-1"></i>৳{{ number_format($report->fixed_amount, 0) }}
                                            </span>
                                        @endif
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>{{ $report->reporter_name }}
                                        </small>
                                    </div>
                                    <div class="text-center border-top pt-1">
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>{{ $report->created_at->diffForHumans() }} | {{ $report->created_at->format('d M, h:i A') }}
                                        </small>
                                    </div>
                                    <div class="d-flex justify-content-center gap-2 mt-1">
                                        <span class="badge bg-success"><i class="fas fa-thumbs-up me-1"></i>{{ $report->correct_votes ?? 0 }}</span>
                                        <span class="badge bg-danger"><i class="fas fa-thumbs-down me-1"></i>{{ $report->incorrect_votes ?? 0 }}</span>
                                        <span class="badge bg-secondary"><i class="fas fa-comments me-1"></i>মন্তব্য {{ ($report->fuelStation->comments_count ?? 0) > 0 ? '(' . $report->fuelStation->comments_count . ')' : 'করুন' }}</span>
                                    </div>
                                    {{-- Pump notification subscribe button --}}
                                    <div class="text-center mt-2 pt-2 border-top">
                                        <button class="btn btn-sm btn-outline-warning fuel-subscribe-btn" 
                                            data-station-id="{{ $report->fuel_station_id }}"
                                            onclick="event.preventDefault(); event.stopPropagation(); toggleFuelSubscription(this);">
                                            <i class="far fa-bell"></i> <span>🔔 আপডেট নোটিফিকেশন পান</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                        </a>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ route('fuel.index') }}" class="btn btn-warning btn-lg">
                    <i class="fas fa-gas-pump me-2"></i>সব আপডেট দেখুন এবং যুক্ত করুন
                </a>
            </div>
        </div>
    </section>
    <style>
        .fuel-section {
            background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
        }
        .fuel-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border-radius: 12px;
        }
        .fuel-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .fuel-subscribe-btn {
            font-size: 0.75rem;
            border-radius: 20px;
            padding: 4px 14px;
            transition: all 0.3s ease;
        }
        .fuel-subscribe-btn.subscribed {
            background: #ff6d00;
            border-color: #ff6d00;
            color: #fff;
        }
        .fuel-subscribe-btn.subscribed:hover {
            background: #e65100;
            border-color: #e65100;
        }
    </style>
    @endif

    <!-- Satkhirar Am (Mango) Section -->
    @if($mangoEnabled && $mangoStores->count() > 0)
    <section class="py-5 mango-home-section">
        <div class="container">
            <div class="section-header text-center mb-4" data-aos="fade-up">
                <h2><i class="fas fa-store me-2 text-warning"></i>🥭 সাতক্ষীরার আম</h2>
                <p class="text-muted">সাতক্ষীরার সেরা আম সংগ্রহ করুন সরাসরি বাগান থেকে</p>
                <div class="underline" style="background: linear-gradient(90deg, #f59e0b, #d97706);"></div>
            </div>
            <div class="row g-3">
                @foreach($mangoStores as $store)
                    <div class="col-md-4 col-sm-6">
                        <a href="{{ route('mango.show', $store->id) }}" class="text-decoration-none">
                            <div class="card mango-home-card h-100 shadow-sm">
                                <div class="card-body text-center py-3">
                                    @if($store->logo)
                                        <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->store_name }}"
                                             class="rounded-circle mb-2"
                                             style="width:60px;height:60px;object-fit:cover;border:2px solid #f59e0b;">
                                    @else
                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                             style="width:60px;height:60px;background:#f59e0b;font-size:1.8rem;">🥭</div>
                                    @endif
                                    <h6 class="fw-bold mb-1 text-dark">{{ $store->store_name }}</h6>
                                    <p class="text-muted small mb-2">{{ $store->owner_name }}</p>
                                    @if($store->upazila)
                                        <span class="badge bg-light text-dark border small">
                                            <i class="fas fa-map-marker-alt me-1 text-danger"></i>{{ $store->upazila->name_bn ?? $store->upazila->name }}
                                        </span>
                                    @endif
                                    <div class="mt-2">
                                        <span class="badge bg-success">{{ $store->products_count }} জাত আম</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('mango.index') }}" class="btn btn-warning px-5">
                    <i class="fas fa-store me-2"></i>সব স্টোর দেখুন
                </a>
            </div>
        </div>
    </section>
    <style>
        .mango-home-section { background: #fffbf0; }
        .mango-home-card { border: none; border-top: 3px solid #f59e0b; transition: transform 0.2s, box-shadow 0.2s; }
        .mango-home-card:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(245,158,11,0.2) !important; }
    </style>
    @endif

    <!-- Blood Top Donors Section -->
    @if($bloodOnHomepage && $topBloodDonors->count() > 0)
    <section class="py-5 blood-home-section">
        <div class="container">
            <div class="section-header text-center mb-4" data-aos="fade-up">
                <h2><i class="fas fa-tint me-2 text-danger"></i>🩸 এক্সপ্লোর রক্তদাতা</h2>
                <p class="text-muted">সাতক্ষীরার সেরা রক্তদাতা যারা সবচেয়ে বেশিবার রক্তদান করেছেন</p>
                <div class="underline" style="background: linear-gradient(90deg, #dc3545, #a71d2a);"></div>
            </div>
            
            <div class="row g-3 justify-content-center">
                @foreach($topBloodDonors->take(3) as $index => $donor)
                    <div class="col-md-4 col-6" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <div class="card border-0 shadow-sm blood-donor-card h-100 text-center {{ $index === 0 ? 'top-donor-card' : '' }}">
                            <div class="card-body py-4">
                                @if($index === 0)
                                    <div class="top-donor-crown mb-2">
                                        <i class="fas fa-crown text-warning" style="font-size: 1.5rem;"></i>
                                    </div>
                                @endif
                                <div class="blood-donor-avatar mx-auto mb-3 {{ $index === 0 ? 'top-avatar' : '' }}">
                                    <span class="blood-group-badge">{{ $donor->blood_group }}</span>
                                </div>
                                <h6 class="fw-bold mb-1">{{ $donor->name }}</h6>
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    {{ $donor->upazila ? $donor->upazila->name_bn ?? $donor->upazila->name : ($donor->outside_area ?? 'সাতক্ষীরা') }}
                                </p>
                                <div class="donation-count-badge">
                                    <i class="fas fa-heart text-danger me-1"></i>
                                    <strong>{{ $donor->donation_histories_count }}</strong> বার রক্তদান
                                </div>
                                @if($donor->last_donation_date)
                                    <small class="text-muted d-block mt-1">
                                        সর্বশেষ: {{ $donor->last_donation_date->format('d M, Y') }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-4" data-aos="fade-up">
                <a href="{{ route('blood.index') }}" class="btn btn-danger btn-lg">
                    <i class="fas fa-tint me-2"></i>সকল রক্তদাতা দেখুন
                </a>
                <a href="{{ route('blood.register') }}" class="btn btn-outline-danger btn-lg ms-2">
                    <i class="fas fa-user-plus me-1"></i>রক্তদাতা হিসেবে যোগ দিন
                </a>
            </div>
        </div>
    </section>
    <style>
        .blood-home-section {
            background: linear-gradient(135deg, #fff5f5 0%, #ffe0e0 100%);
        }
        .blood-donor-card {
            border-radius: 16px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .blood-donor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(220,53,69,0.15) !important;
        }
        .top-donor-card {
            border: 2px solid #ffc107 !important;
            background: linear-gradient(135deg, #fffdf0, #fff9e6);
        }
        .blood-donor-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #dc3545, #a71d2a);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .blood-donor-avatar.top-avatar {
            width: 85px;
            height: 85px;
            box-shadow: 0 0 0 4px #ffc107;
        }
        .blood-group-badge {
            color: #fff;
            font-weight: 800;
            font-size: 1.2rem;
        }
        .top-avatar .blood-group-badge {
            font-size: 1.4rem;
        }
        .donation-count-badge {
            display: inline-block;
            background: #fff5f5;
            border: 1px solid #ffcdd2;
            border-radius: 20px;
            padding: 4px 14px;
            font-size: 0.85rem;
            color: #c62828;
        }
        .top-donor-crown {
            animation: crownBounce 2s infinite;
        }
        @keyframes crownBounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
    </style>
    @endif

    <!-- Categories Section -->
    <section class="py-5">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2>{{ __('messages.popular_categories') }}</h2>
                <p class="text-muted">{{ __('messages.site_tagline') }}</p>
                <div class="underline"></div>
            </div>
            
            <!-- Category Flip Container -->
            <div class="category-flip-container">
                <!-- Front Side - Top 4 Categories -->
                <div class="category-flip-front" id="categoryFront">
                    <div class="row g-4">
                        @foreach($categories->take(4) as $category)
                            <div class="col-lg-3 col-md-6 col-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                                <a href="{{ route('categories.show', $category) }}" class="text-decoration-none">
                                    <div class="category-card">
                                        <div class="icon-wrapper" style="background-color: {{ $category->color }}">
                                            <i class="{{ $category->icon ?? 'fas fa-folder' }}"></i>
                                        </div>
                                        <h5>{{ app()->getLocale() == 'bn' ? ($category->name_bn ?? $category->name) : $category->name }}</h5>
                                        <p class="listing-count mb-0">{{ $category->listings_count }} {{ app()->getLocale() == 'bn' ? 'টি তথ্য' : 'listings' }}</p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    @if($categories->count() > 4)
                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-success btn-lg" onclick="flipCategories()">
                                <i class="fas fa-th-large me-2"></i>{{ app()->getLocale() == 'bn' ? 'আরও ক্যাটাগরি দেখুন' : 'View More Categories' }} <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    @endif
                </div>
                
                <!-- Back Side - Remaining Categories -->
                <div class="category-flip-back" id="categoryBack" style="display: none;">
                    <div class="row g-4">
                        @foreach($categories->skip(4) as $category)
                            <div class="col-lg-3 col-md-4 col-6">
                                <a href="{{ route('categories.show', $category) }}" class="text-decoration-none">
                                    <div class="category-card">
                                        <div class="icon-wrapper" style="background-color: {{ $category->color }}">
                                            <i class="{{ $category->icon ?? 'fas fa-folder' }}"></i>
                                        </div>
                                        <h5>{{ app()->getLocale() == 'bn' ? ($category->name_bn ?? $category->name) : $category->name }}</h5>
                                        <p class="listing-count mb-0">{{ $category->listings_count }} {{ app()->getLocale() == 'bn' ? 'টি তথ্য' : 'listings' }}</p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-outline-success btn-lg" onclick="flipCategories()">
                            <i class="fas fa-arrow-left me-2"></i>{{ app()->getLocale() == 'bn' ? 'জনপ্রিয় ক্যাটাগরি দেখুন' : 'Back to Popular' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Upazilas Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2>{{ __('messages.our_upazilas') }}</h2>
                <p class="text-muted">{{ __('messages.site_tagline') }}</p>
                <div class="underline"></div>
            </div>
            
            <div class="row g-4 mobile-scroll-row">
                @foreach($upazilas as $upazila)
                    <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                        <a href="{{ route('upazilas.show', $upazila) }}" class="text-decoration-none">
                            <div class="upazila-card">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-map-marker-alt fa-3x text-success"></i>
                                    </div>
                                    <h5>{{ app()->getLocale() == 'bn' ? ($upazila->name_bn ?? $upazila->name) : $upazila->name }}</h5>
                                    <p class="text-muted mb-2">{{ $upazila->listings_count }} {{ app()->getLocale() == 'bn' ? 'টি তথ্য' : 'listings' }}</p>
                                    @if($upazila->population)
                                        <small class="text-muted">{{ app()->getLocale() == 'bn' ? 'জনসংখ্যা:' : 'Population:' }} {{ number_format($upazila->population) }}</small>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Stats Counter Section -->
    <section class="py-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, #0d6e3a 0%, #1a8a4a 50%, #0d6e3a 100%);">
        <div class="position-absolute w-100 h-100" style="top:0;left:0;background:url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 80 80%22><circle cx=%2240%22 cy=%2240%22 r=%221%22 fill=%22rgba(255,255,255,0.05)%22/></svg>') repeat;"></div>
        <div class="container position-relative">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold text-white" style="font-size: 2rem;">{{ app()->getLocale() == 'bn' ? 'সাতক্ষীরা জেলার তথ্য ভান্ডার' : 'Satkhira District Data Hub' }}</h2>
                <div style="width: 60px; height: 4px; background: rgba(255,255,255,0.6); border-radius: 2px; margin: 12px auto 0;"></div>
            </div>
            <div class="row g-4 text-center">
                <div class="col-6 col-md-3" data-aos="zoom-in" data-aos-delay="0">
                    <div class="p-4 rounded-4" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.15);">
                        <i class="fas fa-eye fa-2x text-white mb-3"></i>
                        <h3 class="fw-bold text-white mb-1 counter" data-target="{{ $totalVisitors }}">0</h3>
                        <p class="text-white-50 mb-0 small">{{ app()->getLocale() == 'bn' ? 'মোট ভিজিটর' : 'Total Visitors' }}</p>
                    </div>
                </div>
                <div class="col-6 col-md-3" data-aos="zoom-in" data-aos-delay="100">
                    <div class="p-4 rounded-4" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.15);">
                        <i class="fas fa-list-alt fa-2x text-white mb-3"></i>
                        <h3 class="fw-bold text-white mb-1 counter" data-target="{{ $totalListings }}">0</h3>
                        <p class="text-white-50 mb-0 small">{{ app()->getLocale() == 'bn' ? 'মোট তথ্য' : 'Total Listings' }}</p>
                    </div>
                </div>
                <div class="col-6 col-md-3" data-aos="zoom-in" data-aos-delay="200">
                    <div class="p-4 rounded-4" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.15);">
                        <i class="fas fa-th-large fa-2x text-white mb-3"></i>
                        <h3 class="fw-bold text-white mb-1 counter" data-target="{{ $categories->count() }}">0</h3>
                        <p class="text-white-50 mb-0 small">{{ app()->getLocale() == 'bn' ? 'ক্যাটাগরি' : 'Categories' }}</p>
                    </div>
                </div>
                <div class="col-6 col-md-3" data-aos="zoom-in" data-aos-delay="300">
                    <div class="p-4 rounded-4" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.15);">
                        <i class="fas fa-map-marked-alt fa-2x text-white mb-3"></i>
                        <h3 class="fw-bold text-white mb-1 counter" data-target="{{ $upazilas->count() }}">0</h3>
                        <p class="text-white-50 mb-0 small">{{ app()->getLocale() == 'bn' ? 'উপজেলা' : 'Upazilas' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- আমাদের টিম Section -->
    @if(isset($teamMembers) && $teamMembers->count() > 0)
    <section class="team-section py-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3" style="font-size: 0.9rem; border-radius: 50px;"><i class="fas fa-crown me-1"></i>{{ app()->getLocale() == 'bn' ? 'আমাদের টিম' : 'Our Team' }}</span>
                <h2 class="fw-bold" style="font-size: 2rem;">{{ app()->getLocale() == 'bn' ? 'যারা এই পোর্টাল পরিচালনা করছেন' : 'The People Behind This Portal' }}</h2>
                <div style="width: 60px; height: 4px; background: linear-gradient(90deg, #1a73e8, #34a853); border-radius: 2px; margin: 12px auto 0;"></div>
            </div>
            <div class="row g-4 justify-content-center mobile-scroll-row">
                @foreach($teamMembers as $member)
                    @php
                        $colors = [
                            ['from' => '#667eea', 'to' => '#764ba2'],
                            ['from' => '#f093fb', 'to' => '#f5576c'],
                            ['from' => '#4facfe', 'to' => '#00f2fe'],
                        ];
                        $color = $colors[$loop->index % 3];
                        $initials = '';
                        if($member->user) {
                            $parts = explode(' ', $member->user->name);
                            $initials = strtoupper(substr($parts[0], 0, 1));
                            if(count($parts) > 1) $initials .= strtoupper(substr(end($parts), 0, 1));
                        }
                        $isSuperAdmin = $member->website_role === 'super_admin';
                        $ribbonLabels = ['Founder', 'Co-Founder', 'Contributor'];
                        $ribbonLabelsBn = ['Founder', 'Co-Founder', 'Contributor'];
                        $ribbonLabel = $ribbonLabels[$loop->index] ?? 'Member';
                        $ribbonIcons = ['fa-crown', 'fa-star', 'fa-gem'];
                        $ribbonIcon = $ribbonIcons[$loop->index] ?? 'fa-user';
                        $ribbonColors = [
                            'linear-gradient(135deg, #ff9800, #f57c00)',
                            'linear-gradient(135deg, #1a73e8, #0d47a1)',
                            'linear-gradient(135deg, #34a853, #1b7a34)',
                        ];
                        $ribbonColor = $ribbonColors[$loop->index] ?? 'linear-gradient(135deg, #666, #444)';
                    @endphp
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 150 }}">
                        <div class="team-card {{ $isSuperAdmin ? 'team-card-featured' : '' }}">
                            <div class="team-card-ribbon" style="background: {{ $ribbonColor }};"><i class="fas {{ $ribbonIcon }} me-1"></i>{{ $ribbonLabel }}</div>
                            <div class="team-card-top" style="background: linear-gradient(135deg, {{ $color['from'] }}, {{ $color['to'] }});">
                                <div class="team-card-pattern"></div>
                                @if($member->user && $member->user->avatar)
                                    <img src="{{ asset('storage/' . $member->user->avatar) }}" alt="{{ $member->user->name }}" class="team-avatar">
                                @else
                                    <div class="team-avatar-initials" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px);">
                                        @if($initials)
                                            <span>{{ $initials }}</span>
                                        @else
                                            <i class="fas fa-user fa-2x"></i>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="team-card-body">
                                <h5 class="fw-bold mb-1">{{ $member->user ? (app()->getLocale() == 'bn' ? ($member->user->name_bn ?? $member->user->name) : $member->user->name) : '' }}</h5>
                                <div class="team-role-badge" style="background: linear-gradient(135deg, {{ $color['from'] }}20, {{ $color['to'] }}20); color: {{ $color['from'] }};">
                                    <i class="fas {{ $isSuperAdmin ? 'fa-shield-alt' : 'fa-user-shield' }} me-1"></i>{{ $member->role_display }}
                                </div>
                                @if($member->designation_display)
                                    <p class="text-muted small mt-2 mb-0">{{ $member->designation_display }}</p>
                                @endif
                                <div class="team-social mt-3">
                                    @if($member->phone)
                                        <a href="tel:{{ $member->phone }}" class="team-social-btn" style="--btn-color: #25D366;" title="{{ $member->phone }}">
                                            <i class="fas fa-phone-alt"></i>
                                        </a>
                                    @endif
                                    @if($member->email)
                                        <a href="mailto:{{ $member->email }}" class="team-social-btn" style="--btn-color: #EA4335;" title="{{ $member->email }}">
                                            <i class="fas fa-envelope"></i>
                                        </a>
                                    @endif
                                    @if($member->facebook_url)
                                        <a href="{{ $member->facebook_url }}" target="_blank" rel="noopener" class="team-social-btn" style="--btn-color: #1877F2;">
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-4" data-aos="fade-up">
                <a href="{{ route('about') }}" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-users me-2"></i>{{ app()->getLocale() == 'bn' ? 'উপজেলা মডারেটরস দেখতে এখানে ক্লিক করুন' : 'View Upazila Moderators' }}
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Promotional Ads Section -->
    @if($homepageAds->count() > 0)
    <section class="py-4 promotional-ads-section">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3" data-aos="fade-up">
                <h5 class="mb-0"><i class="fas fa-bullhorn text-warning me-2"></i>{{ app()->getLocale() == 'bn' ? 'বিজ্ঞাপন' : 'Promotions' }}</h5>
                @if($homepageAds->count() > 4)
                <div class="ad-nav-btns">
                    <button class="btn btn-sm btn-outline-warning me-1" onclick="scrollAds('left')"><i class="fas fa-chevron-left"></i></button>
                    <button class="btn btn-sm btn-outline-warning" onclick="scrollAds('right')"><i class="fas fa-chevron-right"></i></button>
                </div>
                @endif
            </div>
            
            <div class="ad-slider-wrapper" id="adSlider">
                <div class="ad-slider d-flex gap-3">
                    @foreach($homepageAds as $ad)
                        <div class="ad-card-mini flex-shrink-0">
                            <a href="{{ route('listings.show', $ad->listing) }}" class="text-decoration-none">
                                <div class="ad-card-inner">
                                    <span class="ad-badge-mini">AD</span>
                                    <img src="{{ asset('storage/' . $ad->image) }}" 
                                         class="ad-img-mini" 
                                         alt="{{ $ad->title ?? ($ad->listing?->title ?? 'Advertisement') }}">
                                    <div class="ad-info-mini">
                                        <p class="ad-title-mini mb-0">{{ Str::limit($ad->title ?? ($ad->listing?->title ?? ''), 30) }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Featured Listings -->
    @if($featuredListings->count() > 0)
    <section class="py-5">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2>{{ __('messages.featured_listings') }}</h2>
                <p class="text-muted">{{ __('messages.site_tagline') }}</p>
                <div class="underline"></div>
            </div>
            
            <div class="row g-4 mobile-scroll-row">
                @foreach($featuredListings as $listing)
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                        <div class="listing-card">
                            <div class="position-relative">
                                <img src="{{ $listing->image ? asset('storage/' . $listing->image) : 'https://picsum.photos/seed/' . $listing->id . '/400/250' }}" 
                                     class="card-img-top" alt="{{ $listing->title }}" style="height: 180px; object-fit: cover;">
                                <span class="category-badge text-white" style="background-color: {{ $listing->category?->color ?? '#28a745' }}">
                                    {{ app()->getLocale() == 'bn' ? ($listing->category?->name_bn ?? $listing->category?->name ?? 'Unknown') : ($listing->category?->name ?? 'Unknown') }}
                                </span>
                                <span class="featured-badge"><i class="fas fa-star me-1"></i>{{ app()->getLocale() == 'bn' ? 'বিশেষ' : 'Featured' }}</span>
                            </div>
                            <div class="card-body">
                                <h6 class="card-title">{{ app()->getLocale() == 'bn' ? ($listing->title_bn ?? $listing->title) : $listing->title }}</h6>
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $listing->upazila ? (app()->getLocale() == 'bn' ? ($listing->upazila->name_bn ?? $listing->upazila->name) : $listing->upazila->name) : (app()->getLocale() == 'bn' ? 'সকল উপজেলা' : 'All Upazilas') }}
                                </p>
                                <a href="{{ route('listings.show', $listing) }}" class="btn btn-outline-success btn-sm">{{ __('messages.view_details') }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- MP Section -->
    @if($mpProfiles->count() > 0)
    <section class="mp-section py-5">
        <div class="container">
            <div class="section-header text-center mb-5" data-aos="fade-up">
                <h2 class="text-white"><i class="fas fa-user-tie me-2"></i>{{ app()->getLocale() == 'bn' ? 'মাননীয় সংসদ সদস্যগণ' : 'Honorable Members of Parliament' }}</h2>
                <p class="text-white-50">{{ app()->getLocale() == 'bn' ? 'সংসদ সদস্যদের কাছে প্রশ্ন থাকলে করুন, আমরা পৌঁছে দিবো এবং তারা উত্তর দিলে সেটা দেখতে পারবেন' : 'Ask questions to MPs, we will forward them and you can see when they reply' }}</p>
            </div>
            
            <div class="row g-4 justify-content-center mobile-scroll-row">
                @foreach($mpProfiles as $mpProfile)
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="mp-card-new text-center h-100">
                        <div class="mp-image-wrapper mb-3">
                            <img src="{{ $mpProfile->image ? asset('storage/' . $mpProfile->image) : 'https://ui-avatars.com/api/?name=' . urlencode($mpProfile->name) . '&background=28a745&color=fff&size=150' }}" 
                                 alt="{{ $mpProfile->name }}" 
                                 class="mp-avatar">
                        </div>
                        <h5 class="mb-1 text-dark fw-bold">{{ app()->getLocale() == 'bn' ? ($mpProfile->name_bn ?? $mpProfile->name) : $mpProfile->name }}</h5>
                        <p class="text-muted mb-1 small">{{ $mpProfile->designation }}</p>
                        <span class="badge bg-success mb-3">{{ $mpProfile->constituency }}</span>
                        <div class="mt-auto">
                            <a href="{{ route('mp.index') }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-question-circle me-1"></i>{{ app()->getLocale() == 'bn' ? 'প্রশ্ন করুন' : 'Ask Question' }}
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="text-center mt-5" data-aos="fade-up">
                <a href="{{ route('mp.index') }}" class="btn btn-warning btn-lg px-5">
                    <i class="fas fa-list me-2"></i>{{ app()->getLocale() == 'bn' ? 'সকল প্রশ্নোত্তর দেখুন' : 'View All Q&A' }}
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Latest Listings -->
    <section class="py-5">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2>{{ __('messages.latest_listings') }}</h2>
                <p class="text-muted">{{ __('messages.site_tagline') }}</p>
                <div class="underline"></div>
            </div>
            
            <div class="row g-4 mobile-scroll-row">
                @foreach($latestListings as $listing)
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                        <div class="card h-100 border-0 shadow-sm listing-card-new">
                            <div class="position-relative overflow-hidden">
                                <a href="{{ route('listings.show', $listing) }}">
                                    <img src="{{ $listing->image ? asset('storage/' . $listing->image) : 'https://picsum.photos/seed/' . $listing->id . '/400/300' }}" 
                                         class="card-img-top listing-img" 
                                         alt="{{ $listing->title }}">
                                </a>
                                <span class="category-badge position-absolute top-0 start-0 m-2 px-2 py-1 rounded-pill text-white small fw-semibold" 
                                      style="background-color: {{ $listing->category->color ?? '#28a745' }};">
                                    <i class="{{ $listing->category->icon ?? 'fas fa-tag' }} me-1"></i>
                                    {{ app()->getLocale() == 'bn' ? ($listing->category->name_bn ?? $listing->category->name) : $listing->category->name }}
                                </span>
                                @if($listing->is_featured)
                                    <span class="position-absolute top-0 end-0 m-2 badge bg-warning text-dark">
                                        <i class="fas fa-star"></i> Featured
                                    </span>
                                @endif
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title fw-bold mb-2 text-dark listing-title">
                                    <a href="{{ route('listings.show', $listing) }}" class="text-decoration-none text-dark">
                                        {{ Str::limit(app()->getLocale() == 'bn' ? ($listing->title_bn ?? $listing->title) : $listing->title, 45) }}
                                    </a>
                                </h6>
                                <p class="text-muted small mb-2 d-flex align-items-center">
                                    <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                    {{ $listing->upazila ? (app()->getLocale() == 'bn' ? ($listing->upazila->name_bn ?? $listing->upazila->name) : $listing->upazila->name) : (app()->getLocale() == 'bn' ? 'সকল উপজেলা' : 'All Upazilas') }}
                                </p>
                                @if($listing->short_description)
                                    <p class="text-muted small mb-3 listing-desc">{{ Str::limit($listing->short_description, 60) }}</p>
                                @endif
                                <div class="mt-auto">
                                    <a href="{{ route('listings.show', $listing) }}" class="btn btn-outline-success btn-sm w-100">
                                        <i class="fas fa-eye me-1"></i>{{ __('messages.view_details') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-5" data-aos="fade-up">
                <a href="{{ route('listings.index') }}" class="btn btn-primary-custom btn-lg">
                    {{ __('messages.view_all') }} <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Latest News -->
    @if($latestNews->count() > 0)
    <section class="py-5 bg-light">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2>{{ __('messages.latest_news') }}</h2>
                <p class="text-muted">{{ __('messages.site_tagline') }}</p>
                <div class="underline"></div>
            </div>
            
            <div class="row g-4 mobile-scroll-row">
                @foreach($latestNews as $news)
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                        <div class="listing-card">
                            <img src="{{ $news->image ? asset('storage/' . $news->image) : 'https://picsum.photos/seed/news' . $news->id . '/400/250' }}" 
                                 class="card-img-top" alt="{{ $news->title }}" style="height: 180px; object-fit: cover;">
                            <div class="card-body">
                                <small class="text-muted">{{ $news->created_at->format('d M, Y') }}</small>
                                <h6 class="card-title mt-2">{{ Str::limit(app()->getLocale() == 'bn' ? ($news->title_bn ?? $news->title) : $news->title, 50) }}</h6>
                                <a href="{{ route('news.show', $news) }}" class="btn btn-outline-primary btn-sm">{{ __('messages.read_more') }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- App Download Section -->
    <section class="py-5" style="background: linear-gradient(135deg, #0d3b1e 0%, #1a6b3c 50%, #0f4d2a 100%); position: relative; overflow: hidden;">
        <div style="position:absolute;inset:0;opacity:0.05;background-image:url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;1&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        <div class="container position-relative">
            <div class="row align-items-center">
                <div class="col-lg-6 text-center text-lg-start mb-4 mb-lg-0" data-aos="fade-right">
                    <div class="d-inline-block mb-3" style="background: rgba(255,255,255,0.1); border-radius: 20px; padding: 8px 20px;">
                        <span style="color: #4ade80; font-weight: 600; font-size: 0.9rem;"><i class="fas fa-mobile-alt me-2"></i>মোবাইল অ্যাপ</span>
                    </div>
                    <h2 class="text-white fw-bold mb-3" style="font-size: 2rem; line-height: 1.3;">এক্সপ্লোর সাতক্ষীরা<br><span style="color: #4ade80;">অ্যাপ ডাউনলোড করুন</span></h2>
                    <p class="text-white-50 mb-4" style="font-size: 1.05rem; line-height: 1.7;">সাতক্ষীরার সকল তথ্য এখন আপনার হাতের মুঠোয়। জ্বালানি তেলের আপডেট, ব্যবসা প্রতিষ্ঠানের তালিকা, সংবাদ, সংসদ সদস্যদের প্রশ্নোত্তর — সবকিছু এক অ্যাপে!</p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start mb-4">
                        <div class="d-flex align-items-center" style="background: rgba(255,255,255,0.08); border-radius: 12px; padding: 10px 16px;">
                            <i class="fas fa-bolt text-warning me-2" style="font-size: 1.2rem;"></i>
                            <span class="text-white" style="font-size: 0.85rem;">দ্রুত অ্যাক্সেস</span>
                        </div>
                        <div class="d-flex align-items-center" style="background: rgba(255,255,255,0.08); border-radius: 12px; padding: 10px 16px;">
                            <i class="fas fa-bell text-info me-2" style="font-size: 1.2rem;"></i>
                            <span class="text-white" style="font-size: 0.85rem;">পুশ নোটিফিকেশন</span>
                        </div>
                        <div class="d-flex align-items-center" style="background: rgba(255,255,255,0.08); border-radius: 12px; padding: 10px 16px;">
                            <i class="fas fa-wifi me-2 text-danger" style="font-size: 1.2rem;"></i>
                            <span class="text-white" style="font-size: 0.85rem;">অফলাইন সাপোর্ট</span>
                        </div>
                    </div>
                    <a href="{{ route('app.download') }}" class="btn btn-lg px-5 py-3" style="background: linear-gradient(135deg, #4ade80, #22c55e); color: #0d3b1e; font-weight: 700; border-radius: 14px; font-size: 1.1rem; box-shadow: 0 4px 20px rgba(74,222,128,0.3);">
                        <i class="fab fa-android me-2"></i>Android APK ডাউনলোড
                    </a>
                </div>
                <div class="col-lg-6 text-center" data-aos="fade-left">
                    <div style="position: relative; display: inline-block;">
                        <!-- Phone mockup -->
                        <div style="width: 260px; height: 480px; background: #111; border-radius: 36px; border: 3px solid #333; padding: 12px; margin: 0 auto; box-shadow: 0 20px 60px rgba(0,0,0,0.5); position: relative; overflow: hidden;">
                            <div style="width: 100%; height: 100%; border-radius: 26px; overflow: hidden; position: relative;">
                                <img src="/icons/icon-512x512.png" alt="App Preview" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.2;">
                                <div style="position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 20px;">
                                    <img src="/icons/app-logo-96.png" alt="Logo" style="width: 72px; height: 72px; border-radius: 18px; margin-bottom: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                                    <h5 class="text-white fw-bold mb-1" style="font-size: 1rem;">এক্সপ্লোর সাতক্ষীরা</h5>
                                    <small style="color: #4ade80;">v1.1.0</small>
                                </div>
                            </div>
                            <!-- Notch -->
                            <div style="position: absolute; top: 12px; left: 50%; transform: translateX(-50%); width: 80px; height: 22px; background: #111; border-radius: 0 0 14px 14px;"></div>
                        </div>
                        <!-- Floating badges -->
                        <div style="position: absolute; top: 30px; right: -20px; background: #fff; border-radius: 12px; padding: 8px 14px; box-shadow: 0 4px 15px rgba(0,0,0,0.15); animation: float 3s ease-in-out infinite;">
                            <span style="font-size: 0.8rem; font-weight: 600; color: #1a6b3c;"><i class="fas fa-gas-pump text-warning me-1"></i>তেল আপডেট</span>
                        </div>
                        <div style="position: absolute; bottom: 60px; left: -20px; background: #fff; border-radius: 12px; padding: 8px 14px; box-shadow: 0 4px 15px rgba(0,0,0,0.15); animation: float 3s ease-in-out infinite 1.5s;">
                            <span style="font-size: 0.8rem; font-weight: 600; color: #1a6b3c;"><i class="fas fa-bell text-info me-1"></i>নোটিফিকেশন</span>
                        </div>
                    </div>
                    <!-- Store badges -->
                    <div class="mt-4 d-flex flex-wrap gap-2 justify-content-center">
                        <div style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15); border-radius: 10px; padding: 10px 18px; display: flex; align-items: center; gap: 10px;">
                            <i class="fab fa-google-play" style="font-size: 1.4rem; color: #4ade80;"></i>
                            <div style="text-align: left;">
                                <small style="color: #999; font-size: 0.65rem; display: block;">Google Play</small>
                                <span style="color: #fff; font-weight: 600; font-size: 0.8rem;">শীঘ্রই আসছে</span>
                            </div>
                        </div>
                        <div style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15); border-radius: 10px; padding: 10px 18px; display: flex; align-items: center; gap: 10px;">
                            <i class="fab fa-apple" style="font-size: 1.5rem; color: #fff;"></i>
                            <div style="text-align: left;">
                                <small style="color: #999; font-size: 0.65rem; display: block;">App Store</small>
                                <span style="color: #fff; font-weight: 600; font-size: 0.8rem;">শীঘ্রই আসছে</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5" style="background: linear-gradient(135deg, #28a745 0%, #1a5f2a 100%);">
        <div class="container text-center text-white">
            <h2 class="mb-3" data-aos="fade-up">{{ app()->getLocale() == 'bn' ? 'আপনিও তথ্য দিয়ে সহযোগিতা করুন' : 'Help us by adding information' }}</h2>
            <p class="mb-4" data-aos="fade-up" data-aos-delay="100">{{ app()->getLocale() == 'bn' ? 'আপনার এলাকার হোম টিউটর, টু-লেট, রেস্টুরেন্ট বা অন্য কোন তথ্য যোগ করতে রেজিস্টার করুন' : 'Register to add home tutor, to-let, restaurant or other information from your area' }}</p>
            @guest
                <a href="{{ route('register') }}" class="btn btn-warning btn-lg me-2" data-aos="fade-up" data-aos-delay="200">
                    <i class="fas fa-user-plus me-2"></i>{{ __('messages.register') }}
                </a>
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg" data-aos="fade-up" data-aos-delay="250">
                    <i class="fas fa-sign-in-alt me-2"></i>{{ __('messages.login') }}
                </a>
            @else
                <a href="{{ route('dashboard.listings.create') }}" class="btn btn-warning btn-lg" data-aos="fade-up" data-aos-delay="200">
                    <i class="fas fa-plus me-2"></i>{{ __('messages.add_listing') }}
                </a>
            @endguest
        </div>
    </section>
@endsection

@push('scripts')
<script>
function flipCategories() {
    const front = document.getElementById('categoryFront');
    const back = document.getElementById('categoryBack');
    
    if (front.style.display !== 'none') {
        front.style.opacity = '0';
        front.style.transform = 'rotateY(90deg)';
        setTimeout(() => {
            front.style.display = 'none';
            back.style.display = 'block';
            back.style.opacity = '0';
            back.style.transform = 'rotateY(-90deg)';
            setTimeout(() => {
                back.style.opacity = '1';
                back.style.transform = 'rotateY(0)';
            }, 50);
        }, 300);
    } else {
        back.style.opacity = '0';
        back.style.transform = 'rotateY(90deg)';
        setTimeout(() => {
            back.style.display = 'none';
            front.style.display = 'block';
            front.style.opacity = '0';
            front.style.transform = 'rotateY(-90deg)';
            setTimeout(() => {
                front.style.opacity = '1';
                front.style.transform = 'rotateY(0)';
            }, 50);
        }, 300);
    }
}
</script>
<style>
.category-flip-container {
    perspective: 1000px;
}
.category-flip-front, .category-flip-back {
    transition: all 0.3s ease;
    transform-style: preserve-3d;
}

/* Consistent Listing Card Styling */
.listing-card-new {
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.listing-card-new:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}

.listing-card-new .listing-img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.listing-card-new:hover .listing-img {
    transform: scale(1.05);
}

.listing-card-new .card-body {
    padding: 15px;
}

.listing-card-new .listing-title {
    font-size: 0.95rem;
    line-height: 1.4;
    height: 2.8em;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.listing-card-new .listing-title a:hover {
    color: #28a745 !important;
}

.listing-card-new .listing-desc {
    height: 2.6em;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

/* Featured Listings Cards */
.listing-card .card-img-top {
    height: 180px;
    object-fit: cover;
}

.listing-card .card-body {
    padding: 15px;
}

.listing-card .card-title {
    font-size: 0.95rem;
    line-height: 1.4;
    height: 2.8em;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

/* Promotional Ads Section - Mini Card Slider */
.promotional-ads-section {
    background: linear-gradient(135deg, #fff8e1 0%, #fff3cd 100%);
    border-top: 3px solid #ffc107;
    border-bottom: 3px solid #ffc107;
}

.ad-slider-wrapper {
    overflow-x: auto;
    scroll-behavior: smooth;
    scrollbar-width: none;
    -ms-overflow-style: none;
}

.ad-slider-wrapper::-webkit-scrollbar {
    display: none;
}

.ad-card-mini {
    width: 180px;
    min-width: 180px;
}

.ad-card-inner {
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    background: #fff;
    transition: all 0.3s ease;
}

.ad-card-inner:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.ad-badge-mini {
    position: absolute;
    top: 5px;
    left: 5px;
    background: linear-gradient(135deg, #ffc107, #ff9800);
    color: #000;
    font-size: 0.6rem;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 10px;
    z-index: 5;
}

.ad-img-mini {
    width: 100%;
    height: 120px;
    object-fit: cover;
}

.ad-info-mini {
    padding: 8px;
    background: #fff;
}

.ad-title-mini {
    font-size: 0.75rem;
    color: #333;
    font-weight: 500;
    line-height: 1.3;
    height: 2.6em;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

@media (max-width: 768px) {
    .ad-card-mini {
        width: 150px;
        min-width: 150px;
    }
    .ad-img-mini {
        height: 100px;
    }
    /* Mobile Carousel for card rows */
    .mobile-scroll-row {
        display: flex !important;
        flex-wrap: nowrap !important;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 8px;
        margin-left: -4px;
        margin-right: -4px;
        scrollbar-width: none;
        scroll-behavior: smooth;
        justify-content: flex-start !important;
    }
    .mobile-scroll-row::-webkit-scrollbar {
        display: none;
    }
    .mobile-scroll-row > [class*="col-"] {
        flex: 0 0 85%;
        max-width: 85%;
        scroll-snap-align: center;
        padding-left: 6px;
        padding-right: 6px;
    }
    .mobile-scroll-row > [class*="col-"]:first-child {
        margin-left: 7.5%;
    }
    .mobile-scroll-row > [class*="col-"]:last-child {
        margin-right: 7.5%;
    }
    /* Carousel dot indicators */
    .carousel-dots {
        display: flex;
        justify-content: center;
        gap: 6px;
        padding: 10px 0 4px;
    }
    .carousel-dots .dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #ccc;
        transition: all 0.3s ease;
        border: none;
        padding: 0;
        cursor: pointer;
    }
    .carousel-dots .dot.active {
        width: 20px;
        border-radius: 4px;
        background: var(--primary, #1a6b3c);
    }
}

/* Team Section */
.team-section {
    background: linear-gradient(180deg, #f8faff 0%, #eef2ff 100%);
    position: relative;
}
.team-card {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    position: relative;
    height: 100%;
}
.team-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(26,115,232,0.15);
}
.team-card-featured {
    border: 2px solid rgba(102,126,234,0.3);
}
.team-card-ribbon {
    position: absolute;
    top: 16px;
    right: -8px;
    background: linear-gradient(135deg, #ff9800, #f57c00);
    color: #fff;
    padding: 4px 16px 4px 12px;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 4px 0 0 4px;
    z-index: 2;
    box-shadow: 0 2px 8px rgba(255,152,0,0.4);
}
.team-card-ribbon::after {
    content: '';
    position: absolute;
    right: 0;
    bottom: -8px;
    border-top: 8px solid #e65100;
    border-right: 8px solid transparent;
}
.team-card-top {
    padding: 40px 20px 30px;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.team-card-pattern {
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 1px, transparent 1px),
                      radial-gradient(circle at 80% 20%, rgba(255,255,255,0.08) 1px, transparent 1px);
    background-size: 30px 30px;
}
.team-avatar {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid rgba(255,255,255,0.9);
    box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    position: relative;
    z-index: 1;
}
.team-avatar-initials {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    border: 4px solid rgba(255,255,255,0.9);
    box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    position: relative;
    z-index: 1;
}
.team-avatar-initials span {
    font-size: 2.2rem;
    font-weight: 700;
    letter-spacing: 2px;
    text-shadow: 0 2px 4px rgba(0,0,0,0.2);
}
.team-card-body {
    padding: 24px 24px 28px;
    text-align: center;
}
.team-role-badge {
    display: inline-block;
    padding: 4px 14px;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
}
.team-social {
    display: flex;
    justify-content: center;
    gap: 10px;
}
.team-social-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #e9ecef;
    color: #6c757d;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}
.team-social-btn:hover {
    background: var(--btn-color);
    border-color: var(--btn-color);
    color: #fff;
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}
</style>
@endpush

@push('scripts')
<script>
function scrollAds(direction) {
    const slider = document.getElementById('adSlider');
    const scrollAmount = 200;
    if (direction === 'left') {
        slider.scrollLeft -= scrollAmount;
    } else {
        slider.scrollLeft += scrollAmount;
    }
}

// Modern mobile carousel with dots & auto-slide
if (window.innerWidth <= 768) {
    document.querySelectorAll('.mobile-scroll-row').forEach(function(row) {
        if (row.scrollWidth <= row.clientWidth) return;
        var cards = Array.from(row.querySelectorAll(':scope > [class*="col-"]'));
        if (cards.length < 2) return;

        // Force scroll to first card immediately (no animation)
        row.style.scrollBehavior = 'auto';
        row.scrollLeft = 0;
        // Re-enable smooth scroll after a tick
        setTimeout(function() { row.style.scrollBehavior = ''; }, 50);

        // Create dot indicators
        var dotsWrap = document.createElement('div');
        dotsWrap.className = 'carousel-dots';
        cards.forEach(function(_, i) {
            var d = document.createElement('button');
            d.className = 'dot' + (i === 0 ? ' active' : '');
            d.setAttribute('aria-label', 'Slide ' + (i + 1));
            d.addEventListener('click', function() {
                goTo(i);
                pauseAuto();
            });
            dotsWrap.appendChild(d);
        });
        row.parentNode.insertBefore(dotsWrap, row.nextSibling);
        var dots = dotsWrap.querySelectorAll('.dot');

        var current = 0;
        var paused = false;
        var pauseTimer;

        function updateDots(idx) {
            dots.forEach(function(d, i) {
                d.classList.toggle('active', i === idx);
            });
        }

        function goTo(idx) {
            current = idx;
            var card = cards[idx];
            var scrollPos = card.offsetLeft - row.offsetLeft - (row.clientWidth - card.offsetWidth) / 2;
            row.scrollTo({ left: Math.max(0, scrollPos), behavior: 'smooth' });
            updateDots(idx);
        }

        function pauseAuto() {
            paused = true;
            clearTimeout(pauseTimer);
            pauseTimer = setTimeout(function() { paused = false; }, 5000);
        }

        // Detect current card on scroll (for manual swipe)
        var scrollDebounce;
        row.addEventListener('scroll', function() {
            clearTimeout(scrollDebounce);
            scrollDebounce = setTimeout(function() {
                var center = row.scrollLeft + row.clientWidth / 2;
                var closest = 0;
                var minDist = Infinity;
                cards.forEach(function(card, i) {
                    var cardCenter = card.offsetLeft - row.offsetLeft + card.offsetWidth / 2;
                    var dist = Math.abs(center - cardCenter);
                    if (dist < minDist) { minDist = dist; closest = i; }
                });
                current = closest;
                updateDots(closest);
            }, 80);
        });

        // Pause on touch
        row.addEventListener('touchstart', function() { pauseAuto(); }, { passive: true });

        // Auto-slide: show 1st card for 3.5s, then advance
        setInterval(function() {
            if (paused) return;
            current++;
            if (current >= cards.length) current = 0;
            goTo(current);
        }, 3500);
    });
}

// Stats counter animation
const counters = document.querySelectorAll('.counter');
const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
            entry.target.classList.add('counted');
            const target = +entry.target.getAttribute('data-target');
            const duration = 2000;
            const step = Math.max(1, Math.ceil(target / (duration / 16)));
            let current = 0;
            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    entry.target.textContent = target.toLocaleString();
                    clearInterval(timer);
                } else {
                    entry.target.textContent = current.toLocaleString();
                }
            }, 16);
        }
    });
}, { threshold: 0.5 });
counters.forEach(c => counterObserver.observe(c));
</script>
@endpush
