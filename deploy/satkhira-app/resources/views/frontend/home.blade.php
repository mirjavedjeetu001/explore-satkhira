@extends('frontend.layouts.app')

{{-- Title not set here so homepage shows only site name in browser tab --}}

@section('content')
    <!-- Hero Slider -->
    <section class="hero-slider">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @forelse($sliders as $index => $slider)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('{{ $slider->image ? asset('storage/' . $slider->image) : 'https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?w=1920' }}')">
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
                    <div class="carousel-item active" style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?w=1920')">
                        <div class="carousel-caption">
                            <h2>{{ __('messages.hero_title') }}</h2>
                            <p>{{ __('messages.hero_subtitle') }}</p>
                            <a href="{{ route('upazilas.index') }}" class="btn btn-primary-custom btn-lg mt-3">
                                {{ __('messages.view_all') }} <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
            @if($sliders->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            @endif
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
                        <div class="d-flex flex-column gap-2 align-items-center align-items-lg-end">
                            <button type="button" class="btn btn-ramadan btn-sm" data-bs-toggle="modal" data-bs-target="#ramadanScheduleModal">
                                <i class="fas fa-calendar-alt me-1"></i>সময়সূচী
                            </button>
                            <button type="button" class="btn btn-ramadan-share btn-sm" onclick="generateShareImage()">
                                <i class="fas fa-download me-1"></i>ডাউনলোড
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Hidden canvas for image generation -->
    <canvas id="shareCanvas" style="display: none;" width="800" height="500"></canvas>

    <!-- Ramadan Full Schedule Modal -->
    <div class="modal fade" id="ramadanScheduleModal" tabindex="-1" aria-labelledby="ramadanScheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content ramadan-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="ramadanScheduleModalLabel">
                        <i class="fas fa-moon me-2"></i>রমজান ১৪৪৭ হিজরী - সেহরি ও ইফতারের সময়সূচী
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
                                    <tr class="{{ $date == $today ? 'today-row' : '' }}">
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
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        box-shadow: 0 3px 10px rgba(212, 175, 55, 0.4);
    }
    
    .btn-ramadan:hover {
        background: linear-gradient(135deg, #e5c158, #d4af37);
        color: #1a472a;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(212, 175, 55, 0.5);
    }
    
    .btn-ramadan-share {
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
        border: 1px solid rgba(212, 175, 55, 0.6);
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.8rem;
        transition: all 0.3s ease;
    }
    
    .btn-ramadan-share:hover {
        background: rgba(212, 175, 55, 0.3);
        color: #fff;
        border-color: #d4af37;
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
    }
    
    .ramadan-modal .modal-header .modal-title {
        color: #d4af37;
    }
    
    .ramadan-modal .modal-body {
        color: #333;
        background: #fefefe;
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
        // Today's times
        const sehriTime = '{{ $todaySchedule['sehri'] ?? '5:00' }}';
        const iftarTime = '{{ $todaySchedule['iftar'] ?? '6:00' }}';
        
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
        }
        
        function updateCountdown() {
            const now = new Date();
            const today = now.toDateString();
            
            // Parse sehri time (AM)
            const [sehriHour, sehriMin] = sehriTime.split(':').map(Number);
            const sehriDate = new Date(today + ' ' + sehriHour + ':' + sehriMin + ':00');
            
            // Parse iftar time (PM - add 12 hours)
            const [iftarHour, iftarMin] = iftarTime.split(':').map(Number);
            const iftarDate = new Date(today + ' ' + (iftarHour + 12) + ':' + iftarMin + ':00');
            
            const sehriCountdownEl = document.getElementById('sehriCountdown');
            const iftarCountdownEl = document.getElementById('iftarCountdown');
            
            // Sehri countdown
            if (now < sehriDate) {
                const diff = sehriDate - now;
                const hours = Math.floor(diff / (1000 * 60 * 60));
                const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                sehriCountdownEl.textContent = 'বাকি ' + hours + ' ঘণ্টা ' + mins + ' মিনিট';
            } else {
                sehriCountdownEl.textContent = 'সেহরি শেষ';
            }
            
            // Iftar countdown
            if (now < iftarDate) {
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
        
        // Update current time every second
        setInterval(updateCurrentTime, 1000);
        // Update countdown every minute
        setInterval(updateCountdown, 60000);
    });
    
    // Generate shareable image
    function generateShareImage() {
        const canvas = document.getElementById('shareCanvas');
        const ctx = canvas.getContext('2d');
        
        // Canvas dimensions
        const width = 800;
        const height = 500;
        
        // Create gradient background
        const gradient = ctx.createLinearGradient(0, 0, width, height);
        gradient.addColorStop(0, '#1a472a');
        gradient.addColorStop(0.5, '#2d5016');
        gradient.addColorStop(1, '#1a472a');
        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, width, height);
        
        // Add decorative border
        ctx.strokeStyle = '#d4af37';
        ctx.lineWidth = 8;
        ctx.strokeRect(20, 20, width - 40, height - 40);
        
        // Inner border
        ctx.strokeStyle = 'rgba(212, 175, 55, 0.3)';
        ctx.lineWidth = 2;
        ctx.strokeRect(35, 35, width - 70, height - 70);
        
        // Arabic text - Ramadan Mubarak
        ctx.fillStyle = '#d4af37';
        ctx.font = 'bold 36px Amiri, serif';
        ctx.textAlign = 'center';
        ctx.fillText('رمضان مبارك', width / 2, 90);
        
        // Bengali title
        ctx.fillStyle = '#ffffff';
        ctx.font = 'bold 32px Hind Siliguri, sans-serif';
        ctx.fillText('রমজান মোবারক ১৪৪৭ হিজরী', width / 2, 140);
        
        // Today's date
        const ramadanDay = '{{ $todaySchedule['day'] ?? 1 }}';
        const todayBar = '{{ $todaySchedule['bar'] ?? 'রবি' }}';
        ctx.fillStyle = '#d4af37';
        ctx.font = 'bold 28px Hind Siliguri, sans-serif';
        ctx.fillText(ramadanDay + ' রমজান - ' + todayBar, width / 2, 185);
        
        // Separator line
        ctx.strokeStyle = '#d4af37';
        ctx.lineWidth = 2;
        ctx.beginPath();
        ctx.moveTo(200, 210);
        ctx.lineTo(600, 210);
        ctx.stroke();
        
        // Sehri box
        const boxY = 240;
        const boxHeight = 100;
        
        // Sehri background
        ctx.fillStyle = 'rgba(63, 81, 181, 0.4)';
        roundRect(ctx, 80, boxY, 280, boxHeight, 15);
        ctx.fill();
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.3)';
        ctx.lineWidth = 2;
        roundRect(ctx, 80, boxY, 280, boxHeight, 15);
        ctx.stroke();
        
        // Sehri text
        ctx.fillStyle = 'rgba(255, 255, 255, 0.8)';
        ctx.font = '18px Hind Siliguri, sans-serif';
        ctx.fillText('সেহরির শেষ সময়', 220, boxY + 35);
        ctx.fillStyle = '#ffffff';
        ctx.font = 'bold 36px Hind Siliguri, sans-serif';
        ctx.fillText('{{ $todaySchedule['sehri'] ?? '5:00' }} AM', 220, boxY + 78);
        
        // Iftar background
        ctx.fillStyle = 'rgba(255, 152, 0, 0.4)';
        roundRect(ctx, 440, boxY, 280, boxHeight, 15);
        ctx.fill();
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.3)';
        ctx.lineWidth = 2;
        roundRect(ctx, 440, boxY, 280, boxHeight, 15);
        ctx.stroke();
        
        // Iftar text
        ctx.fillStyle = 'rgba(255, 255, 255, 0.8)';
        ctx.font = '18px Hind Siliguri, sans-serif';
        ctx.fillText('ইফতারের সময়', 580, boxY + 35);
        ctx.fillStyle = '#ffffff';
        ctx.font = 'bold 36px Hind Siliguri, sans-serif';
        ctx.fillText('{{ $todaySchedule['iftar'] ?? '6:00' }} PM', 580, boxY + 78);
        
        // Location text
        ctx.fillStyle = 'rgba(255, 255, 255, 0.7)';
        ctx.font = '18px Hind Siliguri, sans-serif';
        ctx.fillText('📍 সাতক্ষীরা ও পার্শ্ববর্তী এলাকার জন্য প্রযোজ্য', width / 2, 385);
        
        // Website URL
        ctx.fillStyle = '#d4af37';
        ctx.font = 'bold 24px Hind Siliguri, sans-serif';
        ctx.fillText('🌐 exploresatkhira.com', width / 2, 430);
        
        // Date
        const today = new Date();
        const dateStr = today.toLocaleDateString('bn-BD', { day: 'numeric', month: 'long', year: 'numeric' });
        ctx.fillStyle = 'rgba(255, 255, 255, 0.6)';
        ctx.font = '16px Hind Siliguri, sans-serif';
        ctx.fillText(dateStr, width / 2, 465);
        
        // Download the image
        const link = document.createElement('a');
        link.download = 'ramadan-sehri-iftar-' + ramadanDay + '-satkhira.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
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
            
            <div class="row g-4">
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
            
            <div class="row g-4">
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
            
            <div class="row g-4 justify-content-center">
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
            
            <div class="row g-4">
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
            
            <div class="row g-4">
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
</script>
@endpush
