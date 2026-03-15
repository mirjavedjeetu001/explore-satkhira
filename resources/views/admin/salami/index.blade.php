@extends('admin.layouts.app')

@section('title', 'সালামি ক্যালকুলেটর')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h1><i class="fas fa-calculator me-2"></i>🌙 সালামি ক্যালকুলেটর</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.salami.users') }}" class="btn btn-outline-primary">
            <i class="fas fa-users me-1"></i> ব্যবহারকারী
        </a>
        <a href="{{ route('admin.salami.export') }}" class="btn btn-outline-success">
            <i class="fas fa-download me-1"></i> Export CSV
        </a>
        <form action="{{ route('admin.salami.toggle-status') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn {{ $settings->is_enabled ? 'btn-danger' : 'btn-success' }}">
                <i class="fas fa-power-off me-1"></i>
                {{ $settings->is_enabled ? 'বন্ধ করুন' : 'চালু করুন' }}
            </button>
        </form>
    </div>
</div>

<!-- Status Alert -->
<div class="alert {{ $settings->is_enabled ? 'alert-success' : 'alert-warning' }} mb-4">
    <i class="fas {{ $settings->is_enabled ? 'fa-check-circle' : 'fa-exclamation-triangle' }} me-2"></i>
    সালামি ক্যালকুলেটর বর্তমানে <strong>{{ $settings->is_enabled ? 'চালু' : 'বন্ধ' }}</strong> আছে।
    @if($settings->is_enabled)
        <a href="{{ route('salami.index') }}" target="_blank" class="alert-link ms-2">
            <i class="fas fa-external-link-alt"></i> দেখুন
        </a>
    @endif
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 col-6 mb-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body text-center">
                <div class="display-6 mb-2">{{ number_format($stats['total_entries']) }}</div>
                <div class="small">মোট এন্ট্রি</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body text-center">
                <div class="display-6 mb-2">৳{{ number_format($stats['total_amount']) }}</div>
                <div class="small">মোট টাকা</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card bg-info text-white h-100">
            <div class="card-body text-center">
                <div class="display-6 mb-2">{{ number_format($stats['unique_users']) }}</div>
                <div class="small">ব্যবহারকারী</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card bg-warning text-dark h-100">
            <div class="card-body text-center">
                <div class="display-6 mb-2">{{ number_format($stats['today_entries']) }}</div>
                <div class="small">আজকের এন্ট্রি (৳{{ number_format($stats['today_amount']) }})</div>
            </div>
        </div>
    </div>
</div>

<!-- Settings Card -->
<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-cog me-2"></i>সেটিংস
    </div>
    <div class="card-body">
        <form action="{{ route('admin.salami.update-settings') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">শিরোনাম</label>
                    <input type="text" class="form-control" name="title" value="{{ $settings->title }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">বর্ণনা</label>
                    <input type="text" class="form-control" name="description" value="{{ $settings->description }}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> সংরক্ষণ করুন
            </button>
        </form>
    </div>
</div>

<!-- Search & Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form action="" method="GET" class="row g-3">
            <div class="col-md-6">
                <input type="text" class="form-control" name="search" placeholder="নাম বা ফোন খুঁজুন..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}" placeholder="From">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> খুঁজুন
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Users Table (Grouped) -->
<div class="admin-table">
    <div class="table-responsive">
        <table class="table table-hover" id="usersTable">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>ব্যবহারকারী</th>
                    <th>মোবাইল</th>
                    <th>এন্ট্রি</th>
                    <th>মোট টাকা</th>
                    <th>সর্বশেষ</th>
                    <th width="150">অ্যাকশন</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                    <tr class="user-row" data-phone="{{ $user->phone }}">
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:35px;height:35px;font-size:14px;">
                                    {{ mb_substr($user->user_name, 0, 1) }}
                                </div>
                                <strong>{{ $user->user_name }}</strong>
                            </div>
                        </td>
                        <td><code>{{ $user->phone }}</code></td>
                        <td><span class="badge bg-info">{{ $user->entries_count }} জন</span></td>
                        <td><span class="text-success fw-bold fs-5">৳{{ number_format($user->total_amount) }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($user->last_entry)->format('d M, h:i A') }}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary toggle-details" data-phone="{{ $user->phone }}">
                                <i class="fas fa-eye me-1"></i> বিস্তারিত
                            </button>
                            <form action="{{ route('admin.salami.destroy-user', $user->phone) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('এই ব্যবহারকারীর সকল এন্ট্রি মুছে ফেলতে চান?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <!-- Hidden Details Row -->
                    <tr class="details-row d-none" id="details-{{ $user->phone }}">
                        <td colspan="7" class="bg-light p-0">
                            <div class="p-3">
                                <h6 class="mb-3"><i class="fas fa-list me-2"></i>{{ $user->user_name }} এর সালামির তালিকা</h6>
                                <table class="table table-sm table-bordered mb-0 bg-white">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th>সালামিদাতা</th>
                                            <th>সম্পর্ক</th>
                                            <th>টাকা</th>
                                            <th>নোট</th>
                                            <th>তারিখ</th>
                                            <th width="60">মুছুন</th>
                                        </tr>
                                    </thead>
                                    <tbody class="entries-body" data-phone="{{ $user->phone }}">
                                        <tr>
                                            <td colspan="6" class="text-center py-3">
                                                <i class="fas fa-spinner fa-spin"></i> লোড হচ্ছে...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                            কোন ডাটা পাওয়া যায়নি
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($users->hasPages())
        <div class="p-3 border-top">{{ $users->links() }}</div>
    @endif
