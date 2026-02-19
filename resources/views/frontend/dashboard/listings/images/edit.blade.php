@extends('frontend.layouts.app')

@section('title', 'ছবি সম্পাদনা - ' . $listing->title)

@section('content')
<!-- Page Header -->
<section class="page-header py-4" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="text-white mb-0">ছবি সম্পাদনা</h3>
                <p class="text-white-50 mb-0">{{ $listing->title }}</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('dashboard.listings.images', $listing) }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-1"></i>ফিরে যান
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Content -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-edit text-success me-2"></i>ছবি সম্পাদনা ফর্ম</h5>
                    </div>
                    <div class="card-body">
                        <!-- Current Image Preview -->
                        <div class="text-center mb-4">
                            <img src="{{ asset('storage/' . $image->image) }}" 
                                 alt="{{ $image->title }}" 
                                 class="img-fluid rounded shadow-sm" 
                                 style="max-height: 300px;">
                            <div class="mt-2">
                                বর্তমান স্ট্যাটাস: {!! $image->status_badge !!}
                                {!! $image->type_badge !!}
                            </div>
                        </div>

                        <form action="{{ route('dashboard.listings.images.update', [$listing, $image]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">ছবির ধরন <span class="text-danger">*</span></label>
                                <select name="type" class="form-select" required>
                                    @foreach($types as $value => $label)
                                        <option value="{{ $value }}" {{ old('type', $image->type) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">ছবির অবস্থান <span class="text-danger">*</span></label>
                                    <select name="position" class="form-select" required>
                                        @foreach($positions as $value => $label)
                                            <option value="{{ $value }}" {{ old('position', $image->position) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">ছবিটি কোন পাশে/কোণায় দেখাবে</div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">ছবির আকার <span class="text-danger">*</span></label>
                                    <select name="display_size" class="form-select" required>
                                        @foreach($sizes as $value => $label)
                                            <option value="{{ $value }}" {{ old('display_size', $image->display_size) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">ছবিটি কত বড় আকারে দেখাবে</div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">শিরোনাম (ঐচ্ছিক)</label>
                                <input type="text" name="title" class="form-control" 
                                       value="{{ old('title', $image->title) }}" 
                                       placeholder="যেমন: ঈদ স্পেশাল অফার ২০২৬">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">বিবরণ (ঐচ্ছিক)</label>
                                <textarea name="description" class="form-control" rows="3" 
                                          placeholder="অফার বা প্রচারের বিস্তারিত বিবরণ লিখুন">{{ old('description', $image->description) }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">শুরুর তারিখ (ঐচ্ছিক)</label>
                                    <input type="date" name="valid_from" class="form-control" 
                                           value="{{ old('valid_from', $image->valid_from?->format('Y-m-d')) }}">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">শেষের তারিখ (ঐচ্ছিক)</label>
                                    <input type="date" name="valid_until" class="form-control" 
                                           value="{{ old('valid_until', $image->valid_until?->format('Y-m-d')) }}">
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" 
                                           id="is_active" value="1" {{ old('is_active', $image->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">সক্রিয় রাখুন</label>
                                </div>
                            </div>

                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                সম্পাদনা করলে ছবিটি পুনরায় অনুমোদনের জন্য পাঠানো হবে।
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i>সংরক্ষণ করুন
                                </button>
                                <a href="{{ route('dashboard.listings.images', $listing) }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>বাতিল
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
