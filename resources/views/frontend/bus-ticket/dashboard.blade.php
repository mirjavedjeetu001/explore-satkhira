@extends('frontend.layouts.app')

@section('title', 'ড্যাশবোর্ড - বাস টিকেট রিসেল')

@section('content')
<div class="py-4">
    <div class="container">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1"><i class="fas fa-tachometer-alt me-2 text-primary"></i>আমার বিজ্ঞাপন</h3>
                <p class="text-muted mb-0">স্বাগতম, {{ $seller->name }}</p>
            </div>
            <div>
                <a href="{{ route('bus-ticket.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>নতুন টিকেট যোগ করুন
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-1">উপলব্ধ টিকেট</h6>
                                <h3 class="mb-0">{{ $availableTickets->total() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-ticket-alt fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-secondary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-1">বিক্রি হয়েছে</h6>
                                <h3 class="mb-0">{{ $soldTickets->total() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check-circle fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-1">মোট আগ্রহী</h6>
                                <h3 class="mb-0">{{ $seller->tickets->sum('interested_count') }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-eye fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Available Tickets -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-list me-2 text-success"></i>আমার টিকেট</h5>
                    </div>
                    <div class="card-body p-0">
                        @if($availableTickets->isEmpty() && $soldTickets->isEmpty())
                            <div class="text-center py-5">
                                <div style="font-size: 3rem;">🚌</div>
                                <p class="text-muted mt-3">আপনার কোনো টিকেট বিজ্ঞাপন নেই</p>
                                <a href="{{ route('bus-ticket.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>প্রথম টিকেট পোস্ট করুন
                                </a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>রুট</th>
                                            <th>তারিখ</th>
                                            <th>দাম</th>
                                            <th>আগ্রহী</th>
                                            <th>স্ট্যাটাস</th>
                                            <th>অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($availableTickets as $ticket)
                                            <tr>
                                                <td>
                                                    <strong>{{ $ticket->from_location }} → {{ $ticket->to_location }}</strong><br>
                                                    <small class="text-muted">{{ $ticket->getTicketTypeLabel() }}, {{ $ticket->seat_count }} সিট</small>
                                                </td>
                                                <td>{{ $ticket->journey_date->format('d M, Y') }}</td>
                                                <td>৳{{ number_format($ticket->price_per_ticket) }}</td>
                                                <td>
                                                    <span class="badge bg-info">{{ $ticket->interested_count }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success">উপলব্ধ</span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('bus-ticket.edit', $ticket->id) }}" class="btn btn-outline-primary" title="এডিট">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('bus-ticket.mark-sold', $ticket->id) }}" method="POST" class="d-inline" onsubmit="return confirm('টিকেটটি বিক্রি হয়ে গেছে হিসেবে চিহ্নিত করবেন?')">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm" title="বিক্রি হয়ে গেছে - Sold Out">
                                                                <i class="fas fa-check-circle me-1"></i>Sold Out
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('bus-ticket.destroy', $ticket->id) }}" method="POST" class="d-inline" onsubmit="return confirm('নিশ্চিত মুছে ফেলতে চান?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger" title="মুছুন">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @foreach($soldTickets as $ticket)
                                            <tr class="table-secondary">
                                                <td>
                                                    <strong>{{ $ticket->from_location }} → {{ $ticket->to_location }}</strong><br>
                                                    <small class="text-muted">{{ $ticket->getTicketTypeLabel() }}, {{ $ticket->seat_count }} সিট</small>
                                                </td>
                                                <td>{{ $ticket->journey_date->format('d M, Y') }}</td>
                                                <td>৳{{ number_format($ticket->price_per_ticket) }}</td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $ticket->interested_count }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">বিক্রি হয়েছে</span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <form action="{{ route('bus-ticket.mark-available', $ticket->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-success" title="আবার সক্রিয় করুন">
                                                                <i class="fas fa-undo"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('bus-ticket.destroy', $ticket->id) }}" method="POST" class="d-inline" onsubmit="return confirm('নিশ্চিত মুছে ফেলতে চান?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger" title="মুছুন">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Profile & Settings -->
            <div class="col-lg-4">

                <!-- In-Person Requests -->
                @if($inpersonRequests->isNotEmpty())
                <div class="card shadow-sm mb-4 border-warning">
                    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-handshake me-2"></i>সামনাসামনি কেনার অনুরোধ</h5>
                        @if($unreadRequestCount > 0)
                            <span class="badge bg-danger">{{ $unreadRequestCount }} নতুন</span>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($inpersonRequests as $req)
                            <li class="list-group-item {{ !$req->is_read ? 'bg-warning bg-opacity-10' : '' }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="mb-1 fw-semibold">
                                            @if(!$req->is_read)
                                                <span class="badge bg-danger me-1" style="font-size:.6rem;">নতুন</span>
                                            @endif
                                            {{ $req->buyer_name }}
                                        </p>
                                        <a href="tel:{{ $req->buyer_phone }}" class="btn btn-sm btn-primary py-0 px-2">
                                            <i class="fas fa-phone me-1"></i>{{ $req->buyer_phone }}
                                        </a>
                                        @if($req->ticket)
                                        <p class="mb-0 mt-1 small text-muted">
                                            <i class="fas fa-ticket-alt me-1"></i>{{ $req->ticket->from_location }} → {{ $req->ticket->to_location }}
                                            ({{ $req->ticket->journey_date->format('d M') }})
                                        </p>
                                        @endif
                                    </div>
                                    <small class="text-muted">{{ $req->created_at->diffForHumans() }}</small>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @if($unreadRequestCount > 0)
                    <div class="card-footer bg-white text-center py-2">
                        <button class="btn btn-sm btn-outline-secondary" onclick="markRequestsRead()">
                            <i class="fas fa-check-double me-1"></i>সব পড়া হয়েছে চিহ্নিত করুন
                        </button>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Profile Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-user me-2 text-primary"></i>প্রোফাইল</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('bus-ticket.update-profile') }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">নাম</label>
                                <input type="text" name="name" class="form-control" value="{{ $seller->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">ফোন</label>
                                <input type="text" class="form-control" value="{{ $seller->phone }}" disabled>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">WhatsApp</label>
                                <input type="text" name="whatsapp" class="form-control" value="{{ $seller->whatsapp }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">ঠিকানা</label>
                                <textarea name="address" class="form-control" rows="2">{{ $seller->address }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save me-2"></i>প্রোফাইল আপডেট
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Change Password -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-lock me-2 text-warning"></i>পাসওয়ার্ড পরিবর্তন</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('bus-ticket.change-password') }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">বর্তমান পাসওয়ার্ড</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">নতুন পাসওয়ার্ড</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">নতুন পাসওয়ার্ড নিশ্চিত করুন</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-warning w-100">
                                <i class="fas fa-key me-2"></i>পাসওয়ার্ড পরিবর্তন
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Logout -->
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <form method="POST" action="{{ route('bus-ticket.logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-sign-out-alt me-2"></i>লগআউট
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function markRequestsRead() {
    fetch('{{ route('bus-ticket.mark-requests-read') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    }).then(() => location.reload());
}
</script>
@endpush
@endsection
