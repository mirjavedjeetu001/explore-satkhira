@extends('admin.layouts.app')

@section('title', 'নতুন পাম্প যুক্ত করুন')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-plus-circle me-2"></i>নতুন পাম্প যুক্ত করুন</h1>
    <a href="{{ route('admin.fuel.stations') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> ফিরে যান
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.fuel.stations.store') }}" method="POST">
            @csrf
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">পাম্পের নাম <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">উপজেলা <span class="text-danger">*</span></label>
                    <select name="upazila_id" class="form-select @error('upazila_id') is-invalid @enderror" required>
                        <option value="">-- উপজেলা নির্বাচন করুন --</option>
                        @foreach($upazilas as $upazila)
                            <option value="{{ $upazila->id }}" {{ old('upazila_id') == $upazila->id ? 'selected' : '' }}>
                                {{ $upazila->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('upazila_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">ঠিকানা</label>
                    <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" 
                           value="{{ old('address') }}">
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold"><i class="fas fa-map-marker-alt text-danger me-1"></i>Google Maps লিংক</label>
                    <input type="url" name="google_map_link" class="form-control @error('google_map_link') is-invalid @enderror" 
                           value="{{ old('google_map_link') }}" placeholder="https://maps.google.com/...">
                    @error('google_map_link')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">ফোন নম্বর</label>
                    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                           value="{{ old('phone') }}">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" 
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">সক্রিয়</label>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> সংরক্ষণ করুন
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
