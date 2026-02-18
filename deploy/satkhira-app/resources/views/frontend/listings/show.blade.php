@extends('frontend.layouts.app')

@section('title', $listing->title . ' - Satkhira Portal')

@section('content')
<!-- Page Header -->
<section class="page-header py-4" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">হোম</a></li>
                <li class="breadcrumb-item"><a href="{{ route('upazilas.show', $listing->upazila) }}" class="text-white-50">{{ $listing->upazila->name ?? 'উপজেলা' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('categories.show', $listing->category) }}" class="text-white-50">{{ $listing->category->name ?? 'ক্যাটাগরি' }}</a></li>
                <li class="breadcrumb-item active text-white">{{ Str::limit($listing->title, 30) }}</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Listing Details -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <img src="{{ $listing->image ? asset('storage/' . $listing->image) : 'https://picsum.photos/seed/' . $listing->id . '/800/400' }}" 
                         class="card-img-top" alt="{{ $listing->title }}" style="max-height: 400px; object-fit: cover;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="badge bg-success me-2">{{ $listing->category->name ?? 'N/A' }}</span>
                                <span class="badge bg-secondary">{{ $listing->upazila->name ?? 'N/A' }}</span>
                                @if($listing->is_featured)
                                    <span class="badge bg-warning text-dark"><i class="fas fa-star"></i> ফিচার্ড</span>
                                @endif
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-eye me-1"></i>{{ $listing->views ?? 0 }} বার দেখা হয়েছে
                            </small>
                        </div>
                        
                        <h2 class="card-title mb-4">{{ $listing->title }}</h2>
                        
                        <div class="listing-description mb-4">
                            {!! nl2br(e($listing->description)) !!}
                        </div>
                        
                        <!-- Contact Info -->
                        <div class="border-top pt-4">
                            <h5 class="mb-3"><i class="fas fa-address-card text-success me-2"></i>যোগাযোগের তথ্য</h5>
                            <div class="row g-3">
                                @if($listing->address)
                                    <div class="col-md-6">
                                        <div class="d-flex">
                                            <i class="fas fa-map-marker-alt text-danger me-3 mt-1"></i>
                                            <div>
                                                <strong>ঠিকানা</strong>
                                                <p class="mb-0 text-muted">{{ $listing->address }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if($listing->phone)
                                    <div class="col-md-6">
                                        <div class="d-flex">
                                            <i class="fas fa-phone text-success me-3 mt-1"></i>
                                            <div>
                                                <strong>ফোন</strong>
                                                <p class="mb-0"><a href="tel:{{ $listing->phone }}" class="text-decoration-none">{{ $listing->phone }}</a></p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if($listing->email)
                                    <div class="col-md-6">
                                        <div class="d-flex">
                                            <i class="fas fa-envelope text-primary me-3 mt-1"></i>
                                            <div>
                                                <strong>ইমেইল</strong>
                                                <p class="mb-0"><a href="mailto:{{ $listing->email }}" class="text-decoration-none">{{ $listing->email }}</a></p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if($listing->website)
                                    <div class="col-md-6">
                                        <div class="d-flex">
                                            <i class="fas fa-globe text-info me-3 mt-1"></i>
                                            <div>
                                                <strong>ওয়েবসাইট</strong>
                                                <p class="mb-0"><a href="{{ $listing->website }}" target="_blank" class="text-decoration-none">{{ $listing->website }}</a></p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Map -->
                        @if($listing->latitude && $listing->longitude)
                            <div class="border-top pt-4 mt-4">
                                <h5 class="mb-3"><i class="fas fa-map text-success me-2"></i>ম্যাপে অবস্থান</h5>
                                <div class="ratio ratio-16x9">
                                    <iframe 
                                        src="https://www.google.com/maps?q={{ $listing->latitude }},{{ $listing->longitude }}&z=15&output=embed"
                                        style="border:0;" allowfullscreen="" loading="lazy">
                                    </iframe>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-transparent">
                        <small class="text-muted">
                            <i class="fas fa-user me-1"></i>যোগ করেছেন: {{ $listing->user->name ?? 'Admin' }} | 
                            <i class="fas fa-calendar me-1"></i>{{ $listing->created_at->format('d M, Y') }}
                        </small>
                    </div>
                </div>
                
                <!-- Comments Section -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-comments text-success me-2"></i>মন্তব্যসমূহ</h5>
                    </div>
                    <div class="card-body">
                        @auth
                            <form action="{{ route('listings.comment', $listing) }}" method="POST" class="mb-4">
                                @csrf
                                <div class="mb-3">
                                    <textarea name="content" class="form-control" rows="3" placeholder="আপনার মন্তব্য লিখুন..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-paper-plane me-1"></i>মন্তব্য করুন
                                </button>
                            </form>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>মন্তব্য করতে <a href="{{ route('login') }}">লগইন করুন</a>
                            </div>
                        @endauth
                        
                        @forelse($listing->comments()->approved()->latest()->get() as $comment)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $comment->user->name ?? 'Anonymous' }}</strong>
                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-0 mt-2">{{ $comment->content }}</p>
                            </div>
                        @empty
                            <p class="text-muted text-center py-4">
                                <i class="fas fa-comments fa-2x mb-2 d-block"></i>
                                কোন মন্তব্য নেই। প্রথম মন্তব্য করুন!
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Share -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="mb-3"><i class="fas fa-share-alt text-success me-2"></i>শেয়ার করুন</h6>
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                               target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($listing->title) }}" 
                               target="_blank" class="btn btn-outline-info btn-sm">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($listing->title . ' ' . request()->url()) }}" 
                               target="_blank" class="btn btn-outline-success btn-sm">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Related Listings -->
                @if(isset($relatedListings) && $relatedListings->count() > 0)
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h6 class="mb-0"><i class="fas fa-th-list text-success me-2"></i>সম্পর্কিত তথ্য</h6>
                        </div>
                        <div class="card-body p-0">
                            @foreach($relatedListings as $related)
                                <a href="{{ route('listings.show', $related) }}" class="d-flex border-bottom p-3 text-decoration-none">
                                    <img src="{{ $related->image ? asset('storage/' . $related->image) : 'https://picsum.photos/seed/' . $related->id . '/60/60' }}" 
                                         alt="{{ $related->title }}" width="60" height="60" class="rounded me-3" style="object-fit: cover;">
                                    <div>
                                        <h6 class="mb-1 text-dark">{{ Str::limit($related->title, 30) }}</h6>
                                        <small class="text-muted">{{ $related->upazila->name ?? '' }}</small>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
