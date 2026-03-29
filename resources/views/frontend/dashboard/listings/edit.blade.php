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
                                    <label for="upazila_id" class="form-label">উপজেলা</label>
                                    <select class="form-select @error('upazila_id') is-invalid @enderror" id="upazila_id" name="upazila_id" 
                                            @if(auth()->user()->isModerator() && auth()->user()->upazila_id) disabled @endif>
                                        @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                                            <option value="" {{ empty($listing->upazila_id) ? 'selected' : '' }}>🌍 সকল উপজেলা (All Upazilas)</option>
                                        @else
                                            <option value="">নির্বাচন করুন</option>
                                        @endif
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
                                    @elseif(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i>সকল উপজেলা নির্বাচন করলে এই তথ্য সব উপজেলায় দেখাবে</small>
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
                                <label for="images" class="form-label">ছবি</label>
                                
                                <!-- Current Images -->
                                <div class="mb-3">
                                    <p class="text-muted small mb-2">বর্তমান ছবিসমূহ:</p>
                                    <div class="d-flex flex-wrap gap-2" id="currentImages">
                                        @if($listing->image)
                                            <div class="position-relative">
                                                <img src="{{ asset('storage/' . $listing->image) }}" alt="{{ $listing->title }}" 
                                                     class="rounded" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #28a745;">
                                                <span class="position-absolute top-0 start-0 badge bg-success" style="font-size: 0.6rem;">প্রধান</span>
                                            </div>
                                        @endif
                                        @if($listing->gallery && is_array($listing->gallery))
                                            @foreach($listing->gallery as $galleryImage)
                                                <div class="position-relative">
                                                    <img src="{{ asset('storage/' . $galleryImage) }}" alt="Gallery" 
                                                         class="rounded" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #dee2e6;">
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                
                                <input type="file" class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" 
                                       id="imageInput" name="images[]" accept="image/*" multiple>
                                <small class="text-muted"><i class="fas fa-info-circle me-1"></i>নতুন ছবি আপলোড করলে পুরনো সব ছবি প্রতিস্থাপিত হবে। একাধিক ছবি নির্বাচন করতে পারবেন (সর্বোচ্চ ৫টি, প্রতিটি সর্বোচ্চ 2MB)</small>
                                @error('images')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="imagePreview" class="mt-2 d-flex flex-wrap gap-2"></div>
                            </div>
                            
                            <!-- Google Map Section -->
                            <div class="mb-4">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="fas fa-map-marked-alt text-success me-2"></i>Google Map অবস্থান (ঐচ্ছিক)</h6>
                                        <p class="text-muted small mb-3">Google Maps থেকে embed code অথবা latitude/longitude দিন</p>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Google Maps Embed URL/Code</label>
                                            <textarea name="map_embed" class="form-control @error('map_embed') is-invalid @enderror" 
                                                      rows="3" placeholder="Google Maps থেকে Share > Embed a map > Copy HTML করে এখানে paste করুন">{{ old('map_embed', $listing->map_embed) }}</textarea>
                                            @error('map_embed')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">অথবা নিচে latitude/longitude দিন</small>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Latitude (অক্ষাংশ)</label>
                                                <input type="text" name="latitude" class="form-control @error('latitude') is-invalid @enderror" 
                                                       value="{{ old('latitude', $listing->latitude) }}" placeholder="যেমন: 22.7100">
                                                @error('latitude')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Longitude (দ্রাঘিমাংশ)</label>
                                                <input type="text" name="longitude" class="form-control @error('longitude') is-invalid @enderror" 
                                                       value="{{ old('longitude', $listing->longitude) }}" placeholder="যেমন: 89.0700">
                                                @error('longitude')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        @if($listing->latitude && $listing->longitude)
                                            <div class="mt-2">
                                                <p class="small text-success mb-2"><i class="fas fa-check-circle me-1"></i>বর্তমান ম্যাপ অবস্থান সেট আছে</p>
                                            </div>
                                        @endif
                                        
                                        <div class="mt-2">
                                            <small class="text-info"><i class="fas fa-info-circle me-1"></i>
                                                <a href="https://www.google.com/maps" target="_blank">Google Maps</a> এ আপনার লোকেশন খুঁজুন, 
                                                Share বাটনে ক্লিক করুন, তারপর "Embed a map" ট্যাব থেকে HTML কপি করুন।
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Doctor Specific Fields (shown only for Doctor category) -->
                            <div class="mb-4" id="doctorFields" style="display: none;">
                                <div class="card border-0" style="background: linear-gradient(135deg, #fce4ec 0%, #f8bbd9 100%);">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="fas fa-user-md text-danger me-2"></i>ডাক্তার সম্পর্কিত তথ্য</h6>
                                        <p class="text-muted small mb-3">এই তথ্যগুলো শুধুমাত্র ডাক্তার ক্যাটাগরির জন্য প্রযোজ্য</p>
                                        
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label">কোন হাসপাতালে চেম্বার/বসেন</label>
                                                <input type="text" name="hospital_name" class="form-control @error('hospital_name') is-invalid @enderror" 
                                                       value="{{ old('hospital_name', $listing->extra_fields['hospital_name'] ?? '') }}" placeholder="যেমন: সাতক্ষীরা সদর হাসপাতাল, ইসলামী ব্যাংক মেডিকেল সেন্টার">
                                                @error('hospital_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="text-muted">একাধিক হাসপাতাল থাকলে কমা দিয়ে আলাদা করুন</small>
                                            </div>
                                            
                                            <div class="col-12">
                                                <label class="form-label">বিশেষজ্ঞতা / কি কি বিষয়ে অভিজ্ঞ</label>
                                                <input type="text" name="specialization" class="form-control @error('specialization') is-invalid @enderror" 
                                                       value="{{ old('specialization', $listing->extra_fields['specialization'] ?? '') }}" placeholder="যেমন: মেডিসিন, শিশু রোগ, হৃদরোগ, চর্ম রোগ">
                                                @error('specialization')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-12">
                                                <label class="form-label">কি কি রোগী দেখেন</label>
                                                <textarea name="diseases_treated" class="form-control @error('diseases_treated') is-invalid @enderror" 
                                                          rows="2" placeholder="যেমন: জ্বর, সর্দি-কাশি, ডায়াবেটিস, উচ্চ রক্তচাপ, গ্যাস্ট্রিক সমস্যা">{{ old('diseases_treated', $listing->extra_fields['diseases_treated'] ?? '') }}</textarea>
                                                @error('diseases_treated')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label class="form-label">ডিগ্রি / যোগ্যতা</label>
                                                <input type="text" name="degrees" class="form-control @error('degrees') is-invalid @enderror" 
                                                       value="{{ old('degrees', $listing->extra_fields['degrees'] ?? '') }}" placeholder="যেমন: MBBS, BCS (Health), FCPS">
                                                @error('degrees')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label class="form-label">চেম্বার সময়</label>
                                                <input type="text" name="chamber_time" class="form-control @error('chamber_time') is-invalid @enderror" 
                                                       value="{{ old('chamber_time', $listing->extra_fields['chamber_time'] ?? '') }}" placeholder="যেমন: সন্ধ্যা ৫টা - রাত ৯টা">
                                                @error('chamber_time')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label class="form-label">ভিজিট ফি</label>
                                                <input type="text" name="visit_fee" class="form-control @error('visit_fee') is-invalid @enderror" 
                                                       value="{{ old('visit_fee', $listing->extra_fields['visit_fee'] ?? '') }}" placeholder="যেমন: ৫০০ টাকা">
                                                @error('visit_fee')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label class="form-label">সিরিয়ালের জন্য নম্বর</label>
                                                <input type="text" name="serial_number" class="form-control @error('serial_number') is-invalid @enderror" 
                                                       value="{{ old('serial_number', $listing->extra_fields['serial_number'] ?? '') }}" placeholder="যেমন: 01712-345678">
                                                @error('serial_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="text-muted">রোগীরা এই নম্বরে কল করে সিরিয়াল নিতে পারবে</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Newspaper Specific Fields (shown only for Newspaper category) -->
                            <div class="mb-4" id="newspaperFields" style="display: none;">
                                <div class="card border-0" style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="fas fa-newspaper text-primary me-2"></i>সংবাদপত্র সম্পর্কিত তথ্য</h6>
                                        <p class="text-muted small mb-3">এই তথ্যগুলো শুধুমাত্র সংবাদপত্র ক্যাটাগরির জন্য প্রযোজ্য</p>
                                        
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">সংবাদপত্রের ধরন <span class="text-danger">*</span></label>
                                                <select name="newspaper_type" class="form-select @error('newspaper_type') is-invalid @enderror">
                                                    <option value="">ধরন নির্বাচন করুন</option>
                                                    <option value="local" {{ old('newspaper_type', $listing->extra_fields['newspaper_type'] ?? '') == 'local' ? 'selected' : '' }}>🏘️ স্থানীয় (Local)</option>
                                                    <option value="national" {{ old('newspaper_type', $listing->extra_fields['newspaper_type'] ?? '') == 'national' ? 'selected' : '' }}>🇧🇩 জাতীয় (National)</option>
                                                    <option value="international" {{ old('newspaper_type', $listing->extra_fields['newspaper_type'] ?? '') == 'international' ? 'selected' : '' }}>🌍 আন্তর্জাতিক (International)</option>
                                                </select>
                                                @error('newspaper_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label class="form-label">ফরম্যাট <span class="text-danger">*</span></label>
                                                <select name="newspaper_format" class="form-select @error('newspaper_format') is-invalid @enderror">
                                                    <option value="">ফরম্যাট নির্বাচন করুন</option>
                                                    <option value="online_only" {{ old('newspaper_format', $listing->extra_fields['newspaper_format'] ?? '') == 'online_only' ? 'selected' : '' }}>💻 শুধু অনলাইন পোর্টাল</option>
                                                    <option value="both" {{ old('newspaper_format', $listing->extra_fields['newspaper_format'] ?? '') == 'both' ? 'selected' : '' }}>📰 অনলাইন + অফলাইন (উভয়)</option>
                                                    <option value="offline_only" {{ old('newspaper_format', $listing->extra_fields['newspaper_format'] ?? '') == 'offline_only' ? 'selected' : '' }}>🗞️ শুধু অফলাইন (প্রিন্ট)</option>
                                                </select>
                                                @error('newspaper_format')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

@push('scripts')
<script>
// Category specific fields toggle
const categorySelect = document.getElementById('category_id');
const doctorFields = document.getElementById('doctorFields');
const newspaperFields = document.getElementById('newspaperFields');

// Category data with slug mapping
const categoryData = {
    @foreach($categories as $category)
    '{{ $category->id }}': '{{ $category->slug }}',
    @endforeach
};

function toggleDoctorFields() {
    const selectedId = categorySelect.value;
    const selectedSlug = categoryData[selectedId] || '';
    
    if (selectedSlug === 'doctor') {
        doctorFields.style.display = 'block';
    } else {
        doctorFields.style.display = 'none';
    }
    
    if (selectedSlug === 'newspaper') {
        newspaperFields.style.display = 'block';
    } else {
        newspaperFields.style.display = 'none';
    }
}

categorySelect.addEventListener('change', toggleDoctorFields);
// Check on page load (in case of existing doctor listing)
document.addEventListener('DOMContentLoaded', toggleDoctorFields);

document.getElementById('imageInput').addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreview');
    const currentImages = document.getElementById('currentImages');
    preview.innerHTML = '';
    
    const files = e.target.files;
    if (files.length > 5) {
        alert('সর্বোচ্চ ৫টি ছবি নির্বাচন করতে পারবেন!');
        e.target.value = '';
        return;
    }
    
    if (files.length > 0) {
        currentImages.style.display = 'none';
    } else {
        currentImages.style.display = 'flex';
    }
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        if (file.size > 2 * 1024 * 1024) {
            alert('প্রতিটি ছবি সর্বোচ্চ 2MB হতে পারবে!');
            e.target.value = '';
            preview.innerHTML = '';
            currentImages.style.display = 'flex';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(event) {
            const div = document.createElement('div');
            div.className = 'position-relative';
            div.innerHTML = `
                <img src="${event.target.result}" class="rounded" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid ${i === 0 ? '#28a745' : '#dee2e6'};">
                ${i === 0 ? '<span class="position-absolute top-0 start-0 badge bg-success" style="font-size: 0.6rem;">প্রধান</span>' : ''}
            `;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
