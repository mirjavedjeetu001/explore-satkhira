@extends('frontend.layouts.app')
@section('title', 'ডোনর ড্যাশবোর্ড - ' . $donor->name)

@push('styles')
<style>
    .dashboard-hero { background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%); color: white; padding: 2rem 0; }
    .stat-card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); text-align: center; padding: 1.5rem; }
    .btn-blood { background: #dc3545; color: white; border: none; border-radius: 8px; }
    .btn-blood:hover { background: #a71d2a; color: white; }
    .btn-blood-outline { border: 2px solid #dc3545; color: #dc3545; border-radius: 8px; background: transparent; }
    .btn-blood-outline:hover { background: #dc3545; color: white; }
    .profile-card, .section-card { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
    .availability-badge { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 20px; font-weight: 600; }
    .available-yes { background: #d4edda; color: #155724; }
    .available-no { background: #f8d7da; color: #721c24; }
    .available-cooldown { background: #fff3cd; color: #856404; }
    .blood-badge { width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.1rem; }
</style>
@endpush

@section('content')
<section class="dashboard-hero">
    <div class="container">
        <div class="d-flex align-items-center gap-3">
            <div class="blood-badge">{{ $donor->blood_group }}</div>
            <div>
                <h2 class="fw-bold mb-0">{{ $donor->name }}</h2>
                <p class="mb-0 opacity-90">
                    @if($donor->type === 'organization')
                        <i class="fas fa-building me-1"></i>{{ $donor->organization_name }}
                    @else
                        <i class="fas fa-user me-1"></i>ব্যক্তিগত ডোনর
                    @endif
                </p>
            </div>
        </div>
    </div>
</section>

<section class="py-4">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        {{-- Quick Status Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="text-danger fs-4 mb-1"><i class="fas fa-tint"></i></div>
                    <div class="fw-bold fs-5">{{ $donor->blood_group }}</div>
                    <small class="text-muted">রক্তের গ্রুপ</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    @if($donor->is_currently_available)
                        <div class="text-success fs-4 mb-1"><i class="fas fa-check-circle"></i></div>
                        <div class="fw-bold fs-5 text-success">Available</div>
                    @elseif($donor->next_available_date)
                        <div class="text-warning fs-4 mb-1"><i class="fas fa-clock"></i></div>
                        <div class="fw-bold fs-6 text-warning">{{ $donor->next_available_date->format('d M, Y') }}</div>
                    @else
                        <div class="text-danger fs-4 mb-1"><i class="fas fa-times-circle"></i></div>
                        <div class="fw-bold fs-5 text-danger">Not Available</div>
                    @endif
                    <small class="text-muted">অবস্থা</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="text-info fs-4 mb-1"><i class="fas fa-calendar-alt"></i></div>
                    <div class="fw-bold fs-6">{{ $donor->last_donation_date ? $donor->last_donation_date->format('d M, Y') : 'নেই' }}</div>
                    <small class="text-muted">সর্বশেষ রক্তদান</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="text-secondary fs-4 mb-1"><i class="fas fa-exclamation-triangle"></i></div>
                    <div class="fw-bold fs-5">{{ $donor->not_reachable_count }}</div>
                    <small class="text-muted">অভিযোগ</small>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- Left: Actions --}}
            <div class="col-lg-4">
                {{-- Quick Actions --}}
                <div class="section-card card mb-4">
                    <div class="card-header bg-white fw-bold"><i class="fas fa-bolt me-2 text-warning"></i>দ্রুত অ্যাকশন</div>
                    <div class="card-body d-grid gap-2">
                        <form action="{{ route('blood.toggle-availability') }}" method="POST">
                            @csrf
                            <button class="btn {{ $donor->is_available ? 'btn-blood-outline' : 'btn-blood' }} w-100">
                                <i class="fas {{ $donor->is_available ? 'fa-eye-slash' : 'fa-eye' }} me-2"></i>
                                {{ $donor->is_available ? 'Not Available করুন' : 'Available করুন' }}
                            </button>
                        </form>
                        <a href="{{ route('blood.show', $donor->id) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-eye me-2"></i>আমার প্রোফাইল দেখুন
                        </a>
                        <a href="{{ route('blood.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-2"></i>সব ডোনর দেখুন
                        </a>
                        @if($donor->type === 'organization')
                            <a href="{{ route('blood.org-donors') }}" class="btn btn-outline-danger">
                                <i class="fas fa-users me-2"></i>আমার ডোনর তালিকা
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Record Donation --}}
                <div class="section-card card mb-4">
                    <div class="card-header bg-white fw-bold"><i class="fas fa-heartbeat me-2 text-danger"></i>রক্তদান রেকর্ড</div>
                    <div class="card-body">
                        <form action="{{ route('blood.record-donation') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">রক্তদানের তারিখ</label>
                                <input type="date" name="donation_date" class="form-control" max="{{ date('Y-m-d') }}" required>
                            </div>
                            <button type="submit" class="btn btn-blood w-100"><i class="fas fa-plus me-2"></i>রক্তদান রেকর্ড করুন</button>
                        </form>
                        @if($donor->last_donation_date)
                            <div class="mt-2 text-muted small">
                                <i class="fas fa-info-circle me-1"></i>সর্বশেষ দান: {{ $donor->last_donation_date->format('d M, Y') }}
                                @if($donor->next_available_date)
                                    <br>পরবর্তী Available: {{ $donor->next_available_date->format('d M, Y') }}
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Change Password --}}
                <div class="section-card card mb-4">
                    <div class="card-header bg-white fw-bold"><i class="fas fa-lock me-2 text-secondary"></i>পাসওয়ার্ড পরিবর্তন</div>
                    <div class="card-body">
                        <form action="{{ route('blood.change-password') }}" method="POST">
                            @csrf
                            <div class="mb-2">
                                <input type="password" name="current_password" class="form-control" placeholder="বর্তমান পাসওয়ার্ড" required>
                            </div>
                            <div class="mb-2">
                                <input type="password" name="password" class="form-control" placeholder="নতুন পাসওয়ার্ড" required>
                            </div>
                            <div class="mb-2">
                                <input type="password" name="password_confirmation" class="form-control" placeholder="পাসওয়ার্ড নিশ্চিত করুন" required>
                            </div>
                            <button type="submit" class="btn btn-outline-secondary w-100"><i class="fas fa-key me-2"></i>পরিবর্তন করুন</button>
                        </form>
                    </div>
                </div>

                {{-- Logout --}}
                <a href="{{ route('blood.logout') }}" class="btn btn-outline-danger w-100 mb-4"><i class="fas fa-sign-out-alt me-2"></i>লগআউট</a>
            </div>

            {{-- Right: Profile Edit --}}
            <div class="col-lg-8">
                <div class="section-card card">
                    <div class="card-header bg-white fw-bold"><i class="fas fa-user-edit me-2 text-primary"></i>প্রোফাইল আপডেট</div>
                    <div class="card-body">
                        <form action="{{ route('blood.update-profile') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">নাম <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $donor->name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">ফোন (পরিবর্তনযোগ্য নয়)</label>
                                    <input type="text" class="form-control bg-light" value="{{ $donor->phone }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">হোয়াটসঅ্যাপ নম্বর</label>
                                    <input type="text" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', $donor->whatsapp_number) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">ইমেইল</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $donor->email) }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">ঠিকানা</label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address', $donor->address) }}">
                                </div>

                                {{-- Upazila / Outside Satkhira --}}
                                <div class="col-md-6">
                                    <label class="form-label">উপজেলা</label>
                                    <select name="upazila_id" class="form-select" id="dashUpazilaSelect">
                                        <option value="">নির্বাচন করুন</option>
                                        @foreach(\App\Models\Upazila::active()->ordered()->get() as $upazila)
                                            <option value="{{ $upazila->id }}" {{ old('upazila_id', $donor->upazila_id) == $upazila->id ? 'selected' : '' }}>{{ $upazila->name_bn ?? $upazila->name }}</option>
                                        @endforeach
                                        <option value="outside" {{ !$donor->upazila_id && $donor->outside_area ? 'selected' : '' }}>🌍 সাতক্ষীরার বাহিরে</option>
                                    </select>
                                </div>
                                <div class="col-md-6" id="dashOutsideField" style="{{ !$donor->upazila_id && $donor->outside_area ? '' : 'display:none' }}">
                                    <label class="form-label">বর্তমান এলাকা/শহর</label>
                                    <input type="text" name="outside_area" class="form-control" value="{{ old('outside_area', $donor->outside_area) }}" placeholder="যেমন: ঢাকা, চট্টগ্রাম...">
                                </div>

                                {{-- Hide Phone --}}
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="hide_phone" id="hidePhone" value="1" {{ $donor->hide_phone ? 'checked' : '' }} onchange="document.getElementById('altContactField').classList.toggle('d-none')">
                                        <label class="form-check-label" for="hidePhone">ফোন নম্বর লুকান</label>
                                    </div>
                                </div>
                                <div class="col-12 {{ $donor->hide_phone ? '' : 'd-none' }}" id="altContactField">
                                    <label class="form-label">বিকল্প যোগাযোগ</label>
                                    <input type="text" name="alternative_contact" class="form-control" value="{{ old('alternative_contact', $donor->alternative_contact) }}" placeholder="যেমন: ফেসবুক লিংক, অন্য নম্বর">
                                </div>

                                {{-- Available Areas --}}
                                <div class="col-12">
                                    <label class="form-label fw-semibold">কোন কোন উপজেলায় রক্ত দিতে পারবেন?</label>
                                    <div class="row g-2">
                                        @php $selectedAreas = $donor->available_areas ?? []; @endphp
                                        @foreach(\App\Models\Upazila::active()->ordered()->get() as $upazila)
                                            <div class="col-md-4 col-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="available_areas[]" value="{{ $upazila->id }}" id="area{{ $upazila->id }}"
                                                        {{ in_array($upazila->id, $selectedAreas) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="area{{ $upazila->id }}">{{ $upazila->name_bn ?? $upazila->name }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Available For (diseases/patient types) --}}
                                <div class="col-12">
                                    <label class="form-label fw-semibold">কোন ধরনের রোগীদের জন্য রক্ত দিতে রাজি?</label>
                                    <div class="row g-2">
                                        @php 
                                            $availableFor = $donor->available_for ?? [];
                                            $diseases = ['থ্যালাসেমিয়া', 'ক্যান্সার', 'অপারেশন', 'দুর্ঘটনা', 'প্রসব', 'ডেঙ্গু', 'কিডনি রোগ', 'সকল রোগী'];
                                        @endphp
                                        @foreach($diseases as $disease)
                                            <div class="col-md-3 col-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="available_for[]" value="{{ $disease }}" id="disease{{ $loop->index }}"
                                                        {{ in_array($disease, $availableFor) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="disease{{ $loop->index }}">{{ $disease }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-blood px-4"><i class="fas fa-save me-2"></i>আপডেট করুন</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sel = document.getElementById('dashUpazilaSelect');
    const field = document.getElementById('dashOutsideField');
    if (sel && field) {
        sel.addEventListener('change', function() {
            field.style.display = this.value === 'outside' ? '' : 'none';
        });
    }
});
</script>
@endpush
@endsection
