@extends('frontend.layouts.app')
@section('title', 'আমার ডোনর তালিকা - ' . $org->organization_name)

@push('styles')
<style>
    .org-hero { background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%); color: white; padding: 2rem 0; }
    .btn-blood { background: #dc3545; color: white; border: none; border-radius: 8px; }
    .btn-blood:hover { background: #a71d2a; color: white; }
</style>
@endpush

@section('content')
<section class="org-hero">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h3 class="fw-bold mb-1"><i class="fas fa-building me-2"></i>{{ $org->organization_name }}</h3>
                <p class="mb-0 opacity-75">আপনার সংগঠনের রক্তদাতা তালিকা</p>
            </div>
            <div>
                <a href="{{ route('blood.org-add-donor') }}" class="btn btn-warning">
                    <i class="fas fa-user-plus me-1"></i>ডোনর যোগ করুন
                </a>
                <a href="{{ route('blood.dashboard') }}" class="btn btn-light ms-1">
                    <i class="fas fa-arrow-left me-1"></i>ড্যাশবোর্ড
                </a>
            </div>
        </div>
    </div>
</section>

<section class="py-4">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif

        <!-- Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm text-center py-3">
                    <div class="text-danger fs-3"><i class="fas fa-users"></i></div>
                    <h4 class="fw-bold mb-0">{{ $donors->total() }}</h4>
                    <small class="text-muted">মোট ডোনর</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm text-center py-3">
                    <div class="text-success fs-3"><i class="fas fa-check-circle"></i></div>
                    <h4 class="fw-bold mb-0">{{ $donors->where('is_available', true)->count() }}</h4>
                    <small class="text-muted">Available</small>
                </div>
            </div>
        </div>

        <!-- Donors Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-tint text-danger me-2"></i>ডোনর তালিকা ({{ $donors->total() }})</h5>
            </div>
            <div class="card-body p-0">
                @if($donors->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>নাম</th>
                                    <th>গ্রুপ</th>
                                    <th>ফোন</th>
                                    <th>রক্তদান</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($donors as $donor)
                                    <tr>
                                        <td>
                                            <strong>{{ $donor->name }}</strong>
                                            <br><small class="text-muted">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                {{ $donor->upazila ? ($donor->upazila->name_bn ?? $donor->upazila->name) : ($donor->outside_area ?? '-') }}
                                            </small>
                                        </td>
                                        <td><span class="badge bg-danger">{{ $donor->blood_group }}</span></td>
                                        <td>{{ $donor->phone }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $donor->donation_histories_count }} বার</span>
                                            @if($donor->last_donation_date)
                                                <br><small class="text-muted">{{ $donor->last_donation_date->format('d M, Y') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($donor->is_available)
                                                <span class="badge bg-success">Available</span>
                                            @else
                                                <span class="badge bg-secondary">Not Available</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <form action="{{ route('blood.org-toggle-donor', $donor->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm {{ $donor->is_available ? 'btn-outline-warning' : 'btn-outline-success' }}" title="{{ $donor->is_available ? 'Not Available' : 'Available' }} করুন">
                                                    <i class="fas {{ $donor->is_available ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                                </button>
                                            </form>
                                            <a href="{{ route('blood.org-edit-donor', $donor->id) }}" class="btn btn-sm btn-outline-primary" title="সম্পাদনা">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('blood.org-delete-donor', $donor->id) }}" method="POST" class="d-inline" onsubmit="return confirm('ডোনর মুছে ফেলতে চান?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" title="মুছুন"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">{{ $donors->links() }}</div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">এখনো কোনো ডোনর যোগ করা হয়নি</h5>
                        <a href="{{ route('blood.org-add-donor') }}" class="btn btn-blood mt-2">
                            <i class="fas fa-user-plus me-1"></i>প্রথম ডোনর যোগ করুন
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
