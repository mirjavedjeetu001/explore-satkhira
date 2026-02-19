@extends('frontend.layouts.app')

@section('title', 'ছবি আপলোড করুন - ' . $listing->title)

@section('content')
<!-- Page Header -->
<section class="page-header py-4" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="text-white mb-0">নতুন ছবি আপলোড করুন</h3>
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
                        <h5 class="mb-0"><i class="fas fa-upload text-success me-2"></i>ছবি আপলোড ফর্ম</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('dashboard.listings.images.store', $listing) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">ছবির ধরন <span class="text-danger">*</span></label>
                                <select name="type" class="form-select" required>
                                    @foreach($types as $value => $label)
                                        <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">ছবিটি কোন ধরনের তা নির্বাচন করুন</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">ছবির অবস্থান <span class="text-danger">*</span></label>
                                    <select name="position" class="form-select" required>
                                        @foreach($positions as $value => $label)
                                            <option value="{{ $value }}" {{ old('position', 'center') == $value ? 'selected' : '' }}>
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
                                            <option value="{{ $value }}" {{ old('display_size', 'medium') == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">ছবিটি কত বড় আকারে দেখাবে</div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">ছবি নির্বাচন করুন <span class="text-danger">*</span></label>
                                <input type="file" name="images[]" class="form-control" multiple accept="image/*" required id="imageInput">
                                <div class="form-text">একসাথে সর্বোচ্চ ১০টি ছবি নির্বাচন করতে পারবেন (প্রতিটি সর্বোচ্চ ২MB)</div>
                                
                                <!-- Image Preview -->
                                <div id="imagePreview" class="row g-2 mt-2"></div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">শিরোনাম (ঐচ্ছিক)</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}" 
                                       placeholder="যেমন: ঈদ স্পেশাল অফার ২০২৬">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">বিবরণ (ঐচ্ছিক)</label>
                                <textarea name="description" class="form-control" rows="3" 
                                          placeholder="অফার বা প্রচারের বিস্তারিত বিবরণ লিখুন">{{ old('description') }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">শুরুর তারিখ (ঐচ্ছিক)</label>
                                    <input type="date" name="valid_from" class="form-control" value="{{ old('valid_from') }}">
                                    <div class="form-text">অফার কবে থেকে শুরু হবে</div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">শেষের তারিখ (ঐচ্ছিক)</label>
                                    <input type="date" name="valid_until" class="form-control" value="{{ old('valid_until') }}">
                                    <div class="form-text">অফার কবে শেষ হবে</div>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                আপলোড করা ছবি প্রশাসকের অনুমোদনের পর পাবলিক পেজে প্রদর্শিত হবে।
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-upload me-1"></i>আপলোড করুন
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

@push('scripts')
<script>
document.getElementById('imageInput').addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    
    if (this.files.length > 10) {
        alert('সর্বোচ্চ ১০টি ছবি নির্বাচন করতে পারবেন');
        this.value = '';
        return;
    }
    
    Array.from(this.files).forEach((file, index) => {
        if (file.size > 2 * 1024 * 1024) {
            alert(`${file.name} ফাইলটি ২MB এর বেশি`);
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const col = document.createElement('div');
            col.className = 'col-4 col-md-3';
            col.innerHTML = `
                <div class="border rounded p-1">
                    <img src="${e.target.result}" alt="Preview" class="img-fluid rounded" style="height: 80px; width: 100%; object-fit: cover;">
                </div>
            `;
            preview.appendChild(col);
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endpush
@endsection
