@extends('frontend.layouts.app')

@section('title', $listing->title . ' - Satkhira Portal')

@php
    // Group promotional images by position
    $promotionalImages = $listing->approvedImages ?? collect();
    $topAds = $promotionalImages->whereIn('position', ['top-left', 'top-right', 'full-width']);
    $sidebarAds = $promotionalImages->whereIn('position', ['left', 'right', 'center']);
    $bottomAds = $promotionalImages->whereIn('position', ['bottom-left', 'bottom-right']);
    $allOtherAds = $promotionalImages->whereNotIn('position', ['top-left', 'top-right', 'full-width', 'left', 'right', 'center', 'bottom-left', 'bottom-right']);
    
    // SEO Variables
    $seoTitle = $listing->title . ' - ' . ($listing->category->name ?? '') . ' | ' . ($listing->upazila->name ?? 'সাতক্ষীরা');
    $seoDescription = Str::limit(strip_tags($listing->description ?? $listing->title . ' - ' . ($listing->category->name ?? '') . ' সাতক্ষীরা জেলায়'), 160);
    $seoImage = $listing->image ? asset('storage/' . $listing->image) : asset('images/og-image.jpg');
    $seoKeywords = implode(', ', array_filter([
        $listing->title,
        $listing->category->name ?? '',
        $listing->upazila->name ?? '',
        'সাতক্ষীরা',
        'Satkhira',
        $listing->address ?? ''
    ]));
@endphp

@section('seo_title', $seoTitle)
@section('seo_description', $seoDescription)
@section('seo_keywords', $seoKeywords)
@section('seo_image', $seoImage)
@section('canonical_url', route('listings.show', $listing))
@section('og_type', 'business.business')

@section('content')
<!-- Page Header -->
<section class="page-header py-4" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">হোম</a></li>
                @if($listing->upazila)
                    <li class="breadcrumb-item"><a href="{{ route('upazilas.show', $listing->upazila) }}" class="text-white-50">{{ $listing->upazila->name ?? 'উপজেলা' }}</a></li>
                @else
                    <li class="breadcrumb-item text-white-50">সকল উপজেলা</li>
                @endif
                <li class="breadcrumb-item"><a href="{{ route('categories.show', $listing->category) }}" class="text-white-50">{{ $listing->category->name ?? 'ক্যাটাগরি' }}</a></li>
                <li class="breadcrumb-item active text-white">{{ Str::limit($listing->title, 30) }}</li>
            </ol>
        </nav>
    </div>
</section>

