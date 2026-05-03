@extends('admin.layouts.app')

@section('title', 'সব টিকেট - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">সব টিকেট</h4>
        <div>
            <a href="{{ route('admin.bus-ticket.index') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i>সেলার তালিকা
            </a>
            <a href="{{ route('admin.bus-ticket.settings') }}" class="btn btn-outline-primary">
                <i class="fas fa-cog me-2"></i>সেটিংস
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.bus-ticket.tickets') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">স্ট্যাটাস</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">সব</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>উপলব্ধ</option>
                        <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>বিক্রি হয়েছে</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">সেলার</label>
                    <select name="seller_id" class="form-select" onchange="this.form.submit()">
                        <option value="">সব সেলার</option>
                        @foreach($sellers as $s)
                            <option value="{{ $s->id }}" {{ request('seller_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('admin.bus-ticket.tickets') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times me-2"></i>ফিল্টার সরান
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>রুট</th>
                            <th>তারিখ</th>
                            <th>সেলার</th>
                            <th>দাম</th>
                            <th>আগ্রহী</th>
                            <th>স্ট্যাটাস</th>
                            <th>অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr>
                                <td>
                                    <strong>{{ $ticket->from_location }} → {{ $ticket->to_location }}</strong><br>
                                    <small class="text-muted">{{ $ticket->getTicketTypeLabel() }}, {{ $ticket->seat_count }} সিট</small>
                                    @if($ticket->bus_name)
                                        <br><small class="text-muted">{{ $ticket->bus_name }}</small>
                                    @endif
                                </td>
                                <td>{{ $ticket->journey_date->format('d M, Y') }}</td>
                                <td>
                                    <strong>{{ $ticket->seller->name }}</strong><br>
                                    <small class="text-muted">{{ $ticket->seller->phone }}</small>
                                </td>
                                <td>৳{{ number_format($ticket->price_per_ticket) }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $ticket->interested_count }}</span>
                                </td>
                                <td>
                                    @if($ticket->is_sold)
                                        <span class="badge bg-secondary">বিক্রি হয়েছে</span><br>
                                        <small class="text-muted">{{ $ticket->sold_at?->format('d M, Y') }}</small>
                                    @else
                                        <span class="badge bg-success">উপলব্ধ</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.bus-ticket.destroy-ticket', $ticket->id) }}" method="POST" class="d-inline" onsubmit="return confirm('নিশ্চিত মুছে ফেলতে চান?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="মুছুন">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div style="font-size: 3rem;">🚌</div>
                                    <p class="text-muted mt-3">কোনো টিকেট পাওয়া যায়নি</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($tickets->hasPages())
            <div class="card-footer">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
