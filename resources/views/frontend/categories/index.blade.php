@extends('frontend.layouts.app')

@section('title', 'সকল ক্যাটাগরি - Satkhira Portal')

@section('content')
<!-- Page Header -->
<section class="page-header py-5" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center text-white">
                <h1 class="display-5 fw-bold">সকল ক্যাটাগরি</h1>
                <p class="lead mb-0">বিভিন্ন ধরনের তথ্য ক্যাটাগরি অনুযায়ী খুঁজুন</p>
            </div>
        </div>
    </div>
</section>

<!-- Categories Grid -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            @forelse($categories ?? [] as $category)
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <a href="{{ route('categories.show', $category) }}" class="text-decoration-none">
                        <div class="category-card card h-100 border-0 shadow-sm text-center">
                            <div class="card-body py-5">
                                <div class="icon-wrapper mb-3">
                                    <i class="{{ $category->icon ?? 'fas fa-folder' }} fa-3x text-success"></i>
                                </div>
                                <h5 class="card-title text-dark">{{ app()->getLocale() == 'bn' ? ($category->name_bn ?? $category->name) : $category->name }}</h5>
                                @if($category->description)
                                    <p class="card-text text-muted small">{{ Str::limit($category->description, 50) }}</p>
                                @endif
                                <span class="badge bg-success">{{ $category->listings_count ?? $category->listings->count() }} তথ্য</span>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-th-large fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">কোন ক্যাটাগরি পাওয়া যায়নি</h5>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .category-card {
        transition: all 0.3s ease;
    }
    .category-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .category-card .icon-wrapper {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(32, 201, 151, 0.1));
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
</style>
@endpush
