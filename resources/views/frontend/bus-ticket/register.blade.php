@extends('frontend.layouts.app')

@section('title', 'রেজিস্ট্রেশন - বাস টিকেট রিসেল')

@section('content')
<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-success text-white text-center py-3">
                        <div style="font-size: 3rem;">🚌</div>
                        <h4 class="mb-0 mt-2">বাস টিকেট রিসেল</h4>
                        <p class="mb-0 small">নতুন সেলার রেজিস্ট্রেশন</p>
                    </div>
                    <div class="card-body p-4">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('bus-ticket.register.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-semibold">আপনার নাম <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}" placeholder="পুরো নাম" required>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">ফোন নম্বর <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                           value="{{ old('phone') }}" placeholder="01XXXXXXXXX" required>
                                </div>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text text-muted">এই নম্বর দিয়ে লগইন করতে হবে</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">WhatsApp নম্বর</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
                                    <input type="tel" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror"
                                           value="{{ old('whatsapp') }}" placeholder="01XXXXXXXXX (ঐচ্ছিক)">
                                </div>
                                @error('whatsapp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">উপজেলা</label>
                                <select name="upazila_id" class="form-select @error('upazila_id') is-invalid @enderror">
                                    <option value="">উপজেলা নির্বাচন করুন</option>
                                    @foreach($upazilas as $upazila)
                                        <option value="{{ $upazila->id }}" {{ old('upazila_id') == $upazila->id ? 'selected' : '' }}>
                                            {{ $upazila->name_bn ?? $upazila->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('upazila_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">ঠিকানা</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                                          rows="2" placeholder="আপনার ঠিকানা (ঐচ্ছিক)">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4">
                            <h5 class="fw-bold mb-3 text-success"><i class="fas fa-ticket-alt me-2"></i>প্রথম টিকেটের তথ্য</h5>
                            <p class="text-muted small mb-3">রেজিস্ট্রেশনের সাথে সাথে আপনার প্রথম টিকেটের বিজ্ঞাপন পোস্ট হবে। পরবর্তীতে ড্যাশবোর্ড থেকে আরও টিকেট যোগ করতে পারবেন।</p>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">কোথা থেকে <span class="text-danger">*</span></label>
                                    <input type="text" name="from_location" class="form-control @error('from_location') is-invalid @enderror"
                                           value="{{ old('from_location') }}" placeholder="যেমন: সাতক্ষীরা" required>
                                    @error('from_location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">কোথায় <span class="text-danger">*</span></label>
                                    <input type="text" name="to_location" class="form-control @error('to_location') is-invalid @enderror"
                                           value="{{ old('to_location') }}" placeholder="যেমন: ঢাকা" required>
                                    @error('to_location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">ভ্রমণের তারিখ <span class="text-danger">*</span></label>
                                    <input type="date" name="journey_date" class="form-control @error('journey_date') is-invalid @enderror"
                                           value="{{ old('journey_date') }}" min="{{ date('Y-m-d') }}" required>
                                    @error('journey_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">বাসের নাম</label>
                                    <input type="text" name="bus_name" class="form-control @error('bus_name') is-invalid @enderror"
                                           value="{{ old('bus_name') }}" placeholder="যেমন: হানিফ, এনা পরিবহন">
                                    @error('bus_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold">টিকেটের ধরন <span class="text-danger">*</span></label>
                                    <select name="ticket_type" class="form-select @error('ticket_type') is-invalid @enderror" required>
                                        <option value="seat" {{ old('ticket_type') == 'seat' ? 'selected' : '' }}>নন-এসি (সীট)</option>
                                        <option value="ac" {{ old('ticket_type') == 'ac' ? 'selected' : '' }}>এসি বাস</option>
                                        <option value="sleeper" {{ old('ticket_type') == 'sleeper' ? 'selected' : '' }}>স্লিপার</option>
                                        <option value="deluxe" {{ old('ticket_type') == 'deluxe' ? 'selected' : '' }}>ডিলাক্স</option>
                                    </select>
                                    @error('ticket_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold">সিট সংখ্যা <span class="text-danger">*</span></label>
                                    <input type="number" name="seat_count" class="form-control @error('seat_count') is-invalid @enderror"
                                           value="{{ old('seat_count', 1) }}" min="1" max="10" required>
                                    @error('seat_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold">দাম (প্রতি টিকেট) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">৳</span>
                                        <input type="number" name="price_per_ticket" class="form-control @error('price_per_ticket') is-invalid @enderror"
                                               value="{{ old('price_per_ticket') }}" min="0" required>
                                    </div>
                                    @error('price_per_ticket')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">যোগাযোগের নম্বর <span class="text-danger">*</span></label>
                                    <input type="tel" name="contact_number" class="form-control @error('contact_number') is-invalid @enderror"
                                           value="{{ old('contact_number') }}" placeholder="01XXXXXXXXX" required>
                                    <div class="form-text text-muted">ক্রেতারা এই নম্বরে যোগাযোগ করবে</div>
                                    @error('contact_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">WhatsApp নম্বর</label>
                                    <input type="tel" name="whatsapp_number" class="form-control @error('whatsapp_number') is-invalid @enderror"
                                           value="{{ old('whatsapp_number') }}" placeholder="01XXXXXXXXX (ঐচ্ছিক)">
                                    @error('whatsapp_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">বিস্তারিত বিবরণ</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                          rows="2" placeholder="অতিরিক্ত তথ্য যেমন: ছাড়ার সময়, কাউন্টারের লোকেশন ইত্যাদি">{{ old('description') }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <hr class="my-4">
                            <h5 class="fw-bold mb-3 text-success"><i class="fas fa-lock me-2"></i>পাসওয়ার্ড সেট করুন</h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">পাসওয়ার্ড <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                               placeholder="কমপক্ষে ৪ অক্ষর" required>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">পাসওয়ার্ড নিশ্চিত করুন <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" name="password_confirmation" class="form-control"
                                               placeholder="আবার লিখুন" required>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                                <i class="fas fa-user-plus me-2"></i>রেজিস্ট্রেশন এবং প্রথম টিকেট পোস্ট করুন
                            </button>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="mb-2">ইতিমধ্যে রেজিস্ট্রেশন আছে?</p>
                            <a href="{{ route('bus-ticket.login') }}" class="btn btn-outline-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>লগইন করুন
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
