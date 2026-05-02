@extends('frontend.layouts.app')

@section('title', $store->store_name . ' - সাতক্ষীরার আম')
@section('meta_description', $store->description ?? $store->store_name . ' - সাতক্ষীরার আম বিক্রেতা। ' . ($store->upazila->name_bn ?? '') . ' এলাকায় আম পাওয়া যাচ্ছে।')

@section('content')
<div class="mango-store-page py-4">
    <div class="container">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-warning alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">হোম</a></li>
                <li class="breadcrumb-item"><a href="{{ route('mango.index') }}">সাতক্ষীরার আম</a></li>
                <li class="breadcrumb-item active">{{ $store->store_name }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            <!-- Store Info Sidebar -->
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4 store-info-card">
                    <div class="card-body text-center py-4">
                        @if($store->logo)
                            <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->store_name }}"
                                 class="rounded-circle mb-3" style="width:100px;height:100px;object-fit:cover;border:4px solid #f59e0b;">
                        @else
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                 style="width:100px;height:100px;background:linear-gradient(135deg,#f59e0b,#d97706);font-size:3rem;">
                                🥭
                            </div>
                        @endif
                        <h4 class="fw-bold mb-1">{{ $store->store_name }}</h4>
                        <p class="text-muted mb-3">
                            <i class="fas fa-user me-1"></i>{{ $store->owner_name }}
                        </p>
                        @if($store->upazila)
                            <div class="mb-3">
                                <span class="badge bg-warning text-dark px-3 py-2">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    {{ $store->upazila->name_bn ?? $store->upazila->name }}
                                    @if($store->address), {{ $store->address }}@endif
                                </span>
                            </div>
                        @elseif($store->address)
                            <p class="text-muted small mb-3">
                                <i class="fas fa-map-marker-alt me-1 text-danger"></i>{{ $store->address }}
                            </p>
                        @endif
                        <span class="badge bg-light text-muted border">
                            <i class="fas fa-eye me-1"></i>{{ number_format($store->view_count) }} বার দেখা হয়েছে
                        </span>
                        <div class="mt-3">
                            @php
                                $averageRating = round($store->ratings_avg_rating ?? 0, 1);
                                $filledStars = (int) round($store->ratings_avg_rating ?? 0);
                            @endphp
                            <div class="d-flex align-items-center justify-content-center gap-1 mb-1">
                                @for($star = 1; $star <= 5; $star++)
                                    <i class="fas fa-star {{ $star <= $filledStars ? 'text-warning' : 'text-secondary opacity-50' }}"></i>
                                @endfor
                            </div>
                            <small class="text-muted fw-semibold">
                                {{ number_format($averageRating, 1) }} / 5 ({{ $store->ratings_count }} টি রেটিং)
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Contact Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <i class="fas fa-phone me-2"></i>যোগাযোগ করুন
                    </div>
                    <div class="card-body">
                        <a href="tel:{{ $store->phone }}" class="btn btn-outline-success w-100 mb-2 btn-lg">
                            <i class="fas fa-phone me-2"></i>{{ $store->phone }}
                        </a>
                        @if($store->whatsapp)
                            <a href="https://wa.me/88{{ $store->whatsapp }}" target="_blank"
                               class="btn btn-success w-100 mb-2">
                                <i class="fab fa-whatsapp me-2"></i>WhatsApp এ মেসেজ দিন
                            </a>
                        @endif
                        @if($store->facebook_url)
                            <a href="{{ $store->facebook_url }}" target="_blank"
                               class="btn btn-primary w-100">
                                <i class="fab fa-facebook me-2"></i>Facebook পেজ
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Delivery Info -->
                @if($store->delivery_info)
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-info text-white">
                            <i class="fas fa-truck me-2"></i>ডেলিভারি তথ্য
                        </div>
                        <div class="card-body">
                            <p class="mb-0" style="white-space: pre-line;">{{ $store->delivery_info }}</p>
                        </div>
                    </div>
                @endif

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <i class="fas fa-star me-2"></i>রেটিং দিন
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-3">শুধু ফোন নম্বর দিলেই রেটিং দিতে পারবেন। একই ফোন নম্বর থেকে একবারই রেটিং নেওয়া হবে।</p>
                        <form action="{{ route('mango.rate', $store->id) }}" method="POST">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label small fw-semibold">মোবাইল নম্বর</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                       placeholder="01XXXXXXXXX" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">রেটিং</label>
                                <input type="hidden" id="rating-input" name="rating" value="{{ old('rating') }}" required>
                                <div class="rating-stars-wrap @error('rating') is-invalid @enderror" role="radiogroup" aria-label="rating">
                                    @for($star = 1; $star <= 5; $star++)
                                        <button type="button" class="star-btn" data-rating="{{ $star }}" aria-label="{{ $star }} star">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    @endfor
                                </div>
                                <small id="rating-help" class="text-muted d-block mt-1">স্টার সিলেক্ট করুন</small>
                                @error('rating')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <button class="btn btn-warning w-100" type="submit">
                                <i class="fas fa-paper-plane me-1"></i>রেটিং সাবমিট করুন
                            </button>
                        </form>
                    </div>
                </div>

                @if($store->description)
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <i class="fas fa-info-circle me-2"></i>স্টোর সম্পর্কে
                        </div>
                        <div class="card-body">
                            <p class="mb-0" style="white-space: pre-line;">{{ $store->description }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Products -->
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold mb-0">
                        <i class="fas fa-leaf me-2 text-success"></i>আমের তালিকা
                    </h4>
                    <span class="badge bg-success fs-6">{{ $store->products->count() }} জাত</span>
                </div>

                @if($store->products->isEmpty())
                    <div class="text-center py-5 bg-white rounded shadow-sm">
                        <div style="font-size:3rem;">🥭</div>
                        <p class="text-muted mt-2">এখনো কোনো আম যোগ করা হয়নি।</p>
                    </div>
                @else
                    <div class="row g-3">
                        @foreach($store->products as $product)
                            <div class="col-sm-6">
                                <div class="card product-card h-100 shadow-sm {{ !$product->is_available ? 'opacity-60' : '' }}">
                                    <div class="position-relative">
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                             alt="{{ $product->name }}"
                                             class="card-img-top product-img"
                                             style="height:200px;object-fit:cover;">
                                        @if(!$product->is_available)
                                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
                                                 style="background:rgba(0,0,0,0.5);border-radius:4px 4px 0 0;">
                                                <span class="badge bg-danger fs-6">স্টক নেই</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-1">{{ $product->name }}</h6>
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="badge bg-warning text-dark fs-6">
                                                ৳{{ number_format($product->price_per_kg, 0) }}/কেজি
                                            </span>
                                            @if($product->min_order_kg)
                                                <span class="badge bg-light text-dark border small">
                                                    সর্বনিম্ন {{ $product->min_order_kg }} কেজি
                                                </span>
                                            @endif
                                        </div>
                                        @if($product->description)
                                            <p class="text-muted small mb-0" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                                {{ $product->description }}
                                            </p>
                                        @endif
                                    </div>
                                    @if($product->is_available)
                                        <div class="card-footer bg-transparent">
                                            <a href="tel:{{ $store->phone }}" class="btn btn-sm btn-success w-100">
                                                <i class="fas fa-phone me-1"></i>অর্ডার করুন
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-4">
            <a href="{{ route('mango.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>সব স্টোর দেখুন
            </a>
        </div>

    </div>
</div>

<style>
.mango-store-page { background: #fffbf0; min-height: 60vh; }
.store-info-card { border-top: 4px solid #f59e0b; }
.product-card { border: none; border-top: 3px solid #22c55e; transition: transform 0.2s; }
.product-card:hover { transform: translateY(-3px); }
.opacity-60 { opacity: 0.6; }
.rating-stars-wrap {
    display: flex;
    align-items: center;
    gap: 0.35rem;
}
.star-btn {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    border: 1px solid #fcd34d;
    background: #fff;
    color: #d1d5db;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}
.star-btn:hover {
    transform: translateY(-2px);
    border-color: #f59e0b;
}
.star-btn.active {
    background: #fffbeb;
    color: #f59e0b;
    border-color: #f59e0b;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ratingInput = document.getElementById('rating-input');
    const ratingHelp = document.getElementById('rating-help');
    const starButtons = Array.from(document.querySelectorAll('.star-btn'));

    if (!ratingInput || starButtons.length === 0) {
        return;
    }

    const messages = {
        1: '1 star - আরও উন্নতি দরকার',
        2: '2 star - মোটামুটি',
        3: '3 star - ভালো',
        4: '4 star - খুব ভালো',
        5: '5 star - দারুণ'
    };

    function paintStars(value) {
        const selected = Number(value || 0);
        starButtons.forEach((btn) => {
            const btnRating = Number(btn.dataset.rating);
            btn.classList.toggle('active', btnRating <= selected);
        });
        ratingHelp.textContent = messages[selected] || 'স্টার সিলেক্ট করুন';
    }

    starButtons.forEach((btn) => {
        btn.addEventListener('click', function () {
            ratingInput.value = this.dataset.rating;
            paintStars(ratingInput.value);
        });
    });

    paintStars(ratingInput.value);
});
</script>
@endsection
