@extends('admin.layouts.app')

@section('title', 'রিপোর্ট বিস্তারিত - ' . $report->fuelStation->name)

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h1><i class="fas fa-eye me-2"></i>রিপোর্ট বিস্তারিত</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.fuel.reports.edit', $report->id) }}" class="btn btn-primary">
            <i class="fas fa-edit me-1"></i> এডিট করুন
        </a>
        <a href="{{ route('admin.fuel.reports') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> ফিরে যান
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-gas-pump me-1"></i>{{ $report->fuelStation->name }} - {{ $report->fuelStation->upazila->name }}
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>রিপোর্টকারী:</strong> {{ $report->reporter_name }}</p>
                        <p><strong>ফোন:</strong> {{ $report->reporter_phone }}</p>
                        @if($report->reporter_email)
                            <p><strong>ইমেইল:</strong> {{ $report->reporter_email }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <p><strong>তারিখ:</strong> {{ $report->created_at->format('d M Y, h:i A') }}</p>
                        <p><strong>সময়:</strong> {{ $report->created_at->diffForHumans() }}</p>
                        <p>
                            <strong>স্ট্যাটাস:</strong> 
                            @if($report->is_verified)
                                <span class="badge bg-success">যাচাইকৃত</span>
                            @else
                                <span class="badge bg-warning text-dark">যাচাই করা হয়নি</span>
                            @endif
                        </p>
                    </div>
                </div>
                
                <div class="row g-3 mb-3">
                    <div class="col-md-4 text-center">
                        <div class="p-3 rounded {{ $report->petrol_available ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                            <h5 class="fw-bold">পেট্রোল</h5>
                            <span class="badge fs-6 {{ $report->petrol_available ? 'bg-success' : 'bg-danger' }}">
                                {{ $report->petrol_available ? 'আছে' : 'নেই' }}
                            </span>
                            @if($report->petrol_price)
                                <p class="mt-2 mb-0 small">প্রতি লিটার: <strong>৳{{ number_format($report->petrol_price, 0) }}</strong></p>
                            @endif
                            @if($report->petrol_selling_price)
                                <p class="mb-0 fw-bold text-primary">বিক্রয়: ৳{{ number_format($report->petrol_selling_price, 0) }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="p-3 rounded {{ $report->diesel_available ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                            <h5 class="fw-bold">ডিজেল</h5>
                            <span class="badge fs-6 {{ $report->diesel_available ? 'bg-success' : 'bg-danger' }}">
                                {{ $report->diesel_available ? 'আছে' : 'নেই' }}
                            </span>
                            @if($report->diesel_price)
                                <p class="mt-2 mb-0 small">প্রতি লিটার: <strong>৳{{ number_format($report->diesel_price, 0) }}</strong></p>
                            @endif
                            @if($report->diesel_selling_price)
                                <p class="mb-0 fw-bold text-primary">বিক্রয়: ৳{{ number_format($report->diesel_selling_price, 0) }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="p-3 rounded {{ $report->octane_available ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                            <h5 class="fw-bold">অকটেন</h5>
                            <span class="badge fs-6 {{ $report->octane_available ? 'bg-success' : 'bg-danger' }}">
                                {{ $report->octane_available ? 'আছে' : 'নেই' }}
                            </span>
                            @if($report->octane_price)
                                <p class="mt-2 mb-0 small">প্রতি লিটার: <strong>৳{{ number_format($report->octane_price, 0) }}</strong></p>
                            @endif
                            @if($report->octane_selling_price)
                                <p class="mb-0 fw-bold text-primary">বিক্রয়: ৳{{ number_format($report->octane_selling_price, 0) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="text-center mb-3">
                    <span class="badge bg-{{ $report->queue_status == 'none' ? 'success' : ($report->queue_status == 'short' ? 'info' : ($report->queue_status == 'medium' ? 'warning' : 'danger')) }} fs-6 px-3 py-2">
                        <i class="fas fa-users me-1"></i>{{ $report->queue_status_bangla }}
                    </span>
                </div>
                
                @if($report->notes)
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-1"></i><strong>নোট:</strong> {{ $report->notes }}
                    </div>
                @endif
                
                <div class="d-flex justify-content-center gap-3">
                    <span class="badge bg-success fs-6 px-3 py-2"><i class="fas fa-thumbs-up me-1"></i>{{ $report->correct_votes ?? 0 }}</span>
                    <span class="badge bg-danger fs-6 px-3 py-2"><i class="fas fa-thumbs-down me-1"></i>{{ $report->incorrect_votes ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><i class="fas fa-images me-1"></i>ছবিসমূহ</div>
            <div class="card-body">
                @if($report->images && count($report->images) > 0)
                    @foreach($report->images as $img)
                        <div class="mb-2">
                            <img src="{{ asset('uploads/fuel/' . $img) }}" alt="ছবি" class="img-fluid rounded" style="cursor: pointer;" onclick="window.open(this.src)">
                        </div>
                    @endforeach
                @elseif($report->image)
                    <img src="{{ asset('uploads/fuel/' . $report->image) }}" alt="ছবি" class="img-fluid rounded" style="cursor: pointer;" onclick="window.open(this.src)">
                @else
                    <p class="text-muted text-center mb-0">কোন ছবি নেই</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
