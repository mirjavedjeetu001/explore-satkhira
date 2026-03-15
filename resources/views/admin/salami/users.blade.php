@extends('admin.layouts.app')

@section('title', 'সালামি - ব্যবহারকারী')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-users me-2"></i>🌙 সালামি ব্যবহারকারী</h1>
    <a href="{{ route('admin.salami.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> ফিরে যান
    </a>
</div>

<div class="admin-table">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ব্যবহারকারী</th>
                    <th>মোবাইল</th>
                    <th>এন্ট্রি সংখ্যা</th>
                    <th>মোট সালামি</th>
                    <th>শেষ এন্ট্রি</th>
                    <th width="100">অ্যাকশন</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td><strong>{{ $user->user_name }}</strong></td>
                        <td>{{ $user->phone ?? '-' }}</td>
                        <td><span class="badge bg-primary">{{ $user->entries_count }}</span></td>
                        <td><span class="text-success fw-bold">৳{{ number_format($user->total_amount) }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($user->last_entry)->format('d M, h:i A') }}</td>
                        <td>
                            <a href="{{ route('admin.salami.user-entries', $user->session_id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> বিস্তারিত
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                            কোন ব্যবহারকারী পাওয়া যায়নি
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
@endsection