</div>

<style>
.details-row td {
    border-left: 4px solid #0d6efd;
}
.avatar-sm {
    font-weight: bold;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle details
    document.querySelectorAll('.toggle-details').forEach(btn => {
        btn.addEventListener('click', async function() {
            const phone = this.dataset.phone;
            const detailsRow = document.getElementById('details-' + phone);
            const entriesBody = detailsRow.querySelector('.entries-body');
            
            // Toggle visibility
            detailsRow.classList.toggle('d-none');
            
            // Update button text
            if (detailsRow.classList.contains('d-none')) {
                this.innerHTML = '<i class="fas fa-eye me-1"></i> বিস্তারিত';
            } else {
                this.innerHTML = '<i class="fas fa-eye-slash me-1"></i> লুকান';
                
                // Load entries if not loaded
                if (entriesBody.dataset.loaded !== 'true') {
                    try {
                        const response = await fetch(`/admin/salami/entries/${phone}`);
                        const data = await response.json();
                        
                        let html = '';
                        data.entries.forEach(entry => {
                            html += `
                                <tr>
                                    <td><strong>${entry.giver_name}</strong></td>
                                    <td>${entry.giver_relation || '-'}</td>
                                    <td><span class="text-success fw-bold">৳${Number(entry.amount).toLocaleString()}</span></td>
                                    <td>${entry.note || '-'}</td>
                                    <td>${entry.created_at}</td>
                                    <td>
                                        <button class="btn btn-sm btn-danger delete-entry" data-id="${entry.id}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                        
                        entriesBody.innerHTML = html;
                        entriesBody.dataset.loaded = 'true';
                    } catch (error) {
                        entriesBody.innerHTML = '<tr><td colspan="6" class="text-danger text-center">লোড করতে সমস্যা হয়েছে</td></tr>';
                    }
                }
            }
        });
    });
    
    // Delete single entry
    document.addEventListener('click', async function(e) {
        if (e.target.closest('.delete-entry')) {
            const btn = e.target.closest('.delete-entry');
            const id = btn.dataset.id;
            
            if (!confirm('আপনি কি নিশ্চিত?')) return;
            
            try {
                const response = await fetch(`/admin/salami/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    btn.closest('tr').remove();
                    location.reload(); // Refresh to update counts
                }
            } catch (error) {
                alert('মুছতে সমস্যা হয়েছে');
            }
        }
    });
});
</script>
@endpush

<!-- Clear All Button -->
@if($stats['total_entries'] > 0)
<div class="mt-4 text-end">
    <form action="{{ route('admin.salami.clear-all') }}" method="POST" 
          onsubmit="return confirm('আপনি কি সকল ডাটা মুছে ফেলতে চান? এটি পূর্বাবস্থায় ফেরানো যাবে না!')">
        @csrf
        <button type="submit" class="btn btn-outline-danger">
            <i class="fas fa-trash-alt me-1"></i> সকল ডাটা মুছুন
        </button>
    </form>
</div>
@endif
@endsection
