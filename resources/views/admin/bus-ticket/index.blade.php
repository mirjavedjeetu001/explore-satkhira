@extends('admin.layouts.app')

@section('title', 'বাস টিকেট রিসেল - Admin')

@section('content')
<div class="container-fluid">
    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-1">মোট সেলার</h6>
                            <h3 class="mb-0">{{ $totalSellers }}</h3>
                        </div>
                        <i class="fas fa-users fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-1">সক্রিয় সেলার</h6>
                            <h3 class="mb-0">{{ $activeSellers }}</h3>
                        </div>
                        <i class="fas fa-user-check fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-1">উপলব্ধ টিকেট</h6>
                            <h3 class="mb-0">{{ $availableTickets }}</h3>
                        </div>
                        <i class="fas fa-ticket-alt fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-secondary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-1">বিক্রি হয়েছে</h6>
                            <h3 class="mb-0">{{ $soldTickets }}</h3>
                        </div>
                        <i class="fas fa-check-double fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">সেলার তালিকা</h4>
        <div>
            <a href="{{ route('admin.bus-ticket.settings') }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-cog me-2"></i>সেটিংস
            </a>
            <a href="{{ route('admin.bus-ticket.tickets') }}" class="btn btn-outline-success">
                <i class="fas fa-list me-2"></i>সব টিকেট
            </a>
        </div>
    </div>

    <!-- Sellers Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>সেলার</th>
                            <th>যোগাযোগ</th>
                            <th>অবস্থান</th>
                            <th class="text-center">উপলব্ধ টিকেট</th>
                            <th class="text-center">বিক্রি হয়েছে</th>
                            <th>স্ট্যাটাস</th>
                            <th>অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sellers as $seller)
                            <tr>
                                <td>
                                    <strong>{{ $seller->name }}</strong><br>
                                    <small class="text-muted">{{ $seller->created_at->format('d M, Y') }}</small>
                                </td>
                                <td>
                                    <div><i class="fas fa-phone me-1 text-muted"></i>{{ $seller->phone }}</div>
                                    @if($seller->whatsapp)
                                        <div><i class="fab fa-whatsapp me-1 text-success"></i>{{ $seller->whatsapp }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($seller->upazila)
                                        {{ $seller->upazila->name_bn ?? $seller->upazila->name }}<br>
                                    @endif
                                    <small class="text-muted">{{ Str::limit($seller->address, 30) }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success">{{ $seller->available_tickets_count ?? 0 }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $seller->sold_tickets_count ?? 0 }}</span>
                                </td>
                                <td>
                                    @if($seller->is_active)
                                        <span class="badge bg-success">সক্রিয়</span>
                                    @else
                                        <span class="badge bg-danger">নিষ্ক্রিয়</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.bus-ticket.show', $seller->id) }}" class="btn btn-outline-primary" title="দেখুন">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.bus-ticket.toggle-seller', $seller->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-warning" title="স্ট্যাটাস পরিবর্তন">
                                                <i class="fas fa-toggle-on"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.bus-ticket.destroy-seller', $seller->id) }}" method="POST" class="d-inline" onsubmit="return confirm('নিশ্চিত মুছে ফেলতে চান? সব টিকেটও মুছে যাবে।')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="মুছুন">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div style="font-size: 3rem;">🚌</div>
                                    <p class="text-muted mt-3">এখনো কোনো সেলার নেই</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($sellers->hasPages())
            <div class="card-footer">
                {{ $sellers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
