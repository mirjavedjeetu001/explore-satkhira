@extends('frontend.layouts.app')

@section('title', 'সংস্করণ ম্যানেজমেন্ট - ' . $listing->title)

@section('content')
<!-- Page Header -->
<section class="page-header py-4" style="background: linear-gradient(135deg, #1565c0, #42a5f5);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="text-white mb-1"><i class="fas fa-newspaper me-2"></i>সংস্করণ ম্যানেজমেন্ট</h3>
                <p class="text-white-50 mb-0">{{ $listing->title }}</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('dashboard.listings') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>তথ্যসমূহে ফিরুন
                </a>
            </div>
        </div>
        <nav aria-label="breadcrumb" class="mt-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">ড্যাশবোর্ড</a></li>
                <li class="breadcrumb-item"><a href="{{ route('dashboard.listings') }}" class="text-white-50">আমার তথ্যসমূহ</a></li>
                <li class="breadcrumb-item active text-white">সংস্করণ</li>
            </ol>
        </nav>
    </div>
</section>

<section class="py-5">
    <div class="container">
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

        <div class="row">
            <!-- Upload Form -->
            <div class="col-lg-5 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-upload me-2"></i>নতুন সংস্করণ আপলোড</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('dashboard.newspaper.editions.store', $listing) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label">তারিখ <span class="text-danger">*</span></label>
                                <input type="date" name="edition_date" class="form-control @error('edition_date') is-invalid @enderror" 
                                       value="{{ old('edition_date', date('Y-m-d')) }}" required>
                                @error('edition_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">শিরোনাম (ঐচ্ছিক)</label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                       value="{{ old('title') }}" placeholder="যেমন: আজকের সংস্করণ">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">বিবরণ (ঐচ্ছিক)</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                          rows="2" placeholder="সংক্ষিপ্ত বিবরণ...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">পাতার ছবি <span class="text-danger">*</span></label>
                                <input type="file" name="pages[]" class="form-control @error('pages') is-invalid @enderror @error('pages.*') is-invalid @enderror" 
                                       accept="image/*" multiple required>
                                @error('pages')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('pages.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted"><i class="fas fa-info-circle me-1"></i>পত্রিকার পাতাগুলোর ছবি আপলোড করুন (সর্বোচ্চ ৩০টি, প্রতিটি সর্বোচ্চ ৫MB)</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">PDF ফাইল (ঐচ্ছিক)</label>
                                <input type="file" name="pdf_file" class="form-control @error('pdf_file') is-invalid @enderror" accept=".pdf">
                                @error('pdf_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted"><i class="fas fa-info-circle me-1"></i>সম্পূর্ণ পত্রিকার PDF আপলোড করতে পারেন (সর্বোচ্চ ২০MB)</small>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-cloud-upload-alt me-2"></i>সংস্করণ আপলোড করুন
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Editions List -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-list text-primary me-2"></i>আপলোড করা সংস্করণ ({{ $editions->total() }})</h5>
                        <a href="{{ route('newspapers.show', $listing) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                            <i class="fas fa-external-link-alt me-1"></i>পাবলিক পেজ
                        </a>
                    </div>
                    <div class="card-body p-0">
                        @if($editions->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($editions as $edition)
                                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                        <div>
                                            <h6 class="mb-1">
                                                <i class="fas fa-calendar-day text-primary me-2"></i>
                                                {{ $edition->edition_date->format('d M Y') }}
                                                <span class="text-muted">({{ $edition->edition_date->locale('bn')->translatedFormat('l') }})</span>
                                            </h6>
                                            @if($edition->title)
                                                <small class="text-muted d-block">{{ $edition->title }}</small>
                                            @endif
                                            <small class="text-muted">
                                                <i class="fas fa-file-image me-1"></i>{{ count($edition->pages ?? []) }} পাতা
                                                @if($edition->pdf_file)
                                                    <span class="ms-2"><i class="fas fa-file-pdf text-danger me-1"></i>PDF আছে</span>
                                                @endif
                                            </small>
                                        </div>
                                        <div class="btn-group">
                                            <a href="{{ route('newspapers.edition.read', [$listing, $edition]) }}" class="btn btn-sm btn-outline-success" title="পড়ুন" target="_blank">
                                                <i class="fas fa-book-reader"></i>
                                            </a>
                                            <form action="{{ route('dashboard.newspaper.editions.delete', [$listing, $edition]) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('আপনি কি এই সংস্করণটি মুছে ফেলতে চান?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="মুছুন">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="p-3">
                                {{ $editions->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">কোন সংস্করণ আপলোড করা হয়নি</h6>
                                <p class="text-muted small">বাম পাশের ফর্ম থেকে আজকের সংস্করণ আপলোড করুন</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
