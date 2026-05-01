@extends('admin.layouts.app')

@section('title', 'সাতক্ষীরার আম')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h1><i class="fas fa-store me-2"></i>🥭 সাতক্ষীরার আম</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('mango.index') }}" target="_blank" class="btn btn-outline-success">
            <i class="fas fa-external-link-alt me-1"></i> ফ্রন্টেন্ড দেখুন
        </a>
        <form action="{{ route('admin.mango.toggle-status') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn {{ $settings->is_enabled ? 'btn-danger' : 'btn-success' }}">
                <i class="fas fa-power-off me-1"></i>
                {{ $settings->is_enabled ? 'বন্ধ করুন' : 'চালু করুন' }}
            </button>
        </form>
    </div>
</div>

<!-- Status Alert -->
<div class="alert {{ $settings->is_enabled ? 'alert-success' : 'alert-warning' }} mb-4">
    <i class="fas {{ $settings->is_enabled ? 'fa-check-circle' : 'fa-exclamation-triangle' }} me-2"></i>
    সাতক্ষীরার আম ফিচার বর্তমানে <strong>{{ $settings->is_enabled ? 'চালু' : 'বন্ধ' }}</strong> আছে।
</div>

<!-- Stats -->
<div class="row mb-4">
    <div class="col-md-3 col-6 mb-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body text-center">
                <div class="display-6 mb-1">{{ number_format($stats['total_stores']) }}</div>
                <div class="small">মোট স্টোর</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body text-center">
                <div class="display-6 mb-1">{{ number_format($stats['active_stores']) }}</div>
                <div class="small">সক্রিয় স্টোর</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card bg-warning text-dark h-100">
            <div class="card-body text-center">
                <div class="display-6 mb-1">{{ number_format($stats['total_products']) }}</div>
                <div class="small">মোট পণ্য</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card bg-info text-white h-100">
            <div class="card-body text-center">
                <div class="display-6 mb-1">{{ number_format($stats['today_new']) }}</div>
                <div class="small">আজকের নতুন</div>
            </div>
        </div>
    </div>
</div>

<!-- Settings -->
<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-cog me-2"></i>সেটিংস
    </div>
    <div class="card-body">
        <form action="{{ route('admin.mango.settings') }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">শিরোনাম</label>
                    <input type="text" class="form-control" name="title" value="{{ $settings->title }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">বিবরণ</label>
                    <input type="text" class="form-control" name="description" value="{{ $settings->description }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">আপডেট</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Stores Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-list me-2"></i>সব স্টোর</span>
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="খুঁজুন..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-sm btn-outline-primary">খুঁজুন</button>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>স্টোর</th>
                        <th>মালিক</th>
                        <th>মোবাইল</th>
                        <th>উপজেলা</th>
                        <th>পণ্য</th>
                        <th>ভিউ</th>
                        <th>অবস্থা</th>
                        <th>একশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stores as $store)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($store->logo)
                                        <img src="{{ asset('storage/' . $store->logo) }}" alt=""
                                             style="width:36px;height:36px;object-fit:cover;border-radius:50%;">
                                    @else
                                        <div style="width:36px;height:36px;background:#f59e0b;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.2rem;">🥭</div>
                                    @endif
                                    <a href="{{ route('admin.mango.show', $store->id) }}" class="fw-semibold text-decoration-none">
                                        {{ $store->store_name }}
                                    </a>
                                </div>
                            </td>
                            <td>{{ $store->owner_name }}</td>
                            <td><a href="tel:{{ $store->phone }}">{{ $store->phone }}</a></td>
                            <td>{{ $store->upazila?->name_bn ?? $store->upazila?->name ?? '-' }}</td>
                            <td><span class="badge bg-success">{{ $store->products_count }}</span></td>
                            <td>{{ number_format($store->view_count) }}</td>
                            <td>
                                <span class="badge {{ $store->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $store->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.mango.show', $store->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.mango.toggle-store', $store->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $store->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                                title="{{ $store->is_active ? 'নিষ্ক্রিয় করুন' : 'সক্রিয় করুন' }}">
                                            <i class="fas fa-toggle-{{ $store->is_active ? 'on' : 'off' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.mango.destroy', $store->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('এই স্টোর এবং সকল পণ্য মুছে ফেলবেন?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">কোনো স্টোর নেই</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($stores->hasPages())
        <div class="card-footer">
            {{ $stores->withQueryString()->links() }}
        </div>
    @endif
</div>

@if(session('success'))
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index:9999;">
        <div class="toast show bg-success text-white">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            </div>
        </div>
    </div>
@endif
@endsection
