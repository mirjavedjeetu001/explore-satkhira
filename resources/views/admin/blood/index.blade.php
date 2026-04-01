@extends('admin.layouts.app')

@section('title', '🩸 রক্তদাতা তালিকা')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h1><i class="fas fa-tint me-2 text-danger"></i>রক্তদাতা তালিকা</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.blood.comments') }}" class="btn btn-outline-info">
            <i class="fas fa-comments me-1"></i> মন্তব্য
        </a>
        <a href="{{ route('admin.blood.settings') }}" class="btn btn-outline-secondary">
            <i class="fas fa-cog me-1"></i> সেটিংস
        </a>
        <a href="{{ route('admin.blood.create') }}" class="btn btn-danger">
            <i class="fas fa-plus me-1"></i> নতুন ডোনর
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md col-6">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body py-3">
                <div class="text-danger fs-4 fw-bold">{{ $stats['total'] }}</div>
                <small class="text-muted">মোট ডোনর</small>
            </div>
        </div>
    </div>
    <div class="col-md col-6">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body py-3">
                <div class="text-success fs-4 fw-bold">{{ $stats['active'] }}</div>
                <small class="text-muted">সক্রিয়</small>
            </div>
        </div>
    </div>
    <div class="col-md col-6">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body py-3">
                <div class="text-primary fs-4 fw-bold">{{ $stats['available'] }}</div>
                <small class="text-muted">Available</small>
            </div>
        </div>
    </div>
    <div class="col-md col-6">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body py-3">
                <div class="text-info fs-4 fw-bold">{{ $stats['organizations'] }}</div>
                <small class="text-muted">সংগঠন</small>
            </div>
        </div>
    </div>
    <div class="col-md col-6">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body py-3">
                <div class="text-secondary fs-4 fw-bold">{{ number_format($stats['page_views']) }}</div>
                <small class="text-muted">পেজ ভিউ</small>
            </div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form class="row g-2 align-items-end">
            <div class="col-md-2">
                <select name="blood_group" class="form-select">
                    <option value="">সব গ্রুপ</option>
                    @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                        <option value="{{ $bg }}" {{ request('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="upazila" class="form-select">
                    <option value="">সব উপজেলা</option>
                    @foreach($upazilas as $u)
                        <option value="{{ $u->id }}" {{ request('upazila') == $u->id ? 'selected' : '' }}>{{ $u->name_bn ?? $u->name }}</option>
                    @endforeach
                    <option value="outside" {{ request('upazila') == 'outside' ? 'selected' : '' }}>🌍 সাতক্ষীরার বাহিরে</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="type" class="form-select">
                    <option value="">সব ধরন</option>
                    <option value="individual" {{ request('type') == 'individual' ? 'selected' : '' }}>ব্যক্তিগত</option>
                    <option value="organization" {{ request('type') == 'organization' ? 'selected' : '' }}>সংগঠন</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">সব স্ট্যাটাস</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>সক্রিয়</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয়</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="text" name="search" class="form-control" placeholder="অনুসন্ধান..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="fas fa-search me-1"></i>খুঁজুন</button>
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
                        <th>ID</th>
                        <th>গ্রুপ</th>
                        <th>নাম</th>
                        <th>ফোন</th>
                        <th>উপজেলা</th>
                        <th>ধরন</th>
                        <th>সর্বশেষ দান</th>
                        <th>অবস্থা</th>
                        <th>স্ট্যাটাস</th>
                        <th>অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donors as $donor)
                        <tr>
                            <td>{{ $donor->id }}</td>
                            <td><span class="badge bg-danger">{{ $donor->blood_group }}</span></td>
                            <td>
                                <strong>{{ $donor->name }}</strong>
                                @if($donor->type === 'organization')
                                    <br><small class="text-muted"><i class="fas fa-building me-1"></i>{{ $donor->organization_name }}</small>
                                @endif
                            </td>
                            <td>{{ $donor->phone }}</td>
                            <td>{{ $donor->upazila ? ($donor->upazila->name_bn ?? $donor->upazila->name ?? '-') : ($donor->outside_area ? '🌍 ' . $donor->outside_area : '-') }}</td>
                            <td>
                                <span class="badge {{ $donor->type === 'organization' ? 'bg-info' : 'bg-secondary' }}">
                                    {{ $donor->type === 'organization' ? 'সংগঠন' : 'ব্যক্তিগত' }}
                                </span>
                            </td>
                            <td>
                                @if($donor->last_donation_date)
                                    {{ $donor->last_donation_date->format('d M, Y') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($donor->is_currently_available)
                                    <span class="badge bg-success">Available</span>
                                @elseif($donor->next_available_date)
                                    <span class="badge bg-warning text-dark">{{ $donor->next_available_date->format('d/m') }}</span>
                                @else
                                    <span class="badge bg-secondary">Not Available</span>
                                @endif
                                @if($donor->not_reachable_count > 0)
                                    <span class="badge bg-danger" title="Not Reachable রিপোর্ট">{{ $donor->not_reachable_count }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $donor->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $donor->status === 'active' ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.blood.show', $donor->id) }}" class="btn btn-sm btn-outline-primary" title="দেখুন"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.blood.edit', $donor->id) }}" class="btn btn-sm btn-outline-warning" title="এডিট"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.blood.toggle-status', $donor->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm {{ $donor->status === 'active' ? 'btn-outline-secondary' : 'btn-outline-success' }}" title="{{ $donor->status === 'active' ? 'নিষ্ক্রিয়' : 'সক্রিয়' }}">
                                            <i class="fas {{ $donor->status === 'active' ? 'fa-ban' : 'fa-check' }}"></i>
                                        </button>
                                    </form>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteDonor({{ $donor->id }})" title="মুছুন"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="text-center py-4 text-muted">কোনো ডোনর পাওয়া যায়নি।</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($donors->hasPages())
        <div class="card-footer bg-white">{{ $donors->links() }}</div>
    @endif
</div>

<script>
function deleteDonor(id) {
    if (!confirm('আপনি কি নিশ্চিত এই ডোনরকে মুছে ফেলতে চান?')) return;
    fetch(`{{ url('admin/blood') }}/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    }).then(r => r.json()).then(d => {
        if (d.success) location.reload();
        else alert(d.message || 'সমস্যা হয়েছে');
    }).catch(() => alert('সমস্যা হয়েছে'));
}
</script>
@endsection
