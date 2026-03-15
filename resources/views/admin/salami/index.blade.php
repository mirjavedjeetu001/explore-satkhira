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
            <div class="col-md-4">
                <input type="text" class="form-control" name="search" placeholder="নাম বা ফোন খুঁজুন..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}" placeholder="From">
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}" placeholder="To">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> খুঁজুন
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Entries Table -->
<div class="admin-table">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ব্যবহারকারী</th>
                    <th>মোবাইল</th>
                    <th>সালামিদাতা</th>
                    <th>সম্পর্ক</th>
                    <th>টাকা</th>
                    <th>নোট</th>
                    <th>তারিখ</th>
                    <th width="80">অ্যাকশন</th>
                </tr>
            </thead>
            <tbody>
                @forelse($entries as $entry)
                    <tr>
                        <td>{{ $entry->id }}</td>
                        <td><strong>{{ $entry->user_name }}</strong></td>
                        <td>{{ $entry->phone ?? '-' }}</td>
                        <td>{{ $entry->giver_name }}</td>
                        <td>{{ $entry->giver_relation ?? '-' }}</td>
                        <td><span class="text-success fw-bold">৳{{ number_format($entry->amount) }}</span></td>
                        <td>{{ $entry->note ?? '-' }}</td>
                        <td>{{ $entry->created_at->format('d M, h:i A') }}</td>
                        <td>
                            <form action="{{ route('admin.salami.destroy', $entry->id) }}" method="POST" 
                                  onsubmit="return confirm('আপনি কি নিশ্চিত?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                            কোন ডাটা পাওয়া যায়নি
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($entries->hasPages())
        <div class="p-3 border-top">{{ $entries->links() }}</div>
    @endif
</div>

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