<!-- TOP ADS ZONE (full-width, top-left, top-right) -->
@if($topAds->count() > 0)
<section class="py-3 bg-light">
    <div class="container">
        <div class="row g-3 justify-content-between">
            @foreach($topAds as $ad)
                @php
                    // Display size takes priority for width
                    $colClass = match($ad->display_size) {
                        'full-width' => 'col-12',
                        'extra-large' => 'col-12 col-md-10',
                        'large' => 'col-12 col-md-8',
                        'medium' => 'col-md-6',
                        'small' => 'col-md-4',
                        default => match($ad->position) {
                            'full-width' => 'col-12',
                            'top-left' => 'col-md-6',
                            'top-right' => 'col-md-6 ms-auto',
                            default => 'col-md-6'
                        }
                    };
                    // Add alignment class based on position
                    if($ad->display_size !== 'full-width' && $ad->position === 'top-right') {
                        $colClass .= ' ms-auto';
                    } elseif($ad->display_size !== 'full-width' && $ad->position !== 'top-left') {
                        $colClass .= ' mx-auto';
                    }
                    $imgHeight = match($ad->display_size) {
                        'small' => '120px',
                        'medium' => '180px',
                        'large' => '240px',
                        'extra-large' => '300px',
                        'full-width' => '250px',
                        default => '180px'
                    };
                @endphp
                <div class="{{ $colClass }}">
                    <div class="ad-banner position-relative overflow-hidden rounded shadow-sm" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#adModal{{ $ad->id }}">
                        <img src="{{ asset('storage/' . $ad->image) }}" alt="{{ $ad->title }}" class="w-100" style="height: {{ $imgHeight }}; object-fit: cover;">
                        <div class="ad-overlay position-absolute bottom-0 start-0 end-0 p-2 text-white" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                            <span class="badge bg-{{ $ad->type == 'offer' ? 'danger' : ($ad->type == 'promotion' ? 'warning' : 'info') }} me-1">
                                {{ \App\Models\ListingImage::getTypes()[$ad->type] ?? $ad->type }}
                            </span>
                            @if($ad->title)<small>{{ $ad->title }}</small>@endif
                        </div>
                    </div>
                </div>
                @include('frontend.listings._ad_modal', ['ad' => $ad])
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Listing Details -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    @php
                        $allImages = [];
                        if ($listing->image) {
                            $allImages[] = $listing->image;
                        }
                        if ($listing->gallery && is_array($listing->gallery)) {
                            $allImages = array_merge($allImages, $listing->gallery);
                        }
                    @endphp
                    
                    @if(count($allImages) > 1)
                        <!-- Image Carousel -->
                        <div id="listingCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                @foreach($allImages as $index => $img)
                                    <button type="button" data-bs-target="#listingCarousel" data-bs-slide-to="{{ $index }}" 
                                            class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"></button>
                                @endforeach
                            </div>
                            <div class="carousel-inner">
                                @foreach($allImages as $index => $img)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $img) }}" class="d-block w-100" alt="{{ $listing->title }}" 
                                             style="max-height: 400px; object-fit: cover;">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#listingCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#listingCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                        <!-- Thumbnail Gallery -->
                        <div class="d-flex gap-2 p-2 bg-light justify-content-center flex-wrap">
                            @foreach($allImages as $index => $img)
                                <img src="{{ asset('storage/' . $img) }}" 
                                     class="rounded cursor-pointer listing-thumb {{ $index === 0 ? 'border border-success border-2' : 'border' }}" 
                                     style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;"
                                     data-bs-target="#listingCarousel" data-bs-slide-to="{{ $index }}"
                                     onclick="document.querySelectorAll('.listing-thumb').forEach(t => t.classList.remove('border-success', 'border-2')); this.classList.add('border-success', 'border-2');">
                            @endforeach
                        </div>
                    @else
                        <!-- Single Image -->
                        <img src="{{ $listing->image ? asset('storage/' . $listing->image) : 'https://picsum.photos/seed/' . $listing->id . '/800/400' }}" 
                             class="card-img-top" alt="{{ $listing->title }}" style="max-height: 400px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="badge bg-success me-2">{{ $listing->category->name ?? 'N/A' }}</span>
                                <span class="badge bg-secondary">{{ $listing->upazila->name ?? 'সকল উপজেলা' }}</span>
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
                        @if($listing->map_embed || ($listing->latitude && $listing->longitude))
                            <div class="border-top pt-4 mt-4">
                                <h5 class="mb-3"><i class="fas fa-map text-success me-2"></i>ম্যাপে অবস্থান</h5>
                                <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm">
                                    @if($listing->map_embed)
                                        @php
                                            // Extract src from iframe if full HTML provided
                                            $mapEmbed = $listing->map_embed;
                                            if (preg_match('/src=["\']([^"\']+)["\']/', $mapEmbed, $matches)) {
                                                $mapSrc = $matches[1];
                                            } else {
                                                $mapSrc = $mapEmbed;
                                            }
                                        @endphp
                                        <iframe 
                                            src="{{ $mapSrc }}"
                                            style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                                        </iframe>
                                    @else
                                        <iframe 
                                            src="https://www.google.com/maps?q={{ $listing->latitude }},{{ $listing->longitude }}&z=15&output=embed"
                                            style="border:0;" allowfullscreen="" loading="lazy">
                                        </iframe>
                                    @endif
                                </div>
                                <div class="mt-2">
                                    <a href="https://www.google.com/maps?q={{ $listing->latitude ?? '' }},{{ $listing->longitude ?? '' }}" 
                                       target="_blank" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-external-link-alt me-1"></i>Google Maps এ দেখুন
                                    </a>
                                </div>
                            </div>
                        @endif

                        <!-- Offers, Promotions, Banners Section (Other positions) -->
                        @if($allOtherAds->count() > 0)
                            <div class="border-top pt-4 mt-4">
                                <h5 class="mb-3"><i class="fas fa-bullhorn text-warning me-2"></i>অফার ও প্রচার</h5>
                                <div class="row g-3">
                                    @foreach($allOtherAds as $promoImage)
                                        <div class="{{ $promoImage->getDisplaySizeClass() }}">
                                            <div class="card border h-100">
                                                @php
                                                    $imgHeight = match($promoImage->display_size) {
                                                        'small' => '150px',
                                                        'medium' => '200px',
                                                        'large' => '280px',
                                                        'extra-large' => '350px',
                                                        'full-width' => '400px',
                                                        default => '200px'
                                                    };
                                                @endphp
                                                <img src="{{ asset('storage/' . $promoImage->image) }}" 
                                                     class="card-img-top" 
                                                     alt="{{ $promoImage->title }}" 
                                                     style="height: {{ $imgHeight }}; object-fit: cover; cursor: pointer;"
                                                     data-bs-toggle="modal" 
                                                     data-bs-target="#adModal{{ $promoImage->id }}">
                                                <div class="card-body p-2">
                                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-1">
                                                        <span class="badge bg-{{ $promoImage->type == 'offer' ? 'danger' : ($promoImage->type == 'promotion' ? 'primary' : 'info') }}">
                                                            {{ \App\Models\ListingImage::getTypes()[$promoImage->type] ?? $promoImage->type }}
                                                        </span>
                                                        @if($promoImage->valid_until)
                                                            <small class="text-muted">মেয়াদ: {{ $promoImage->valid_until->format('d M Y') }}</small>
                                                        @endif
                                                    </div>
                                                    @if($promoImage->title)
                                                        <h6 class="mt-2 mb-0">{{ $promoImage->title }}</h6>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @include('frontend.listings._ad_modal', ['ad' => $promoImage])
                                    @endforeach
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
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @auth
                            <form action="{{ route('listings.comment', $listing) }}" method="POST" class="mb-4">
                                @csrf
                                <div class="mb-3">
                                    <textarea name="content" class="form-control" rows="3" placeholder="আপনার মন্তব্য লিখুন..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-paper-plane me-1"></i>মন্তব্য করুন
                                </button>
                                <small class="text-muted ms-2">মন্তব্য অ্যাডমিন অনুমোদনের পর প্রদর্শিত হবে।</small>
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
                <!-- SIDEBAR ADS (left, right, center positions) -->
                @if($sidebarAds->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0"><i class="fas fa-ad me-2"></i>বিজ্ঞাপন</h6>
                    </div>
                    <div class="card-body p-2">
                        @foreach($sidebarAds as $ad)
                            @php
                                $imgHeight = match($ad->display_size) {
                                    'small' => '120px',
                                    'medium' => '180px',
                                    'large' => '240px',
                                    'extra-large' => '280px',
                                    'full-width' => '200px',
                                    default => '180px'
                                };
                            @endphp
                            <div class="sidebar-ad mb-3 position-relative overflow-hidden rounded" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#adModal{{ $ad->id }}">
                                <img src="{{ asset('storage/' . $ad->image) }}" alt="{{ $ad->title }}" class="w-100" style="height: {{ $imgHeight }}; object-fit: cover;">
                                <div class="position-absolute bottom-0 start-0 end-0 p-2 text-white" style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                                    <span class="badge bg-{{ $ad->type == 'offer' ? 'danger' : ($ad->type == 'promotion' ? 'warning' : 'info') }} mb-1">
                                        {{ \App\Models\ListingImage::getTypes()[$ad->type] ?? $ad->type }}
                                    </span>
                                    @if($ad->title)<div class="small fw-bold">{{ $ad->title }}</div>@endif
                                </div>
                            </div>
                            @include('frontend.listings._ad_modal', ['ad' => $ad])
                        @endforeach
                    </div>
                </div>
                @endif
                
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

<!-- BOTTOM ADS ZONE (bottom-left, bottom-right) -->
@if($bottomAds->count() > 0)
<section class="py-4 bg-light">
    <div class="container">
        <div class="row g-3">
            @foreach($bottomAds as $ad)
                @php
                    $colClass = match($ad->position) {
                        'bottom-left' => 'col-md-6',
                        'bottom-right' => 'col-md-6 ms-auto',
                        default => 'col-md-6'
                    };
                    $imgHeight = match($ad->display_size) {
                        'small' => '120px',
                        'medium' => '180px',
                        'large' => '240px',
                        'extra-large' => '300px',
                        'full-width' => '200px',
                        default => '180px'
                    };
                @endphp
                <div class="{{ $colClass }}">
                    <div class="ad-banner position-relative overflow-hidden rounded shadow-sm" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#adModal{{ $ad->id }}">
                        <img src="{{ asset('storage/' . $ad->image) }}" alt="{{ $ad->title }}" class="w-100" style="height: {{ $imgHeight }}; object-fit: cover;">
                        <div class="ad-overlay position-absolute bottom-0 start-0 end-0 p-2 text-white" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                            <span class="badge bg-{{ $ad->type == 'offer' ? 'danger' : ($ad->type == 'promotion' ? 'warning' : 'info') }} me-1">
                                {{ \App\Models\ListingImage::getTypes()[$ad->type] ?? $ad->type }}
                            </span>
                            @if($ad->title)<small>{{ $ad->title }}</small>@endif
                        </div>
                    </div>
                </div>
                @include('frontend.listings._ad_modal', ['ad' => $ad])
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
