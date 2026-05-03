@extends('admin.layouts.app')

@section('title', 'সেলার ডিটেইল - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">সেলার বিস্তারিত</h4>
        <a href="{{ route('admin.bus-ticket.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>ফিরে যান
        </a>
    </div>

    <div class="row">
        <!-- Seller Info -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ $seller->name }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted">ফোন</td>
                            <td class="fw-semibold">{{ $seller->phone }}</td>
                        </tr>
                        @if($seller->whatsapp)
                            <tr>
                                <td class="text-muted">WhatsApp</td>
                                <td class="fw-semibold">{{ $seller->whatsapp }}</td>
                            </tr>
                        @endif
                        @if($seller->upazila)
                            <tr>
                                <td class="text-muted">উপজেলা</td>
                                <td class="fw-semibold">{{ $seller->upazila->name_bn ?? $seller->upazila->name }}</td>
                            </tr>
                        @endif
                        @if($seller->address)
                            <tr>
                                <td class="text-muted">ঠিকানা</td>
                                <td class="fw-semibold">{{ $seller->address }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="text-muted">স্ট্যাটাস</td>
                            <td>
                                @if($seller->is_active)
                                    <span class="badge bg-success">সক্রিয়</span>
                                @else
                                    <span class="badge bg-danger">নিষ্ক্রিয়</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">যোগদান</td>
                            <td class="fw-semibold">{{ $seller->created_at->format('d M, Y') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="d-flex gap-2">
                        <form action="{{ route('admin.bus-ticket.toggle-seller', $seller->id) }}" method="POST" class="flex-fill">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="fas fa-toggle-on me-2"></i>স্ট্যাটাস পরিবর্তন
                            </button>
                        </form>
                        <form action="{{ route('admin.bus-ticket.destroy-seller', $seller->id) }}" method="POST" class="flex-fill" onsubmit="return confirm('নিশ্চিত মুছে ফেলতে চান?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash me-2"></i>মুছুন
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tickets List -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">সব টিকেট</h5>
                    <span class="badge bg-white text-dark">{{ $seller->tickets->count() }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>রুট</th>
                                    <th>তারিখ</th>
                                    <th>দাম</th>
                                    <th>আগ্রহী</th>
                                    <th>স্ট্যাটাস</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($seller->tickets as $ticket)
                                    <tr>
                                        <td>
                                            <strong>{{ $ticket->from_location }} → {{ $ticket->to_location }}</strong><br>
                                            <small class="text-muted">{{ $ticket->getTicketTypeLabel() }}</small>
                                        </td>
                                        <td>{{ $ticket->journey_date->format('d M, Y') }}</td>
                                        <td>৳{{ number_format($ticket->price_per_ticket) }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $ticket->interested_count }}</span>
                                        </td>
                                        <td>
                                            @if($ticket->is_sold)
                                                <span class="badge bg-secondary">বিক্রি হয়েছে</span>
                                            @else
                                                <span class="badge bg-success">উপলব্ধ</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <p class="text-muted mb-0">কোনো টিকেট নেই</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
