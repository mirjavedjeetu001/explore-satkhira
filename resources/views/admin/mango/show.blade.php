@extends('admin.layouts.app')

@section('title', $store->store_name . ' - আম স্টোর')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h1>🥭 {{ $store->store_name }}</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('mango.show', $store->id) }}" target="_blank" class="btn btn-outline-success">
            <i class="fas fa-external-link-alt me-1"></i> পাবলিক ভিউ
        </a>
        <form action="{{ route('admin.mango.toggle-store', $store->id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn {{ $store->is_active ? 'btn-warning' : 'btn-success' }}">
                <i class="fas fa-toggle-{{ $store->is_active ? 'on' : 'off' }} me-1"></i>
                {{ $store->is_active ? 'নিষ্ক্রিয় করুন' : 'সক্রিয় করুন' }}
            </button>
        </form>
        <a href="{{ route('admin.mango.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> ফিরে যান
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    <!-- Store Info -->
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center py-4">
                @if($store->logo)
                    <img src="{{ asset('storage/' . $store->logo) }}" alt=""
                         class="rounded-circle mb-3"
                         style="width:90px;height:90px;object-fit:cover;border:3px solid #f59e0b;">
                @else
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                         style="width:90px;height:90px;background:#f59e0b;font-size:2.5rem;">🥭</div>
                @endif
                <h5 class="fw-bold">{{ $store->store_name }}</h5>
                <p class="text-muted mb-2">{{ $store->owner_name }}</p>
                <span class="badge {{ $store->is_active ? 'bg-success' : 'bg-secondary' }} mb-3">
                    {{ $store->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                </span>
            </div>
            <div class="card-body border-top pt-3">
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted">মোবাইল</td><td><a href="tel:{{ $store->phone }}">{{ $store->phone }}</a></td></tr>
                    @if($store->whatsapp)
                        <tr><td class="text-muted">WhatsApp</td><td>{{ $store->whatsapp }}</td></tr>
                    @endif
                    @if($store->upazila)
                        <tr><td class="text-muted">উপজেলা</td><td>{{ $store->upazila->name_bn ?? $store->upazila->name }}</td></tr>
                    @endif
                    @if($store->address)
                        <tr><td class="text-muted">ঠিকানা</td><td>{{ $store->address }}</td></tr>
                    @endif
                    <tr><td class="text-muted">মোট ভিউ</td><td>{{ number_format($store->view_count) }}</td></tr>
                    <tr><td class="text-muted">যোগ করেছেন</td><td>{{ $store->created_at->format('d M Y') }}</td></tr>
                </table>
            </div>
            @if($store->description)
                <div class="card-body border-top">
                    <p class="text-muted small mb-0">{{ $store->description }}</p>
                </div>
            @endif
            @if($store->delivery_info)
                <div class="card-body border-top">
                    <strong class="small">ডেলিভারি:</strong>
                    <p class="text-muted small mb-0 mt-1">{{ $store->delivery_info }}</p>
                </div>
            @endif
            @if($store->facebook_url)
                <div class="card-body border-top">
                    <a href="{{ $store->facebook_url }}" target="_blank" class="btn btn-sm btn-primary w-100">
                        <i class="fab fa-facebook me-1"></i>Facebook পেজ
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Products -->
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header">
                <i class="fas fa-leaf me-2 text-success"></i>পণ্যের তালিকা ({{ $store->products->count() }} টি)
            </div>
            <div class="card-body">
                @if($store->products->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <div style="font-size:2.5rem;">🥭</div>
                        <p>এখনো কোনো পণ্য যোগ করা হয়নি</p>
                    </div>
                @else
                    <div class="row g-3">
                        @foreach($store->products as $product)
                            <div class="col-sm-6">
                                <div class="card h-100 {{ !$product->is_available ? 'opacity-50' : '' }}">
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         alt="{{ $product->name }}"
                                         class="card-img-top"
                                         style="height:160px;object-fit:cover;">
                                    <div class="card-body p-2">
                                        <h6 class="fw-bold mb-1">{{ $product->name }}</h6>
                                        <div class="d-flex gap-1 flex-wrap mb-1">
                                            <span class="badge bg-warning text-dark">৳{{ number_format($product->price_per_kg, 0) }}/কেজি</span>
                                            @if($product->min_order_kg)
                                                <span class="badge bg-light text-dark border">সর্বনিম্ন {{ $product->min_order_kg }} কেজি</span>
                                            @endif
                                            <span class="badge {{ $product->is_available ? 'bg-success' : 'bg-danger' }}">
                                                {{ $product->is_available ? 'আছে' : 'নেই' }}
                                            </span>
                                        </div>
                                        @if($product->description)
                                            <p class="text-muted small mb-0">{{ $product->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
