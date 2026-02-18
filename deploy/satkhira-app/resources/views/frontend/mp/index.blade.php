@extends('frontend.layouts.app')

@section('title', 'সংসদ সদস্য - Satkhira Portal')

@section('content')
<!-- Page Header -->
<section class="page-header py-5" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center text-white">
                <h1 class="display-5 fw-bold">সংসদ সদস্য</h1>
                <p class="lead mb-0">সাতক্ষীরা জেলার মাননীয় সংসদ সদস্যদের তথ্য ও প্রশ্নোত্তর</p>
            </div>
        </div>
    </div>
</section>

<!-- MP Profiles Grid -->
<section class="py-5">
    <div class="container">
        <h3 class="text-center mb-4"><i class="fas fa-user-tie text-success me-2"></i>আমাদের সংসদ সদস্যগণ</h3>
        
        <div class="row g-4">
            @forelse($mpProfiles ?? [] as $mp)
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="card border-0 shadow-sm h-100 mp-card">
                        <div class="card-body text-center p-4">
                            @if($mp->image)
                                <img src="{{ asset('storage/' . $mp->image) }}" alt="{{ $mp->name }}" 
                                     class="rounded-circle img-thumbnail mb-3" width="120" height="120" style="object-fit: cover;">
                            @else
                                <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-3" 
                                     style="width: 120px; height: 120px;">
                                    <i class="fas fa-user fa-3x"></i>
                                </div>
                            @endif
                            
                            <h5 class="text-success mb-1">{{ $mp->name_bn ?? $mp->name }}</h5>
                            <p class="text-muted mb-2">{{ $mp->designation ?? 'সংসদ সদস্য' }}</p>
                            <span class="badge bg-success mb-3">
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $mp->constituency ?? 'সাতক্ষীরা' }}
                            </span>
                            
                            @if($mp->bio)
                                <p class="small text-muted mb-3">{{ Str::limit($mp->bio, 100) }}</p>
                            @endif
                            
                            <div class="d-flex justify-content-center gap-2">
                                @if($mp->phone)
                                    <a href="tel:{{ $mp->phone }}" class="btn btn-sm btn-outline-success" title="ফোন">
                                        <i class="fas fa-phone"></i>
                                    </a>
                                @endif
                                @if($mp->email)
                                    <a href="mailto:{{ $mp->email }}" class="btn btn-sm btn-outline-success" title="ইমেইল">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                @endif
                                @if($mp->facebook)
                                    <a href="{{ $mp->facebook }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 text-center pb-4">
                            <a href="#ask-question" class="btn btn-success btn-sm" onclick="selectMp({{ $mp->id }})">
                                <i class="fas fa-question-circle me-1"></i>প্রশ্ন করুন
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-user-tie fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">সংসদ সদস্যের তথ্য শীঘ্রই যোগ করা হবে</h5>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Ask Question Section -->
<section class="py-5 bg-light" id="ask-question">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="fas fa-question-circle me-2"></i>প্রশ্ন করুন</h4>
                    </div>
                    <div class="card-body p-4">
                        @auth
                            @if(session('success'))
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                </div>
                            @endif
                            
                            <form action="{{ route('mp.question') }}" method="POST">
                                @csrf
                                
                                <div class="mb-3">
                                    <label class="form-label">কোন আসনের সংসদ সদস্যকে প্রশ্ন করতে চান? <span class="text-danger">*</span></label>
                                    <select name="mp_profile_id" id="mp_profile_id" class="form-select @error('mp_profile_id') is-invalid @enderror" required>
                                        <option value="">-- সংসদ সদস্য নির্বাচন করুন --</option>
                                        @foreach($mpProfiles ?? [] as $mp)
                                            <option value="{{ $mp->id }}" {{ old('mp_profile_id') == $mp->id ? 'selected' : '' }}>
                                                {{ $mp->name_bn ?? $mp->name }} - {{ $mp->constituency ?? 'সাতক্ষীরা' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('mp_profile_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">আপনার প্রশ্ন লিখুন <span class="text-danger">*</span></label>
                                    <textarea name="question" class="form-control @error('question') is-invalid @enderror" rows="4" required 
                                              placeholder="আপনার প্রশ্ন এখানে লিখুন...">{{ old('question') }}</textarea>
                                    @error('question')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-paper-plane me-1"></i>প্রশ্ন জমা দিন
                                </button>
                                <small class="text-muted d-block mt-2">* আপনার প্রশ্ন অনুমোদনের পর প্রকাশিত হবে</small>
                            </form>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-lock fa-3x text-muted mb-3"></i>
                                <h5>প্রশ্ন করতে লগইন করুন</h5>
                                <p class="text-muted">মাননীয় সংসদ সদস্যকে প্রশ্ন করতে আপনাকে প্রথমে লগইন করতে হবে</p>
                                <a href="{{ route('login') }}" class="btn btn-success">
                                    <i class="fas fa-sign-in-alt me-1"></i>লগইন করুন
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-outline-success">
                                    <i class="fas fa-user-plus me-1"></i>রেজিস্ট্রেশন করুন
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Questions Filter -->
<section class="py-4 bg-white border-bottom">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-center gap-2">
            <a href="{{ route('mp.index') }}" 
               class="btn btn-sm {{ !request('mp') ? 'btn-success' : 'btn-outline-success' }}">
                <i class="fas fa-th-large me-1"></i>সকল প্রশ্ন
            </a>
            @foreach($mpProfiles ?? [] as $mp)
                <a href="{{ route('mp.index', ['mp' => $mp->id]) }}" 
                   class="btn btn-sm {{ request('mp') == $mp->id ? 'btn-success' : 'btn-outline-secondary' }}">
                    {{ $mp->constituency ?? $mp->name }}
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Questions & Answers -->
<section class="py-5">
    <div class="container">
        <h3 class="text-center mb-5"><i class="fas fa-comments text-success me-2"></i>প্রশ্নোত্তর</h3>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @forelse($questions ?? [] as $question)
                    <div class="card border-0 shadow-sm mb-4" data-aos="fade-up">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                         style="width: 45px; height: 45px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $question->user->name ?? 'Anonymous' }}</strong>
                                        <small class="text-muted d-block">{{ $question->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    @if($question->mpProfile)
                                        <span class="badge bg-info mb-1">
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $question->mpProfile->constituency ?? 'N/A' }}
                                        </span>
                                        <br>
                                    @endif
                                    @if($question->answer)
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>উত্তর দেওয়া হয়েছে</span>
                                    @else
                                        <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>উত্তরের অপেক্ষায়</span>
                                    @endif
                                </div>
                            </div>
                            
                            @if($question->mpProfile)
                                <div class="mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-user-tie me-1"></i>প্রশ্ন করা হয়েছে: 
                                        <strong class="text-success">{{ $question->mpProfile->name_bn ?? $question->mpProfile->name }}</strong>
                                    </small>
                                </div>
                            @endif
                            
                            <div class="bg-light p-3 rounded mb-3">
                                <strong class="text-success"><i class="fas fa-question me-2"></i>প্রশ্ন:</strong>
                                <p class="mb-0 mt-2">{{ $question->question }}</p>
                            </div>
                            
                            @if($question->answer)
                                <div class="bg-success bg-opacity-10 p-3 rounded border-start border-success border-3">
                                    <strong class="text-success"><i class="fas fa-comment-dots me-2"></i>উত্তর:</strong>
                                    <p class="mb-0 mt-2">{{ $question->answer }}</p>
                                    @if($question->answered_at)
                                        <small class="text-muted d-block mt-2">উত্তর দেওয়া হয়েছে: {{ $question->answered_at->format('d M, Y') }}</small>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="fas fa-comments fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">এখনো কোন প্রশ্ন প্রকাশিত হয়নি</h5>
                        <p class="text-muted">প্রথম প্রশ্ন করুন!</p>
                    </div>
                @endforelse
                
                @if(isset($questions) && $questions->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $questions->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<style>
.mp-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.mp-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}
</style>

<script>
function selectMp(mpId) {
    document.getElementById('mp_profile_id').value = mpId;
}
</script>
@endsection
