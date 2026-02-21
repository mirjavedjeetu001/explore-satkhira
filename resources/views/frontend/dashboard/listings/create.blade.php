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
                                    <label class="form-label">উপজেলা</label>
                                    <select name="upazila_id" class="form-select @error('upazila_id') is-invalid @enderror"
                                            @if(auth()->user()->isModerator() && auth()->user()->upazila_id) disabled @endif>
                                        @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                                            <option value="">🌍 সকল উপজেলা (All Upazilas)</option>
                                        @else
                                            <option value="">উপজেলা নির্বাচন করুন</option>
                                        @endif
                                        @foreach($upazilas ?? [] as $upazila)
                                            <option value="{{ $upazila->id }}" {{ old('upazila_id', request('upazila') ?? (auth()->user()->isModerator() ? auth()->user()->upazila_id : '')) == $upazila->id ? 'selected' : '' }}>
                                                {{ $upazila->name_bn ?? $upazila->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if(auth()->user()->isModerator() && auth()->user()->upazila_id)
                                        <input type="hidden" name="upazila_id" value="{{ auth()->user()->upazila_id }}">
                                        <small class="text-info"><i class="fas fa-info-circle me-1"></i>মডারেটর হিসেবে আপনি শুধুমাত্র নির্ধারিত উপজেলায় তথ্য যোগ করতে পারবেন</small>
                                    @elseif(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i>সকল উপজেলা নির্বাচন করলে এই তথ্য সব উপজেলায় দেখাবে</small>
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
                                
                                <div class="col-12">
                                    <label class="form-label">ছবি <span class="text-danger">*</span></label>
                                    <input type="file" name="images[]" id="imageInput" class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" accept="image/*" multiple required>
                                    @error('images')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @error('images.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i>সর্বনিম্ন ১টি ছবি আবশ্যক। একাধিক ছবি নির্বাচন করতে পারবেন (সর্বোচ্চ ৫টি, প্রতিটি সর্বোচ্চ 2MB)</small>
                                    <div id="imagePreview" class="mt-2 d-flex flex-wrap gap-2"></div>
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
                                
                                <!-- Doctor Specific Fields (shown only for Doctor category) -->
                                <div class="col-12" id="doctorFields" style="display: none;">
                                    <div class="card border-0" style="background: linear-gradient(135deg, #fce4ec 0%, #f8bbd9 100%);">
                                        <div class="card-body">
                                            <h6 class="card-title"><i class="fas fa-user-md text-danger me-2"></i>ডাক্তার সম্পর্কিত তথ্য</h6>
                                            <p class="text-muted small mb-3">এই তথ্যগুলো শুধুমাত্র ডাক্তার ক্যাটাগরির জন্য প্রযোজ্য</p>
                                            
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label class="form-label">কোন হাসপাতালে চেম্বার/বসেন <span class="text-danger">*</span></label>
                                                    <input type="text" name="hospital_name" class="form-control @error('hospital_name') is-invalid @enderror" 
                                                           value="{{ old('hospital_name') }}" placeholder="যেমন: সাতক্ষীরা সদর হাসপাতাল, ইসলামী ব্যাংক মেডিকেল সেন্টার">
                                                    @error('hospital_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">একাধিক হাসপাতাল থাকলে কমা দিয়ে আলাদা করুন</small>
                                                </div>
                                                
                                                <div class="col-12">
                                                    <label class="form-label">বিশেষজ্ঞতা / কি কি বিষয়ে অভিজ্ঞ <span class="text-danger">*</span></label>
                                                    <input type="text" name="specialization" class="form-control @error('specialization') is-invalid @enderror" 
                                                           value="{{ old('specialization') }}" placeholder="যেমন: মেডিসিন, শিশু রোগ, হৃদরোগ, চর্ম রোগ">
                                                    @error('specialization')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                
                                                <div class="col-12">
                                                    <label class="form-label">কি কি রোগী দেখেন</label>
                                                    <textarea name="diseases_treated" class="form-control @error('diseases_treated') is-invalid @enderror" 
                                                              rows="2" placeholder="যেমন: জ্বর, সর্দি-কাশি, ডায়াবেটিস, উচ্চ রক্তচাপ, গ্যাস্ট্রিক সমস্যা">{{ old('diseases_treated') }}</textarea>
                                                    @error('diseases_treated')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <label class="form-label">ডিগ্রি / যোগ্যতা</label>
                                                    <input type="text" name="degrees" class="form-control @error('degrees') is-invalid @enderror" 
                                                           value="{{ old('degrees') }}" placeholder="যেমন: MBBS, BCS (Health), FCPS">
                                                    @error('degrees')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <label class="form-label">চেম্বার সময়</label>
                                                    <input type="text" name="chamber_time" class="form-control @error('chamber_time') is-invalid @enderror" 
                                                           value="{{ old('chamber_time') }}" placeholder="যেমন: সন্ধ্যা ৫টা - রাত ৯টা">
                                                    @error('chamber_time')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <label class="form-label">ভিজিট ফি</label>
                                                    <input type="text" name="visit_fee" class="form-control @error('visit_fee') is-invalid @enderror" 
                                                           value="{{ old('visit_fee') }}" placeholder="যেমন: ৫০০ টাকা">
                                                    @error('visit_fee')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
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

@push('scripts')
<script>
// Doctor category specific fields toggle
const categorySelect = document.querySelector('select[name="category_id"]');
const doctorFields = document.getElementById('doctorFields');

// Category data with slug mapping
const categoryData = {
    @foreach($categories ?? [] as $category)
    '{{ $category->id }}': '{{ $category->slug }}',
    @endforeach
};

function toggleDoctorFields() {
    const selectedId = categorySelect.value;
    const selectedSlug = categoryData[selectedId] || '';
    
    if (selectedSlug === 'doctor') {
        doctorFields.style.display = 'block';
        // Make hospital and specialization required
        doctorFields.querySelector('input[name="hospital_name"]').required = true;
        doctorFields.querySelector('input[name="specialization"]').required = true;
    } else {
        doctorFields.style.display = 'none';
        // Remove required
        doctorFields.querySelector('input[name="hospital_name"]').required = false;
        doctorFields.querySelector('input[name="specialization"]').required = false;
    }
}

categorySelect.addEventListener('change', toggleDoctorFields);
// Check on page load (in case of old() values)
document.addEventListener('DOMContentLoaded', toggleDoctorFields);

document.getElementById('imageInput').addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    
    const files = e.target.files;
    if (files.length > 5) {
        alert('সর্বোচ্চ ৫টি ছবি নির্বাচন করতে পারবেন!');
        e.target.value = '';
        return;
    }
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        if (file.size > 2 * 1024 * 1024) {
            alert('প্রতিটি ছবি সর্বোচ্চ 2MB হতে পারবে!');
            e.target.value = '';
            preview.innerHTML = '';
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
