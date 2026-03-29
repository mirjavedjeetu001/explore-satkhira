@extends('frontend.layouts.app')

@section('title', $station->name . ' - তেলের ইতিহাস')
@section('meta_description', $station->name . ' পেট্রোল পাম্পে তেলের প্রাপ্যতার ইতিহাস দেখুন')

@section('content')
<div class="fuel-station-page py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Station Header -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h1 class="h3 fw-bold text-primary mb-2">
                                    <i class="fas fa-gas-pump me-2"></i>{{ $station->name }}
                                </h1>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $station->upazila->name }}
                                    @if($station->address)
                                        - {{ $station->address }}
                                    @endif
                                </p>
                                @if($station->phone)
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-phone me-1"></i>{{ $station->phone }}
                                    </p>
                                @endif
                                @if($station->google_map_link)
                                    <a href="{{ $station->google_map_link }}" target="_blank" class="btn btn-sm btn-outline-success mt-2">
                                        <i class="fas fa-map-marker-alt me-1"></i>Google Maps এ দেখুন
                                    </a>
                                @endif
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <a href="{{ route('fuel.create-report', $station->id) }}" class="btn btn-primary btn-lg mb-2">
                                    <i class="fas fa-plus-circle me-2"></i>আপডেট দিন
                                </a>
                                <div>
                                    <button class="btn btn-outline-warning fuel-subscribe-btn"
                                        data-station-id="{{ $station->id }}"
                                        onclick="toggleFuelSubscription(this);">
                                        <i class="far fa-bell"></i> <span>🔔 আপডেট নোটিফিকেশন পান</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Latest Status -->
                @if($station->reports->count() > 0)
                    @php $latestReport = $station->reports->first(); @endphp
                    <div class="card shadow-sm mb-4 border-{{ ($latestReport->petrol_available || $latestReport->diesel_available || $latestReport->octane_available) ? 'success' : 'danger' }}" style="border-width: 2px;">
                        <div class="card-header bg-{{ ($latestReport->petrol_available || $latestReport->diesel_available || $latestReport->octane_available) ? 'success' : 'danger' }} text-white">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>সর্বশেষ আপডেট - {{ $latestReport->created_at->diffForHumans() }} ({{ $latestReport->created_at->format('d M Y, h:i A') }})</h5>
                                <div class="d-flex gap-2">
                                    @if($latestReport->is_verified)
                                        <span class="badge bg-warning text-dark"><i class="fas fa-check-circle me-1"></i>যাচাইকৃত</span>
                                    @else
                                        <span class="badge bg-light text-dark"><i class="fas fa-exclamation-triangle me-1"></i>যাচাই করা হয়নি</span>
                                    @endif
                                    <span class="badge bg-info"><i class="fas fa-eye me-1"></i>{{ number_format($station->view_count ?? 0) }} বার দেখা হয়েছে</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-4 text-center">
                                    <div class="p-3 rounded {{ $latestReport->petrol_available ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                                        <i class="fas fa-gas-pump fa-2x mb-2 {{ $latestReport->petrol_available ? 'text-success' : 'text-danger' }}"></i>
                                        <h5 class="fw-bold">পেট্রোল</h5>
                                        <span class="badge fs-6 {{ $latestReport->petrol_available ? 'bg-success' : 'bg-danger' }}">
                                            {{ $latestReport->petrol_available ? 'আছে' : 'নেই' }}
                                        </span>
                                        @if($latestReport->petrol_price)
                                            <p class="mt-2 mb-0 small text-muted">প্রতি লিটার: <strong>৳{{ number_format($latestReport->petrol_price, 0) }}</strong></p>
                                        @endif
                                        @if($latestReport->petrol_selling_price)
                                            <p class="mb-0 fw-bold text-primary">বিক্রয় মূল্য: ৳{{ number_format($latestReport->petrol_selling_price, 0) }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="p-3 rounded {{ $latestReport->diesel_available ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                                        <i class="fas fa-gas-pump fa-2x mb-2 {{ $latestReport->diesel_available ? 'text-success' : 'text-danger' }}"></i>
                                        <h5 class="fw-bold">ডিজেল</h5>
                                        <span class="badge fs-6 {{ $latestReport->diesel_available ? 'bg-success' : 'bg-danger' }}">
                                            {{ $latestReport->diesel_available ? 'আছে' : 'নেই' }}
                                        </span>
                                        @if($latestReport->diesel_price)
                                            <p class="mt-2 mb-0 small text-muted">প্রতি লিটার: <strong>৳{{ number_format($latestReport->diesel_price, 0) }}</strong></p>
                                        @endif
                                        @if($latestReport->diesel_selling_price)
                                            <p class="mb-0 fw-bold text-primary">বিক্রয় মূল্য: ৳{{ number_format($latestReport->diesel_selling_price, 0) }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="p-3 rounded {{ $latestReport->octane_available ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                                        <i class="fas fa-gas-pump fa-2x mb-2 {{ $latestReport->octane_available ? 'text-success' : 'text-danger' }}"></i>
                                        <h5 class="fw-bold">অকটেন</h5>
                                        <span class="badge fs-6 {{ $latestReport->octane_available ? 'bg-success' : 'bg-danger' }}">
                                            {{ $latestReport->octane_available ? 'আছে' : 'নেই' }}
                                        </span>
                                        @if($latestReport->octane_price)
                                            <p class="mt-2 mb-0 small text-muted">প্রতি লিটার: <strong>৳{{ number_format($latestReport->octane_price, 0) }}</strong></p>
                                        @endif
                                        @if($latestReport->octane_selling_price)
                                            <p class="mb-0 fw-bold text-primary">বিক্রয় মূল্য: ৳{{ number_format($latestReport->octane_selling_price, 0) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <span class="badge bg-{{ $latestReport->queue_status == 'none' ? 'success' : ($latestReport->queue_status == 'short' ? 'info' : ($latestReport->queue_status == 'medium' ? 'warning' : 'danger')) }} fs-5 px-4 py-2">
                                    <i class="fas fa-users me-2"></i>{{ $latestReport->queue_status_bangla }}
                                </span>
                                @if($latestReport->fixed_amount)
                                    <div class="mt-2">
                                        <span class="badge bg-warning text-dark fs-5 px-4 py-2">
                                            <i class="fas fa-hand-holding-usd me-2"></i>মাথাপিছু ৳{{ number_format($latestReport->fixed_amount, 0) }} টাকার তেল দিচ্ছে
                                        </span>
                                    </div>
                                @endif
                            </div>
                            @if($latestReport->notes)
                                <div class="alert alert-info mt-3 mb-0">
                                    <i class="fas fa-info-circle me-2"></i>{{ $latestReport->notes }}
                                </div>
                            @endif
                            
                            @if($latestReport->images && count($latestReport->images) > 0)
                                <div class="text-center mt-3">
                                    <p class="text-muted small mb-2"><i class="fas fa-camera me-1"></i>প্রমাণের ছবি</p>
                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                        @foreach($latestReport->images as $img)
                                            <img src="{{ asset('uploads/fuel/' . $img) }}" alt="তথ্য প্রমাণ" class="img-fluid rounded" style="max-height: 250px; cursor: pointer;" onclick="window.open(this.src)">
                                        @endforeach
                                    </div>
                                </div>
                            @elseif($latestReport->image)
                                <div class="text-center mt-3">
                                    <img src="{{ asset('uploads/fuel/' . $latestReport->image) }}" alt="তথ্য প্রমাণ" class="img-fluid rounded" style="max-height: 300px; cursor: pointer;" onclick="window.open(this.src)">
                                    <p class="text-muted small mt-2"><i class="fas fa-camera me-1"></i>প্রমাণের ছবি</p>
                                </div>
                            @endif
                            
                            <p class="text-center text-muted mt-3 mb-0 small">
                                <i class="fas fa-user me-1"></i>আপডেট করেছেন: {{ $latestReport->reporter_name }}
                            </p>
                            
                            <!-- Voting Section -->
                            <div class="vote-section mt-3 pt-3 border-top text-center" id="vote-section-{{ $latestReport->id }}">
                                <p class="mb-2">এই তথ্য কি সঠিক?</p>
                                <div class="d-flex justify-content-center gap-3">
                                    <button type="button" class="btn btn-outline-success" 
                                            onclick="voteReport({{ $latestReport->id }}, 'correct')" id="btn-correct-{{ $latestReport->id }}">
                                        <i class="fas fa-thumbs-up me-1"></i>সঠিক 
                                        <span class="badge bg-success">{{ $latestReport->correct_votes ?? 0 }}</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger"
                                            onclick="voteReport({{ $latestReport->id }}, 'incorrect')" id="btn-incorrect-{{ $latestReport->id }}">
                                        <i class="fas fa-thumbs-down me-1"></i>ভুল 
                                        <span class="badge bg-danger">{{ $latestReport->incorrect_votes ?? 0 }}</span>
                                    </button>
                                </div>
                                @if($latestReport->is_verified)
                                    <div class="mt-2">
                                        <span class="badge bg-success fs-6"><i class="fas fa-check-circle me-1"></i>যাচাইকৃত তথ্য</span>
                                    </div>
                                @elseif(($latestReport->incorrect_votes ?? 0) > ($latestReport->correct_votes ?? 0) && ($latestReport->incorrect_votes ?? 0) >= 2)
                                    <div class="mt-2">
                                        <span class="badge bg-danger fs-6"><i class="fas fa-exclamation-triangle me-1"></i>এই তথ্য ভুল হতে পারে</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Comments Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-comments me-2"></i>মন্তব্য ({{ $station->comments->count() }})</h5>
                    </div>
                    <div class="card-body">
                        <!-- Comment Form -->
                        <form action="{{ route('fuel.store-comment', $station->id) }}" method="POST" class="mb-4">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">আপনার নাম <span class="text-danger">*</span></label>
                                    <input type="text" name="commenter_name" class="form-control @error('commenter_name') is-invalid @enderror" placeholder="আপনার নাম লিখুন" required>
                                    @error('commenter_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">মোবাইল নম্বর <span class="text-danger">*</span></label>
                                    <input type="text" name="commenter_phone" class="form-control @error('commenter_phone') is-invalid @enderror" placeholder="01XXXXXXXXX" required>
                                    @error('commenter_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">মন্তব্য <span class="text-danger">*</span></label>
                                    <textarea name="comment" class="form-control @error('comment') is-invalid @enderror" rows="3" placeholder="আপনার মন্তব্য লিখুন..." required></textarea>
                                    @error('comment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-2"></i>মন্তব্য করুন
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Existing Comments -->
                        @if($station->comments && $station->comments->count() > 0)
                            <hr>
                            <div class="comments-list">
                                @foreach($station->comments->sortByDesc('created_at') as $comment)
                                    <div class="comment-item mb-3 p-3 bg-light rounded">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <strong><i class="fas fa-user-circle me-1 text-primary"></i>{{ $comment->commenter_name }}</strong>
                                                <span class="text-muted small ms-2">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <span class="text-muted small">{{ $comment->created_at->format('d M Y, h:i A') }}</span>
                                        </div>
                                        <p class="mb-0 mt-2">{{ $comment->comment }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-3">
                                <i class="fas fa-comment-slash fa-2x mb-2"></i>
                                <p class="mb-0">এখনো কোন মন্তব্য নেই। প্রথম মন্তব্য করুন!</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Report History -->
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i>আপডেটের ইতিহাস</h5>
                    </div>
                    <div class="card-body p-0">
                        @if($station->reports->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>সময়</th>
                                            <th>পেট্রোল</th>
                                            <th>ডিজেল</th>
                                            <th>অকটেন</th>
                                            <th>লাইন</th>
                                            <th>আপডেটকারী</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($station->reports as $report)
                                            <tr>
                                                <td>
                                                    <small>{{ $report->created_at->format('d M Y') }}</small><br>
                                                    <small class="text-muted">{{ $report->created_at->format('h:i A') }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $report->petrol_available ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $report->petrol_available ? 'আছে' : 'নেই' }}
                                                    </span>
                                                    @if($report->petrol_price)
                                                        <small class="d-block text-muted">৳{{ number_format($report->petrol_price, 0) }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge {{ $report->diesel_available ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $report->diesel_available ? 'আছে' : 'নেই' }}
                                                    </span>
                                                    @if($report->diesel_price)
                                                        <small class="d-block text-muted">৳{{ number_format($report->diesel_price, 0) }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge {{ $report->octane_available ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $report->octane_available ? 'আছে' : 'নেই' }}
                                                    </span>
                                                    @if($report->octane_price)
                                                        <small class="d-block text-muted">৳{{ number_format($report->octane_price, 0) }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $report->queue_status == 'none' ? 'success' : ($report->queue_status == 'short' ? 'info' : ($report->queue_status == 'medium' ? 'warning' : 'danger')) }}">
                                                        {{ $report->queue_status_bangla }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ $report->reporter_name }}
                                                    @if($report->is_verified)
                                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>যাচাইকৃত</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle me-1"></i>যাচাই করা হয়নি</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                                <p class="text-muted">এই পাম্পে কোন আপডেট নেই</p>
                                <a href="{{ route('fuel.create-report', $station->id) }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> প্রথম আপডেট দিন
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Back Button -->
                <div class="text-center mt-4">
                    <a href="{{ route('fuel.index') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>সব পাম্প দেখুন
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function voteReport(reportId, voteType) {
    fetch(`/fuel/report/${reportId}/vote`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ vote: voteType })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update vote counts
            const correctBtn = document.getElementById(`btn-correct-${reportId}`);
            const incorrectBtn = document.getElementById(`btn-incorrect-${reportId}`);
            
            correctBtn.innerHTML = `<i class="fas fa-thumbs-up me-1"></i>সঠিক <span class="badge bg-success">${data.correct_votes}</span>`;
            incorrectBtn.innerHTML = `<i class="fas fa-thumbs-down me-1"></i>ভুল <span class="badge bg-danger">${data.incorrect_votes}</span>`;
            
            // Disable buttons after voting
            correctBtn.disabled = true;
            incorrectBtn.disabled = true;
            correctBtn.classList.add('disabled');
            incorrectBtn.classList.add('disabled');
            
            alert(data.message);
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        alert('সমস্যা হয়েছে। আবার চেষ্টা করুন।');
    });
}
</script>
@endpush
