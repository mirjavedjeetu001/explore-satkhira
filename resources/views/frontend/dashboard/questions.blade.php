@extends('frontend.layouts.app')

@section('title', 'আমার প্রশ্নসমূহ - Satkhira Portal')

@section('content')
<!-- Page Header -->
<section class="page-header py-4" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="text-white mb-0">আমার প্রশ্নসমূহ</h3>
                <p class="text-white-50 mb-0">সংসদ সদস্যকে জিজ্ঞাসিত আপনার সকল প্রশ্ন</p>
            </div>
            <div class="col-md-4 text-md-end">
                @if(auth()->user()->wants_mp_questions)
                    <a href="{{ route('mp.index') }}" class="btn btn-light">
                        <i class="fas fa-plus me-1"></i>নতুন প্রশ্ন করুন
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Dashboard Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-4">
                        <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=28a745&color=fff&size=100' }}" 
                             alt="{{ auth()->user()->name }}" class="rounded-circle mb-3" width="100" height="100" style="object-fit: cover;">
                        <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                        <p class="text-muted small mb-0">{{ auth()->user()->email }}</p>
                        <span class="badge bg-success mt-2">{{ auth()->user()->role->display_name ?? 'User' }}</span>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-tachometer-alt me-2"></i>ড্যাশবোর্ড
                        </a>
                        @if(!auth()->user()->comment_only)
                            <a href="{{ route('dashboard.listings') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-list me-2"></i>আমার তথ্যসমূহ
                            </a>
                        @endif
                        @if(auth()->user()->wants_mp_questions)
                            <a href="{{ route('dashboard.my-questions') }}" class="list-group-item list-group-item-action active">
                                <i class="fas fa-question-circle me-2"></i>আমার প্রশ্নসমূহ
                            </a>
                        @endif
                        <a href="{{ route('dashboard.profile') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-user-edit me-2"></i>প্রোফাইল সম্পাদনা
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-question-circle text-info me-2"></i>আমার সকল প্রশ্ন ({{ $questions->total() }})</h5>
                    </div>
                    <div class="card-body">
                        @if($questions->count() > 0)
                            @foreach($questions as $question)
                                <div class="border rounded p-3 mb-3 {{ $question->answer ? 'bg-light' : '' }}">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            @if($question->status == 'approved')
                                                @if($question->answer)
                                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>উত্তর দেওয়া হয়েছে</span>
                                                @else
                                                    <span class="badge bg-info">অনুমোদিত - উত্তরের অপেক্ষায়</span>
                                                @endif
                                            @elseif($question->status == 'pending')
                                                <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>অপেক্ষমাণ</span>
                                            @else
                                                <span class="badge bg-danger">বাতিল</span>
                                            @endif
                                        </div>
                                        <small class="text-muted">{{ $question->created_at->format('d M Y, h:i A') }}</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <strong><i class="fas fa-question text-primary me-2"></i>প্রশ্ন:</strong>
                                        <p class="mb-0 mt-1">{{ $question->question }}</p>
                                    </div>
                                    
                                    @if($question->answer)
                                        <div class="bg-white p-3 rounded border-start border-4 border-success">
                                            <strong class="text-success"><i class="fas fa-reply me-2"></i>উত্তর:</strong>
                                            <p class="mb-0 mt-1">{{ $question->answer }}</p>
                                            @if($question->answered_at)
                                                <small class="text-muted mt-2 d-block">
                                                    উত্তর দেওয়া হয়েছে: {{ $question->answered_at->format('d M Y') }}
                                                </small>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                            
                            <div class="mt-4">
                                {{ $questions->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-comments fa-4x text-muted mb-3"></i>
                                <h5>কোন প্রশ্ন পাওয়া যায়নি</h5>
                                <p class="text-muted">আপনি এখনও সংসদ সদস্যকে কোন প্রশ্ন করেননি</p>
                                <a href="{{ route('mp.index') }}" class="btn btn-success">
                                    <i class="fas fa-plus me-1"></i>প্রথম প্রশ্ন করুন
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
