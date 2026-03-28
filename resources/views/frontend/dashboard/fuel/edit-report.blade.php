@extends('frontend.layouts.app')

@section('title', 'রিপোর্ট এডিট - ' . $report->fuelStation->name)

@section('content')
<section class="page-header py-4" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="text-white mb-0"><i class="fas fa-edit me-2"></i>রিপোর্ট এডিট করুন</h3>
                <p class="text-white-50 mb-0">{{ $report->fuelStation->name }} - {{ $report->fuelStation->upazila->name }}</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('dashboard.fuel.reports') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-1"></i>ফিরে যান
                </a>
            </div>
        </div>
    </div>
</section>

<section class="py-4">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="alert alert-info">
            <strong><i class="fas fa-gas-pump me-1"></i>{{ $report->fuelStation->name }}</strong> - {{ $report->fuelStation->upazila->name }}
            | রিপোর্ট #{{ $report->id }} | {{ $report->created_at->format('d M Y, h:i A') }}
        </div>

        <form action="{{ route('dashboard.fuel.reports.update', $report->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white"><i class="fas fa-info-circle me-1"></i>রিপোর্টকারীর তথ্য</div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">নাম</label>
                                    <input type="text" name="reporter_name" class="form-control" value="{{ old('reporter_name', $report->reporter_name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">ফোন</label>
                                    <input type="text" name="reporter_phone" class="form-control" value="{{ old('reporter_phone', $report->reporter_phone) }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white"><i class="fas fa-gas-pump me-1"></i>তেলের তথ্য</div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="fw-bold">পেট্রোল</h6>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" name="petrol_available" value="1" id="petrolAvailable" {{ old('petrol_available', $report->petrol_available) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="petrolAvailable">আছে</label>
                                            </div>
                                            <label class="form-label small">দাম</label>
                                            <input type="number" name="petrol_price" class="form-control form-control-sm" value="{{ old('petrol_price', $report->petrol_price) }}" step="0.01">
                                            <label class="form-label small mt-2">বিক্রয় মূল্য</label>
                                            <input type="number" name="petrol_selling_price" class="form-control form-control-sm" value="{{ old('petrol_selling_price', $report->petrol_selling_price) }}" step="0.01">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="fw-bold">ডিজেল</h6>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" name="diesel_available" value="1" id="dieselAvailable" {{ old('diesel_available', $report->diesel_available) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="dieselAvailable">আছে</label>
                                            </div>
                                            <label class="form-label small">দাম</label>
                                            <input type="number" name="diesel_price" class="form-control form-control-sm" value="{{ old('diesel_price', $report->diesel_price) }}" step="0.01">
                                            <label class="form-label small mt-2">বিক্রয় মূল্য</label>
                                            <input type="number" name="diesel_selling_price" class="form-control form-control-sm" value="{{ old('diesel_selling_price', $report->diesel_selling_price) }}" step="0.01">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="fw-bold">অকটেন</h6>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" name="octane_available" value="1" id="octaneAvailable" {{ old('octane_available', $report->octane_available) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="octaneAvailable">আছে</label>
                                            </div>
                                            <label class="form-label small">দাম</label>
                                            <input type="number" name="octane_price" class="form-control form-control-sm" value="{{ old('octane_price', $report->octane_price) }}" step="0.01">
                                            <label class="form-label small mt-2">বিক্রয় মূল্য</label>
                                            <input type="number" name="octane_selling_price" class="form-control form-control-sm" value="{{ old('octane_selling_price', $report->octane_selling_price) }}" step="0.01">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">লাইনের অবস্থা</label>
                                    <select name="queue_status" class="form-select" required>
                                        <option value="none" {{ $report->queue_status == 'none' ? 'selected' : '' }}>কোন লাইন নেই</option>
                                        <option value="short" {{ $report->queue_status == 'short' ? 'selected' : '' }}>ছোট লাইন</option>
                                        <option value="medium" {{ $report->queue_status == 'medium' ? 'selected' : '' }}>মাঝারি লাইন</option>
                                        <option value="long" {{ $report->queue_status == 'long' ? 'selected' : '' }}>বড় লাইন</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">মাথাপিছু কত টাকার তেল</label>
                                    <div class="input-group">
                                        <span class="input-group-text">৳</span>
                                        <input type="number" name="fixed_amount" class="form-control" value="{{ old('fixed_amount', $report->fixed_amount) }}" placeholder="যেমন: 500" step="1" min="0">
                                    </div>
                                    <small class="text-muted">খালি = সীমাহীন</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">ভেরিফিকেশন</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" name="is_verified" value="1" id="isVerified" {{ $report->is_verified ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isVerified">যাচাইকৃত</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="form-label fw-semibold">নোট</label>
                                <textarea name="notes" class="form-control" rows="2">{{ old('notes', $report->notes) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Existing Images -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white"><i class="fas fa-images me-1"></i>বর্তমান ছবি</div>
                        <div class="card-body">
                            @if($report->images && count($report->images) > 0)
                                <div class="row g-2">
                                    @foreach($report->images as $img)
                                        <div class="col-6" id="img-container-{{ $loop->index }}">
                                            <div class="position-relative">
                                                <img src="{{ asset('uploads/fuel/' . $img) }}" alt="ছবি" class="img-fluid rounded mb-1" style="cursor: pointer;" onclick="window.open(this.src)">
                                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" onclick="deleteImage({{ $report->id }}, '{{ $img }}', {{ $loop->index }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted text-center mb-0">কোন ছবি নেই</p>
                            @endif
                        </div>
                    </div>

                    <!-- Add New Images -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white"><i class="fas fa-plus me-1"></i>নতুন ছবি</div>
                        <div class="card-body">
                            <input type="file" name="new_images[]" class="form-control" multiple accept="image/*">
                            <small class="text-muted">সর্বোচ্চ ৫MB প্রতিটি</small>
                        </div>
                    </div>

                    <!-- Vote Info -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white"><i class="fas fa-vote-yea me-1"></i>ভোট</div>
                        <div class="card-body text-center">
                            <span class="badge bg-success fs-6 px-3 py-2 me-2">
                                <i class="fas fa-thumbs-up me-1"></i>{{ $report->correct_votes ?? 0 }}
                            </span>
                            <span class="badge bg-danger fs-6 px-3 py-2">
                                <i class="fas fa-thumbs-down me-1"></i>{{ $report->incorrect_votes ?? 0 }}
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-save me-2"></i>আপডেট সেভ করুন
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

@push('scripts')
<script>
function deleteImage(reportId, imageName, index) {
    if (confirm('এই ছবিটি মুছে ফেলতে চান?')) {
        fetch(`/dashboard/fuel/reports/${reportId}/delete-image`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ image: imageName })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById('img-container-' + index).remove();
            } else {
                alert('মুছতে সমস্যা হয়েছে');
            }
        });
    }
}
</script>
@endpush
@endsection
