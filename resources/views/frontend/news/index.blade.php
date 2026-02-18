@extends('frontend.layouts.app')

@section('title', 'সংবাদ ও ঘোষণা')

@section('content')
    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <h1>সংবাদ ও ঘোষণা</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">হোম</a></li>
                    <li class="breadcrumb-item active">সংবাদ</li>
                </ol>
            </nav>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Filter -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">সর্বশেষ সংবাদ</h5>
                        <form action="{{ route('news.index') }}" method="GET" class="d-flex gap-2">
                            <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">সকল প্রকার</option>
                                <option value="news" {{ request('type') == 'news' ? 'selected' : '' }}>সংবাদ</option>
                                <option value="notice" {{ request('type') == 'notice' ? 'selected' : '' }}>নোটিশ</option>
                                <option value="event" {{ request('type') == 'event' ? 'selected' : '' }}>ইভেন্ট</option>
                            </select>
                        </form>
                    </div>

                    @if($news->count() > 0)
                        <div class="row g-4">
                            @foreach($news as $item)
                                <div class="col-md-6" data-aos="fade-up">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <img src="{{ $item->image ? asset('storage/' . $item->image) : 'https://picsum.photos/seed/news' . $item->id . '/600/400' }}" 
                                             class="card-img-top" alt="{{ $item->title }}" style="height: 200px; object-fit: cover;">
                                        <div class="card-body">
                                            <div class="d-flex gap-2 mb-2">
                                                <span class="badge bg-{{ $item->type == 'news' ? 'primary' : ($item->type == 'notice' ? 'warning' : 'info') }}">
                                                    {{ $item->type == 'news' ? 'সংবাদ' : ($item->type == 'notice' ? 'নোটিশ' : 'ইভেন্ট') }}
                                                </span>
                                                @if($item->is_featured)
                                                    <span class="badge bg-success"><i class="fas fa-star"></i></span>
                                                @endif
                                            </div>
                                            <h5 class="card-title">{{ Str::limit($item->title_bn ?? $item->title, 50) }}</h5>
                                            <p class="card-text text-muted small">
                                                {{ Str::limit(strip_tags($item->content), 100) }}
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>{{ $item->created_at->format('d M, Y') }}
                                                </small>
                                                <a href="{{ route('news.show', $item) }}" class="btn btn-sm btn-outline-success">
                                                    পড়ুন <i class="fas fa-arrow-right ms-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-5 d-flex justify-content-center">
                            {{ $news->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-newspaper fa-4x text-muted mb-3"></i>
                            <h4>কোন সংবাদ পাওয়া যায়নি</h4>
                            <p class="text-muted">শীঘ্রই নতুন সংবাদ প্রকাশিত হবে</p>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Featured News -->
                    @if($featuredNews->count() > 0)
                        <div class="card border-0 shadow-sm mb-4" data-aos="fade-left">
                            <div class="card-header bg-success text-white">
                                <i class="fas fa-star me-2"></i>বিশেষ সংবাদ
                            </div>
                            <div class="list-group list-group-flush">
                                @foreach($featuredNews as $featured)
                                    <a href="{{ route('news.show', $featured) }}" class="list-group-item list-group-item-action">
                                        <div class="d-flex">
                                            <img src="{{ $featured->image ? asset('storage/' . $featured->image) : 'https://picsum.photos/seed/f' . $featured->id . '/100/70' }}" 
                                                 alt="{{ $featured->title }}" class="rounded me-3" style="width: 80px; height: 60px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-1">{{ Str::limit($featured->title_bn ?? $featured->title, 40) }}</h6>
                                                <small class="text-muted">{{ $featured->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Categories -->
                    <div class="card border-0 shadow-sm" data-aos="fade-left">
                        <div class="card-header bg-success text-white">
                            <i class="fas fa-folder me-2"></i>সংবাদের প্রকার
                        </div>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('news.index') }}" class="list-group-item list-group-item-action {{ !request('type') ? 'active' : '' }}">
                                <i class="fas fa-list me-2"></i>সকল
                            </a>
                            <a href="{{ route('news.index', ['type' => 'news']) }}" class="list-group-item list-group-item-action {{ request('type') == 'news' ? 'active' : '' }}">
                                <i class="fas fa-newspaper me-2"></i>সংবাদ
                            </a>
                            <a href="{{ route('news.index', ['type' => 'notice']) }}" class="list-group-item list-group-item-action {{ request('type') == 'notice' ? 'active' : '' }}">
                                <i class="fas fa-bullhorn me-2"></i>নোটিশ
                            </a>
                            <a href="{{ route('news.index', ['type' => 'event']) }}" class="list-group-item list-group-item-action {{ request('type') == 'event' ? 'active' : '' }}">
                                <i class="fas fa-calendar-alt me-2"></i>ইভেন্ট
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
