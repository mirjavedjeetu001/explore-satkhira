@extends('frontend.layouts.app')
@section('title', $survey->title . ' - সার্ভে')

@push('styles')
<style>
    .survey-hero {
        background: linear-gradient(135deg, #1a237e 0%, #283593 50%, #3949ab 100%);
        color: white;
        padding: 3rem 0;
        position: relative;
        overflow: hidden;
    }
    .survey-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
        animation: heroGlow 10s ease-in-out infinite;
    }
    @keyframes heroGlow {
        0%, 100% { transform: translate(0, 0); }
        50% { transform: translate(30px, 30px); }
    }
    .countdown-box {
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 20px;
        display: inline-flex;
        gap: 15px;
        border: 1px solid rgba(255,255,255,0.2);
    }
    .countdown-item {
        text-align: center;
        min-width: 60px;
    }
    .countdown-item .number {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1;
        display: block;
    }
    .countdown-item .label {
        font-size: 0.75rem;
        opacity: 0.8;
        text-transform: uppercase;
    }
    .survey-image {
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        max-height: 350px;
        object-fit: cover;
        width: 100%;
    }
    .vote-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 5px 30px rgba(0,0,0,0.08);
    }
    .option-radio {
        display: none;
    }
    .option-label {
        display: block;
        padding: 15px 20px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
        margin-bottom: 10px;
    }
    .option-label:hover {
        border-color: #3949ab;
        background: #f5f5ff;
    }
    .option-radio:checked + .option-label {
        border-color: #1a237e;
        background: linear-gradient(135deg, #e8eaf6, #c5cae9);
        color: #1a237e;
        font-weight: 700;
    }
    .option-radio:checked + .option-label .option-circle {
        background: #1a237e;
        border-color: #1a237e;
    }
    .option-radio:checked + .option-label .option-circle::after {
        content: '✓';
        color: white;
        font-size: 14px;
    }
    .option-circle {
        width: 24px;
        height: 24px;
        border: 2px solid #ccc;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        transition: all 0.3s ease;
    }
    .result-bar {
        background: #f0f0f0;
        border-radius: 12px;
        overflow: hidden;
        height: 45px;
        margin-bottom: 12px;
        position: relative;
    }
    .result-fill {
        height: 100%;
        border-radius: 12px;
        display: flex;
        align-items: center;
        padding: 0 15px;
        font-weight: 700;
        color: white;
        transition: width 1s ease;
        min-width: fit-content;
    }
    .result-label {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-weight: 600;
        z-index: 2;
    }
    .result-pct {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-weight: 700;
        z-index: 2;
    }
    .form-section {
        background: #f8f9ff;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e8eaf6;
    }
    .live-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #e53935;
        color: white;
        padding: 5px 15px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.85rem;
        animation: livePulse 1.5s ease-in-out infinite;
    }
    @keyframes livePulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    .live-dot {
        width: 8px;
        height: 8px;
        background: white;
        border-radius: 50%;
        animation: dotPulse 1s infinite;
    }
    @keyframes dotPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.5); }
    }
    .ended-badge {
        background: #616161;
        color: white;
        padding: 5px 15px;
        border-radius: 50px;
        font-weight: 700;
    }
    .total-votes-badge {
        background: linear-gradient(135deg, #1a237e, #3949ab);
        color: white;
        padding: 10px 25px;
        border-radius: 50px;
        font-size: 1.1rem;
        font-weight: 700;
    }
    .comment-list {
        max-height: 300px;
        overflow-y: auto;
    }
    .comment-item {
        background: #f5f5f5;
        border-radius: 8px;
        padding: 10px 15px;
        margin-bottom: 8px;
    }
    @media (max-width: 768px) {
        .countdown-item .number { font-size: 1.4rem; }
        .countdown-box { gap: 10px; padding: 15px; }
        .countdown-item { min-width: 45px; }
    }
</style>
@endpush

@section('content')
<!-- Hero -->
<section class="survey-hero">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="fas fa-poll fa-2x"></i>
                    @if($survey->is_live)
                        <span class="live-badge"><span class="live-dot"></span> LIVE</span>
                    @elseif($survey->is_ended)
                        <span class="ended-badge"><i class="fas fa-flag-checkered me-1"></i>সমাপ্ত</span>
                    @else
                        <span class="ended-badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>আসন্ন</span>
                    @endif
                </div>
                <h1 class="fw-bold mb-2">{{ $survey->title }}</h1>
                <p class="fs-5 opacity-90 mb-4">{{ $survey->question }}</p>

                @if(!$survey->is_ended)
                    <div class="mb-3">
                        <small class="opacity-75 d-block mb-2">
                            @if($survey->is_live)
                                সার্ভে শেষ হবে:
                            @else
                                সার্ভে শুরু হবে:
                            @endif
                        </small>
                        <div class="countdown-box" id="countdown" 
                             data-target="{{ $survey->is_live ? $survey->end_time->toIso8601String() : $survey->start_time->toIso8601String() }}">
                            <div class="countdown-item"><span class="number" id="cd-days">00</span><span class="label">দিন</span></div>
                            <div class="countdown-item"><span class="number" id="cd-hours">00</span><span class="label">ঘণ্টা</span></div>
                            <div class="countdown-item"><span class="number" id="cd-minutes">00</span><span class="label">মিনিট</span></div>
                            <div class="countdown-item"><span class="number" id="cd-seconds">00</span><span class="label">সেকেন্ড</span></div>
                        </div>
                    </div>
                @endif
            </div>
            @if($survey->image)
            <div class="col-lg-5 text-center">
                <img src="{{ asset('storage/' . $survey->image) }}" alt="{{ $survey->title }}" class="survey-image">
            </div>
            @endif
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Vote Form -->
            <div class="col-lg-7">
                @if($survey->is_live && !$hasVoted)
                    <div class="card vote-card">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h4 class="fw-bold"><i class="fas fa-vote-yea me-2 text-primary"></i>আপনার মতামত দিন</h4>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <form action="{{ route('survey.vote', $survey->id) }}" method="POST" id="voteForm">
                                @csrf
                                <input type="hidden" name="device_fingerprint" id="deviceFingerprint">

                                <!-- Options -->
                                <div class="mb-4">
                                    @foreach($survey->options as $i => $option)
                                        <input type="radio" name="selected_option" value="{{ $option }}" id="option{{ $i }}" class="option-radio" required
                                               {{ old('selected_option') == $option ? 'checked' : '' }}>
                                        <label for="option{{ $i }}" class="option-label d-flex align-items-center">
                                            <span class="option-circle"></span>
                                            {{ $option }}
                                        </label>
                                    @endforeach
                                </div>

                                <!-- Comment box (for অন্যান্য) -->
                                @if($survey->has_comment_option)
                                    <div class="mb-3" id="commentBox" style="display: none;">
                                        <label class="form-label fw-semibold">আপনার মতামত লিখুন</label>
                                        <textarea name="comment" class="form-control" rows="3" placeholder="আপনার মতামত...">{{ old('comment') }}</textarea>
                                    </div>
                                @endif

                                <!-- Info Fields -->
                                <div class="form-section">
                                    <h6 class="fw-bold mb-3"><i class="fas fa-user me-2"></i>আপনার তথ্য</h6>
                                    <p class="small text-muted mb-3"><i class="fas fa-lock me-1"></i>এই তথ্য সম্পূর্ণ গোপন থাকবে, শুধুমাত্র ভোট যাচাইয়ের জন্য ব্যবহৃত হবে।</p>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">নাম <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" required value="{{ old('name') }}" placeholder="আপনার নাম">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">মোবাইল নম্বর <span class="text-danger">*</span></label>
                                            <input type="text" name="phone" class="form-control" required value="{{ old('phone') }}" placeholder="01XXXXXXXXX" maxlength="11" pattern="01[3-9][0-9]{8}" title="সঠিক বাংলাদেশি মোবাইল নম্বর দিন (যেমন: 01712345678)">
                                            @error('phone')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">ক্লাস <span class="text-danger">*</span></label>
                                            <select name="class_type" class="form-select" required id="classType">
                                                <option value="">-- নির্বাচন করুন --</option>
                                                <option value="intermediate" {{ old('class_type') == 'intermediate' ? 'selected' : '' }}>ইন্টারমিডিয়েট</option>
                                                <option value="honours" {{ old('class_type') == 'honours' ? 'selected' : '' }}>অনার্স</option>
                                            </select>
                                        </div>

                                        <!-- Intermediate fields -->
                                        <div class="col-md-6 intermediate-fields" style="display: none;">
                                            <label class="form-label">বিভাগ <span class="text-danger">*</span></label>
                                            <select name="department" class="form-select department-select">
                                                <option value="">-- নির্বাচন করুন --</option>
                                                <option value="বিজ্ঞান" {{ old('department') == 'বিজ্ঞান' ? 'selected' : '' }}>বিজ্ঞান</option>
                                                <option value="মানবিক" {{ old('department') == 'মানবিক' ? 'selected' : '' }}>মানবিক</option>
                                                <option value="ব্যবসায় শিক্ষা" {{ old('department') == 'ব্যবসায় শিক্ষা' ? 'selected' : '' }}>ব্যবসায় শিক্ষা</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 intermediate-fields" style="display: none;">
                                            <label class="form-label">বর্ষ <span class="text-danger">*</span></label>
                                            <select name="year" class="form-select year-select">
                                                <option value="">-- নির্বাচন করুন --</option>
                                                <option value="১ম বর্ষ" {{ old('year') == '১ম বর্ষ' ? 'selected' : '' }}>১ম বর্ষ</option>
                                                <option value="২য় বর্ষ" {{ old('year') == '২য় বর্ষ' ? 'selected' : '' }}>২য় বর্ষ</option>
                                            </select>
                                        </div>

                                        <!-- Honours fields -->
                                        <div class="col-md-6 honours-fields" style="display: none;">
                                            <label class="form-label">ডিপার্টমেন্ট <span class="text-danger">*</span></label>
                                            <input type="text" name="department" class="form-control department-input" value="{{ old('department') }}" placeholder="যেমন: বাংলা, ইংরেজি, হিসাববিজ্ঞান">
                                        </div>
                                        <div class="col-md-3 honours-fields" style="display: none;">
                                            <label class="form-label">বর্ষ <span class="text-danger">*</span></label>
                                            <select name="year" class="form-select year-input">
                                                <option value="">-- বর্ষ --</option>
                                                <option value="১ম বর্ষ" {{ old('year') == '১ম বর্ষ' ? 'selected' : '' }}>১ম বর্ষ</option>
                                                <option value="২য় বর্ষ" {{ old('year') == '২য় বর্ষ' ? 'selected' : '' }}>২য় বর্ষ</option>
                                                <option value="৩য় বর্ষ" {{ old('year') == '৩য় বর্ষ' ? 'selected' : '' }}>৩য় বর্ষ</option>
                                                <option value="৪র্থ বর্ষ" {{ old('year') == '৪র্থ বর্ষ' ? 'selected' : '' }}>৪র্থ বর্ষ</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 honours-fields" style="display: none;">
                                            <label class="form-label">সেশন</label>
                                            <input type="text" name="session" class="form-control" value="{{ old('session') }}" placeholder="যেমন: 2023-24">
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-lg w-100 text-white fw-bold" style="background: linear-gradient(135deg, #1a237e, #3949ab); border-radius: 12px; padding: 14px;">
                                        <i class="fas fa-vote-yea me-2"></i>ভোট দিন
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @elseif($hasVoted)
                    <div class="card vote-card">
                        <div class="card-body text-center py-5">
                            <div class="mb-3"><i class="fas fa-check-circle fa-4x text-success"></i></div>
                            <h3 class="fw-bold text-success">আপনার ভোট গৃহীত হয়েছে!</h3>
                            <p class="text-muted">ধন্যবাদ আপনার মতামতের জন্য। ফলাফল পাশে দেখুন।</p>
                        </div>
                    </div>
                @else
                    <div class="card vote-card">
                        <div class="card-body text-center py-5">
                            @if($survey->is_ended)
                                <div class="mb-3"><i class="fas fa-flag-checkered fa-4x text-secondary"></i></div>
                                <h3 class="fw-bold">সার্ভে শেষ হয়েছে</h3>
                                <p class="text-muted">এই সার্ভের সময়সীমা শেষ হয়ে গেছে। ফলাফল পাশে দেখুন।</p>
                            @else
                                <div class="mb-3"><i class="fas fa-clock fa-4x text-warning"></i></div>
                                <h3 class="fw-bold">সার্ভে এখনো শুরু হয়নি</h3>
                                <p class="text-muted">{{ $survey->start_time->format('d M Y, h:i A') }} তে শুরু হবে।</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Results -->
            <div class="col-lg-5">
                <div class="card vote-card" id="resultsCard">
                    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h4 class="fw-bold mb-0"><i class="fas fa-chart-bar me-2 text-success"></i>লাইভ ফলাফল</h4>
                        @if($survey->is_live)
                            <span class="live-badge"><span class="live-dot"></span> LIVE</span>
                        @endif
                    </div>
                    <div class="card-body px-4 pb-4" id="resultsBody">
                        @foreach($results as $i => $result)
                            <div class="result-bar" data-option="{{ $result['option'] }}">
                                @php
                                    $colors = ['#28a745', '#dc3545', '#ffc107', '#17a2b8', '#6f42c1', '#fd7e14'];
                                    $color = $colors[$i % count($colors)];
                                @endphp
                                <div class="result-fill" style="width: {{ max($result['percentage'], 5) }}%; background: {{ $color }};">
                                </div>
                                <span class="result-label" style="color: {{ $result['percentage'] > 30 ? 'white' : '#333' }};">{{ $result['option'] }}</span>
                                <span class="result-pct" style="color: {{ $result['percentage'] > 80 ? 'white' : '#333' }};">{{ $result['count'] }} ({{ $result['percentage'] }}%)</span>
                            </div>
                        @endforeach

                        <div class="text-center mt-4">
                            <div style="margin-bottom: 12px;">
                                <span class="total-votes-badge" id="totalVotes">মোট ভোট: {{ $totalVotes }}</span>
                            </div>
                            @if($cancelledVotes > 0)
                                <div style="margin-top: 10px;">
                                    <span style="display: inline-block; background: #dc3545; color: white; font-size: 0.8rem; padding: 5px 12px; border-radius: 50px;" id="cancelledVotes"><i class="fas fa-ban me-1"></i>বাতিল ভোট: {{ $cancelledVotes }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Chart -->
                <div class="card vote-card mt-4">
                    <div class="card-body">
                        <canvas id="surveyChart" height="250"></canvas>
                    </div>
                </div>

                <!-- Comments (অন্যান্য) -->
                @php $comments = $survey->votes->whereNotNull('comment')->where('comment', '!=', ''); @endphp
                @if($comments->count() > 0)
                <div class="card vote-card mt-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold"><i class="fas fa-comments me-2 text-info"></i>মতামত ({{ $comments->count() }})</h5>
                    </div>
                    <div class="card-body px-4 pb-4 comment-list">
                        @foreach($comments->take(20) as $vote)
                            <div class="comment-item">
                                <small class="text-muted">{{ $vote->created_at->diffForHumans() }}</small>
                                <p class="mb-0">{{ $vote->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Device fingerprint
    (function() {
        const fpEl = document.getElementById('deviceFingerprint');
        if (!fpEl) return;
        const fp = [
            navigator.userAgent,
            navigator.language,
            screen.width + 'x' + screen.height,
            new Date().getTimezoneOffset(),
            navigator.hardwareConcurrency || ''
        ].join('|');
        let hash = 0;
        for (let i = 0; i < fp.length; i++) {
            const char = fp.charCodeAt(i);
            hash = ((hash << 5) - hash) + char;
            hash |= 0;
        }
        fpEl.value = Math.abs(hash).toString(36);
    })();

    // Countdown
    const countdownEl = document.getElementById('countdown');
    if (countdownEl) {
        const target = new Date(countdownEl.dataset.target).getTime();
        const timer = setInterval(function() {
            const now = new Date().getTime();
            const diff = target - now;
            if (diff <= 0) {
                clearInterval(timer);
                location.reload();
                return;
            }
            document.getElementById('cd-days').textContent = String(Math.floor(diff / 86400000)).padStart(2, '0');
            document.getElementById('cd-hours').textContent = String(Math.floor((diff % 86400000) / 3600000)).padStart(2, '0');
            document.getElementById('cd-minutes').textContent = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
            document.getElementById('cd-seconds').textContent = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
        }, 1000);
    }

    // Class type toggle
    const classType = document.getElementById('classType');
    if (classType) {
        function toggleClassFields() {
            const val = classType.value;
            document.querySelectorAll('.intermediate-fields').forEach(el => {
                el.style.display = val === 'intermediate' ? '' : 'none';
                el.querySelectorAll('select, input').forEach(inp => {
                    inp.disabled = val !== 'intermediate';
                    if (val === 'intermediate') inp.setAttribute('required', '');
                    else inp.removeAttribute('required');
                });
            });
            document.querySelectorAll('.honours-fields').forEach(el => {
                el.style.display = val === 'honours' ? '' : 'none';
                el.querySelectorAll('select, input').forEach(inp => {
                    inp.disabled = val !== 'honours';
                    if (val === 'honours' && !inp.name.includes('session')) inp.setAttribute('required', '');
                    else if (val !== 'honours') inp.removeAttribute('required');
                });
            });
        }
        classType.addEventListener('change', toggleClassFields);
        toggleClassFields();
    }

    // Comment box toggle for last option (অন্যান্য)
    @if($survey->has_comment_option)
        const options = document.querySelectorAll('.option-radio');
        const commentBox = document.getElementById('commentBox');
        const lastOptionIdx = options.length - 1;
        options.forEach(function(radio, idx) {
            radio.addEventListener('change', function() {
                commentBox.style.display = idx === lastOptionIdx ? 'block' : 'none';
            });
        });
        // Restore on page load
        if (options[lastOptionIdx] && options[lastOptionIdx].checked) {
            commentBox.style.display = 'block';
        }
    @endif

    // Chart
    const chartColors = ['#28a745', '#dc3545', '#ffc107', '#17a2b8', '#6f42c1', '#fd7e14'];
    let chartData = {
        labels: {!! json_encode(collect($results)->pluck('option')) !!},
        counts: {!! json_encode(collect($results)->pluck('count')) !!}
    };
    let chart = null;
    if (typeof Chart !== 'undefined') {
        const ctx = document.getElementById('surveyChart').getContext('2d');
        chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: chartData.labels,
                datasets: [{
                    data: chartData.counts,
                    backgroundColor: chartColors.slice(0, chartData.labels.length),
                    borderWidth: 3,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom', labels: { font: { size: 13 }, padding: 15 } },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const pct = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                return context.label + ': ' + context.parsed + ' (' + pct + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // Live polling (every 10 seconds)
    @if($survey->is_live)
    setInterval(function() {
        fetch('{{ route("survey.results", $survey->id) }}')
            .then(r => r.json())
            .then(data => {
                // Update bars
                data.results.forEach(function(r, i) {
                    const bars = document.querySelectorAll('.result-bar');
                    if (bars[i]) {
                        const fill = bars[i].querySelector('.result-fill');
                        const pct = bars[i].querySelector('.result-pct');
                        const label = bars[i].querySelector('.result-label');
                        fill.style.width = Math.max(r.percentage, 5) + '%';
                        pct.textContent = r.count + ' (' + r.percentage + '%)';
                        pct.style.color = r.percentage > 80 ? 'white' : '#333';
                        label.style.color = r.percentage > 30 ? 'white' : '#333';
                    }
                });
                // Update total
                document.getElementById('totalVotes').textContent = 'মোট ভোট: ' + data.totalVotes;
                // Update cancelled
                const cancelledEl = document.getElementById('cancelledVotes');
                if (cancelledEl && data.cancelledVotes !== undefined) {
                    cancelledEl.innerHTML = '<i class="fas fa-ban me-1"></i>বাতিল ভোট: ' + data.cancelledVotes;
                }
                // Update chart
                if (chart) {
                    chart.data.datasets[0].data = data.results.map(r => r.count);
                    chart.update();
                }
            })
            .catch(() => {});
    }, 10000);
    @endif
</script>
@endpush
