@extends('frontend.layouts.app')

@section('title', 'নতুন তথ্য যোগ করুন - Satkhira Portal')

@section('content')
<!-- Page Header -->
<section class="page-header py-4" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
    <div class="container">
        <h3 class="text-white mb-0">নতুন তথ্য যোগ করুন</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">ড্যাশবোর্ড</a></li>
                <li class="breadcrumb-item active text-white">তথ্য যোগ করুন</li>
            </ol>
        </nav>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('dashboard.listings.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">শিরোনাম (Title) <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                           value="{{ old('title') }}" required placeholder="যেমন: সাতক্ষীরা সদর হাসপাতাল">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">ক্যাটাগরি <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                        <option value="">ক্যাটাগরি নির্বাচন করুন</option>
                                        @foreach($categories ?? [] as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', request('category')) == $category->id ? 'selected' : '' }}>
                                                {{ app()->getLocale() == 'bn' ? ($category->name_bn ?? $category->name) : $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">উপজেলা <span class="text-danger">*</span></label>
                                    <select name="upazila_id" class="form-select @error('upazila_id') is-invalid @enderror" required
                                            @if(auth()->user()->isModerator() && auth()->user()->upazila_id) disabled @endif>
                                        <option value="">উপজেলা নির্বাচন করুন</option>
                                        @foreach($upazilas ?? [] as $upazila)
                                            <option value="{{ $upazila->id }}" {{ old('upazila_id', request('upazila') ?? (auth()->user()->isModerator() ? auth()->user()->upazila_id : '')) == $upazila->id ? 'selected' : '' }}>
                                                {{ $upazila->name_bn ?? $upazila->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if(auth()->user()->isModerator() && auth()->user()->upazila_id)
                                        <input type="hidden" name="upazila_id" value="{{ auth()->user()->upazila_id }}">
                                        <small class="text-info"><i class="fas fa-info-circle me-1"></i>মডারেটর হিসেবে আপনি শুধুমাত্র নির্ধারিত উপজেলায় তথ্য যোগ করতে পারবেন</small>
                                    @endif
                                    @error('upazila_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">বিবরণ (Description) <span class="text-danger">*</span></label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                              rows="4" required placeholder="বিস্তারিত বিবরণ লিখুন...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">ঠিকানা</label>
                                    <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" 
                                           value="{{ old('address') }}" placeholder="সম্পূর্ণ ঠিকানা">
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">ফোন নম্বর</label>
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone') }}" placeholder="01XXXXXXXXX">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">ইমেইল</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email') }}" placeholder="email@example.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">ওয়েবসাইট</label>
                                    <input type="url" name="website" class="form-control @error('website') is-invalid @enderror" 
                                           value="{{ old('website') }}" placeholder="https://example.com">
                                    @error('website')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">ছবি</label>
                                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Max 2MB, JPG/PNG format</small>
                                </div>
                                
                                <!-- Google Map Section -->
                                <div class="col-12">
                                    <div class="card bg-light border-0">
                                        <div class="card-body">
                                            <h6 class="card-title"><i class="fas fa-map-marked-alt text-success me-2"></i>Google Map অবস্থান (ঐচ্ছিক)</h6>
                                            <p class="text-muted small mb-3">Google Maps থেকে embed code অথবা latitude/longitude দিন</p>
                                            
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label class="form-label">Google Maps Embed URL/Code</label>
                                                    <textarea name="map_embed" class="form-control @error('map_embed') is-invalid @enderror" 
                                                              rows="3" placeholder="Google Maps থেকে Share > Embed a map > Copy HTML করে এখানে paste করুন">{{ old('map_embed') }}</textarea>
                                                    @error('map_embed')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">অথবা নিচে latitude/longitude দিন</small>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <label class="form-label">Latitude (অক্ষাংশ)</label>
                                                    <input type="text" name="latitude" class="form-control @error('latitude') is-invalid @enderror" 
                                                           value="{{ old('latitude') }}" placeholder="যেমন: 22.7100">
                                                    @error('latitude')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <label class="form-label">Longitude (দ্রাঘিমাংশ)</label>
                                                    <input type="text" name="longitude" class="form-control @error('longitude') is-invalid @enderror" 
                                                           value="{{ old('longitude') }}" placeholder="যেমন: 89.0700">
                                                    @error('longitude')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            
                                            <div class="mt-2">
                                                <small class="text-info"><i class="fas fa-info-circle me-1"></i>
                                                    <a href="https://www.google.com/maps" target="_blank">Google Maps</a> এ আপনার লোকেশন খুঁজুন, 
                                                    Share বাটনে ক্লিক করুন, তারপর "Embed a map" ট্যাব থেকে HTML কপি করুন।
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        আপনার তথ্য জমা দেওয়ার পর এডমিন কর্তৃক অনুমোদিত হলে প্রকাশিত হবে।
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-paper-plane me-2"></i>তথ্য জমা দিন
                                    </button>
                                    <a href="{{ route('dashboard.listings') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>বাতিল
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
