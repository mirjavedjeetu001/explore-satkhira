@extends('frontend.layouts.app')

@section('title', 'আমার স্টোর - ' . $store->store_name)

@section('content')
<div class="mango-dashboard-page py-4">
    <div class="container">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold mb-0">🥭 {{ $store->store_name }}</h3>
                <p class="text-muted mb-0 small">{{ $store->owner_name }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('mango.show', $store->id) }}" class="btn btn-outline-success btn-sm" target="_blank">
                    <i class="fas fa-eye me-1"></i>পাবলিক ভিউ
                </a>
                <form action="{{ route('mango.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-sign-out-alt me-1"></i>লগআউট
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">

            <!-- Left: Products -->
            <div class="col-lg-7">
                <!-- Existing Products -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-leaf me-2 text-success"></i>আমের তালিকা ({{ $store->products->count() }} টি)</span>
                    </div>
                    <div class="card-body">
                        @forelse($store->products as $product)
                            <div class="product-row d-flex gap-3 mb-3 p-3 border rounded {{ !$product->is_available ? 'opacity-60' : '' }}">
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     style="width:70px;height:70px;object-fit:cover;border-radius:8px;flex-shrink:0;">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $product->name }}</h6>
                                            <span class="badge bg-warning text-dark">৳{{ number_format($product->price_per_kg, 0) }}/কেজি</span>
                                            @if($product->min_order_kg)
                                                <span class="badge bg-light text-dark border">সর্বনিম্ন {{ $product->min_order_kg }} কেজি</span>
                                            @endif
                                            @if(!$product->is_available)
                                                <span class="badge bg-danger">স্টক নেই</span>
                                            @endif
                                        </div>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-outline-primary" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#editProduct{{ $product->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('mango.product.destroy', $product->id) }}" method="POST"
                                                  onsubmit="return confirm('এই আম মুছে ফেলবেন?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @if($product->description)
                                        <p class="text-muted small mb-0 mt-1">{{ $product->description }}</p>
                                    @endif
                                </div>
                            </div>
                            <!-- Edit Form (collapsed) -->
                            <div class="collapse mb-3" id="editProduct{{ $product->id }}">
                                <div class="card border-primary">
                                    <div class="card-body">
                                        <form action="{{ route('mango.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf @method('PUT')
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <label class="form-label small">আমের জাত</label>
                                                    <input type="text" class="form-control form-control-sm" name="name" value="{{ $product->name }}" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label small">দাম/কেজি (৳)</label>
                                                    <input type="number" class="form-control form-control-sm" name="price_per_kg" value="{{ $product->price_per_kg }}" min="1" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label small">সর্বনিম্ন (কেজি)</label>
                                                    <input type="number" class="form-control form-control-sm" name="min_order_kg" value="{{ $product->min_order_kg }}" step="0.5" min="0.5">
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="form-label small">বিবরণ</label>
                                                    <input type="text" class="form-control form-control-sm" name="description" value="{{ $product->description }}" placeholder="যেমন: মিষ্টি, সুগন্ধি...">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small">নতুন ছবি</label>
                                                    <input type="file" class="form-control form-control-sm" name="image" accept="image/*">
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="is_available" value="1"
                                                               id="avail{{ $product->id }}" {{ $product->is_available ? 'checked' : '' }}>
                                                        <label class="form-check-label small" for="avail{{ $product->id }}">স্টকে আছে</label>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-sm btn-primary">আপডেট করুন</button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                                            data-bs-toggle="collapse" data-bs-target="#editProduct{{ $product->id }}">বাতিল</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-3 text-muted">
                                <div style="font-size:2rem;">🥭</div>
                                <p class="mb-0">এখনো কোনো আম যোগ করা হয়নি</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Add Product Form -->
                <div class="card shadow-sm border-success">
                    <div class="card-header bg-success text-white">
                        <i class="fas fa-plus me-2"></i>নতুন আম যোগ করুন
                    </div>
                    <div class="card-body">
                        <form action="{{ route('mango.product.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">আমের জাত <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" placeholder="যেমন: হিমসাগর, ল্যাংড়া, আম্রপালি" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">দাম/কেজি (৳) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="price_per_kg" placeholder="120" min="1" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">সর্বনিম্ন অর্ডার (কেজি)</label>
                                    <input type="number" class="form-control" name="min_order_kg" placeholder="2" step="0.5" min="0.5">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">আমের ছবি <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" name="image" accept="image/*" required>
                                    <div class="form-text">JPG/PNG, সর্বোচ্চ ৩ MB। আমের স্পষ্ট ছবি দিন।</div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">বিবরণ</label>
                                    <textarea class="form-control" name="description" rows="2"
                                              placeholder="এই আমের বৈশিষ্ট্য, স্বাদ, গুণাগুণ সম্পর্কে লিখুন..."></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-plus me-2"></i>আম যোগ করুন
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right: Store Settings -->
            <div class="col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <i class="fas fa-cog me-2"></i>স্টোরের তথ্য সম্পাদনা
                    </div>
                    <div class="card-body">
                        <form action="{{ route('mango.store.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('PUT')

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">আপনার নাম <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="owner_name" value="{{ $store->owner_name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">স্টোরের নাম <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="store_name" value="{{ $store->store_name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">উপজেলা</label>
                                <select name="upazila_id" class="form-select form-select-sm">
                                    <option value="">নির্বাচন করুন</option>
                                    @foreach($upazilas as $upazila)
                                        <option value="{{ $upazila->id }}" {{ $store->upazila_id == $upazila->id ? 'selected' : '' }}>
                                            {{ $upazila->name_bn ?? $upazila->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">ঠিকানা</label>
                                <input type="text" class="form-control form-control-sm" name="address" value="{{ $store->address }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">WhatsApp</label>
                                <input type="tel" class="form-control form-control-sm" name="whatsapp" value="{{ $store->whatsapp }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Facebook পেজ</label>
                                <input type="url" class="form-control form-control-sm" name="facebook_url" value="{{ $store->facebook_url }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">স্টোর সম্পর্কে</label>
                                <textarea class="form-control form-control-sm" name="description" rows="3">{{ $store->description }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">ডেলিভারি তথ্য</label>
                                <textarea class="form-control form-control-sm" name="delivery_info" rows="3">{{ $store->delivery_info }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">লোগো পরিবর্তন</label>
                                @if($store->logo)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $store->logo) }}" alt="লোগো"
                                             style="width:50px;height:50px;object-fit:cover;border-radius:50%;">
                                        <span class="text-muted small ms-2">বর্তমান লোগো</span>
                                    </div>
                                @endif
                                <input type="file" class="form-control form-control-sm" name="logo" accept="image/*">
                            </div>

                            <hr>
                            <p class="small text-muted fw-semibold">পাসওয়ার্ড পরিবর্তন (ঐচ্ছিক)</p>
                            <div class="mb-3">
                                <label class="form-label small">নতুন পাসওয়ার্ড</label>
                                <input type="password" class="form-control form-control-sm" name="password" placeholder="খালি রাখলে পরিবর্তন হবে না">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small">নিশ্চিত করুন</label>
                                <input type="password" class="form-control form-control-sm" name="password_confirmation">
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-warning fw-bold">
                                    <i class="fas fa-save me-2"></i>সেভ করুন
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
.mango-dashboard-page { background: #fffbf0; min-height: 60vh; }
.opacity-60 { opacity: 0.6; }
.product-row { background: #fff; }
</style>
@endsection
