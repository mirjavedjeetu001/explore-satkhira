@extends('frontend.layouts.app')

@section('title', ($category->name_bn ?? $category->name) . ' - Satkhira Portal')

@php
    $categoryName = $category->name_bn ?? $category->name;
    $seoTitle = $categoryName . ' সাতক্ষীরা - ' . ($categoryName) . ' তালিকা | এক্সপ্লোর সাতক্ষীরা';
    $seoDescription = 'সাতক্ষীরা জেলার সকল ' . $categoryName . ' এর তালিকা। সাতক্ষীরা সদর, কালীগঞ্জ, শ্যামনগর, আশাশুনি, দেবহাটা, কলারোয়া, তালা উপজেলায় ' . $categoryName . ' খুঁজুন। ঠিকানা, ফোন নম্বর সহ সম্পূর্ণ তথ্য।';
    $seoKeywords = $categoryName . ', ' . $categoryName . ' সাতক্ষীরা, ' . ($category->name ?? '') . ' Satkhira, সাতক্ষীরা ' . $categoryName . ', সাতক্ষীরা সদর ' . $categoryName . ', কালীগঞ্জ ' . $categoryName . ', শ্যামনগর ' . $categoryName;
@endphp

@section('seo_title', $seoTitle)
@section('seo_description', $seoDescription)
@section('seo_keywords', $seoKeywords)
@section('canonical_url', route('categories.show', $category))

@section('content')
<!-- Page Header -->
<section class="page-header py-5" style="background: linear-gradient(135deg, {{ $category->color ?? 'var(--primary-color)' }}, var(--secondary-color));">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center text-white">
                <i class="{{ $category->icon ?? 'fas fa-folder' }} fa-3x mb-3"></i>
                <h1 class="display-5 fw-bold">{{ $category->name_bn ?? $category->name }}</h1>
                @if($category->description)
                    <p class="lead mb-3">{{ $category->description }}</p>
                @endif
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">হোম</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('categories.index') }}" class="text-white-50">ক্যাটাগরি</a></li>
                        <li class="breadcrumb-item active text-white">{{ $category->name_bn ?? $category->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Search & Filter -->
<section class="py-4 bg-light border-bottom">
    <div class="container">
        <form action="{{ route('categories.show', $category) }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small text-muted">অনুসন্ধান</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="নাম, ঠিকানা দিয়ে খুঁজুন..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted">উপজেলা</label>
                    <select name="upazila" class="form-select">
                        <option value="">সকল উপজেলা</option>
                        @foreach($upazilas as $upazila)
                            <option value="{{ $upazila->id }}" {{ request('upazila') == $upazila->id ? 'selected' : '' }}>
                                {{ $upazila->name_bn ?? $upazila->name }} ({{ $upazila->listings_count }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-filter me-1"></i>ফিল্টার করুন
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Upazila Quick Filter -->
<section class="py-3 bg-white border-bottom">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-center gap-2">
            <a href="{{ route('categories.show', $category) }}" 
               class="btn btn-sm {{ !request('upazila') ? 'btn-success' : 'btn-outline-success' }}">
                <i class="fas fa-th-large me-1"></i>সকল উপজেলা
            </a>
            @foreach($upazilas as $upazila)
                @if($upazila->listings_count > 0)
                <a href="{{ route('categories.show', ['category' => $category, 'upazila' => $upazila->id]) }}" 
                   class="btn btn-sm {{ request('upazila') == $upazila->id ? 'btn-success' : 'btn-outline-secondary' }}">
                    {{ $upazila->name_bn ?? $upazila->name }} ({{ $upazila->listings_count }})
                </a>
                @endif
            @endforeach
        </div>
    </div>
</section>

<!-- Listings Grid -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-8">
                <h4>
                    <i class="{{ $category->icon ?? 'fas fa-list' }} text-success me-2"></i>
                    {{ $category->name_bn ?? $category->name }}
                    <span class="badge bg-secondary ms-2">{{ $listings->total() }} টি</span>
                </h4>
            </div>
            <div class="col-md-4 text-md-end">
                @auth
                    <a href="{{ route('dashboard.listings.create') }}?category={{ $category->id }}" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i>তথ্য যোগ করুন
                    </a>
                @endauth
            </div>
        </div>
        
        <div class="row g-4">
            @forelse($listings as $listing)
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                    <div class="listing-card card h-100 border-0 shadow-sm">
                        <div class="position-relative">
                            <img src="{{ $listing->image ? asset('storage/' . $listing->image) : 'https://picsum.photos/seed/' . $listing->id . '/400/180' }}" 
                                 class="card-img-top" alt="{{ $listing->title }}" style="height: 180px; object-fit: cover;">
                            <span class="badge bg-info position-absolute top-0 end-0 m-2">
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $listing->upazila->name ?? 'সকল উপজেলা' }}
                            </span>
                            @if($listing->is_featured)
                                <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2">
                                    <i class="fas fa-star"></i> ফিচার্ড
                                </span>
                            @endif
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ Str::limit($listing->title_bn ?? $listing->title, 40) }}</h5>
                            <p class="card-text text-muted small">{{ Str::limit($listing->description, 80) }}</p>
                            @if($listing->address)
                                <p class="mb-2 small"><i class="fas fa-map-marker-alt text-danger me-2"></i>{{ Str::limit($listing->address, 40) }}</p>
                            @endif
                            @if($listing->phone)
                                <p class="mb-2 small"><i class="fas fa-phone text-success me-2"></i>{{ $listing->phone }}</p>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <a href="{{ route('listings.show', $listing) }}" class="btn btn-outline-success w-100">
                                বিস্তারিত দেখুন <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">এই ক্যাটাগরিতে কোন তথ্য পাওয়া যায়নি</h5>
                        <p class="text-muted">প্রথম তথ্য যোগ করুন!</p>
                        @auth
                            <a href="{{ route('dashboard.listings.create') }}?category={{ $category->id }}" class="btn btn-success">
                                <i class="fas fa-plus me-1"></i>তথ্য যোগ করুন
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-success">
                                <i class="fas fa-sign-in-alt me-1"></i>লগইন করুন
                            </a>
                        @endauth
                    </div>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($listings->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $listings->withQueryString()->links() }}
            </div>
        @endif
    </div>
</section>

<!-- Related Categories -->
@php
    $relatedCategories = \App\Models\Category::active()
        ->where('id', '!=', $category->id)
        ->whereNull('parent_id')
        ->withCount(['listings' => fn($q) => $q->approved()])
        ->orderByDesc('listings_count')
        ->limit(4)
        ->get();
@endphp

@if($relatedCategories->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <h4 class="mb-4"><i class="fas fa-th-large text-success me-2"></i>অন্যান্য ক্যাটাগরি</h4>
        <div class="row g-4">
            @foreach($relatedCategories as $relCat)
                <div class="col-md-6 col-lg-3">
                    <a href="{{ route('categories.show', $relCat) }}" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm text-center p-4 category-card">
                            <i class="{{ $relCat->icon ?? 'fas fa-folder' }} fa-3x mb-3" style="color: {{ $relCat->color ?? '#28a745' }}"></i>
                            <h5 class="text-dark">{{ $relCat->name_bn ?? $relCat->name }}</h5>
                            <p class="text-muted mb-0">{{ $relCat->listings_count }} টি তথ্য</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<style>
.listing-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.listing-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}
.category-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}
</style>
@endsection
