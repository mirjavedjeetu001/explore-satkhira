@extends('frontend.layouts.app')

@section('title', 'সকল উপজেলা - Satkhira Portal')

@section('content')
<!-- Page Header -->
<section class="page-header py-5" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center text-white">
                <h1 class="display-5 fw-bold">সাতক্ষীরা জেলার সকল উপজেলা</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">হোম</a></li>
                        <li class="breadcrumb-item active text-white">উপজেলাসমূহ</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Upazilas Grid -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            @forelse($upazilas ?? [] as $upazila)
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="upazila-card card h-100 border-0 shadow-sm">
                        <div class="position-relative">
                            @if($upazila->image)
                                <img src="{{ asset('storage/' . $upazila->image) }}" class="card-img-top" alt="{{ $upazila->name }}" 
                                     style="height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-success d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-map-marker-alt fa-4x text-white"></i>
                                </div>
                            @endif
                            <div class="position-absolute bottom-0 start-0 end-0 p-3" 
                                 style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                                <h4 class="text-white mb-0">{{ $upazila->name }}</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($upazila->description)
                                <p class="card-text text-muted">{{ Str::limit($upazila->description, 100) }}</p>
                            @endif
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-success">
                                    <i class="fas fa-list me-1"></i>{{ $upazila->listings_count ?? $upazila->listings->count() }} তথ্য
                                </span>
                                <a href="{{ route('upazilas.show', $upazila) }}" class="btn btn-outline-success btn-sm">
                                    বিস্তারিত দেখুন <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-map-marker-alt fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">কোন উপজেলা পাওয়া যায়নি</h5>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Info Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h3 class="mb-4">সাতক্ষীরা জেলা সম্পর্কে</h3>
                <p class="lead text-muted">
                    সাতক্ষীরা বাংলাদেশের দক্ষিণ-পশ্চিমাঞ্চলের খুলনা বিভাগের একটি জেলা। 
                    এই জেলায় ৭টি উপজেলা রয়েছে: সাতক্ষীরা সদর, কালীগঞ্জ, শ্যামনগর, আশাশুনি, দেবহাটা, তালা এবং কলারোয়া।
                    সুন্দরবন এই জেলার অন্যতম আকর্ষণ।
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
