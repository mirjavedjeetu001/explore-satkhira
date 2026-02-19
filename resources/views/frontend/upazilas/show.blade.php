@extends('frontend.layouts.app')

@section('title', $upazila->name . ' - Satkhira Portal')

@section('content')
<!-- Page Header -->
<section class="page-header py-5" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center text-white">
                <h1 class="display-5 fw-bold">{{ $upazila->name }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">হোম</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('upazilas.index') }}" class="text-white-50">উপজেলা</a></li>
                        <li class="breadcrumb-item active text-white">{{ $upazila->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Categories Filter -->
<section class="py-4 bg-light border-bottom">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-center gap-2">
            <a href="{{ route('upazilas.show', $upazila) }}" 
               class="btn {{ !request('category') ? 'btn-success' : 'btn-outline-success' }}">
                <i class="fas fa-th-large me-1"></i>সকল ({{ $upazila->listings()->approved()->count() }})
            </a>
            @foreach($categories ?? [] as $category)
                @php
                    $count = $upazila->listings()->approved()->where('category_id', $category->id)->count();
                @endphp
                <a href="{{ route('upazilas.show', ['upazila' => $upazila, 'category' => $category->slug]) }}" 
                   class="btn {{ request('category') == $category->slug ? 'btn-success' : 'btn-outline-success' }}">
                    <i class="{{ $category->icon ?? 'fas fa-folder' }} me-1"></i>{{ $category->name }} ({{ $count }})
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Listings Grid -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-8">
                <h4><i class="fas fa-list text-success me-2"></i>
                    {{ request('category') ? ($categories->where('slug', request('category'))->first()->name ?? 'তথ্য') : 'সকল তথ্য' }}
                </h4>
            </div>
            <div class="col-md-4 text-md-end">
                @auth
                    <a href="{{ route('dashboard.listings.create') }}?upazila={{ $upazila->id }}" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i>তথ্য যোগ করুন
                    </a>
                @endauth
            </div>
        </div>
        
        <div class="row g-4">
            @forelse($listings ?? [] as $listing)
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                    <div class="listing-card card h-100 border-0 shadow-sm">
                        <div class="position-relative">
                            <img src="{{ $listing->image ? asset('storage/' . $listing->image) : 'https://picsum.photos/seed/' . $listing->id . '/400/180' }}" 
                                 class="card-img-top" alt="{{ $listing->title }}" style="height: 180px; object-fit: cover;">
                            <span class="badge bg-success position-absolute top-0 end-0 m-2">
                                {{ $listing->category->name ?? 'N/A' }}
                            </span>
                            @if($listing->is_featured)
                                <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2">
                                    <i class="fas fa-star"></i> ফিচার্ড
                                </span>
                            @endif
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ Str::limit($listing->title, 40) }}</h5>
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
                        @auth
                            <a href="{{ route('dashboard.listings.create') }}?upazila={{ $upazila->id }}" class="btn btn-success mt-3">
                                <i class="fas fa-plus me-1"></i>প্রথম তথ্য যোগ করুন
                            </a>
                        @endauth
                    </div>
                </div>
            @endforelse
        </div>
        
        @if(isset($listings) && $listings->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $listings->withQueryString()->links() }}
            </div>
        @endif
    </div>
</section>

<!-- About Upazila -->
@if($upazila->description)
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h4 class="mb-4">{{ $upazila->name }} সম্পর্কে</h4>
                <p class="text-muted">{{ $upazila->description }}</p>
            </div>
        </div>
    </div>
</section>
@endif
@endsection
