@extends('admin.layouts.app')

@section('title', 'জ্বালানি তেল - পাম্পসমূহ')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h1><i class="fas fa-building me-2"></i>পেট্রোল পাম্পসমূহ</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.fuel.reports') }}" class="btn btn-outline-primary">
            <i class="fas fa-list me-1"></i> রিপোর্ট
        </a>
        <a href="{{ route('admin.fuel.stations.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-1"></i> নতুন পাম্প যুক্ত করুন
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <i class="fas fa-list me-2"></i>পাম্প তালিকা ({{ $stations->total() }})
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>নাম</th>
                        <th>উপজেলা</th>
                        <th>ঠিকানা</th>
                        <th>ফোন</th>
                        <th>সর্বশেষ আপডেট</th>
                        <th>স্ট্যাটাস</th>
                        <th>অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stations as $station)
                        <tr>
                            <td>{{ $station->id }}</td>
                            <td><strong>{{ $station->name }}</strong></td>
                            <td>{{ $station->upazila->name }}</td>
                            <td>{{ $station->address ?? '-' }}</td>
                            <td>{{ $station->phone ?? '-' }}</td>
                            <td>
                                @if($station->latestReport)
                                    <span class="badge {{ ($station->latestReport->petrol_available || $station->latestReport->diesel_available || $station->latestReport->octane_available) ? 'bg-success' : 'bg-danger' }}">
                                        {{ ($station->latestReport->petrol_available || $station->latestReport->diesel_available || $station->latestReport->octane_available) ? 'তেল আছে' : 'তেল নেই' }}
                                    </span>
                                    <br><small class="text-muted">{{ $station->latestReport->created_at->diffForHumans() }}</small>
                                @else
                                    <span class="text-muted">কোন আপডেট নেই</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $station->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $station->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                </span>
                                @if($station->is_locked)
                                    <span class="badge bg-danger"><i class="fas fa-lock me-1"></i>লক</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.fuel.stations.edit', $station->id) }}" class="btn btn-sm btn-outline-primary" title="এডিট">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm {{ $station->is_locked ? 'btn-warning' : 'btn-outline-warning' }}" onclick="toggleLock({{ $station->id }})" title="{{ $station->is_locked ? 'আনলক করুন' : 'লক করুন' }}">
                                    <i class="fas fa-{{ $station->is_locked ? 'lock-open' : 'lock' }}"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteStation({{ $station->id }})" title="মুছুন">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">কোন পাম্প পাওয়া যায়নি</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($stations->hasPages())
        <div class="card-footer">
            {{ $stations->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
function toggleLock(id) {
    fetch(`/admin/fuel/stations/${id}/toggle-lock`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        }
    })
    .catch(error => alert('একটি ত্রুটি হয়েছে'));
}

function deleteStation(id) {
    if (confirm('আপনি কি এই পাম্পটি মুছে ফেলতে চান? এর সব রিপোর্টও মুছে যাবে।')) {
        fetch(`/admin/fuel/stations/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Delete failed');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('পাম্প মুছে ফেলা হয়েছে');
                location.reload();
            }
        })
        .catch(error => {
            alert('মুছতে সমস্যা হয়েছে। পেজ রিফ্রেশ করে আবার চেষ্টা করুন।');
            console.error('Error:', error);
        });
    }
}
</script>
@endpush
@endsection
