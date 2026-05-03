@extends('frontend.layouts.app')

@section('title', 'টিকেট এডিট - বাস টিকেট বেচাকেনা')

@section('content')
<div class="py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-edit me-2"></i>টিকেট বিজ্ঞাপন এডিট</h4>
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

                        <form method="POST" action="{{ route('bus-ticket.update', $ticket->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">কোথা থেকে <span class="text-danger">*</span></label>
                                    <input type="text" name="from_location" class="form-control @error('from_location') is-invalid @enderror"
                                           value="{{ old('from_location', $ticket->from_location) }}" required>
                                    @error('from_location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">কোথায় <span class="text-danger">*</span></label>
                                    <input type="text" name="to_location" class="form-control @error('to_location') is-invalid @enderror"
                                           value="{{ old('to_location', $ticket->to_location) }}" required>
                                    @error('to_location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">ভ্রমণের তারিখ <span class="text-danger">*</span></label>
                                    <input type="date" name="journey_date" class="form-control @error('journey_date') is-invalid @enderror"
                                           value="{{ old('journey_date', $ticket->journey_date->format('Y-m-d')) }}" required>
                                    @error('journey_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">বাসের নাম</label>
                                    <input type="text" name="bus_name" class="form-control @error('bus_name') is-invalid @enderror"
                                           value="{{ old('bus_name', $ticket->bus_name) }}">
                                    @error('bus_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold">টিকেটের ধরন <span class="text-danger">*</span></label>
                                    <select name="ticket_type" class="form-select @error('ticket_type') is-invalid @enderror" required>
                                        <option value="seat" {{ old('ticket_type', $ticket->ticket_type) == 'seat' ? 'selected' : '' }}>নন-এসি (সীট)</option>
                                        <option value="ac" {{ old('ticket_type', $ticket->ticket_type) == 'ac' ? 'selected' : '' }}>এসি বাস</option>
                                        <option value="sleeper" {{ old('ticket_type', $ticket->ticket_type) == 'sleeper' ? 'selected' : '' }}>স্লিপার</option>
                                        <option value="deluxe" {{ old('ticket_type', $ticket->ticket_type) == 'deluxe' ? 'selected' : '' }}>ডিলাক্স</option>
                                    </select>
                                    @error('ticket_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold">সিট সংখ্যা <span class="text-danger">*</span></label>
                                    <input type="number" name="seat_count" class="form-control @error('seat_count') is-invalid @enderror"
                                           value="{{ old('seat_count', $ticket->seat_count) }}" min="1" max="10" required>
                                    @error('seat_count')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-semibold">দাম (প্রতি টিকেট) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">৳</span>
                                        <input type="number" name="price_per_ticket" class="form-control @error('price_per_ticket') is-invalid @enderror"
                                               value="{{ old('price_per_ticket', $ticket->price_per_ticket) }}" min="0" required>
                                    </div>
                                    @error('price_per_ticket')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">যোগাযোগের নম্বর <span class="text-danger">*</span></label>
                                <input type="tel" name="contact_number" class="form-control @error('contact_number') is-invalid @enderror"
                                       value="{{ old('contact_number', $ticket->contact_number) }}" required>
                                @error('contact_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">WhatsApp নম্বর</label>
                                <input type="tel" name="whatsapp_number" class="form-control @error('whatsapp_number') is-invalid @enderror"
                                       value="{{ old('whatsapp_number', $ticket->whatsapp_number) }}">
                                @error('whatsapp_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">বিস্তারিত বিবরণ</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                          rows="3">{{ old('description', $ticket->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-2"></i>আপডেট করুন
                                </button>
                                <a href="{{ route('bus-ticket.dashboard') }}" class="btn btn-outline-secondary px-4">
                                    <i class="fas fa-times me-2"></i>বাতিল
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
