@extends('frontend.layouts.app')

@section('title', 'এক্সপ্লোর ব্লাড - রক্তদাতা খুঁজুন')
@section('meta_description', 'সাতক্ষীরা জেলার রক্তদাতাদের তালিকা। প্রয়োজনে রক্তদাতা খুঁজুন, রক্তদাতা হিসেবে নিবন্ধন করুন।')

@push('styles')
<style>
    .blood-hero {
        background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%);
        color: white;
        padding: 3rem 0;
    }
    .blood-hero h1 { font-size: 2rem; font-weight: 700; }
    .blood-stat-card {
        background: rgba(255,255,255,0.15);
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        backdrop-filter: blur(10px);
    }
    .blood-stat-card .stat-number { font-size: 1.8rem; font-weight: 700; }
    .blood-stat-card .stat-label { font-size: 0.85rem; opacity: 0.9; }
    .donor-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        transition: transform 0.2s, box-shadow 0.2s;
        overflow: hidden;
    }
    .donor-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }
    .blood-badge {
        width: 55px; height: 55px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 1.1rem;
        color: white;
        background: linear-gradient(135deg, #dc3545, #a71d2a);
        flex-shrink: 0;
    }
    .available-badge {
        display: inline-block; padding: 3px 10px; border-radius: 50px;
        font-size: 0.75rem; font-weight: 600;
    }
    .available-badge.yes { background: #d4edda; color: #155724; }
    .available-badge.no { background: #f8d7da; color: #721c24; }
    .available-badge.cooldown { background: #fff3cd; color: #856404; }
    .filter-card { border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
    .btn-blood { background: #dc3545; color: white; border: none; border-radius: 8px; }
    .btn-blood:hover { background: #a71d2a; color: white; }
    .btn-blood-outline { border: 2px solid #dc3545; color: #dc3545; border-radius: 8px; background: transparent; }
    .btn-blood-outline:hover { background: #dc3545; color: white; }
    .org-badge {
        display: inline-block; padding: 2px 8px; border-radius: 4px;
        font-size: 0.7rem; font-weight: 600;
        background: #e7f5ff; color: #1971c2;
    }
</style>
@endpush

@section('content')
<!-- Hero -->
<section class="blood-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1><i class="fas fa-tint me-2"></i>এক্সপ্লোর ব্লাড</h1>
                <p class="mb-3 opacity-90">সাতক্ষীরা জেলার রক্তদাতাদের তালিকা। জরুরি প্রয়োজনে রক্তদাতা খুঁজুন অথবা রক্তদাতা হিসেবে নিবন্ধন করে অন্যের জীবন বাঁচান।</p>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('blood.register') }}" class="btn btn-light btn-lg px-4">
                        <i class="fas fa-hand-holding-heart me-1"></i> রক্তদাতা হিসেবে নিবন্ধন
                    </a>
                    @if(Session::has('blood_donor_id'))
                        <a href="{{ route('blood.dashboard') }}" class="btn btn-outline-light btn-lg px-4">
                            <i class="fas fa-tachometer-alt me-1"></i> ড্যাশবোর্ড
                        </a>
                    @else
                        <a href="{{ route('blood.login') }}" class="btn btn-outline-light btn-lg px-4">
                            <i class="fas fa-sign-in-alt me-1"></i> লগইন
                        </a>
                    @endif
                </div>
            </div>
            <div class="col-lg-5 mt-4 mt-lg-0">
                <div class="row g-3">
                    <div class="col-4">
                        <div class="blood-stat-card">
                            <div class="stat-number">{{ $totalDonors }}</div>
                            <div class="stat-label">মোট ডোনর</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="blood-stat-card">
                            <div class="stat-number">{{ $availableDonors }}</div>
                            <div class="stat-label">Available</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="blood-stat-card">
                            <div class="stat-number">{{ $totalOrganizations }}</div>
                            <div class="stat-label">সংগঠন</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filters -->
<section class="py-4">
    <div class="container">
        <div class="card filter-card mb-4">
            <div class="card-body py-3">
                <form method="GET" action="{{ route('blood.index') }}">
                    <div class="row g-2 align-items-end">
                        <div class="col-6 col-md-2">
                            <label class="form-label small mb-1">রক্তের গ্রুপ</label>
                            <select name="blood_group" class="form-select form-select-sm">
                                <option value="">সকল গ্রুপ</option>
                                @foreach($bloodGroups as $bg)
                                    <option value="{{ $bg }}" {{ request('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-md-2">
                            <label class="form-label small mb-1">উপজেলা</label>
                            <select name="upazila" class="form-select form-select-sm">
                                <option value="">সকল উপজেলা</option>
                                @foreach($upazilas as $up)
                                    <option value="{{ $up->id }}" {{ request('upazila') == $up->id ? 'selected' : '' }}>{{ $up->name_bn }}</option>
                                @endforeach
                                <option value="outside" {{ request('upazila') == 'outside' ? 'selected' : '' }}>🌍 সাতক্ষীরার বাহিরে</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-2">
                            <label class="form-label small mb-1">ধরন</label>
                            <select name="type" class="form-select form-select-sm">
                                <option value="">সকল</option>
                                <option value="individual" {{ request('type') == 'individual' ? 'selected' : '' }}>ব্যক্তিগত</option>
                                <option value="organization" {{ request('type') == 'organization' ? 'selected' : '' }}>সংগঠন</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-2">
                            <label class="form-label small mb-1">সার্চ</label>
                            <input type="text" name="search" class="form-control form-control-sm" value="{{ request('search') }}" placeholder="নাম, ফোন...">
                        </div>
                        <div class="col-6 col-md-2">
                            <div class="form-check mt-md-4">
                                <input class="form-check-input" type="checkbox" name="available_only" value="1" id="availableOnly" {{ request('available_only') ? 'checked' : '' }}>
                                <label class="form-check-label small" for="availableOnly">শুধু Available</label>
                            </div>
                        </div>
                        <div class="col-6 col-md-2">
                            <div class="d-flex gap-1">
                                <button type="submit" class="btn btn-blood btn-sm flex-grow-1"><i class="fas fa-search"></i> খুঁজুন</button>
                                <a href="{{ route('blood.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-undo"></i></a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if($donors->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-tint-slash fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">কোনো রক্তদাতা পাওয়া যায়নি</h5>
                <p class="text-muted">ফিল্টার পরিবর্তন করুন অথবা <a href="{{ route('blood.register') }}" class="text-danger">রক্তদাতা হিসেবে নিবন্ধন করুন</a></p>
            </div>
        @else
            <div class="row g-3">
                @foreach($donors as $donor)
                    <div class="col-md-6 col-lg-4">
                        <div class="card donor-card h-100">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="blood-badge">{{ $donor->blood_group }}</div>
                                    <div class="flex-grow-1 min-w-0">
                                        <h6 class="mb-1 fw-bold text-truncate">{{ $donor->name }}</h6>
                                        @if($donor->type === 'organization' && $donor->organization_name)
                                            <span class="org-badge mb-1"><i class="fas fa-building me-1"></i>{{ $donor->organization_name }}</span>
                                        @endif
                                        <div class="small text-muted">
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $donor->upazila ? $donor->upazila->name_bn : ($donor->outside_area ? '🌍 ' . $donor->outside_area : '') }}
                                            @if($donor->address) • {{ Str::limit($donor->address, 30) }} @endif
                                        </div>
                                    </div>
                                    @if($donor->is_currently_available)
                                        <span class="available-badge yes"><i class="fas fa-check-circle me-1"></i>Available</span>
                                    @elseif($donor->next_available_date)
                                        <span class="available-badge cooldown" title="{{ $donor->next_available_date->format('d M Y') }}"><i class="fas fa-clock me-1"></i>{{ $donor->next_available_date->diffForHumans() }}</span>
                                    @else
                                        <span class="available-badge no"><i class="fas fa-times-circle me-1"></i>Not Available</span>
                                    @endif
                                </div>

                                <div class="mt-2 small">
                                    @if(!$donor->hide_phone)
                                        <a href="tel:{{ $donor->phone }}" class="text-dark me-3"><i class="fas fa-phone me-1 text-success"></i>{{ $donor->phone }}</a>
                                    @elseif($donor->alternative_contact)
                                        <span class="text-muted"><i class="fas fa-phone-slash me-1"></i>{{ $donor->alternative_contact }}</span>
                                    @else
                                        <span class="text-muted"><i class="fas fa-phone-slash me-1"></i>ফোন গোপন</span>
                                    @endif
                                    @if($donor->whatsapp_number || (!$donor->hide_phone && $donor->phone))
                                        @php
                                            $waRaw = $donor->whatsapp_number ?: $donor->phone;
                                            $waNum = preg_replace('/[^0-9]/', '', $waRaw);
                                            if (str_starts_with($waNum, '0')) $waNum = '880' . substr($waNum, 1);
                                        @endphp
                                        <a href="https://wa.me/{{ $waNum }}" target="_blank" class="text-success"><i class="fab fa-whatsapp me-1"></i>WhatsApp</a>
                                    @endif
                                </div>

                                @if($donor->last_donation_date)
                                    <div class="mt-1 small text-muted">
                                        <i class="fas fa-calendar-check me-1"></i>সর্বশেষ দান: {{ $donor->last_donation_date->format('d M Y') }}
                                    </div>
                                @endif
                            </div>
                            <div class="card-footer bg-transparent border-top-0 p-3 pt-0">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('blood.show', $donor->id) }}" class="btn btn-sm btn-blood-outline flex-grow-1"><i class="fas fa-eye me-1"></i>বিস্তারিত</a>
                                    @if(!$donor->hide_phone)
                                        <a href="tel:{{ $donor->phone }}" class="btn btn-sm btn-success"><i class="fas fa-phone"></i></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $donors->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
