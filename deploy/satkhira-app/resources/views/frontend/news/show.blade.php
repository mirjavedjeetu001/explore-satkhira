@extends('frontend.layouts.app')

@section('title', $news->title_bn ?? $news->title)

@section('content')
    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <h1>{{ Str::limit($news->title_bn ?? $news->title, 50) }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">হোম</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('news.index') }}">সংবাদ</a></li>
                    <li class="breadcrumb-item active">বিস্তারিত</li>
                </ol>
            </nav>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <article class="card border-0 shadow-sm" data-aos="fade-up">
                        @if($news->image)
                            <img src="{{ asset('storage/' . $news->image) }}" class="card-img-top" alt="{{ $news->title }}" style="max-height: 400px; object-fit: cover;">
                        @else
                            <img src="https://picsum.photos/seed/news{{ $news->id }}/800/400" class="card-img-top" alt="{{ $news->title }}" style="max-height: 400px; object-fit: cover;">
                        @endif
                        
                        <div class="card-body p-4">
                            <div class="d-flex gap-3 mb-3 flex-wrap">
                                <span class="badge bg-{{ $news->type == 'news' ? 'primary' : ($news->type == 'notice' ? 'warning' : 'info') }} fs-6">
                                    {{ $news->type == 'news' ? 'সংবাদ' : ($news->type == 'notice' ? 'নোটিশ' : 'ইভেন্ট') }}
                                </span>
                                @if($news->is_featured)
                                    <span class="badge bg-success fs-6"><i class="fas fa-star me-1"></i>বিশেষ</span>
                                @endif
                            </div>
                            
                            <h2 class="card-title mb-3">{{ $news->title_bn ?? $news->title }}</h2>
                            
                            <div class="d-flex gap-4 text-muted mb-4 flex-wrap">
                                <span><i class="fas fa-calendar me-1"></i>{{ $news->created_at->format('d M, Y') }}</span>
                                <span><i class="fas fa-eye me-1"></i>{{ $news->views ?? 0 }} বার দেখা হয়েছে</span>
                                @if($news->author)
                                    <span><i class="fas fa-user me-1"></i>{{ $news->author }}</span>
                                @endif
                            </div>
                            
                            <div class="news-content">
                                {!! $news->content !!}
                            </div>
                            
                            <!-- Share Buttons -->
                            <div class="border-top pt-4 mt-4">
                                <h6 class="mb-3"><i class="fas fa-share-alt me-2"></i>শেয়ার করুন:</h6>
                                <div class="d-flex gap-2">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                                       target="_blank" class="btn btn-primary btn-sm">
                                        <i class="fab fa-facebook-f me-1"></i>Facebook
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($news->title) }}" 
                                       target="_blank" class="btn btn-info btn-sm text-white">
                                        <i class="fab fa-twitter me-1"></i>Twitter
                                    </a>
                                    <a href="https://wa.me/?text={{ urlencode($news->title . ' ' . request()->url()) }}" 
                                       target="_blank" class="btn btn-success btn-sm">
                                        <i class="fab fa-whatsapp me-1"></i>WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                    
                    <!-- Back to list -->
                    <div class="mt-4">
                        <a href="{{ route('news.index') }}" class="btn btn-outline-success">
                            <i class="fas fa-arrow-left me-2"></i>সকল সংবাদে ফিরে যান
                        </a>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Related News -->
                    @if($relatedNews->count() > 0)
                        <div class="card border-0 shadow-sm" data-aos="fade-left">
                            <div class="card-header bg-success text-white">
                                <i class="fas fa-link me-2"></i>সম্পর্কিত সংবাদ
                            </div>
                            <div class="list-group list-group-flush">
                                @foreach($relatedNews as $related)
                                    <a href="{{ route('news.show', $related) }}" class="list-group-item list-group-item-action">
                                        <div class="d-flex">
                                            <img src="{{ $related->image ? asset('storage/' . $related->image) : 'https://picsum.photos/seed/r' . $related->id . '/100/70' }}" 
                                                 alt="{{ $related->title }}" class="rounded me-3" style="width: 80px; height: 60px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-1">{{ Str::limit($related->title_bn ?? $related->title, 40) }}</h6>
                                                <small class="text-muted">{{ $related->created_at->diffForHumans() }}</small>
                                            </div>
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

<style>
    .news-content {
        font-size: 1.1rem;
        line-height: 1.8;
    }
    .news-content p {
        margin-bottom: 1.5rem;
    }
    .news-content img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        margin: 1rem 0;
    }
</style>
