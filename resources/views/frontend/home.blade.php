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
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ app()->getLocale() == 'bn' ? ($listing->upazila?->name_bn ?? $listing->upazila?->name ?? 'Unknown') : ($listing->upazila?->name ?? 'Unknown') }}
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
                                    {{ app()->getLocale() == 'bn' ? ($listing->upazila?->name_bn ?? $listing->upazila?->name ?? 'Unknown') : ($listing->upazila?->name ?? 'Unknown') }}
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
</style>
@endpush
