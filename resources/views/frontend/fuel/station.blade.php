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
                                <a href="{{ route('fuel.create-report', $station->id) }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-plus-circle me-2"></i>আপডেট দিন
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Latest Status -->
                @if($station->reports->count() > 0)
                    @php $latestReport = $station->reports->first(); @endphp
                    <div class="card shadow-sm mb-4 border-{{ ($latestReport->petrol_available || $latestReport->diesel_available || $latestReport->octane_available) ? 'success' : 'danger' }}" style="border-width: 2px;">
                        <div class="card-header bg-{{ ($latestReport->petrol_available || $latestReport->diesel_available || $latestReport->octane_available) ? 'success' : 'danger' }} text-white">
                            <h5 class="mb-0"><i class="fas fa-clock me-2"></i>সর্বশেষ আপডেট - {{ $latestReport->created_at->diffForHumans() }}</h5>
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
                                            <p class="mt-2 mb-0 fw-bold">৳{{ number_format($latestReport->petrol_price, 0) }}</p>
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
                                            <p class="mt-2 mb-0 fw-bold">৳{{ number_format($latestReport->diesel_price, 0) }}</p>
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
                                            <p class="mt-2 mb-0 fw-bold">৳{{ number_format($latestReport->octane_price, 0) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <span class="badge bg-{{ $latestReport->queue_status == 'none' ? 'success' : ($latestReport->queue_status == 'short' ? 'info' : ($latestReport->queue_status == 'medium' ? 'warning' : 'danger')) }} fs-5 px-4 py-2">
                                    <i class="fas fa-users me-2"></i>{{ $latestReport->queue_status_bangla }}
                                </span>
                            </div>
                            @if($latestReport->notes)
                                <div class="alert alert-info mt-3 mb-0">
                                    <i class="fas fa-info-circle me-2"></i>{{ $latestReport->notes }}
                                </div>
                            @endif
                            <p class="text-center text-muted mt-3 mb-0 small">
                                <i class="fas fa-user me-1"></i>আপডেট করেছেন: {{ $latestReport->reporter_name }}
                            </p>
                        </div>
                    </div>
                @endif

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
                                                        <i class="fas fa-check-circle text-success ms-1" title="ভেরিফাইড"></i>
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
