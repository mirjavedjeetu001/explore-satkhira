@extends('frontend.layouts.app')
@section('title', $donor->name . ' - রক্তদাতা প্রোফাইল')

@push('styles')
<style>
    .profile-hero { background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%); color: white; padding: 2.5rem 0; }
    .blood-badge-lg { width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.6rem; color: #dc3545; background: white; border: 3px solid rgba(255,255,255,0.3); }
    .info-card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
    .comment-card { background: #f8f9fa; border-radius: 8px; padding: 12px; margin-bottom: 10px; }
    .btn-blood { background: #dc3545; color: white; border: none; border-radius: 8px; }
    .btn-blood:hover { background: #a71d2a; color: white; }
    .available-badge { display: inline-block; padding: 5px 14px; border-radius: 50px; font-size: 0.85rem; font-weight: 600; }
    .available-badge.yes { background: #d4edda; color: #155724; }
    .available-badge.no { background: #f8d7da; color: #721c24; }
    .available-badge.cooldown { background: #fff3cd; color: #856404; }
</style>
@endpush

@section('content')
<section class="profile-hero">
    <div class="container">
        <div class="d-flex align-items-center gap-4">
            <div class="blood-badge-lg">{{ $donor->blood_group }}</div>
            <div>
                <h2 class="mb-1 fw-bold">{{ $donor->name }}</h2>
                @if($donor->type === 'organization' && $donor->organization_name)
                    <span class="badge bg-light text-primary mb-1"><i class="fas fa-building me-1"></i>{{ $donor->organization_name }}</span>
                @endif
                <div class="opacity-90">
                    <i class="fas fa-map-marker-alt me-1"></i>{{ $donor->upazila ? $donor->upazila->name_bn : ($donor->outside_area ? '🌍 ' . $donor->outside_area : '') }}
                    @if($donor->address) • {{ $donor->address }} @endif
                </div>
                <div class="mt-2">
                    @if($donor->is_currently_available)
                        <span class="available-badge yes"><i class="fas fa-check-circle me-1"></i>Available</span>
                    @elseif($donor->next_available_date)
                        <span class="available-badge cooldown"><i class="fas fa-clock me-1"></i>{{ $donor->next_available_date->format('d M Y') }} তারিখে Available হবে</span>
                    @else
                        <span class="available-badge no"><i class="fas fa-times-circle me-1"></i>Not Available</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-4">
    <div class="container">
        <div class="row g-4">
            <!-- Main Info -->
            <div class="col-lg-8">
                <div class="card info-card mb-4">
                    <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-info-circle me-2 text-danger"></i>তথ্য</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <strong class="text-muted small">রক্তের গ্রুপ</strong>
                                <div class="fs-5 fw-bold text-danger">{{ $donor->blood_group }}</div>
                            </div>
                            <div class="col-sm-6">
                                <strong class="text-muted small">ধরন</strong>
                                <div>{{ $donor->type === 'individual' ? 'ব্যক্তিগত' : 'সংগঠন' }}</div>
                            </div>
                            <div class="col-sm-6">
                                <strong class="text-muted small">উপজেলা</strong>
                                <div>{{ $donor->upazila ? ($donor->upazila->name_bn ?? '-') : ($donor->outside_area ? '🌍 ' . $donor->outside_area : '-') }}</div>
                            </div>
                            @if($donor->address)
                            <div class="col-sm-6">
                                <strong class="text-muted small">ঠিকানা</strong>
                                <div>{{ $donor->address }}</div>
                            </div>
                            @endif
                            @if($donor->last_donation_date)
                            <div class="col-sm-6">
                                <strong class="text-muted small">সর্বশেষ রক্তদান</strong>
                                <div>{{ $donor->last_donation_date->format('d M Y') }} ({{ $donor->last_donation_date->diffForHumans() }})</div>
                            </div>
                            @endif
                            @if($donor->available_areas && count($donor->available_areas) > 0)
                            <div class="col-12">
                                <strong class="text-muted small">সেবা এলাকা</strong>
                                <div>
                                    @php $areaUpazilas = \App\Models\Upazila::whereIn('id', $donor->available_areas)->get(); @endphp
                                    @foreach($areaUpazilas as $au)
                                        <span class="badge bg-light text-dark me-1">{{ $au->name_bn }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            @if($donor->available_for && count($donor->available_for) > 0)
                            <div class="col-12">
                                <strong class="text-muted small">যেসব ক্ষেত্রে দিতে পারবে</strong>
                                <div>
                                    @foreach($donor->available_for as $af)
                                        <span class="badge bg-danger bg-opacity-10 text-danger me-1">{{ $af }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Comments -->
                <div class="card info-card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-comments me-2 text-danger"></i>মন্তব্য</h5>
                        <span class="badge bg-danger">{{ $donor->comments->count() }}</span>
                    </div>
                    <div class="card-body">
                        @foreach($donor->comments as $comment)
                            <div class="comment-card">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $comment->name }}</strong>
                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-0 mt-1">{{ $comment->comment }}</p>
                            </div>
                        @endforeach

                        <hr>
                        <h6>মন্তব্য করুন</h6>
                        <form action="{{ route('blood.comment', $donor->id) }}" method="POST">
                            @csrf
                            <div class="row g-2">
                                <div class="col-sm-6">
                                    <input type="text" name="name" class="form-control form-control-sm" placeholder="আপনার নাম *" required value="{{ old('name') }}">
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" name="phone" class="form-control form-control-sm" placeholder="ফোন নম্বর (ঐচ্ছিক)" value="{{ old('phone') }}">
                                </div>
                                <div class="col-12">
                                    <textarea name="comment" class="form-control form-control-sm" rows="2" placeholder="মন্তব্য লিখুন *" required>{{ old('comment') }}</textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-blood btn-sm"><i class="fas fa-paper-plane me-1"></i>মন্তব্য করুন</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar  -->
            <div class="col-lg-4">
                <!-- Contact -->
                <div class="card info-card mb-3">
                    <div class="card-header bg-white"><h6 class="mb-0"><i class="fas fa-phone-alt me-2 text-danger"></i>যোগাযোগ</h6></div>
                    <div class="card-body">
                        @if(!$donor->hide_phone)
                            <a href="tel:{{ $donor->phone }}" class="btn btn-success w-100 mb-2"><i class="fas fa-phone me-2"></i>কল করুন: {{ $donor->phone }}</a>
                        @else
                            <div class="alert alert-warning small mb-2"><i class="fas fa-phone-slash me-1"></i>ফোন নম্বর গোপন রাখা হয়েছে</div>
                            @if($donor->alternative_contact)
                                <div class="small mb-2"><strong>বিকল্প যোগাযোগ:</strong> {{ $donor->alternative_contact }}</div>
                            @endif
                        @endif

                        @php
                            $waRaw = $donor->whatsapp_number ?: ($donor->hide_phone ? null : $donor->phone);
                        @endphp
                        @if($waRaw)
                            @php
                                $waNum = preg_replace('/[^0-9]/', '', $waRaw);
                                if (str_starts_with($waNum, '0')) $waNum = '880' . substr($waNum, 1);
                            @endphp
                            <a href="https://wa.me/{{ $waNum }}" target="_blank" class="btn btn-outline-success w-100 mb-2">
                                <i class="fab fa-whatsapp me-2"></i>WhatsApp
                            </a>
                        @endif

                        @if($donor->email && !$donor->hide_phone)
                            <a href="mailto:{{ $donor->email }}" class="btn btn-outline-primary w-100 mb-2">
                                <i class="fas fa-envelope me-2"></i>ইমেইল
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Report Not Reachable -->
                <div class="card info-card mb-3">
                    <div class="card-body text-center">
                        <p class="small text-muted mb-2">যদি ডোনরের সাথে যোগাযোগ না হয়:</p>
                        <form action="{{ route('blood.report', $donor->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('আপনি কি নিশ্চিত যে এই ডোনরের সাথে যোগাযোগ হচ্ছে না?')">
                                <i class="fas fa-exclamation-triangle me-1"></i>পাওয়া যাচ্ছে না
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Back -->
                <a href="{{ route('blood.index') }}" class="btn btn-outline-secondary w-100"><i class="fas fa-arrow-left me-1"></i>তালিকায় ফিরুন</a>
            </div>
        </div>
    </div>
</section>
@endsection
