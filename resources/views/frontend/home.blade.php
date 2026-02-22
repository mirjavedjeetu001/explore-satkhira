@extends('frontend.layouts.app')

@section('title', __('messages.home'))

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
                                            <i class="fas {{ $category->icon }}"></i>
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
                                            <i class="fas {{ $category->icon }}"></i>
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
                                    <i class="fas fa-{{ $listing->category->icon ?? 'tag' }} me-1"></i>
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
