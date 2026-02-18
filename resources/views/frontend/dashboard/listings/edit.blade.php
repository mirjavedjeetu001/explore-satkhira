@extends('frontend.layouts.app')

@section('title', 'তথ্য সম্পাদনা - Satkhira Portal')

@section('content')
<!-- Page Header -->
<section class="page-header py-4" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="text-white mb-0">তথ্য সম্পাদনা</h3>
                <p class="text-white-50 mb-0">{{ Str::limit($listing->title, 50) }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Edit Form -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-edit text-success me-2"></i>তথ্য সম্পাদনা করুন</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('dashboard.listings.update', $listing) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label for="title" class="form-label">শিরোনাম <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $listing->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="category_id" class="form-label">ক্যাটাগরি <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                        <option value="">নির্বাচন করুন</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $listing->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name_bn ?? $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="upazila_id" class="form-label">উপজেলা <span class="text-danger">*</span></label>
                                    <select class="form-select @error('upazila_id') is-invalid @enderror" id="upazila_id" name="upazila_id" required 
                                            @if(auth()->user()->isModerator() && auth()->user()->upazila_id) disabled @endif>
                                        <option value="">নির্বাচন করুন</option>
                                        @foreach($upazilas as $upazila)
                                            @if(!auth()->user()->isModerator() || auth()->user()->upazila_id == $upazila->id)
                                                <option value="{{ $upazila->id }}" {{ old('upazila_id', $listing->upazila_id) == $upazila->id ? 'selected' : '' }}>
                                                    {{ $upazila->name_bn ?? $upazila->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @if(auth()->user()->isModerator() && auth()->user()->upazila_id)
                                        <input type="hidden" name="upazila_id" value="{{ auth()->user()->upazila_id }}">
                                        <small class="text-muted">আপনি শুধুমাত্র {{ auth()->user()->upazila->name_bn ?? auth()->user()->upazila->name }} এর তথ্য যোগ করতে পারবেন</small>
                                    @endif
                                    @error('upazila_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">বিবরণ <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="5" required>{{ old('description', $listing->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label">ঠিকানা</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="2">{{ old('address', $listing->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">ফোন নম্বর</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $listing->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">ইমেইল</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $listing->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="website" class="form-label">ওয়েবসাইট</label>
                                <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                       id="website" name="website" value="{{ old('website', $listing->website) }}" placeholder="https://">
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="image" class="form-label">ছবি</label>
                                @if($listing->image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $listing->image) }}" alt="{{ $listing->title }}" 
                                             class="rounded" style="max-height: 150px;">
                                        <p class="text-muted small mt-1">বর্তমান ছবি</p>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                       id="image" name="image" accept="image/*">
                                <small class="text-muted">সর্বোচ্চ 2MB, JPG/PNG ফরম্যাট। নতুন ছবি আপলোড করলে পুরনো ছবি প্রতিস্থাপিত হবে।</small>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i>আপডেট করুন
                                </button>
                                <a href="{{ route('dashboard.listings') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>ফিরে যান
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
