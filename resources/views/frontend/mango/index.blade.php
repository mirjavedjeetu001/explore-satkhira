@extends('frontend.layouts.app')

@section('title', $settings->title ?? 'সাতক্ষীরার আম')
@section('meta_description', 'সাতক্ষীরার সেরা আম সংগ্রহ করুন সরাসরি বাগান থেকে। হিমসাগর, ল্যাংড়া, আম্রপালি সহ বিভিন্ন জাতের আম পাওয়া যাচ্ছে।')

@section('content')
<div class="mango-page py-4">
    <div class="container">

        <!-- Header -->
        <div class="mango-header text-center mb-5">
            <div class="mango-icon-big mb-3">🥭</div>
            <h1 class="fw-bold mb-2">{{ $settings->title ?? 'সাতক্ষীরার আম' }}</h1>
            <p class="text-muted lead">{{ $settings->description ?? 'সাতক্ষীরার সেরা আম সংগ্রহ করুন সরাসরি বাগান থেকে!' }}</p>
            <div class="d-flex justify-content-center gap-3 mt-3 flex-wrap">
                <span class="badge bg-success fs-6 px-3 py-2">
                    <i class="fas fa-store me-1"></i> {{ $stores->total() }} টি স্টোর
                </span>
                @if(session('mango_store_id'))
                    <a href="{{ route('mango.dashboard') }}" class="btn btn-warning btn-sm px-4">
                        <i class="fas fa-tachometer-alt me-1"></i> আমার স্টোর
                    </a>
                @else
                    <a href="{{ route('mango.register') }}" class="btn btn-success btn-sm px-4">
                        <i class="fas fa-plus me-1"></i> স্টোর খুলুন
                    </a>
                    <a href="{{ route('mango.login') }}" class="btn btn-outline-success btn-sm px-4">
                        <i class="fas fa-sign-in-alt me-1"></i> লগইন
                    </a>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filter -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('mango.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">উপজেলা অনুযায়ী ফিল্টার</label>
                            <select name="upazila" class="form-select" onchange="this.form.submit()">
                                <option value="">সব উপজেলা</option>
                                @foreach($upazilas as $upazila)
                                    <option value="{{ $upazila->id }}" {{ $selectedUpazila == $upazila->id ? 'selected' : '' }}>
                                        {{ $upazila->name_bn ?? $upazila->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if($selectedUpazila)
                            <div class="col-md-3">
                                <a href="{{ route('mango.index') }}" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-times me-1"></i> ফিল্টার সরান
                                </a>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Store Grid -->
        @if($stores->isEmpty())
            <div class="text-center py-5">
                <div style="font-size: 4rem;">🥭</div>
                <h4 class="mt-3 text-muted">এখনো কোনো স্টোর নেই</h4>
                <p class="text-muted">আপনিই প্রথম আম বিক্রেতা হোন!</p>
                <a href="{{ route('mango.register') }}" class="btn btn-success btn-lg mt-2">
                    <i class="fas fa-store me-2"></i>স্টোর খুলুন
                </a>
            </div>
        @else
            <div class="row g-4">
                @foreach($stores as $store)
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('mango.show', $store->id) }}" class="text-decoration-none">
                            <div class="card mango-store-card h-100 shadow-sm">
                                <!-- Store Logo -->
                                <div class="store-logo-wrap text-center pt-4 pb-2">
                                    @if($store->logo)
                                        <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->store_name }}"
                                             class="store-logo rounded-circle" style="width:80px;height:80px;object-fit:cover;border:3px solid #f59e0b;">
                                    @else
                                        <div class="store-logo-placeholder rounded-circle d-inline-flex align-items-center justify-content-center"
                                             style="width:80px;height:80px;background:linear-gradient(135deg,#f59e0b,#d97706);font-size:2rem;">
                                            🥭
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body text-center pt-2">
                                    <h5 class="fw-bold text-dark mb-1">{{ $store->store_name }}</h5>
                                    <p class="text-muted small mb-2">
                                        <i class="fas fa-user me-1"></i>{{ $store->owner_name }}
                                    </p>
                                    <div class="mb-2">
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-star me-1"></i>{{ number_format($store->ratings_avg_rating ?? 0, 1) }}
                                            <small>({{ $store->ratings_count }})</small>
                                        </span>
                                    </div>
                                    @if($store->upazila)
                                        <span class="badge bg-light text-dark border mb-2">
                                            <i class="fas fa-map-marker-alt me-1 text-danger"></i>
                                            {{ $store->upazila->name_bn ?? $store->upazila->name }}
                                        </span>
                                    @endif
                                    @if($store->description)
                                        <p class="text-muted small mb-3" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                            {{ $store->description }}
                                        </p>
                                    @endif
                                </div>
                                <div class="card-footer bg-transparent d-flex justify-content-between align-items-center px-3 pb-3">
                                    <span class="badge bg-success">
                                        <i class="fas fa-leaf me-1"></i>{{ $store->products_count }} জাত
                                    </span>
                                    <span class="text-primary small fw-semibold">
                                        বিস্তারিত দেখুন <i class="fas fa-arrow-right ms-1"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $stores->withQueryString()->links() }}
            </div>
        @endif

    </div>
</div>

<style>
.mango-page { background: #fffbf0; min-height: 60vh; }
.mango-icon-big { font-size: 3.5rem; }
.mango-store-card { border: none; border-top: 4px solid #f59e0b; transition: transform 0.2s, box-shadow 0.2s; }
.mango-store-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(245,158,11,0.2) !important; }
</style>
@endsection
