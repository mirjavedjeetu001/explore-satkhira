@extends('frontend.layouts.app')

@section('title', 'জ্বালানি আপডেট ম্যানেজমেন্ট - মডারেটর')

@section('content')
<section class="page-header py-4" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="text-white mb-0"><i class="fas fa-gas-pump me-2"></i>জ্বালানি আপডেট ম্যানেজমেন্ট</h3>
                <p class="text-white-50 mb-0">আপনার উপজেলার পাম্পের রিপোর্ট যাচাই ও সম্পাদনা করুন</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('dashboard') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-1"></i>ড্যাশবোর্ড
                </a>
            </div>
        </div>
    </div>
</section>

<section class="py-4">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-4">
                        <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=28a745&color=fff&size=100' }}" 
                             alt="{{ auth()->user()->name }}" class="rounded-circle mb-3" width="100" height="100" style="object-fit: cover;">
                        <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                        <p class="text-muted small mb-0">{{ auth()->user()->email }}</p>
                        @if(auth()->user()->isSuperAdmin())
                            <span class="badge bg-danger mt-2"><i class="fas fa-crown me-1"></i>সুপার অ্যাডমিন</span>
                        @elseif(auth()->user()->isAdmin())
                            <span class="badge bg-primary mt-2"><i class="fas fa-user-shield me-1"></i>অ্যাডমিন</span>
                        @elseif(auth()->user()->is_upazila_moderator)
                            <span class="badge bg-warning text-dark mt-2"><i class="fas fa-shield-alt me-1"></i>উপজেলা মডারেটর</span>
                        @elseif(auth()->user()->is_own_business_moderator)
                            <span class="badge bg-info mt-2"><i class="fas fa-store me-1"></i>নিজস্ব ব্যবসা মডারেটর</span>
                        @else
                            <span class="badge bg-success mt-2">{{ auth()->user()->role->display_name ?? 'User' }}</span>
                        @endif
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-tachometer-alt me-2"></i>ড্যাশবোর্ড
                        </a>
                        @if(!auth()->user()->comment_only)
                            <a href="{{ route('dashboard.listings') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-list me-2"></i>আমার তথ্যসমূহ
                            </a>
                        @endif
                        <a href="{{ route('dashboard.fuel.reports') }}" class="list-group-item list-group-item-action active">
                            <i class="fas fa-gas-pump me-2"></i>জ্বালানি ম্যানেজমেন্ট
                        </a>
                        @if(auth()->user()->is_upazila_moderator || auth()->user()->isAdmin())
                            <a href="{{ route('dashboard.blood.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard.blood.*') ? 'active' : '' }}">
                                <i class="fas fa-tint me-2 text-danger"></i>রক্তদাতা ম্যানেজমেন্ট
                            </a>
                        @endif
                        @php $bloodDonor = \App\Models\BloodDonor::where('phone', auth()->user()->phone)->where('status', 'active')->first(); @endphp
                        @if($bloodDonor)
                            <a href="{{ route('dashboard.blood.my-profile') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-heartbeat me-2 text-danger"></i>আমার রক্তদাতা প্রোফাইল
                            </a>
                        @endif
                        <a href="{{ route('dashboard.profile') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-user-edit me-2"></i>প্রোফাইল সম্পাদনা
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Stats -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm bg-primary text-white">
                            <div class="card-body text-center py-3">
                                <div class="h3 mb-0">{{ $stats['total_reports'] }}</div>
                                <small>মোট রিপোর্ট</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm bg-success text-white">
                            <div class="card-body text-center py-3">
                                <div class="h3 mb-0">{{ $stats['today_reports'] }}</div>
                                <small>আজকের রিপোর্ট</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm bg-info text-white">
                            <div class="card-body text-center py-3">
                                <div class="h3 mb-0">{{ $stats['total_stations'] }}</div>
                                <small>মোট পাম্প</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <form action="{{ route('dashboard.fuel.reports') }}" method="GET" class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label">পাম্প</label>
                                <select name="station" class="form-select">
                                    <option value="">সব পাম্প</option>
                                    @foreach($stations as $station)
                                        <option value="{{ $station->id }}" {{ request('station') == $station->id ? 'selected' : '' }}>
                                            {{ $station->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="show_all" value="1" id="showAll" {{ request('show_all') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="showAll">সব রিপোর্ট দেখুন</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary me-2"><i class="fas fa-search me-1"></i>খুঁজুন</button>
                                <a href="{{ route('dashboard.fuel.reports') }}" class="btn btn-outline-secondary"><i class="fas fa-times"></i></a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Reports Table -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-list me-2"></i>রিপোর্টসমূহ ({{ $reports->total() }})</span>
                        <span class="badge {{ request('show_all') == '1' ? 'bg-warning' : 'bg-success' }}">
                            {{ request('show_all') == '1' ? 'সব রিপোর্ট' : 'সর্বশেষ আপডেট' }}
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>পাম্প</th>
                                        <th>রিপোর্টকারী</th>
                                        <th>পেট্রোল</th>
                                        <th>ডিজেল</th>
                                        <th>অকটেন</th>
                                        <th>সময়</th>
                                        <th>অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reports as $report)
                                        <tr>
                                            <td>
                                                <strong>{{ $report->fuelStation->name }}</strong><br>
                                                <small class="text-muted">{{ $report->fuelStation->upazila->name }}</small>
                                            </td>
                                            <td>
                                                {{ $report->reporter_name }}<br>
                                                <small class="text-muted">{{ $report->reporter_phone }}</small>
                                            </td>
                                            <td>
                                                <span class="badge {{ $report->petrol_available ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $report->petrol_available ? 'আছে' : 'নেই' }}
                                                </span>
                                                @if($report->petrol_price)
                                                    <br><small>৳{{ number_format($report->petrol_price, 0) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $report->diesel_available ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $report->diesel_available ? 'আছে' : 'নেই' }}
                                                </span>
                                                @if($report->diesel_price)
                                                    <br><small>৳{{ number_format($report->diesel_price, 0) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $report->octane_available ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $report->octane_available ? 'আছে' : 'নেই' }}
                                                </span>
                                                @if($report->octane_price)
                                                    <br><small>৳{{ number_format($report->octane_price, 0) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $report->created_at->format('d M Y') }}</small><br>
                                                <small class="text-muted">{{ $report->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('dashboard.fuel.reports.show', $report->id) }}" class="btn btn-outline-info" title="দেখুন">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('dashboard.fuel.reports.edit', $report->id) }}" class="btn btn-outline-primary" title="এডিট">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button class="btn {{ $report->is_verified ? 'btn-success' : 'btn-outline-success' }}" 
                                                            onclick="verifyReport({{ $report->id }})" title="{{ $report->is_verified ? 'ভেরিফাইড' : 'ভেরিফাই করুন' }}">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-outline-danger" onclick="deleteReport({{ $report->id }})" title="মুছুন">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">কোন রিপোর্ট পাওয়া যায়নি</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($reports->hasPages())
                        <div class="card-footer bg-white">
                            {{ $reports->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
function verifyReport(id) {
    fetch(`/dashboard/fuel/reports/${id}/verify`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        }
    });
}

function deleteReport(id) {
    if (confirm('এই রিপোর্টটি মুছে ফেলতে চান?')) {
        fetch(`/dashboard/fuel/reports/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            }
        });
    }
}
</script>
@endpush
@endsection
