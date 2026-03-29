@extends('frontend.layouts.app')

@section('title', ($newspaper->title_bn ?? $newspaper->title) . ' - সংবাদপত্র')
@section('seo_title', ($newspaper->title_bn ?? $newspaper->title) . ' | সংবাদপত্র - এক্সপ্লোর সাতক্ষীরা')
@section('meta_description', Str::limit(strip_tags($newspaper->description ?? $newspaper->title), 160))

@section('content')
<!-- Header -->
<section class="page-header py-4" style="background: linear-gradient(135deg, #1a73e8, #0d47a1);">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">হোম</a></li>
                <li class="breadcrumb-item"><a href="{{ route('newspapers.index') }}" class="text-white-50">সংবাদপত্র</a></li>
                <li class="breadcrumb-item active text-white">{{ Str::limit($newspaper->title_bn ?? $newspaper->title, 30) }}</li>
            </ol>
        </nav>
    </div>
</section>

<section class="py-4">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Newspaper Info Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-start gap-3">
                            @if($newspaper->image)
                                <img src="{{ asset('storage/' . $newspaper->image) }}" alt="{{ $newspaper->title }}" class="rounded" style="width: 80px; height: 80px; object-fit: contain; background: #f8f9fa; padding: 8px;">
                            @else
                                <div class="rounded d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: linear-gradient(135deg, #1a73e8, #0d47a1);">
                                    <i class="fas fa-newspaper fa-2x text-white"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h1 class="h3 fw-bold mb-1">{{ $newspaper->title_bn ?? $newspaper->title }}</h1>
                                @if($newspaper->short_description)
                                    <p class="text-muted mb-2">{{ $newspaper->short_description }}</p>
                                @endif
                                <div class="d-flex flex-wrap gap-2">
                                    @php $nType = $newspaper->extra_fields['newspaper_type'] ?? null; @endphp
                                    @if($nType === 'local')
                                        <span class="badge bg-success"><i class="fas fa-map-marker-alt me-1"></i>স্থানীয়</span>
                                    @elseif($nType === 'national')
                                        <span class="badge bg-primary"><i class="fas fa-flag me-1"></i>জাতীয়</span>
                                    @elseif($nType === 'international')
                                        <span class="badge bg-warning text-dark"><i class="fas fa-globe me-1"></i>আন্তর্জাতিক</span>
                                    @endif
                                    @php $nFormat = $newspaper->extra_fields['newspaper_format'] ?? null; @endphp
                                    @if($nFormat === 'online_only')
                                        <span class="badge bg-info"><i class="fas fa-laptop me-1"></i>শুধু অনলাইন</span>
                                    @elseif($nFormat === 'both')
                                        <span class="badge bg-info"><i class="fas fa-sync me-1"></i>অনলাইন + অফলাইন</span>
                                    @elseif($nFormat === 'offline_only')
                                        <span class="badge bg-secondary"><i class="fas fa-print me-1"></i>শুধু অফলাইন</span>
                                    @endif
                                    @if($newspaper->upazila)
                                        <span class="badge bg-success bg-opacity-75"><i class="fas fa-map-pin me-1"></i>{{ $newspaper->upazila->name_bn ?? $newspaper->upazila->name }}</span>
                                    @endif
                                    <span class="badge bg-secondary"><i class="fas fa-eye me-1"></i>{{ number_format($newspaper->views ?? 0) }} বার দেখা হয়েছে</span>
                                </div>
                            </div>
                        </div>

                        @if($newspaper->website)
                            <div class="mt-3 p-3 bg-light rounded">
                                <i class="fas fa-external-link-alt text-primary me-2"></i>
                                <strong>অনলাইন লিংক:</strong>
                                <a href="{{ $newspaper->website }}" target="_blank" rel="noopener" class="ms-2">
                                    {{ $newspaper->website }} <i class="fas fa-external-link-alt ms-1 small"></i>
                                </a>
                            </div>
                        @endif

                        @if($newspaper->description)
                            <div class="mt-3">
                                {!! nl2br(e($newspaper->description)) !!}
                            </div>
                        @endif

                        <div class="mt-3 d-flex flex-wrap gap-2">
                            @if($newspaper->phone)
                                <a href="tel:{{ $newspaper->phone }}" class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-phone me-1"></i>{{ $newspaper->phone }}
                                </a>
                            @endif
                            @if($newspaper->email)
                                <a href="mailto:{{ $newspaper->email }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-envelope me-1"></i>{{ $newspaper->email }}
                                </a>
                            @endif
                            @if($newspaper->facebook)
                                <a href="{{ $newspaper->facebook }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fab fa-facebook me-1"></i>Facebook
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Upload Edition Form (for authorized users) -->
                @if($canUpload)
                <div class="card shadow-sm border-0 mb-4 border-start border-primary border-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-upload me-2"></i>নতুন সংস্করণ আপলোড করুন</h5>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('newspapers.edition.store', $newspaper) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">সংস্করণের তারিখ <span class="text-danger">*</span></label>
                                    <input type="date" name="edition_date" class="form-control" value="{{ old('edition_date', date('Y-m-d')) }}" required>
                                    @error('edition_date') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">শিরোনাম (ঐচ্ছিক)</label>
                                    <input type="text" name="title" class="form-control" placeholder="যেমন: আজকের সংখ্যা" value="{{ old('title') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">পৃষ্ঠার ছবি <span class="text-danger">*</span></label>
                                    <input type="file" name="pages[]" class="form-control" multiple accept="image/*" required>
                                    <small class="text-muted">একাধিক ছবি সিলেক্ট করুন (সর্বোচ্চ ৩০টি, প্রতিটি ৫MB)</small>
                                    @error('pages') <small class="text-danger d-block">{{ $message }}</small> @enderror
                                    @error('pages.*') <small class="text-danger d-block">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">PDF ফাইল (ঐচ্ছিক)</label>
                                    <input type="file" name="pdf_file" class="form-control" accept=".pdf">
                                    <small class="text-muted">সর্বোচ্চ ২০MB</small>
                                    @error('pdf_file') <small class="text-danger d-block">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">বিবরণ (ঐচ্ছিক)</label>
                                    <textarea name="description" class="form-control" rows="2" placeholder="আজকের গুরুত্বপূর্ণ শিরোনাম...">{{ old('description') }}</textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-cloud-upload-alt me-2"></i>আপলোড করুন
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

                <!-- Editions List -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2 text-primary"></i>দৈনিক সংস্করণ</h5>
                    </div>
                    <div class="card-body">
                        @if($editions->isEmpty())
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <p class="text-muted">এখনো কোনো সংস্করণ আপলোড করা হয়নি</p>
                                @if($newspaper->website)
                                    <a href="{{ $newspaper->website }}" target="_blank" class="btn btn-primary">
                                        <i class="fas fa-external-link-alt me-2"></i>অনলাইনে পড়ুন
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="list-group list-group-flush">
                                @foreach($editions as $edition)
                                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="text-center" style="min-width: 60px;">
                                                <div class="fw-bold text-primary fs-4">{{ $edition->edition_date->format('d') }}</div>
                                                <small class="text-muted">{{ $edition->edition_date->translatedFormat('M Y') }}</small>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">
                                                    {{ $edition->title ?? $edition->edition_date->translatedFormat('l, d F Y') }}
                                                </h6>
                                                @if($edition->description)
                                                    <small class="text-muted">{{ Str::limit($edition->description, 80) }}</small>
                                                @endif
                                                <div class="mt-1">
                                                    @if($edition->pages && count($edition->pages) > 0)
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                            <i class="fas fa-images me-1"></i>{{ count($edition->pages) }} পৃষ্ঠা
                                                        </span>
                                                    @endif
                                                    @if($edition->pdf_file)
                                                        <span class="badge bg-danger bg-opacity-10 text-danger">
                                                            <i class="fas fa-file-pdf me-1"></i>PDF
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('newspapers.edition.read', [$newspaper, $edition]) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-book-reader me-1"></i>পড়ুন
                                            </a>
                                            @if($edition->pdf_file)
                                                <a href="{{ asset('storage/' . $edition->pdf_file) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            @endif
                                            @if($canUpload)
                                                <form action="{{ route('newspapers.edition.delete', [$newspaper, $edition]) }}" method="POST" onsubmit="return confirm('এই সংস্করণ মুছে ফেলতে চান?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-3">
                                {{ $editions->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Info -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0"><i class="fas fa-info-circle text-primary me-2"></i>তথ্য</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            @if($newspaper->upazila)
                                <li class="mb-2"><i class="fas fa-map-marker-alt text-success me-2 w-20"></i>{{ $newspaper->upazila->name_bn ?? $newspaper->upazila->name }}</li>
                            @endif
                            @if($newspaper->address)
                                <li class="mb-2"><i class="fas fa-location-dot text-danger me-2"></i>{{ $newspaper->address }}</li>
                            @endif
                            @if($newspaper->phone)
                                <li class="mb-2"><i class="fas fa-phone text-success me-2"></i><a href="tel:{{ $newspaper->phone }}">{{ $newspaper->phone }}</a></li>
                            @endif
                            @if($newspaper->email)
                                <li class="mb-2"><i class="fas fa-envelope text-info me-2"></i><a href="mailto:{{ $newspaper->email }}">{{ $newspaper->email }}</a></li>
                            @endif
                            @if($newspaper->website)
                                <li class="mb-2"><i class="fas fa-globe text-primary me-2"></i><a href="{{ $newspaper->website }}" target="_blank">ওয়েবসাইট</a></li>
                            @endif
                            <li class="mb-0"><i class="fas fa-eye text-secondary me-2"></i>{{ number_format($newspaper->views ?? 0) }} বার দেখা হয়েছে</li>
                        </ul>
                    </div>
                </div>

                <!-- Visit Online -->
                @if($newspaper->website)
                <div class="card shadow-sm border-0 mb-4 bg-primary text-white">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-external-link-alt fa-2x mb-3"></i>
                        <h5 class="fw-bold">অনলাইনে পড়ুন</h5>
                        <p class="small opacity-75">{{ $newspaper->title_bn ?? $newspaper->title }}-এর ওয়েবসাইট</p>
                        <a href="{{ $newspaper->website }}" target="_blank" rel="noopener" class="btn btn-light fw-bold">
                            <i class="fas fa-external-link-alt me-2"></i>ভিজিট করুন
                        </a>
                    </div>
                </div>
                @endif

                <!-- Other Newspapers -->
                @php
                    $otherNewspapers = \App\Models\Listing::approved()
                        ->where('category_id', $category->id)
                        ->where('id', '!=', $newspaper->id)
                        ->take(5)
                        ->get();
                @endphp
                @if($otherNewspapers->isNotEmpty())
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h6 class="mb-0"><i class="fas fa-newspaper text-primary me-2"></i>অন্যান্য সংবাদপত্র</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($otherNewspapers as $other)
                            <a href="{{ route('newspapers.show', $other) }}" class="list-group-item list-group-item-action d-flex align-items-center gap-2">
                                @if($other->image)
                                    <img src="{{ asset('storage/' . $other->image) }}" alt="" class="rounded" style="width:36px;height:36px;object-fit:contain;background:#f8f9fa;padding:2px;">
                                @else
                                    <i class="fas fa-newspaper text-primary"></i>
                                @endif
                                <span>{{ $other->title_bn ?? $other->title }}</span>
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
