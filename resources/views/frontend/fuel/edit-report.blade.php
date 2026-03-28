@extends('frontend.layouts.app')

@section('title', 'নতুন আপডেট দিন - ' . $station->name)
@section('meta_description', $station->name . ' পেট্রোল পাম্পে নতুন আপডেট দিন')

@section('content')
<div class="fuel-report-page py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Header -->
                <div class="text-center mb-4">
                    <h1 class="fw-bold text-primary mb-2">
                        <i class="fas fa-sync-alt me-2"></i>নতুন আপডেট দিন
                    </h1>
                    <p class="text-muted">সর্বশেষ তথ্য থেকে শুরু করে নতুন আপডেট দিন</p>
                </div>

                <div class="card shadow">
                    <div class="card-body p-4">
                        <!-- Station Info -->
                        <div class="alert alert-info mb-4">
                            <h5 class="mb-1"><i class="fas fa-gas-pump me-2"></i>{{ $station->name }}</h5>
                            <small><i class="fas fa-map-marker-alt me-1"></i>{{ $station->upazila->name }}</small>
                        </div>

                        <!-- Latest Report Info -->
                        @if($latestReport && $latestReport->id != $report->id)
                            <div class="alert alert-warning mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>{{ $latestReport->reporter_name }}</strong> সর্বশেষ আপডেট দিয়েছেন {{ $latestReport->created_at->diffForHumans() }}।
                                নিচে সেই তথ্য দেখানো হয়েছে।
                            </div>
                        @endif

                        <form action="{{ route('fuel.store-report', $station->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="fuel_station_id" value="{{ $station->id }}">

                            <!-- Reporter Info -->
                            <h5 class="mb-3 fw-bold text-primary"><i class="fas fa-user me-2"></i>আপনার তথ্য</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">আপনার নাম <span class="text-danger">*</span></label>
                                    <input type="text" name="reporter_name" class="form-control" 
                                           value="{{ old('reporter_name', request()->cookie('fuel_reporter_name')) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">মোবাইল নম্বর <span class="text-danger">*</span></label>
                                    <input type="tel" name="reporter_phone" class="form-control" 
                                           value="{{ old('reporter_phone', request()->cookie('fuel_reporter_phone')) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">৪ সংখ্যার PIN <span class="text-danger">*</span></label>
                                    <input type="text" name="edit_pin" class="form-control" 
                                           placeholder="1234" maxlength="4" pattern="[0-9]{4}" required
                                           style="font-size: 1.2em; letter-spacing: 5px; text-align: center;">
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Fuel Availability -->
                            <h5 class="mb-3 fw-bold text-primary"><i class="fas fa-tint me-2"></i>তেলের প্রাপ্যতা</h5>
                            <div class="row g-4 mb-4">
                                <!-- Petrol -->
                                <div class="col-md-4">
                                    <div class="card h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-gas-pump fa-2x text-warning mb-2"></i>
                                            <h6 class="fw-bold">পেট্রোল</h6>
                                            <div class="form-check form-switch d-flex justify-content-center mb-2">
                                                <input class="form-check-input" type="checkbox" name="petrol_available" value="1" id="petrolAvailable" 
                                                       {{ $latestReport && $latestReport->petrol_available ? 'checked' : '' }} style="width: 3em; height: 1.5em;">
                                            </div>
                                            <label class="form-label small text-muted">প্রতি লিটার দাম (ঐচ্ছিক)</label>
                                            <input type="number" step="0.01" name="petrol_price" class="form-control text-center mb-2" placeholder="৳ প্রতি লিটার" 
                                                   value="{{ $latestReport ? $latestReport->petrol_price : '' }}">
                                            <label class="form-label small text-muted">কত টাকায় দিচ্ছে (ঐচ্ছিক)</label>
                                            <input type="number" step="0.01" name="petrol_selling_price" class="form-control text-center" placeholder="৳ বিক্রয় মূল্য" 
                                                   value="{{ $latestReport ? $latestReport->petrol_selling_price : '' }}">
                                        </div>
                                    </div>
                                </div>
                                <!-- Diesel -->
                                <div class="col-md-4">
                                    <div class="card h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-gas-pump fa-2x text-success mb-2"></i>
                                            <h6 class="fw-bold">ডিজেল</h6>
                                            <div class="form-check form-switch d-flex justify-content-center mb-2">
                                                <input class="form-check-input" type="checkbox" name="diesel_available" value="1" id="dieselAvailable" 
                                                       {{ $latestReport && $latestReport->diesel_available ? 'checked' : '' }} style="width: 3em; height: 1.5em;">
                                            </div>
                                            <label class="form-label small text-muted">প্রতি লিটার দাম (ঐচ্ছিক)</label>
                                            <input type="number" step="0.01" name="diesel_price" class="form-control text-center mb-2" placeholder="৳ প্রতি লিটার" 
                                                   value="{{ $latestReport ? $latestReport->diesel_price : '' }}">
                                            <label class="form-label small text-muted">কত টাকায় দিচ্ছে (ঐচ্ছিক)</label>
                                            <input type="number" step="0.01" name="diesel_selling_price" class="form-control text-center" placeholder="৳ বিক্রয় মূল্য" 
                                                   value="{{ $latestReport ? $latestReport->diesel_selling_price : '' }}">
                                        </div>
                                    </div>
                                </div>
                                <!-- Octane -->
                                <div class="col-md-4">
                                    <div class="card h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-gas-pump fa-2x text-danger mb-2"></i>
                                            <h6 class="fw-bold">অকটেন</h6>
                                            <div class="form-check form-switch d-flex justify-content-center mb-2">
                                                <input class="form-check-input" type="checkbox" name="octane_available" value="1" id="octaneAvailable" 
                                                       {{ $latestReport && $latestReport->octane_available ? 'checked' : '' }} style="width: 3em; height: 1.5em;">
                                            </div>
                                            <label class="form-label small text-muted">প্রতি লিটার দাম (ঐচ্ছিক)</label>
                                            <input type="number" step="0.01" name="octane_price" class="form-control text-center mb-2" placeholder="৳ প্রতি লিটার" 
                                                   value="{{ $latestReport ? $latestReport->octane_price : '' }}">
                                            <label class="form-label small text-muted">কত টাকায় দিচ্ছে (ঐচ্ছিক)</label>
                                            <input type="number" step="0.01" name="octane_selling_price" class="form-control text-center" placeholder="৳ বিক্রয় মূল্য" 
                                                   value="{{ $latestReport ? $latestReport->octane_selling_price : '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Fixed Amount Limit -->
                            <div class="mb-4">
                                <h5 class="mb-3 fw-bold text-primary"><i class="fas fa-hand-holding-usd me-2"></i>মাথাপিছু কত টাকার তেল দিচ্ছে?</h5>
                                <div class="row justify-content-center">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text"><i class="fas fa-money-bill-wave text-success"></i></span>
                                            <input type="number" name="fixed_amount" class="form-control text-center" placeholder="যেমন: 500" step="1" min="0"
                                                   value="{{ $latestReport ? $latestReport->fixed_amount : '' }}">
                                            <span class="input-group-text">টাকা</span>
                                        </div>
                                        <small class="text-muted">খালি রাখলে "সীমাহীন" ধরে নেওয়া হবে</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Queue Status -->
                            <h5 class="mb-3 fw-bold text-primary"><i class="fas fa-users me-2"></i>লাইনের অবস্থা</h5>
                            <div class="row g-2 mb-4">
                                <div class="col-6 col-md-3">
                                    <input type="radio" class="btn-check" name="queue_status" id="queueNone" value="none" 
                                           {{ !$latestReport || $latestReport->queue_status == 'none' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-success w-100 py-3" for="queueNone">
                                        <i class="fas fa-smile fa-lg d-block mb-1"></i>
                                        <span>কোন লাইন নেই</span>
                                    </label>
                                </div>
                                <div class="col-6 col-md-3">
                                    <input type="radio" class="btn-check" name="queue_status" id="queueShort" value="short" 
                                           {{ $latestReport && $latestReport->queue_status == 'short' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-info w-100 py-3" for="queueShort">
                                        <i class="fas fa-meh fa-lg d-block mb-1"></i>
                                        <span>ছোট লাইন</span>
                                    </label>
                                </div>
                                <div class="col-6 col-md-3">
                                    <input type="radio" class="btn-check" name="queue_status" id="queueMedium" value="medium" 
                                           {{ $latestReport && $latestReport->queue_status == 'medium' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-warning w-100 py-3" for="queueMedium">
                                        <i class="fas fa-frown fa-lg d-block mb-1"></i>
                                        <span>মাঝারি লাইন</span>
                                    </label>
                                </div>
                                <div class="col-6 col-md-3">
                                    <input type="radio" class="btn-check" name="queue_status" id="queueLong" value="long" 
                                           {{ $latestReport && $latestReport->queue_status == 'long' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-danger w-100 py-3" for="queueLong">
                                        <i class="fas fa-sad-tear fa-lg d-block mb-1"></i>
                                        <span>বড় লাইন</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Existing Images -->
                            @if($latestReport && ($latestReport->images || $latestReport->image))
                                <div class="mb-3">
                                    <h6 class="fw-bold text-muted">আগের ছবি:</h6>
                                    <div class="d-flex gap-2 flex-wrap">
                                        @if($latestReport->images)
                                            @foreach($latestReport->images as $img)
                                                <img src="{{ asset('uploads/fuel/' . $img) }}" alt="আগের ছবি" class="rounded" style="max-height: 100px;">
                                            @endforeach
                                        @elseif($latestReport->image)
                                            <img src="{{ asset('uploads/fuel/' . $latestReport->image) }}" alt="আগের ছবি" class="rounded" style="max-height: 100px;">
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Image Upload (Required) -->
                            <div class="mb-4">
                                <h5 class="mb-3 fw-bold text-primary"><i class="fas fa-camera me-2"></i>ছবি আপলোড করুন <span class="text-danger">*</span></h5>
                                <p class="text-muted small mb-2">পাম্পের তেল লাইন বা বোর্ডের ছবি দিন (একাধিক ছবি দেওয়া যাবে)</p>
                                <input type="file" name="images[]" class="form-control form-control-lg @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" accept="image/*" multiple required id="imageInput" onchange="previewImages(this)">
                                @error('images')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="imagePreview" class="mt-3 d-flex gap-2 flex-wrap"></div>
                            </div>

                            <!-- Notes -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">অতিরিক্ত মন্তব্য (ঐচ্ছিক)</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="যেমন: সকাল ১০টার পর তেল পাওয়া যেতে পারে..."></textarea>
                            </div>

                            <!-- Submit -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>নতুন আপডেট জমা দিন
                                </button>
                                <a href="{{ route('fuel.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>ফিরে যান
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewImages(input) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    
    if (input.files) {
        Array.from(input.files).forEach(function(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Preview';
                img.className = 'rounded';
                img.style.maxHeight = '150px';
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    }
}
</script>
@endpush
@endsection
