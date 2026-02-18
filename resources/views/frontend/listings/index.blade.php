@extends('frontend.layouts.app')

@section('title', 'সকল তথ্য')

@section('content')
    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <h1>সকল তথ্য</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">হোম</a></li>
                    <li class="breadcrumb-item active">তথ্য সমূহ</li>
                </ol>
            </nav>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <!-- Filter Form -->
            <div class="card mb-4" data-aos="fade-up">
                <div class="card-body">
                    <form action="{{ route('listings.index') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <select name="upazila" class="form-select">
                                    <option value="">সকল উপজেলা</option>
                                    @foreach($upazilas as $upazila)
                                        <option value="{{ $upazila->id }}" {{ request('upazila') == $upazila->id ? 'selected' : '' }}>
                                            {{ $upazila->name_bn ?? $upazila->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="category" class="form-select">
                                    <option value="">সকল ক্যাটাগরি</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name_bn ?? $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="খুঁজুন..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-search me-1"></i> খুঁজুন
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <p class="text-muted mb-0">মোট {{ $listings->total() }} টি ফলাফল পাওয়া গেছে</p>
            </div>

            @if($listings->count() > 0)
                <div class="row g-4">
                    @foreach($listings as $listing)
                        <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                            <div class="listing-card h-100">
                                <div class="position-relative">
                                    <img src="{{ $listing->image ? asset('storage/' . $listing->image) : 'https://picsum.photos/seed/' . $listing->id . '/400/250' }}" 
                                         class="card-img-top" alt="{{ $listing->title }}" style="height: 180px; object-fit: cover;">
                                    @if($listing->category)
                                        <span class="category-badge text-white" style="background-color: {{ $listing->category->color ?? '#28a745' }}">
                                            {{ $listing->category->name_bn ?? $listing->category->name }}
                                        </span>
                                    @endif
                                    @if($listing->is_featured)
                                        <span class="featured-badge"><i class="fas fa-star me-1"></i>বিশেষ</span>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title mb-2">{{ Str::limit($listing->title_bn ?? $listing->title, 40) }}</h6>
                                    @if($listing->upazila)
                                        <p class="text-muted small mb-2">
                                            <i class="fas fa-map-marker-alt me-1 text-success"></i>
                                            {{ $listing->upazila->name_bn ?? $listing->upazila->name }}
                                        </p>
                                    @endif
                                    @if($listing->phone)
                                        <p class="text-muted small mb-2">
                                            <i class="fas fa-phone me-1 text-success"></i>{{ $listing->phone }}
                                        </p>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <small class="text-muted">
                                            <i class="fas fa-eye me-1"></i>{{ $listing->views ?? 0 }}
                                        </small>
                                        <a href="{{ route('listings.show', $listing) }}" class="btn btn-outline-success btn-sm">
                                            বিস্তারিত
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-5 d-flex justify-content-center">
                    {{ $listings->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-search fa-4x text-muted mb-3"></i>
                    <h4>কোন তথ্য পাওয়া যায়নি</h4>
                    <p class="text-muted">আপনার অনুসন্ধান পরিবর্তন করে আবার চেষ্টা করুন</p>
                    <a href="{{ route('listings.index') }}" class="btn btn-success">সব দেখুন</a>
                </div>
            @endif
        </div>
    </section>
@endsection
