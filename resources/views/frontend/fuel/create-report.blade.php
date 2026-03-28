@extends('frontend.layouts.app')

@section('title', 'তেলের আপডেট দিন')
@section('meta_description', 'পেট্রোল পাম্পে তেলের প্রাপ্যতার আপডেট দিন')

@section('content')
<div class="fuel-report-page py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Header -->
                <div class="text-center mb-4">
                    <h1 class="fw-bold text-primary mb-2">
                        <i class="fas fa-plus-circle me-2"></i>তেলের আপডেট দিন
                    </h1>
                    <p class="text-muted">আপনার কাছের পেট্রোল পাম্পে তেলের অবস্থা জানান</p>
                </div>

                <div class="card shadow">
                    <div class="card-body p-4">
                        <form action="{{ route('fuel.store-report', isset($station) ? $station->id : null) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            @if(isset($station))
                                <!-- Existing Station -->
                                <input type="hidden" name="fuel_station_id" value="{{ $station->id }}">
                                <div class="alert alert-info mb-4">
                                    <h5 class="mb-1"><i class="fas fa-gas-pump me-2"></i>{{ $station->name }}</h5>
                                    <small><i class="fas fa-map-marker-alt me-1"></i>{{ $station->upazila->name }}</small>
                                </div>
                            @else
                                <!-- Select or Create Station -->
                                <div class="mb-4">
                                    <div class="form-check form-check-inline mb-3">
                                        <input class="form-check-input" type="radio" name="station_type" id="existingStation" value="existing" checked onchange="toggleStationForm()">
                                        <label class="form-check-label fw-semibold" for="existingStation">বিদ্যমান পাম্প নির্বাচন</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="station_type" id="newStation" value="new" onchange="toggleStationForm()">
                                        <label class="form-check-label fw-semibold" for="newStation">নতুন পাম্প যুক্ত করুন</label>
                                    </div>
                                </div>

                                <!-- Existing Station Selection -->
                                <div id="existingStationForm">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">পেট্রোল পাম্প নির্বাচন করুন <span class="text-danger">*</span></label>
                                        <select name="fuel_station_id" class="form-select form-select-lg @error('fuel_station_id') is-invalid @enderror" id="stationSelect" onchange="loadStationData()">
                                            <option value="">-- পাম্প নির্বাচন করুন --</option>
                                            @php
                                                $stationsByUpazila = \App\Models\FuelStation::with('upazila')->active()->orderBy('name')->get()->groupBy('upazila.name');
                                            @endphp
                                            @foreach($stationsByUpazila as $upazilaName => $stationList)
                                                <optgroup label="{{ $upazilaName }}">
                                                    @foreach($stationList as $sta)
                                                        <option value="{{ $sta->id }}">{{ $sta->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                        @error('fuel_station_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <!-- Station Info Box (shown when station selected) -->
                                    <div id="stationInfoBox" style="display: none;" class="alert alert-info mb-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1"><i class="fas fa-gas-pump me-2"></i><span id="stationName"></span></h6>
                                                <small><i class="fas fa-map-marker-alt me-1"></i><span id="stationAddress"></span></small>
                                            </div>
                                            <a href="#" id="stationMapLink" target="_blank" class="btn btn-sm btn-success" style="display: none;">
                                                <i class="fas fa-map-marker-alt me-1"></i>Map
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <!-- Latest Report Info -->
                                    <div id="latestReportBox" style="display: none;" class="alert alert-warning mb-3">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong><span id="lastReporterName"></span></strong> সর্বশেষ আপডেট দিয়েছেন <span id="lastReportTime"></span>।
                                        নিচে সেই তথ্য পূরণ করা হয়েছে।
                                    </div>
                                </div>

                                <!-- New Station Form -->
                                <input type="hidden" name="is_new_station" id="isNewStation" value="0">
                                <div id="newStationForm" style="display: none;">
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">পাম্পের নাম <span class="text-danger">*</span></label>
                                            <input type="text" name="new_station_name" class="form-control @error('new_station_name') is-invalid @enderror" 
                                                   placeholder="যেমন: পদ্মা ফিলিং স্টেশন">
                                            @error('new_station_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">উপজেলা <span class="text-danger">*</span></label>
                                            <select name="new_station_upazila" class="form-select @error('new_station_upazila') is-invalid @enderror">
                                                <option value="">-- উপজেলা নির্বাচন --</option>
                                                @foreach($upazilas as $upazila)
                                                    <option value="{{ $upazila->id }}">{{ $upazila->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('new_station_upazila')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">ঠিকানা (ঐচ্ছিক)</label>
                                            <input type="text" name="new_station_address" class="form-control" placeholder="পাম্পের ঠিকানা">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">
                                                <i class="fas fa-map-marker-alt text-danger me-1"></i>Google Maps লিংক (ঐচ্ছিক)
                                            </label>
                                            <input type="url" name="new_station_map_link" class="form-control" 
                                                   placeholder="https://maps.google.com/...">
                                            <small class="text-muted">Google Maps এ পাম্পটি খুঁজে Share > Copy Link করুন</small>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <hr class="my-4">

                            <!-- Reporter Info -->
                            <h5 class="mb-3 fw-bold text-primary"><i class="fas fa-user me-2"></i>আপনার তথ্য</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">আপনার নাম <span class="text-danger">*</span></label>
                                    <input type="text" name="reporter_name" class="form-control @error('reporter_name') is-invalid @enderror" 
                                           value="{{ old('reporter_name', request()->cookie('fuel_reporter_name')) }}" required>
                                    @error('reporter_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">মোবাইল নম্বর <span class="text-danger">*</span></label>
                                    <input type="tel" name="reporter_phone" class="form-control @error('reporter_phone') is-invalid @enderror" 
                                           value="{{ old('reporter_phone', request()->cookie('fuel_reporter_phone')) }}" placeholder="01XXXXXXXXX" required>
                                    @error('reporter_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">ইমেইল (ঐচ্ছিক)</label>
                                    <input type="email" name="reporter_email" class="form-control @error('reporter_email') is-invalid @enderror" 
                                           value="{{ old('reporter_email') }}">
                                    @error('reporter_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Edit PIN -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">৪ সংখ্যার PIN <span class="text-danger">*</span></label>
                                    <input type="text" name="edit_pin" class="form-control @error('edit_pin') is-invalid @enderror" 
                                           placeholder="যেমন: 1234" maxlength="4" pattern="[0-9]{4}" required
                                           style="font-size: 1.2em; letter-spacing: 5px; text-align: center;">
                                    <small class="text-muted">এই PIN দিয়ে পরে রিপোর্ট এডিট/ডিলিট করতে পারবেন</small>
                                    @error('edit_pin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Fuel Availability -->
                            <h5 class="mb-3 fw-bold text-primary"><i class="fas fa-tint me-2"></i>তেলের প্রাপ্যতা</h5>
                            <div class="row g-4 mb-4">
                                <!-- Petrol -->
                                <div class="col-md-4">
                                    <div class="card h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-gas-pump fa-2x text-warning mb-2"></i>
                                            <h6 class="fw-bold">পেট্রোল</h6>
                                            <div class="form-check form-switch d-flex justify-content-center mb-2">
                                                <input class="form-check-input" type="checkbox" name="petrol_available" value="1" id="petrolAvailable" style="width: 3em; height: 1.5em;">
                                            </div>
                                            <label class="form-label small text-muted">প্রতি লিটার দাম (ঐচ্ছিক)</label>
                                            <input type="number" step="0.01" name="petrol_price" class="form-control text-center mb-2" placeholder="৳ প্রতি লিটার">
                                            <label class="form-label small text-muted">কত টাকায় দিচ্ছে (ঐচ্ছিক)</label>
                                            <input type="number" step="0.01" name="petrol_selling_price" class="form-control text-center" placeholder="৳ বিক্রয় মূল্য">
                                        </div>
                                    </div>
                                </div>
                                <!-- Diesel -->
                                <div class="col-md-4">
                                    <div class="card h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-gas-pump fa-2x text-success mb-2"></i>
                                            <h6 class="fw-bold">ডিজেল</h6>
                                            <div class="form-check form-switch d-flex justify-content-center mb-2">
                                                <input class="form-check-input" type="checkbox" name="diesel_available" value="1" id="dieselAvailable" style="width: 3em; height: 1.5em;">
                                            </div>
                                            <label class="form-label small text-muted">প্রতি লিটার দাম (ঐচ্ছিক)</label>
                                            <input type="number" step="0.01" name="diesel_price" class="form-control text-center mb-2" placeholder="৳ প্রতি লিটার">
                                            <label class="form-label small text-muted">কত টাকায় দিচ্ছে (ঐচ্ছিক)</label>
                                            <input type="number" step="0.01" name="diesel_selling_price" class="form-control text-center" placeholder="৳ বিক্রয় মূল্য">
                                        </div>
                                    </div>
                                </div>
                                <!-- Octane -->
                                <div class="col-md-4">
                                    <div class="card h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-gas-pump fa-2x text-danger mb-2"></i>
                                            <h6 class="fw-bold">অকটেন</h6>
                                            <div class="form-check form-switch d-flex justify-content-center mb-2">
                                                <input class="form-check-input" type="checkbox" name="octane_available" value="1" id="octaneAvailable" style="width: 3em; height: 1.5em;">
                                            </div>
                                            <label class="form-label small text-muted">প্রতি লিটার দাম (ঐচ্ছিক)</label>
                                            <input type="number" step="0.01" name="octane_price" class="form-control text-center mb-2" placeholder="৳ প্রতি লিটার">
                                            <label class="form-label small text-muted">কত টাকায় দিচ্ছে (ঐচ্ছিক)</label>
                                            <input type="number" step="0.01" name="octane_selling_price" class="form-control text-center" placeholder="৳ বিক্রয় মূল্য">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Fixed Amount Limit -->
                            <div class="mb-4">
                                <h5 class="mb-3 fw-bold text-primary"><i class="fas fa-hand-holding-usd me-2"></i>মাথাপিছু কত টাকার তেল দিচ্ছে?</h5>
                                <p class="text-muted small mb-2">তেলের সংকটে পাম্প যদি নির্দিষ্ট পরিমাণে তেল দেয় (যেমন ১০০, ২০০, ৫০০ টাকা) তাহলে এখানে লিখুন</p>
                                <div class="row justify-content-center">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text"><i class="fas fa-money-bill-wave text-success"></i></span>
                                            <input type="number" name="fixed_amount" class="form-control text-center" placeholder="যেমন: 500" step="1" min="0">
                                            <span class="input-group-text">টাকা</span>
                                        </div>
                                        <small class="text-muted">খালি রাখলে "সীমাহীন" ধরে নেওয়া হবে</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Queue Status -->
                            <h5 class="mb-3 fw-bold text-primary"><i class="fas fa-users me-2"></i>লাইনের অবস্থা</h5>
                            <div class="row g-2 mb-4">
                                <div class="col-6 col-md-3">
                                    <input type="radio" class="btn-check" name="queue_status" id="queueNone" value="none" checked>
                                    <label class="btn btn-outline-success w-100 py-3" for="queueNone">
                                        <i class="fas fa-smile fa-lg d-block mb-1"></i>
                                        <span>কোন লাইন নেই</span>
                                    </label>
                                </div>
                                <div class="col-6 col-md-3">
                                    <input type="radio" class="btn-check" name="queue_status" id="queueShort" value="short">
                                    <label class="btn btn-outline-info w-100 py-3" for="queueShort">
                                        <i class="fas fa-meh fa-lg d-block mb-1"></i>
                                        <span>ছোট লাইন</span>
                                    </label>
                                </div>
                                <div class="col-6 col-md-3">
                                    <input type="radio" class="btn-check" name="queue_status" id="queueMedium" value="medium">
                                    <label class="btn btn-outline-warning w-100 py-3" for="queueMedium">
                                        <i class="fas fa-frown fa-lg d-block mb-1"></i>
                                        <span>মাঝারি লাইন</span>
                                    </label>
                                </div>
                                <div class="col-6 col-md-3">
                                    <input type="radio" class="btn-check" name="queue_status" id="queueLong" value="long">
                                    <label class="btn btn-outline-danger w-100 py-3" for="queueLong">
                                        <i class="fas fa-sad-tear fa-lg d-block mb-1"></i>
                                        <span>বড় লাইন</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Image Upload (Required) -->
                            <div class="mb-4">
                                <h5 class="mb-3 fw-bold text-primary"><i class="fas fa-camera me-2"></i>ছবি আপলোড করুন (ঐচ্ছিক)</h5>
                                <p class="text-muted small mb-2">পাম্পের তেল লাইন বা বোর্ডের ছবি দিন (একাধিক ছবি দেওয়া যাবে)</p>
                                <input type="file" name="images[]" class="form-control form-control-lg @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" accept="image/*" multiple id="imageInput" onchange="previewImages(this)">
                                @error('images')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="imagePreview" class="mt-3 d-flex gap-2 flex-wrap"></div>
                            </div>

                            <!-- Notes -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">অতিরিক্ত মন্তব্য (ঐচ্ছিক)</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="যেমন: সকাল ১০টার পর তেল পাওয়া যেতে পারে..."></textarea>
                            </div>

                            <!-- Submit -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>আপডেট জমা দিন
                                </button>
                                <a href="{{ route('fuel.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>ফিরে যান
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleStationForm() {
    const isNew = document.getElementById('newStation').checked;
    document.getElementById('existingStationForm').style.display = isNew ? 'none' : 'block';
    document.getElementById('newStationForm').style.display = isNew ? 'block' : 'none';
    document.getElementById('isNewStation').value = isNew ? '1' : '0';
    
    // Hide station info boxes when switching
    document.getElementById('stationInfoBox').style.display = 'none';
    document.getElementById('latestReportBox').style.display = 'none';
}

function loadStationData() {
    const stationId = document.getElementById('stationSelect').value;
    const infoBox = document.getElementById('stationInfoBox');
    const reportBox = document.getElementById('latestReportBox');
    
    if (!stationId) {
        infoBox.style.display = 'none';
        reportBox.style.display = 'none';
        return;
    }
    
    fetch('/fuel/api/station/' + stationId)
        .then(response => response.json())
        .then(data => {
            // Show station info
            document.getElementById('stationName').textContent = data.station.name;
            document.getElementById('stationAddress').textContent = data.station.upazila + (data.station.address ? ' - ' + data.station.address : '');
            
            // Show Google Maps link if available
            const mapLink = document.getElementById('stationMapLink');
            if (data.station.google_map_link) {
                mapLink.href = data.station.google_map_link;
                mapLink.style.display = 'inline-block';
            } else {
                mapLink.style.display = 'none';
            }
            
            infoBox.style.display = 'block';
            
            // Fill form with latest report data
            if (data.latest_report) {
                document.getElementById('lastReporterName').textContent = data.latest_report.reporter_name;
                document.getElementById('lastReportTime').textContent = data.latest_report.time;
                reportBox.style.display = 'block';
                
                // Fill fuel availability
                document.getElementById('petrolAvailable').checked = data.latest_report.petrol_available;
                document.getElementById('dieselAvailable').checked = data.latest_report.diesel_available;
                document.getElementById('octaneAvailable').checked = data.latest_report.octane_available;
                
                // Fill prices
                document.querySelector('input[name="petrol_price"]').value = data.latest_report.petrol_price || '';
                document.querySelector('input[name="diesel_price"]').value = data.latest_report.diesel_price || '';
                document.querySelector('input[name="octane_price"]').value = data.latest_report.octane_price || '';
                
                // Fill queue status
                const queueRadio = document.querySelector('input[name="queue_status"][value="' + data.latest_report.queue_status + '"]');
                if (queueRadio) queueRadio.checked = true;
                
                // Fill fixed amount
                document.querySelector('input[name="fixed_amount"]').value = data.latest_report.fixed_amount || '';
            } else {
                reportBox.style.display = 'none';
                // Reset form
                document.getElementById('petrolAvailable').checked = false;
                document.getElementById('dieselAvailable').checked = false;
                document.getElementById('octaneAvailable').checked = false;
                document.querySelector('input[name="petrol_price"]').value = '';
                document.querySelector('input[name="diesel_price"]').value = '';
                document.querySelector('input[name="octane_price"]').value = '';
                document.querySelector('input[name="fixed_amount"]').value = '';
                document.querySelector('input[name="queue_status"][value="none"]').checked = true;
            }
        })
        .catch(error => {
            console.error('Error loading station data:', error);
        });
}

function previewImages(input) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    
    if (input.files) {
        Array.from(input.files).forEach(function(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Preview';
                img.className = 'rounded';
                img.style.maxHeight = '150px';
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    }
}
</script>
@endpush
@endsection
