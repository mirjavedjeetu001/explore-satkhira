@extends('frontend.layouts.app')

@section('title', $settings->title ?? 'বাস টিকেট রিসেল')
@section('meta_description', 'বাস টিকেট কিনেছেন কিন্তু যেতে পারছেন না? রিসেল করুন। অথবা কম দামে টিকেট খুঁজুন। সাতক্ষীরা থেকে ঢাকা ও অন্যান্য রুটের রিসেল টিকেট পাবেন এখানে।')

@section('content')
<div class="bus-ticket-page py-4">
    <div class="container">

        <!-- Header -->
        <div class="bus-ticket-header text-center mb-5">
            <div class="bus-icon-big mb-3">🚌</div>
            <h1 class="fw-bold mb-2">{{ $settings->title ?? 'বাস টিকেট রিসেল' }}</h1>
            <p class="text-muted lead">{{ $settings->description ?? 'টিকেট কিনেছেন কিন্তু যেতে পারছেন না? রিসেল করুন। অথবা কম দামে টিকেট কিনুন।' }}</p>
            <div class="d-flex justify-content-center gap-3 mt-3 flex-wrap">
                <span class="badge bg-success fs-6 px-3 py-2">
                    <i class="fas fa-ticket-alt me-1"></i> {{ $availableTickets->total() }} টি টিকেট উপলব্ধ
                </span>
                @if(session('bus_ticket_seller_id'))
                    <a href="{{ route('bus-ticket.dashboard') }}" class="btn btn-warning btn-sm px-4">
                        <i class="fas fa-tachometer-alt me-1"></i> আমার বিজ্ঞাপন
                    </a>
                @else
                    <a href="{{ route('bus-ticket.register') }}" class="btn btn-primary btn-sm px-4">
                        <i class="fas fa-plus me-1"></i> টিকেট বিক্রি করুন
                    </a>
                    <a href="{{ route('bus-ticket.login') }}" class="btn btn-outline-primary btn-sm px-4">
                        <i class="fas fa-sign-in-alt me-1"></i> লগইন
                    </a>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Available Tickets Section -->
        <div class="mb-5">
            <h3 class="fw-bold mb-4 border-bottom pb-2">
                <i class="fas fa-check-circle text-success me-2"></i>উপলব্ধ টিকেট
                <span class="badge bg-success ms-2">{{ $availableTickets->total() }}</span>
            </h3>

            @if($availableTickets->isEmpty())
                <div class="text-center py-5 bg-light rounded">
                    <div style="font-size: 4rem;">🚌</div>
                    <h4 class="mt-3 text-muted">এখনো কোনো টিকেট নেই</h4>
                    <p class="text-muted">আপনিই প্রথম টিকেট বিক্রেতা হোন!</p>
                    <a href="{{ route('bus-ticket.register') }}" class="btn btn-primary btn-lg mt-2">
                        <i class="fas fa-plus me-2"></i>টিকেট বিক্রি করুন
                    </a>
                </div>
            @else
                <div class="row g-4">
                    @foreach($availableTickets as $ticket)
                        <div class="col-md-6 col-lg-4">
                            <div class="card bus-ticket-card h-100 shadow-sm border-success border-2">
                                <div class="card-header bg-success text-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">{{ $ticket->from_location }} → {{ $ticket->to_location }}</span>
                                        <span class="badge bg-white text-success">{{ $ticket->getTicketTypeLabel() }}</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted"><i class="far fa-calendar me-1"></i>ভ্রমণ তারিখ:</span>
                                            <span class="fw-semibold">{{ $ticket->journey_date->format('d M, Y') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted"><i class="fas fa-bus me-1"></i>বাস:</span>
                                            <span class="fw-semibold">{{ $ticket->bus_name ?? 'নির্ধারিত নয়' }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted"><i class="fas fa-chair me-1"></i>সিট সংখ্যা:</span>
                                            <span class="fw-semibold">{{ $ticket->seat_count }}টি</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted"><i class="fas fa-tag me-1"></i>দাম:</span>
                                            <span class="fw-bold text-primary">৳{{ number_format($ticket->price_per_ticket) }}/টিকেট</span>
                                        </div>
                                    </div>

                                    @if($ticket->description)
                                        <p class="text-muted small mb-3">{{ Str::limit($ticket->description, 100) }}</p>
                                    @endif

                                    <div class="seller-info bg-light rounded p-2 mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <small class="text-muted"><i class="fas fa-user me-1"></i>বিক্রেতা:</small>
                                            <span class="fw-semibold small">{{ $ticket->seller->name }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted"><i class="fas fa-phone me-1"></i>ফোন:</small>
                                            <span class="fw-semibold small">{{ $ticket->contact_number ?: $ticket->seller->phone }}</span>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-eye me-1"></i>{{ $ticket->interested_count }} জন আগ্রহী
                                        </small>
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $ticket->seller->upazila->name_bn ?? $ticket->seller->upazila->name ?? 'সাতক্ষীরা' }}
                                        </small>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    @php
                                        $callPhone = $ticket->contact_number ?: $ticket->seller->phone;
                                        $waPhone = $ticket->whatsapp_number ?: $ticket->seller->whatsapp;
                                        $waNumber = $waPhone ? (str_starts_with($waPhone, '0') ? '88' . $waPhone : ltrim($waPhone, '+')) : null;
                                        $waMessage = urlencode("আমি আপনার বিজ্ঞাপন দেখেছি: {$ticket->from_location} থেকে {$ticket->to_location}, {$ticket->journey_date->format('d M, Y')}. টিকেটটি এখনো আছে?");
                                        $trackUrl = route('bus-ticket.interested', $ticket->id);
                                    @endphp
                                    <div class="d-flex gap-2 mb-2">
                                        <a href="tel:{{ $callPhone }}" onclick="fetch('{{ $trackUrl }}').catch(()=>{});" class="btn btn-primary flex-grow-1">
                                            <i class="fas fa-phone me-1"></i>কল করুন
                                        </a>
                                        @if($waNumber)
                                            <a href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}" target="_blank" rel="noopener" onclick="fetch('{{ $trackUrl }}').catch(()=>{});" class="btn btn-success flex-grow-1">
                                                <i class="fab fa-whatsapp me-1"></i>WhatsApp
                                            </a>
                                        @endif
                                    </div>
                                    <button class="btn btn-warning w-100" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#inperson-{{ $ticket->id }}"
                                        aria-expanded="false"
                                        onclick="fetch('{{ $trackUrl }}').catch(()=>{});">
                                        <i class="fas fa-handshake me-1"></i>সামনাসামনি কিনতে চাই
                                    </button>
                                    @if(session('inperson_success_' . $ticket->id))
                                        <div class="alert alert-success mt-2 py-2 px-3 mb-0">
                                            <i class="fas fa-check-circle me-1"></i>{{ session('inperson_success_' . $ticket->id) }}
                                        </div>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                var el = document.getElementById('inperson-{{ $ticket->id }}');
                                                if (el) { el.classList.remove('collapse'); }
                                            });
                                        </script>
                                    @endif
                                    <div class="collapse mt-2" id="inperson-{{ $ticket->id }}">
                                        <div class="card border-warning">
                                            <div class="card-body py-3 px-3">
                                                <p class="small fw-semibold mb-2"><i class="fas fa-info-circle me-1 text-warning"></i>আপনার তথ্য দিন, বিক্রেতা আপনাকে call করবেন:</p>
                                                <form method="POST" action="{{ route('bus-ticket.inperson-request', $ticket->id) }}">
                                                    @csrf
                                                    <div class="mb-2">
                                                        <input type="text" name="buyer_name" class="form-control form-control-sm @error('buyer_name') is-invalid @enderror"
                                                            placeholder="আপনার নাম" required value="{{ old('buyer_name') }}">
                                                        @error('buyer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                    </div>
                                                    <div class="mb-2">
                                                        <input type="tel" name="buyer_phone" class="form-control form-control-sm @error('buyer_phone') is-invalid @enderror"
                                                            placeholder="আপনার ফোন নম্বর" required value="{{ old('buyer_phone') }}">
                                                        @error('buyer_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                    </div>
                                                    <button type="submit" class="btn btn-warning btn-sm w-100">
                                                        <i class="fas fa-paper-plane me-1"></i>অনুরোধ পাঠান
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $availableTickets->links() }}
                </div>
            @endif
        </div>

        <!-- Sold Tickets Section -->
        <div class="mb-5">
            <h3 class="fw-bold mb-4 border-bottom pb-2">
                <i class="fas fa-check-double text-secondary me-2"></i>বিক্রি হয়ে গেছে
                <span class="badge bg-secondary ms-2">{{ $soldTickets->total() }}</span>
            </h3>

            @if($soldTickets->isEmpty())
                <div class="text-center py-4 text-muted">
                    <p>এখনো কোনো টিকেট বিক্রি হয়নি</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach($soldTickets as $ticket)
                        <div class="col-md-6 col-lg-4">
                            <div class="card bus-ticket-card h-100 shadow-sm opacity-75">
                                <div class="card-header bg-secondary text-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">{{ $ticket->from_location }} → {{ $ticket->to_location }}</span>
                                        <span class="badge bg-dark">SOLD</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted"><i class="far fa-calendar me-1"></i>ভ্রমণ তারিখ:</span>
                                            <span class="fw-semibold">{{ $ticket->journey_date->format('d M, Y') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted"><i class="fas fa-chair me-1"></i>সিট সংখ্যা:</span>
                                            <span class="fw-semibold">{{ $ticket->seat_count }}টি</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted"><i class="fas fa-tag me-1"></i>দাম ছিল:</span>
                                            <span class="fw-bold">৳{{ number_format($ticket->price_per_ticket) }}/টিকেট</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-eye me-1"></i>{{ $ticket->interested_count }} জন আগ্রহী ছিল
                                        </small>
                                        <small class="text-success fw-semibold">
                                            <i class="fas fa-check me-1"></i>বিক্রি হয়েছে
                                        </small>
                                    </div>
                                </div>
                                <div class="card-footer bg-secondary bg-opacity-10">
                                    <button class="btn btn-secondary w-100" disabled>
                                        <i class="fas fa-times-circle me-2"></i>বিক্রি হয়ে গেছে
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $soldTickets->links() }}
                </div>
            @endif
        </div>

    </div>
</div>

<style>
.bus-ticket-page {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    min-height: 100vh;
}
.bus-icon-big {
    font-size: 5rem;
    line-height: 1;
}
.bus-ticket-card {
    transition: transform 0.2s, box-shadow 0.2s;
}
.bus-ticket-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}
</style>
@endsection
