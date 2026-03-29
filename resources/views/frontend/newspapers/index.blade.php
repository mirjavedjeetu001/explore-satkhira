@extends('frontend.layouts.app')

@section('title', 'সংবাদপত্র - Explore Satkhira')
@section('seo_title', 'সংবাদপত্র | স্থানীয় ও জাতীয় পত্রিকা - এক্সপ্লোর সাতক্ষীরা')
@section('meta_description', 'সাতক্ষীরার স্থানীয় ও জাতীয় সংবাদপত্রের তালিকা। অনলাইনে পড়ুন, দৈনিক সংস্করণ দেখুন।')

@section('content')
<!-- Page Header -->
<section class="page-header py-5" style="background: linear-gradient(135deg, #1a73e8, #0d47a1);">
    <div class="container">
        <div class="text-center text-white">
            <i class="fas fa-newspaper fa-3x mb-3"></i>
            <h1 class="display-5 fw-bold">সংবাদপত্র</h1>
            <p class="lead mb-0">স্থানীয় ও জাতীয় সংবাদপত্র - অনলাইনে পড়ুন</p>
        </div>
    </div>
</section>

<!-- Filter Bar -->
<section class="py-3 bg-light border-bottom">
    <div class="container">
        <form action="{{ route('newspapers.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="পত্রিকার নাম খুঁজুন..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="type" class="form-select">
                        <option value="">সকল পত্রিকা</option>
                        <option value="local" {{ request('type') == 'local' ? 'selected' : '' }}>🏘️ স্থানীয়</option>
                        <option value="national" {{ request('type') == 'national' ? 'selected' : '' }}>🇧🇩 জাতীয়</option>
                        <option value="international" {{ request('type') == 'international' ? 'selected' : '' }}>🌍 আন্তর্জাতিক</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="upazila" class="form-select">
                        <option value="">সকল এলাকা</option>
                        @foreach($upazilas as $upazila)
                            <option value="{{ $upazila->id }}" {{ request('upazila') == $upazila->id ? 'selected' : '' }}>
                                {{ $upazila->name_bn ?? $upazila->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i>খুঁজুন
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Newspapers Grid -->
<section class="py-5">
    <div class="container">
        @if($newspapers->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-newspaper fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">কোনো সংবাদপত্র পাওয়া যায়নি</h4>
                <p class="text-muted">শীঘ্রই সংবাদপত্র যুক্ত করা হবে</p>
            </div>
        @else
            <div class="row g-4">
                @foreach($newspapers as $newspaper)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="{{ route('newspapers.show', $newspaper) }}" class="text-decoration-none">
                            <div class="card h-100 border-0 shadow-sm newspaper-card">
                                <div class="card-img-top position-relative" style="height: 180px; background: linear-gradient(135deg, #1a73e8, #0d47a1); display: flex; align-items: center; justify-content: center;">
                                    @if($newspaper->image)
                                        <img src="{{ asset('storage/' . $newspaper->image) }}" alt="{{ $newspaper->title }}" class="img-fluid" style="max-height: 160px; max-width: 90%; object-fit: contain;">
                                    @else
                                        <i class="fas fa-newspaper fa-4x text-white opacity-50"></i>
                                    @endif
                                    @if($newspaper->is_featured)
                                        <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2">
                                            <i class="fas fa-star me-1"></i>বৈশিষ্ট্যযুক্ত
                                        </span>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title fw-bold text-dark mb-2">{{ $newspaper->title_bn ?? $newspaper->title }}</h5>
                                    @if($newspaper->short_description)
                                        <p class="card-text text-muted small mb-2">{{ Str::limit($newspaper->short_description, 80) }}</p>
                                    @endif
                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                        @php $nType = $newspaper->extra_fields['newspaper_type'] ?? null; @endphp
                                        @if($nType === 'local')
                                            <span class="badge bg-success bg-opacity-10 text-success">
                                                <i class="fas fa-map-marker-alt me-1"></i>স্থানীয়
                                            </span>
                                        @elseif($nType === 'national')
                                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                                <i class="fas fa-flag me-1"></i>জাতীয়
                                            </span>
                                        @elseif($nType === 'international')
                                            <span class="badge bg-warning bg-opacity-10 text-warning">
                                                <i class="fas fa-globe me-1"></i>আন্তর্জাতিক
                                            </span>
                                        @endif
                                        @php $nFormat = $newspaper->extra_fields['newspaper_format'] ?? null; @endphp
                                        @if($nFormat === 'online_only')
                                            <span class="badge bg-info bg-opacity-10 text-info">
                                                <i class="fas fa-laptop me-1"></i>অনলাইন
                                            </span>
                                        @elseif($nFormat === 'both')
                                            <span class="badge bg-info bg-opacity-10 text-info">
                                                <i class="fas fa-sync me-1"></i>অনলাইন + অফলাইন
                                            </span>
                                        @elseif($nFormat === 'offline_only')
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                <i class="fas fa-print me-1"></i>অফলাইন
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-top-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-eye me-1"></i>{{ number_format($newspaper->views ?? 0) }}
                                        </small>
                                        @if($newspaper->editions_count > 0)
                                            <span class="badge bg-primary">
                                                <i class="fas fa-calendar me-1"></i>{{ $newspaper->editions_count }} সংস্করণ
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $newspapers->withQueryString()->links() }}
            </div>
        @endif
    </div>
</section>

<style>
    .newspaper-card { transition: all 0.3s ease; border-radius: 12px; overflow: hidden; }
    .newspaper-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important; }
</style>
@endsection
