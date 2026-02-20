@extends('frontend.layouts.app')

@section('title', 'আমার ড্যাশবোর্ড - Satkhira Portal')

@section('content')
<!-- Page Header -->
<section class="page-header py-4" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="text-white mb-0">স্বাগতম, {{ auth()->user()->name }}!</h3>
                <p class="text-white-50 mb-0">আপনার ড্যাশবোর্ড থেকে সকল কার্যক্রম পরিচালনা করুন</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('dashboard.listings.create') }}" class="btn btn-light">
                    <i class="fas fa-plus me-1"></i>নতুন তথ্য যোগ করুন
                </a>
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
                        <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt me-2"></i>ড্যাশবোর্ড
                        </a>
                        <a href="{{ route('dashboard.listings') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard.listings*') ? 'active' : '' }}">
                            <i class="fas fa-list me-2"></i>আমার তথ্যসমূহ
                        </a>
                        <a href="{{ route('dashboard.my-questions') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard.my-questions') ? 'active' : '' }}">
                            <i class="fas fa-question-circle me-2"></i>আমার প্রশ্নসমূহ
                        </a>
                        <a href="{{ route('dashboard.profile') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard.profile') ? 'active' : '' }}">
                            <i class="fas fa-user-edit me-2"></i>প্রোফাইল সম্পাদনা
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9">
                <!-- Stats Cards -->
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                                    <i class="fas fa-list fa-2x text-success"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0">{{ $stats['total_listings'] ?? 0 }}</h3>
                                    <p class="text-muted mb-0">মোট তথ্য</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                                    <i class="fas fa-clock fa-2x text-warning"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0">{{ $stats['pending_listings'] ?? 0 }}</h3>
                                    <p class="text-muted mb-0">অপেক্ষমাণ</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                                    <i class="fas fa-question-circle fa-2x text-info"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0">{{ $stats['total_questions'] ?? 0 }}</h3>
                                    <p class="text-muted mb-0">প্রশ্নসমূহ</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Listings -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-list text-success me-2"></i>সাম্প্রতিক তথ্যসমূহ</h5>
                        <a href="{{ route('dashboard.listings') }}" class="btn btn-sm btn-outline-success">সব দেখুন</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>শিরোনাম</th>
                                        <th>ক্যাটাগরি</th>
                                        <th>স্ট্যাটাস</th>
                                        <th>তারিখ</th>
                                        <th class="text-end">অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentListings ?? [] as $listing)
                                        <tr>
                                            <td>
                                                <a href="{{ route('listings.show', $listing) }}" class="text-decoration-none">
                                                    {{ Str::limit($listing->title, 30) }}
                                                </a>
                                            </td>
                                            <td><span class="badge bg-secondary">{{ $listing->category->name ?? 'N/A' }}</span></td>
                                            <td>
                                                @if($listing->status == 'pending')
                                                    <span class="badge bg-warning text-dark">অপেক্ষমাণ</span>
                                                @elseif($listing->status == 'approved')
                                                    <span class="badge bg-success">প্রকাশিত</span>
                                                @else
                                                    <span class="badge bg-danger">বাতিল</span>
                                                @endif
                                            </td>
                                            <td>{{ $listing->created_at->format('d M Y') }}</td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('dashboard.listings.edit', $listing) }}" class="btn btn-outline-primary" title="এডিট করুন">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($listing->status == 'approved')
                                                        <a href="{{ route('dashboard.listings.images', $listing) }}" class="btn btn-outline-warning" title="বিজ্ঞাপন/অফার যোগ করুন">
                                                            <i class="fas fa-ad"></i>
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('listings.show', $listing) }}" class="btn btn-outline-success" title="দেখুন">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                কোন তথ্য পাওয়া যায়নি। <a href="{{ route('dashboard.listings.create') }}">নতুন তথ্য যোগ করুন</a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Questions -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-question-circle text-info me-2"></i>সাম্প্রতিক প্রশ্নসমূহ</h5>
                        <a href="{{ route('dashboard.my-questions') }}" class="btn btn-sm btn-outline-info">সব দেখুন</a>
                    </div>
                    <div class="card-body">
                        @forelse($recentQuestions ?? [] as $question)
                            <div class="border-bottom pb-3 mb-3">
                                <p class="mb-2">{{ Str::limit($question->question, 100) }}</p>
                                <small class="text-muted">
                                    @if($question->answer)
                                        <span class="badge bg-success">উত্তর দেওয়া হয়েছে</span>
                                    @else
                                        <span class="badge bg-warning text-dark">উত্তরের অপেক্ষায়</span>
                                    @endif
                                    | {{ $question->created_at->diffForHumans() }}
                                </small>
                            </div>
                        @empty
                            <p class="text-center text-muted py-3">
                                কোন প্রশ্ন করা হয়নি। <a href="{{ route('mp.index') }}">সংসদ সদস্যকে প্রশ্ন করুন</a>
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
