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
                            
                            <!-- Duplicate Warning Alert -->
                            <div id="duplicateWarning" class="alert alert-warning d-none mb-4" role="alert">
                                <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>সতর্কতা: সম্ভাব্য ডুপ্লিকেট!</h6>
                                <p class="mb-2">এই তথ্য আগে থেকেই থাকতে পারে। অনুগ্রহ করে নিচের তালিকা দেখুন:</p>
                                <div id="duplicateList"></div>
                                <hr>
                                <p class="mb-0 small"><i class="fas fa-info-circle me-1"></i>যদি এটি ভিন্ন তথ্য হয়, তাহলে সাবমিট করতে পারবেন। অ্যাডমিন পর্যালোচনা করবেন।</p>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">শিরোনাম (Title) <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="titleInput" class="form-control @error('title') is-invalid @enderror" 
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
                                
                                <!-- Job Circular Deadline Field (shown only for Job Circular category - id 21) -->
                                <div class="col-md-6" id="jobDeadlineField" style="display: none;">
                                    <label class="form-label">আবেদনের শেষ তারিখ <span class="text-danger">*</span></label>
                                    <input type="date" name="application_deadline" id="application_deadline" 
                                           class="form-control @error('application_deadline') is-invalid @enderror" 
                                           value="{{ old('application_deadline') }}" min="{{ date('Y-m-d') }}">
                                    @error('application_deadline')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i>এই তারিখ পার হলে বিজ্ঞপ্তি স্বয়ংক্রিয়ভাবে লুকিয়ে যাবে</small>
                                </div>
                                
                                <!-- Event Dates Fields (shown only for Events category - id 22) -->
                                <div class="col-md-6" id="eventStartField" style="display: none;">
                                    <label class="form-label">ইভেন্ট শুরুর তারিখ <span class="text-danger">*</span></label>
                                    <input type="date" name="event_start_date" id="event_start_date" 
                                           class="form-control @error('event_start_date') is-invalid @enderror" 
                                           value="{{ old('event_start_date') }}">
                                    @error('event_start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6" id="eventEndField" style="display: none;">
                                    <label class="form-label">ইভেন্ট শেষের তারিখ <span class="text-danger">*</span></label>
                                    <input type="date" name="event_end_date" id="event_end_date" 
                                           class="form-control @error('event_end_date') is-invalid @enderror" 
                                           value="{{ old('event_end_date') }}">
                                    @error('event_end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i>ইভেন্ট শেষ হলে "সমাপ্ত" হিসেবে দেখাবে</small>
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
                                    <input type="text" name="phone" id="phoneInput" class="form-control @error('phone') is-invalid @enderror" 
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
                                                    <label class="form-label">কোন হাসপাতালে চেম্বার/বসেন</label>
                                                    <input type="text" name="hospital_name" class="form-control @error('hospital_name') is-invalid @enderror" 
                                                           value="{{ old('hospital_name') }}" placeholder="যেমন: সাতক্ষীরা সদর হাসপাতাল, ইসলামী ব্যাংক মেডিকেল সেন্টার">
                                                    @error('hospital_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">একাধিক হাসপাতাল থাকলে কমা দিয়ে আলাদা করুন</small>
                                                </div>
                                                
                                                <div class="col-12">
                                                    <label class="form-label">বিশেষজ্ঞতা / কি কি বিষয়ে অভিজ্ঞ</label>
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
                                                
                                                <div class="col-md-6">
                                                    <label class="form-label">সিরিয়ালের জন্য নম্বর</label>
                                                    <input type="text" name="serial_number" class="form-control @error('serial_number') is-invalid @enderror" 
                                                           value="{{ old('serial_number') }}" placeholder="যেমন: 01712-345678">
                                                    @error('serial_number')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">রোগীরা এই নম্বরে কল করে সিরিয়াল নিতে পারবে</small>
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
// Category specific fields toggle
const categorySelect = document.querySelector('select[name="category_id"]');
const doctorFields = document.getElementById('doctorFields');
const jobDeadlineField = document.getElementById('jobDeadlineField');
const eventStartField = document.getElementById('eventStartField');
const eventEndField = document.getElementById('eventEndField');

// Category data with slug mapping
const categoryData = {
    @foreach($categories ?? [] as $category)
    '{{ $category->id }}': '{{ $category->slug }}',
    @endforeach
};

function toggleCategoryFields() {
    const selectedId = categorySelect.value;
    const selectedSlug = categoryData[selectedId] || '';
    
    // Doctor fields
    if (selectedSlug === 'doctor') {
        doctorFields.style.display = 'block';
        doctorFields.querySelector('input[name="hospital_name"]').required = true;
        doctorFields.querySelector('input[name="specialization"]').required = true;
    } else {
        doctorFields.style.display = 'none';
        doctorFields.querySelector('input[name="hospital_name"]').required = false;
        doctorFields.querySelector('input[name="specialization"]').required = false;
    }
    
    // Job Circular deadline field (category_id = 21)
    if (selectedId === '21') {
        jobDeadlineField.style.display = 'block';
        document.getElementById('application_deadline').required = true;
    } else {
        jobDeadlineField.style.display = 'none';
        document.getElementById('application_deadline').required = false;
    }
    
    // Events date fields (category_id = 22)
    if (selectedId === '22') {
        eventStartField.style.display = 'block';
        eventEndField.style.display = 'block';
        document.getElementById('event_start_date').required = true;
        document.getElementById('event_end_date').required = true;
    } else {
        eventStartField.style.display = 'none';
        eventEndField.style.display = 'none';
        document.getElementById('event_start_date').required = false;
        document.getElementById('event_end_date').required = false;
    }
}

categorySelect.addEventListener('change', toggleCategoryFields);
// Check on page load (in case of old() values)
document.addEventListener('DOMContentLoaded', toggleCategoryFields);

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

// Duplicate Check System
let duplicateCheckTimeout;
const titleInput = document.getElementById('titleInput');
const phoneInput = document.getElementById('phoneInput');
const duplicateWarning = document.getElementById('duplicateWarning');
const duplicateList = document.getElementById('duplicateList');

function checkDuplicates() {
    const title = titleInput.value.trim();
    const phone = phoneInput.value.trim();
    const categoryId = categorySelect.value;
    
    // Only check if we have phone (10+ chars) or title (6+ chars)
    if (phone.length < 10 && title.length < 6) {
        duplicateWarning.classList.add('d-none');
        return;
    }
    
    fetch('{{ route("dashboard.listings.check-duplicate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            phone: phone,
            title: title,
            category_id: categoryId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.has_duplicates) {
            let html = '';
            
            // Phone matches (most important)
            if (data.exact_phone_matches && data.exact_phone_matches.length > 0) {
                html += '<div class="mb-2"><strong class="text-danger"><i class="fas fa-phone me-1"></i>একই ফোন নম্বর:</strong></div>';
                html += '<ul class="list-unstyled mb-2">';
                data.exact_phone_matches.forEach(item => {
                    const statusBadge = item.status === 'approved' 
                        ? '<span class="badge bg-success">অনুমোদিত</span>'
                        : (item.status === 'pending' ? '<span class="badge bg-warning">পেন্ডিং</span>' : '<span class="badge bg-secondary">' + item.status + '</span>');
                    html += `<li class="mb-1 ps-3 border-start border-danger border-3">
                        <strong>${item.title}</strong> ${statusBadge}<br>
                        <small class="text-muted">${item.category} | ${item.upazila} | ${item.phone}</small>
                        ${item.url ? `<a href="${item.url}" target="_blank" class="ms-2 btn btn-sm btn-outline-primary">দেখুন</a>` : ''}
                    </li>`;
                });
                html += '</ul>';
            }
            
            // Title matches
            if (data.similar_title_matches && data.similar_title_matches.length > 0) {
                html += '<div class="mb-2"><strong class="text-warning"><i class="fas fa-heading me-1"></i>একই রকম শিরোনাম:</strong></div>';
                html += '<ul class="list-unstyled mb-0">';
                data.similar_title_matches.forEach(item => {
                    const statusBadge = item.status === 'approved' 
                        ? '<span class="badge bg-success">অনুমোদিত</span>'
                        : (item.status === 'pending' ? '<span class="badge bg-warning">পেন্ডিং</span>' : '<span class="badge bg-secondary">' + item.status + '</span>');
                    html += `<li class="mb-1 ps-3 border-start border-warning border-3">
                        <strong>${item.title}</strong> ${statusBadge}<br>
                        <small class="text-muted">${item.category} | ${item.upazila}${item.phone ? ' | ' + item.phone : ''}</small>
                        ${item.url ? `<a href="${item.url}" target="_blank" class="ms-2 btn btn-sm btn-outline-primary">দেখুন</a>` : ''}
                    </li>`;
                });
                html += '</ul>';
            }
            
            duplicateList.innerHTML = html;
            duplicateWarning.classList.remove('d-none');
        } else {
            duplicateWarning.classList.add('d-none');
        }
    })
    .catch(error => {
        console.error('Duplicate check error:', error);
    });
}

// Debounced duplicate check
function debouncedDuplicateCheck() {
    clearTimeout(duplicateCheckTimeout);
    duplicateCheckTimeout = setTimeout(checkDuplicates, 800);
}

titleInput.addEventListener('input', debouncedDuplicateCheck);
phoneInput.addEventListener('input', debouncedDuplicateCheck);
categorySelect.addEventListener('change', debouncedDuplicateCheck);
</script>
@endpush
