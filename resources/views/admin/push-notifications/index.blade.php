@extends('admin.layouts.app')

@section('title', 'পুশ নোটিফিকেশন')

@section('content')
<div class="container-fluid">
    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="fs-1 text-success mb-1">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="fw-bold text-success">{{ $totalSubscribers }}</h3>
                    <small class="text-muted">মোট সাবস্ক্রাইবার</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="fs-1 text-primary mb-1">
                        <i class="fab fa-android"></i>
                    </div>
                    <h3 class="fw-bold text-primary">{{ $deviceStats['android'] ?? 0 }}</h3>
                    <small class="text-muted">Android ইউজার</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="fs-1 text-info mb-1">
                        <i class="fas fa-desktop"></i>
                    </div>
                    <h3 class="fw-bold text-info">{{ $deviceStats['desktop'] ?? 0 }}</h3>
                    <small class="text-muted">Desktop ইউজার</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="fs-1 text-warning mb-1">
                        <i class="fas fa-bell"></i>
                    </div>
                    <h3 class="fw-bold text-warning">{{ $notifications->total() }}</h3>
                    <small class="text-muted">মোট নোটিফিকেশন পাঠানো</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">📲 পুশ নোটিফিকেশন ইতিহাস</h4>
        <div>
            <a href="{{ route('admin.push-notifications.subscribers') }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-users"></i> সাবস্ক্রাইবার তালিকা
            </a>
            <a href="{{ route('admin.push-notifications.create') }}" class="btn btn-success">
                <i class="fas fa-paper-plane"></i> নোটিফিকেশন পাঠান
            </a>
        </div>
    </div>

    <!-- Notifications History Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($notifications->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-bell-slash fs-1 mb-3 d-block"></i>
                    <p>এখনও কোনো নোটিফিকেশন পাঠানো হয়নি।</p>
                    <a href="{{ route('admin.push-notifications.create') }}" class="btn btn-success">
                        <i class="fas fa-paper-plane"></i> প্রথম নোটিফিকেশন পাঠান
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>তারিখ</th>
                                <th>শিরোনাম</th>
                                <th>বিষয়বস্তু</th>
                                <th class="text-center">সফল</th>
                                <th class="text-center">ব্যর্থ</th>
                                <th>পাঠিয়েছেন</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notifications as $n)
                            <tr>
                                <td class="text-nowrap">
                                    <small>{{ $n->created_at->format('d M Y') }}</small><br>
                                    <small class="text-muted">{{ $n->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <strong>{{ $n->title }}</strong>
                                    @if($n->url)
                                        <br><small class="text-muted">{{ $n->url }}</small>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ Str::limit($n->body, 60) }}</small>
                                    @if($n->image)
                                        <br><span class="badge bg-info text-white"><i class="fas fa-image"></i> ছবি সহ</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success">{{ $n->total_sent }}</span>
                                </td>
                                <td class="text-center">
                                    @if($n->total_failed > 0)
                                        <span class="badge bg-danger">{{ $n->total_failed }}</span>
                                    @else
                                        <span class="badge bg-light text-muted">0</span>
                                    @endif
                                </td>
                                <td><small>{{ $n->sender->name ?? 'Unknown' }}</small></td>
                                <td>
                                    <form action="{{ route('admin.push-notifications.destroy', $n) }}" method="POST" onsubmit="return confirm('ডিলিট করবেন?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="ডিলিট">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
