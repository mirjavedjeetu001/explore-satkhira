@extends('admin.layouts.app')

@section('title', isset($listing) ? 'Edit Listing' : 'Add New Listing')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-list me-2"></i>{{ isset($listing) ? 'Edit Listing' : 'Add New Listing' }}</h1>
    <a href="{{ route('admin.listings.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Listings
    </a>
</div>

<div class="admin-form">
    <form action="{{ isset($listing) ? route('admin.listings.update', $listing) : route('admin.listings.store') }}" 
          method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($listing))
            @method('PUT')
        @endif
        
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Title (Bengali/English) <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                       value="{{ old('title', $listing->title ?? '') }}" required placeholder="যেমন: সাতক্ষীরা সদর হাসপাতাল">
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-4">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="pending" {{ old('status', $listing->status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ old('status', $listing->status ?? 'approved') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ old('status', $listing->status ?? '') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Category <span class="text-danger">*</span></label>
                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                    <option value="">Select Category</option>
                    @foreach($categories ?? [] as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $listing->category_id ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Upazila</label>
                <select name="upazila_id" class="form-select @error('upazila_id') is-invalid @enderror">
                    <option value="">🌍 সকল উপজেলা (All Upazilas)</option>
                    @foreach($upazilas ?? [] as $upazila)
                        <option value="{{ $upazila->id }}" {{ old('upazila_id', $listing->upazila_id ?? '') == $upazila->id ? 'selected' : '' }}>
                            {{ $upazila->name }}
                        </option>
                    @endforeach
                </select>
                @error('upazila_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">সকল উপজেলা নির্বাচন করলে এই তথ্য সব উপজেলায় দেখাবে</small>
            </div>
            
            <div class="col-12">
                <label class="form-label">Description <span class="text-danger">*</span></label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                          rows="4" required placeholder="বিস্তারিত বিবরণ লিখুন...">{{ old('description', $listing->description ?? '') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" 
                       value="{{ old('address', $listing->address ?? '') }}" placeholder="ঠিকানা">
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                       value="{{ old('phone', $listing->phone ?? '') }}" placeholder="01XXXXXXXXX">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                       value="{{ old('email', $listing->email ?? '') }}" placeholder="email@example.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Website</label>
                <input type="url" name="website" class="form-control @error('website') is-invalid @enderror" 
                       value="{{ old('website', $listing->website ?? '') }}" placeholder="https://example.com">
                @error('website')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Image</label>
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @if(isset($listing) && $listing->image)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $listing->image) }}" alt="Current Image" width="100" class="rounded">
                        <small class="text-muted d-block">Current image</small>
                    </div>
                @endif
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Options</label>
                <div class="form-check mt-2">
                    <input type="checkbox" name="is_featured" class="form-check-input" id="is_featured" value="1" 
                           {{ old('is_featured', $listing->is_featured ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_featured">
                        <i class="fas fa-star text-warning me-1"></i>Featured Listing
                    </label>
                </div>
            </div>
            
            <div class="col-md-4">
                <label class="form-label">Latitude (Optional)</label>
                <input type="text" name="latitude" class="form-control @error('latitude') is-invalid @enderror" 
                       value="{{ old('latitude', $listing->latitude ?? '') }}" placeholder="22.7064">
                @error('latitude')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-4">
                <label class="form-label">Longitude (Optional)</label>
                <input type="text" name="longitude" class="form-control @error('longitude') is-invalid @enderror" 
                       value="{{ old('longitude', $listing->longitude ?? '') }}" placeholder="89.0719">
                @error('longitude')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-12">
                <hr>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-2"></i>{{ isset($listing) ? 'Update Listing' : 'Create Listing' }}
                </button>
                <a href="{{ route('admin.listings.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
