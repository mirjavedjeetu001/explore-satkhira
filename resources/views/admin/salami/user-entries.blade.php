@extends('admin.layouts.app')

@section('title', 'সালামি - ' . ($user->user_name ?? 'ব্যবহারকারী'))

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1><i class="fas fa-user me-2"></i>{{ $user->user_name ?? 'ব্যবহারকারী' }}</h1>
        @if($user && $user->phone)
            <p class="text-muted mb-0"><i class="fas fa-phone me-1"></i>{{ $user->phone }}</p>
        @endif
    </div>
    <div>
        <a href="{{ route('admin.salami.users') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> ফিরে যান
        </a>
    </div>
</div>

<!-- Summary Card -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <div class="display-5 mb-2">৳{{ number_format($total) }}</div>
                <div>মোট সালামি</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <div class="display-5 mb-2">{{ $entries->count() }}</div>
                <div>মোট এন্ট্রি</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <div class="display-5 mb-2">৳{{ $entries->count() > 0 ? number_format($total / $entries->count()) : 0 }}</div>
                <div>গড় সালামি</div>
            </div>
        </div>
    </div>
</div>

<div class="admin-table">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>সালামিদাতা</th>
                    <th>সম্পর্ক</th>
                    <th>টাকা</th>
                    <th>নোট</th>
                    <th>তারিখ</th>
                    <th width="80">অ্যাকশন</th>
                </tr>
            </thead>
            <tbody>
                @forelse($entries as $index => $entry)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $entry->giver_name }}</strong></td>
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
                        <td colspan="7" class="text-center py-4 text-muted">
                            কোন এন্ট্রি পাওয়া যায়নি
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if($entries->count() > 0)
            <tfoot class="table-success">
                <tr>
                    <th colspan="3" class="text-end">মোট:</th>
                    <th>৳{{ number_format($total) }}</th>
                    <th colspan="3"></th>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection
