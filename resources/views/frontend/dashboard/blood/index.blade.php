@extends('frontend.layouts.app')

@section('title', 'রক্তদাতা ম্যানেজমেন্ট - মডারেটর')

@section('content')
<section class="page-header py-4" style="background: linear-gradient(135deg, #dc3545, #a71d2a);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="text-white mb-0"><i class="fas fa-tint me-2"></i>রক্তদাতা ম্যানেজমেন্ট</h3>
                <p class="text-white-50 mb-0">আপনার উপজেলার রক্তদাতাদের ম্যানেজ করুন</p>
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
                        <span class="badge bg-warning text-dark mt-2"><i class="fas fa-shield-alt me-1"></i>উপজেলা মডারেটর</span>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-tachometer-alt me-2"></i>ড্যাশবোর্ড
                        </a>
                        @if(auth()->user()->is_upazila_moderator || auth()->user()->isAdmin())
                            <a href="{{ route('dashboard.fuel.reports') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-gas-pump me-2"></i>জ্বালানি ম্যানেজমেন্ট
                            </a>
                        @endif
                        <a href="{{ route('dashboard.blood.index') }}" class="list-group-item list-group-item-action active">
                            <i class="fas fa-tint me-2 text-danger"></i>রক্তদাতা ম্যানেজমেন্ট
                        </a>
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
                    <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                @endif

                {{-- Stats --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm text-center">
                            <div class="card-body py-3">
                                <div class="text-danger fs-4 fw-bold">{{ $stats['total'] }}</div>
                                <small class="text-muted">মোট ডোনর</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm text-center">
                            <div class="card-body py-3">
                                <div class="text-success fs-4 fw-bold">{{ $stats['active'] }}</div>
                                <small class="text-muted">সক্রিয়</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm text-center">
                            <div class="card-body py-3">
                                <div class="text-primary fs-4 fw-bold">{{ $stats['available'] }}</div>
                                <small class="text-muted">Available</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Filters --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <form class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <select name="blood_group" class="form-select form-select-sm">
                                    <option value="">সব গ্রুপ</option>
                                    @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                                        <option value="{{ $bg }}" {{ request('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">সব স্ট্যাটাস</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>সক্রিয়</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয়</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control form-control-sm" placeholder="নাম, ফোন..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-danger btn-sm w-100"><i class="fas fa-search me-1"></i>খুঁজুন</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Table --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white"><i class="fas fa-list me-2"></i>ডোনর তালিকা ({{ $donors->total() }})</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>গ্রুপ</th>
                                        <th>নাম</th>
                                        <th>ফোন</th>
                                        <th>উপজেলা</th>
                                        <th>অবস্থা</th>
                                        <th>স্ট্যাটাস</th>
                                        <th>অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($donors as $donor)
                                        <tr>
                                            <td><span class="badge bg-danger">{{ $donor->blood_group }}</span></td>
                                            <td>
                                                <strong>{{ $donor->name }}</strong>
                                                @if($donor->type === 'organization')
                                                    <br><small class="text-muted"><i class="fas fa-building me-1"></i>{{ $donor->organization_name }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $donor->phone }}</td>
                                            <td>{{ $donor->upazila ? ($donor->upazila->name_bn ?? '-') : ($donor->outside_area ? '🌍 ' . $donor->outside_area : '-') }}</td>
                                            <td>
                                                @if($donor->is_currently_available)
                                                    <span class="badge bg-success">Available</span>
                                                @elseif($donor->next_available_date)
                                                    <span class="badge bg-warning text-dark">{{ $donor->next_available_date->format('d/m') }}</span>
                                                @else
                                                    <span class="badge bg-secondary">Not Available</span>
                                                @endif
                                                @if($donor->not_reachable_count > 0)
                                                    <span class="badge bg-danger" title="Not Reachable">{{ $donor->not_reachable_count }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $donor->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $donor->status === 'active' ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('dashboard.blood.show', $donor->id) }}" class="btn btn-sm btn-outline-primary" title="দেখুন"><i class="fas fa-eye"></i></a>
                                                    <a href="{{ route('dashboard.blood.edit', $donor->id) }}" class="btn btn-sm btn-outline-warning" title="এডিট"><i class="fas fa-edit"></i></a>
                                                    <form action="{{ route('dashboard.blood.toggle-status', $donor->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button class="btn btn-sm {{ $donor->status === 'active' ? 'btn-outline-secondary' : 'btn-outline-success' }}">
                                                            <i class="fas {{ $donor->status === 'active' ? 'fa-ban' : 'fa-check' }}"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="7" class="text-center py-4 text-muted">কোনো ডোনর পাওয়া যায়নি।</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($donors->hasPages())
                        <div class="card-footer bg-white">{{ $donors->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
