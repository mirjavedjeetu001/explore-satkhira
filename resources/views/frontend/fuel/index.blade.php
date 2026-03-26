@extends('frontend.layouts.app')

@section('title', 'জ্বালানি তেল আপডেট')
@section('meta_description', 'সাতক্ষীরার পেট্রোল পাম্পে তেলের বর্তমান অবস্থা জানুন। পেট্রোল, ডিজেল, অকটেনের প্রাপ্যতা এবং দাম।')

@section('content')
<div class="fuel-tracker-page py-4">
    <div class="container">
        <!-- Header -->
        <div class="text-center mb-4">
            <h1 class="fw-bold text-primary mb-2">
                <i class="fas fa-gas-pump me-2"></i>জ্বালানি তেল আপডেট
            </h1>
            <p class="text-muted">সাতক্ষীরার পেট্রোল পাম্পে তেলের বর্তমান অবস্থা জানুন ও শেয়ার করুন</p>
            <div class="d-flex justify-content-center gap-3 mt-3">
                <span class="badge bg-info fs-6 px-3 py-2">
                    <i class="fas fa-eye me-1"></i> {{ number_format($totalViews ?? 0) }} বার দেখা হয়েছে
                </span>
                <span class="badge bg-secondary fs-6 px-3 py-2">
                    <i class="fas fa-gas-pump me-1"></i> {{ $stations->count() }} টি পাম্প
                </span>
            </div>
        </div>

        <!-- Success/Error Messages -->
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

        <!-- Filter & Add Report -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">উপজেলা অনুযায়ী ফিল্টার</label>
                        <select class="form-select" id="upazilaFilter" onchange="filterByUpazila()">
                            <option value="">সব উপজেলা</option>
                            @foreach($upazilas as $upazila)
                                <option value="{{ $upazila->id }}" {{ $selectedUpazila == $upazila->id ? 'selected' : '' }}>
                                    {{ $upazila->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('fuel.create-report') }}" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-plus-circle me-2"></i>তেলের আপডেট দিন
                        </a>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex justify-content-end gap-2">
                            <span class="badge bg-success fs-6 px-3 py-2"><i class="fas fa-check-circle me-1"></i> আছে</span>
                            <span class="badge bg-danger fs-6 px-3 py-2"><i class="fas fa-times-circle me-1"></i> নেই</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stations Grid -->
        <div class="row g-4">
            @forelse($stations as $station)
                @php
                    $report = $station->displayReport;
                    $hasAnyFuel = $report && ($report->petrol_available || $report->diesel_available || $report->octane_available);
                    $isVerified = $report && $report->is_verified;
                @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm station-card {{ $hasAnyFuel ? 'border-success' : 'border-danger' }}" style="border-width: 2px;">
                        <div class="card-header {{ $hasAnyFuel ? 'bg-success' : 'bg-danger' }} text-white py-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="mb-0 fw-bold">
                                        <i class="fas fa-gas-pump me-2"></i>{{ $station->name }}
                                    </h5>
                                    <small><i class="fas fa-map-marker-alt me-1"></i>{{ $station->upazila->name }}</small>
                                </div>
                                @if($isVerified)
                                    <span class="badge bg-warning text-dark" title="যাচাইকৃত তথ্য">
                                        <i class="fas fa-check-circle"></i> যাচাইকৃত
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            @if($report)
                                <!-- Fuel Availability -->
                                <div class="row g-2 mb-3">
                                    <div class="col-4 text-center">
                                        <div class="fuel-badge {{ $report->petrol_available ? 'available' : 'unavailable' }}">
                                            <i class="fas fa-tint"></i>
                                            <span class="d-block fw-bold">পেট্রোল</span>
                                            <span class="badge {{ $report->petrol_available ? 'bg-success' : 'bg-danger' }}">
                                                {{ $report->petrol_available ? 'আছে' : 'নেই' }}
                                            </span>
                                            @if($report->petrol_price)
                                                <small class="d-block text-muted">৳{{ number_format($report->petrol_price, 0) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-4 text-center">
                                        <div class="fuel-badge {{ $report->diesel_available ? 'available' : 'unavailable' }}">
                                            <i class="fas fa-tint"></i>
                                            <span class="d-block fw-bold">ডিজেল</span>
                                            <span class="badge {{ $report->diesel_available ? 'bg-success' : 'bg-danger' }}">
                                                {{ $report->diesel_available ? 'আছে' : 'নেই' }}
                                            </span>
                                            @if($report->diesel_price)
                                                <small class="d-block text-muted">৳{{ number_format($report->diesel_price, 0) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-4 text-center">
                                        <div class="fuel-badge {{ $report->octane_available ? 'available' : 'unavailable' }}">
                                            <i class="fas fa-tint"></i>
                                            <span class="d-block fw-bold">অকটেন</span>
                                            <span class="badge {{ $report->octane_available ? 'bg-success' : 'bg-danger' }}">
                                                {{ $report->octane_available ? 'আছে' : 'নেই' }}
                                            </span>
                                            @if($report->octane_price)
                                                <small class="d-block text-muted">৳{{ number_format($report->octane_price, 0) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Queue Status -->
                                <div class="text-center mb-3">
                                    <span class="badge bg-{{ $report->queue_status == 'none' ? 'success' : ($report->queue_status == 'short' ? 'info' : ($report->queue_status == 'medium' ? 'warning' : 'danger')) }} fs-6 px-3 py-2">
                                        <i class="fas fa-users me-1"></i>{{ $report->queue_status_bangla }}
                                    </span>
                                </div>

                                <!-- Time & Reporter -->
                                <div class="text-center text-muted small">
                                    <i class="fas fa-clock me-1"></i>{{ $report->created_at->diffForHumans() }}
                                    <span class="d-block">{{ $report->created_at->format('d M Y, h:i A') }}</span>
                                    <div class="mt-1">
                                        <i class="fas fa-user me-1"></i>{{ $report->reporter_name }}
                                    </div>
                                </div>
                                
                                <!-- Voting Section -->
                                <div class="vote-section mt-3 pt-3 border-top" id="vote-section-{{ $report->id }}">
                                    <p class="text-center small mb-2">এই তথ্য কি সঠিক?</p>
                                    <div class="d-flex justify-content-center gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-success vote-btn" 
                                                onclick="voteReport({{ $report->id }}, 'correct')" id="btn-correct-{{ $report->id }}">
                                            <i class="fas fa-thumbs-up me-1"></i>সঠিক 
                                            <span class="badge bg-success">{{ $report->correct_votes ?? 0 }}</span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger vote-btn"
                                                onclick="voteReport({{ $report->id }}, 'incorrect')" id="btn-incorrect-{{ $report->id }}">
                                            <i class="fas fa-thumbs-down me-1"></i>ভুল 
                                            <span class="badge bg-danger">{{ $report->incorrect_votes ?? 0 }}</span>
                                        </button>
                                    </div>
                                    @if($isVerified)
                                        <div class="text-center mt-2">
                                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>যাচাইকৃত তথ্য</span>
                                        </div>
                                    @elseif(($report->incorrect_votes ?? 0) > ($report->correct_votes ?? 0) && ($report->incorrect_votes ?? 0) >= 2)
                                        <div class="text-center mt-2">
                                            <span class="badge bg-danger"><i class="fas fa-exclamation-triangle me-1"></i>এই তথ্য ভুল হতে পারে</span>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                                    <p class="mb-0">এই পাম্পে কোন আপডেট নেই</p>
                                    <a href="{{ route('fuel.create-report', $station->id) }}" class="btn btn-sm btn-outline-primary mt-2">
                                        <i class="fas fa-plus"></i> আপডেট দিন
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer bg-light">
                            <div class="d-flex gap-2">
                                <a href="{{ route('fuel.station', $station->id) }}" class="btn btn-sm btn-outline-primary flex-fill">
                                    <i class="fas fa-history me-1"></i>ইতিহাস
                                </a>
                                <a href="{{ route('fuel.create-report', $station->id) }}" class="btn btn-sm btn-primary flex-fill">
                                    <i class="fas fa-plus me-1"></i>আপডেট দিন
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-gas-pump fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">কোন পেট্রোল পাম্প পাওয়া যায়নি</h4>
                        <a href="{{ route('fuel.create-report') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-plus"></i> নতুন পাম্প ও আপডেট যুক্ত করুন
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- My Reports -->
        @if(count($myReports) > 0)
            <div class="card shadow-sm mt-5">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>আমার দেওয়া আপডেটসমূহ</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>পাম্প</th>
                                    <th>পেট্রোল</th>
                                    <th>ডিজেল</th>
                                    <th>অকটেন</th>
                                    <th>সময়</th>
                                    <th>অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($myReports as $report)
                                    <tr>
                                        <td>
                                            <strong>{{ $report->fuelStation->name }}</strong>
                                            <br><small class="text-muted">{{ $report->fuelStation->upazila->name }}</small>
                                        </td>
                                        <td>
                                            <span class="badge {{ $report->petrol_available ? 'bg-success' : 'bg-danger' }}">
                                                {{ $report->petrol_available ? 'আছে' : 'নেই' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $report->diesel_available ? 'bg-success' : 'bg-danger' }}">
                                                {{ $report->diesel_available ? 'আছে' : 'নেই' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $report->octane_available ? 'bg-success' : 'bg-danger' }}">
                                                {{ $report->octane_available ? 'আছে' : 'নেই' }}
                                            </span>
                                        </td>
                                        <td>{{ $report->created_at->diffForHumans() }}</td>
                                        <td>
                                            <a href="{{ route('fuel.edit-report', $report->id) }}" class="btn btn-sm btn-outline-primary" title="আবার আপডেট দিন">
                                                <i class="fas fa-sync-alt"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteReport({{ $report->id }})" title="মুছে ফেলুন">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.station-card {
    transition: transform 0.2s, box-shadow 0.2s;
}
.station-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
.fuel-badge {
    padding: 10px 5px;
    border-radius: 8px;
    background: #f8f9fa;
}
.fuel-badge.available {
    background: #d1e7dd;
}
.fuel-badge.unavailable {
    background: #f8d7da;
}
.fuel-badge i {
    font-size: 1.5rem;
    margin-bottom: 5px;
}
.fuel-badge.available i {
    color: #198754;
}
.fuel-badge.unavailable i {
    color: #dc3545;
}
</style>

@push('scripts')
<script>
function filterByUpazila() {
    const upazila = document.getElementById('upazilaFilter').value;
    if (upazila) {
        window.location.href = '{{ route("fuel.index") }}?upazila=' + upazila;
    } else {
        window.location.href = '{{ route("fuel.index") }}';
    }
}

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
            
            // Show verification status
            const voteSection = document.getElementById(`vote-section-${reportId}`);
            if (data.is_verified) {
                voteSection.innerHTML += '<div class="text-center mt-2"><span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>যাচাইকৃত তথ্য</span></div>';
            }
            
            alert(data.message);
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        alert('সমস্যা হয়েছে। আবার চেষ্টা করুন।');
    });
}

function deleteReport(id) {
    if (confirm('আপনি কি এই আপডেটটি মুছে ফেলতে চান?')) {
        fetch(`/fuel/report/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.error || 'মুছে ফেলতে ব্যর্থ হয়েছে');
            }
        })
        .catch(error => {
            alert('একটি ত্রুটি হয়েছে');
        });
    }
}
</script>
@endpush
@endsection
