@extends('frontend.layouts.app')

@section('title', 'রিপোর্ট বিস্তারিত - ' . $report->fuelStation->name)

@section('content')
<section class="page-header py-4" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="text-white mb-0"><i class="fas fa-eye me-2"></i>রিপোর্ট বিস্তারিত</h3>
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
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Report Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-gas-pump me-1"></i><strong>{{ $report->fuelStation->name }}</strong> - {{ $report->fuelStation->upazila->name }}</span>
                            <span>
                                @if($report->is_verified)
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>যাচাইকৃত</span>
                                @else
                                    <span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle me-1"></i>যাচাই করা হয়নি</span>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>রিপোর্টকারী:</strong> {{ $report->reporter_name }}</p>
                                <p class="mb-1"><strong>ফোন:</strong> {{ $report->reporter_phone }}</p>
                                @if($report->reporter_email)
                                    <p class="mb-1"><strong>ইমেইল:</strong> {{ $report->reporter_email }}</p>
                                @endif
                            </div>
                            <div class="col-md-6 text-md-end">
                                <p class="mb-1"><strong>তারিখ:</strong> {{ $report->created_at->format('d M Y, h:i A') }}</p>
                                <p class="mb-1"><strong>সময়:</strong> {{ $report->created_at->diffForHumans() }}</p>
                                <p class="mb-1">
                                    <span class="badge bg-success"><i class="fas fa-thumbs-up me-1"></i>{{ $report->correct_votes ?? 0 }}</span>
                                    <span class="badge bg-danger"><i class="fas fa-thumbs-down me-1"></i>{{ $report->incorrect_votes ?? 0 }}</span>
                                </p>
                            </div>
                        </div>

                        <!-- Fuel Status -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <div class="p-3 rounded text-center {{ $report->petrol_available ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                                    <h6 class="fw-bold">পেট্রোল</h6>
                                    <span class="badge fs-6 {{ $report->petrol_available ? 'bg-success' : 'bg-danger' }}">
                                        {{ $report->petrol_available ? 'আছে' : 'নেই' }}
                                    </span>
                                    @if($report->petrol_price)
                                        <p class="mb-0 small mt-1">দাম: ৳{{ number_format($report->petrol_price, 0) }}</p>
                                    @endif
                                    @if($report->petrol_selling_price)
                                        <p class="mb-0 small fw-bold text-primary">বিক্রয়: ৳{{ number_format($report->petrol_selling_price, 0) }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 rounded text-center {{ $report->diesel_available ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                                    <h6 class="fw-bold">ডিজেল</h6>
                                    <span class="badge fs-6 {{ $report->diesel_available ? 'bg-success' : 'bg-danger' }}">
                                        {{ $report->diesel_available ? 'আছে' : 'নেই' }}
                                    </span>
                                    @if($report->diesel_price)
                                        <p class="mb-0 small mt-1">দাম: ৳{{ number_format($report->diesel_price, 0) }}</p>
                                    @endif
                                    @if($report->diesel_selling_price)
                                        <p class="mb-0 small fw-bold text-primary">বিক্রয়: ৳{{ number_format($report->diesel_selling_price, 0) }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 rounded text-center {{ $report->octane_available ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                                    <h6 class="fw-bold">অকটেন</h6>
                                    <span class="badge fs-6 {{ $report->octane_available ? 'bg-success' : 'bg-danger' }}">
                                        {{ $report->octane_available ? 'আছে' : 'নেই' }}
                                    </span>
                                    @if($report->octane_price)
                                        <p class="mb-0 small mt-1">দাম: ৳{{ number_format($report->octane_price, 0) }}</p>
                                    @endif
                                    @if($report->octane_selling_price)
                                        <p class="mb-0 small fw-bold text-primary">বিক্রয়: ৳{{ number_format($report->octane_selling_price, 0) }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Queue & Notes -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <strong>লাইন:</strong>
                                <span class="badge bg-{{ $report->queue_status == 'none' ? 'success' : ($report->queue_status == 'short' ? 'info' : ($report->queue_status == 'medium' ? 'warning' : 'danger')) }}">
                                    {{ $report->queue_status_bangla }}
                                </span>
                            </div>
                            @if($report->notes)
                                <div class="col-12">
                                    <strong>নোট:</strong> {{ $report->notes }}
                                </div>
                            @endif
                        </div>

                        <!-- Images -->
                        @if($report->images && count($report->images) > 0)
                            <div class="mt-3">
                                <strong><i class="fas fa-images me-1"></i>ছবি:</strong>
                                <div class="d-flex gap-2 flex-wrap mt-2">
                                    @foreach($report->images as $img)
                                        <img src="{{ asset('uploads/fuel/' . $img) }}" alt="ছবি" class="img-fluid rounded" style="max-height: 200px; cursor: pointer;" onclick="window.open(this.src)">
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-white">
                        <a href="{{ route('dashboard.fuel.reports.edit', $report->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i>এডিট করুন
                        </a>
                        <a href="{{ route('dashboard.fuel.reports') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>তালিকায় ফিরুন
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
