@extends('admin.layouts.app')

@section('title', 'সাবস্ক্রাইবার তালিকা')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">👥 সাবস্ক্রাইবার তালিকা</h4>
        <a href="{{ route('admin.push-notifications.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> ফিরে যান
        </a>
    </div>

    <!-- Device Stats -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body text-center py-3">
                    <h3 class="fw-bold mb-0">{{ $subscribers->total() }}</h3>
                    <small>মোট সাবস্ক্রাইবার</small>
                </div>
            </div>
        </div>
        @foreach($deviceStats as $type => $count)
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-3">
                    <h3 class="fw-bold mb-0">{{ $count }}</h3>
                    <small class="text-muted">
                        @if($type == 'android')
                            <i class="fab fa-android text-success"></i> Android
                        @elseif($type == 'ios')
                            <i class="fab fa-apple"></i> iOS
                        @elseif($type == 'desktop')
                            <i class="fas fa-desktop text-primary"></i> Desktop
                        @else
                            <i class="fas fa-mobile-alt"></i> {{ ucfirst($type) }}
                        @endif
                    </small>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Subscribers Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($subscribers->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-users-slash fs-1 mb-3 d-block"></i>
                    <p>এখনও কোনো সাবস্ক্রাইবার নেই।</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>ডিভাইস</th>
                                <th>ব্রাউজার</th>
                                <th>Endpoint</th>
                                <th>সাবস্ক্রাইব তারিখ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subscribers as $sub)
                            <tr>
                                <td>{{ $subscribers->firstItem() + $loop->index }}</td>
                                <td>
                                    @if($sub->device_type == 'android')
                                        <span class="badge bg-success"><i class="fab fa-android"></i> Android</span>
                                    @elseif($sub->device_type == 'ios')
                                        <span class="badge bg-dark"><i class="fab fa-apple"></i> iOS</span>
                                    @elseif($sub->device_type == 'desktop')
                                        <span class="badge bg-primary"><i class="fas fa-desktop"></i> Desktop</span>
                                    @else
                                        <span class="badge bg-secondary"><i class="fas fa-mobile-alt"></i> {{ ucfirst($sub->device_type) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $sub->browser }}</small>
                                </td>
                                <td>
                                    <small class="text-muted" title="{{ $sub->endpoint }}">
                                        {{ Str::limit($sub->endpoint, 50) }}
                                    </small>
                                </td>
                                <td>
                                    <small>{{ $sub->created_at->format('d M Y, h:i A') }}</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    {{ $subscribers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
