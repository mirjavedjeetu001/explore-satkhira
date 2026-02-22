@extends('frontend.layouts.app')

@section('title', 'ছবি ও প্রচার - ' . $listing->title)

@section('content')
<!-- Page Header -->
<section class="page-header py-4" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="text-white mb-0">ছবি ও প্রচার</h3>
                <p class="text-white-50 mb-0">{{ $listing->title }}</p>
            </div>
            <div class="col-md-4 text-md-end">
                @if(auth()->user()->can_upload_ads)
                    <a href="{{ route('dashboard.listings.images.create', $listing) }}" class="btn btn-light">
                        <i class="fas fa-plus me-1"></i>নতুন ছবি আপলোড করুন
                    </a>
                @else
                    <span class="badge bg-warning text-dark p-2">
                        <i class="fas fa-lock me-1"></i>বিজ্ঞাপন আপলোড অনুমতি নেই
                    </span>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Ad Permission Notice -->
@if(!auth()->user()->can_upload_ads)
<section class="py-3 bg-warning-subtle">
    <div class="container">
        <div class="alert alert-warning mb-0 d-flex align-items-center">
            <i class="fas fa-info-circle fa-2x me-3"></i>
            <div>
                <strong>বিজ্ঞাপন/অফার আপলোড করার অনুমতি নেই</strong>
                <p class="mb-0">আপনার অ্যাকাউন্টে বিজ্ঞাপন আপলোড করার সুবিধা সক্রিয় নেই। বিজ্ঞাপন দিতে চাইলে অনুগ্রহ করে এডমিনের সাথে যোগাযোগ করুন।</p>
                <a href="{{ route('contact') }}" class="btn btn-warning btn-sm mt-2">
                    <i class="fas fa-envelope me-1"></i>এডমিনের সাথে যোগাযোগ করুন
                </a>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-4">
                        <img src="{{ $listing->image ? asset('storage/' . $listing->image) : 'https://picsum.photos/seed/' . $listing->id . '/100/100' }}" 
                             alt="{{ $listing->title }}" class="rounded mb-3" width="100" height="100" style="object-fit: cover;">
                        <h5 class="mb-1">{{ Str::limit($listing->title, 25) }}</h5>
                        <p class="text-muted small mb-0">{{ $listing->category->name ?? 'N/A' }}</p>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('dashboard.listings') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-arrow-left me-2"></i>তথ্যের তালিকায় ফিরুন
                        </a>
                        <a href="{{ route('dashboard.listings.edit', $listing) }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-edit me-2"></i>তথ্য সম্পাদনা করুন
                        </a>
                        <a href="{{ route('listings.show', $listing) }}" class="list-group-item list-group-item-action" target="_blank">
                            <i class="fas fa-external-link-alt me-2"></i>পাবলিক পেজ দেখুন
                        </a>
                    </div>
                </div>
                
                <!-- Summary Card -->
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-header bg-white">
                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>সারসংক্ষেপ</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>মোট ছবি:</span>
                            <span class="badge bg-primary">{{ $images->total() }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>অনুমোদিত:</span>
                            <span class="badge bg-success">{{ $listing->images()->approved()->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>অপেক্ষমাণ:</span>
                            <span class="badge bg-warning text-dark">{{ $listing->images()->pending()->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-images text-success me-2"></i>আপলোড করা ছবিসমূহ</h5>
                    </div>
                    <div class="card-body">
                        @if($images->count() > 0)
                            <div class="row g-3">
                                @foreach($images as $image)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="card h-100 border">
                                            <div class="position-relative">
                                                <img src="{{ asset('storage/' . $image->image) }}" 
                                                     alt="{{ $image->title ?? 'Image' }}" 
                                                     class="card-img-top" 
                                                     style="height: 180px; object-fit: cover;">
                                                <span class="position-absolute top-0 end-0 m-2">
                                                    {!! $image->status_badge !!}
                                                </span>
                                                <span class="position-absolute top-0 start-0 m-2">
                                                    {!! $image->type_badge !!}
                                                </span>
                                            </div>
                                            <div class="card-body p-2">
                                                <h6 class="card-title small mb-1">{{ $image->title ?? 'শিরোনাম নেই' }}</h6>
                                                <p class="card-text small text-muted mb-2">
                                                    {{ $image->created_at->format('d M Y') }}
                                                    @if($image->valid_until)
                                                        <br>মেয়াদ: {{ $image->valid_until->format('d M Y') }}
                                                    @endif
                                                </p>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('dashboard.listings.images.edit', [$listing, $image]) }}" 
                                                       class="btn btn-sm btn-outline-primary flex-fill">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('dashboard.listings.images.destroy', [$listing, $image]) }}" 
                                                          method="POST" class="flex-fill"
                                                          onsubmit="return confirm('আপনি কি এই ছবিটি মুছে ফেলতে চান?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="mt-4">
                                {{ $images->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-images fa-4x text-muted mb-3"></i>
                                <h5>কোন ছবি নেই</h5>
                                <p class="text-muted">আপনি এখনও কোন ছবি আপলোড করেননি</p>
                                @if(auth()->user()->can_upload_ads)
                                    <a href="{{ route('dashboard.listings.images.create', $listing) }}" class="btn btn-success">
                                        <i class="fas fa-plus me-1"></i>প্রথম ছবি আপলোড করুন
                                    </a>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-lock me-1"></i>বিজ্ঞাপন আপলোড করতে এডমিনের সাথে যোগাযোগ করুন
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Info Box -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body">
                        <h6><i class="fas fa-lightbulb text-warning me-2"></i>ছবি আপলোডের নির্দেশিকা</h6>
                        <ul class="small text-muted mb-0">
                            <li>অফার, প্রমোশন, ব্যানার, গ্যালারি, মেনু ইত্যাদি ছবি আপলোড করুন</li>
                            <li>সকল ছবি প্রশাসকের অনুমোদনের পর প্রদর্শিত হবে</li>
                            <li>সর্বোচ্চ ১০টি ছবি একসাথে আপলোড করা যাবে</li>
                            <li>প্রতিটি ছবি সর্বোচ্চ ২ MB হতে পারবে</li>
                            <li>সমর্থিত ফরম্যাট: JPG, JPEG, PNG, GIF, WEBP</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
