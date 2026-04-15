@extends('admin.layouts.app')
@section('title', '📊 সার্ভে ম্যানেজমেন্ট')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h1><i class="fas fa-poll me-2"></i>সার্ভে ম্যানেজমেন্ট</h1>
    <a href="{{ route('admin.surveys.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>নতুন সার্ভে
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body py-3">
                <div class="text-primary fs-4 fw-bold">{{ $surveys->total() }}</div>
                <small class="text-muted">মোট সার্ভে</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body py-3">
                <div class="text-success fs-4 fw-bold">{{ $surveys->where('is_active', true)->count() }}</div>
                <small class="text-muted">সক্রিয়</small>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><i class="fas fa-list me-2"></i>সার্ভে তালিকা</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>শিরোনাম</th>
                        <th>শুরু</th>
                        <th>শেষ</th>
                        <th>ভোট</th>
                        <th>অবস্থা</th>
                        <th>স্ট্যাটাস</th>
                        <th>অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($surveys as $survey)
                        <tr>
                            <td>
                                <strong>{{ $survey->title }}</strong>
                                <br><small class="text-muted">{{ Str::limit($survey->question, 50) }}</small>
                            </td>
                            <td><small>{{ $survey->start_time->format('d M Y, h:i A') }}</small></td>
                            <td><small>{{ $survey->end_time->format('d M Y, h:i A') }}</small></td>
                            <td><span class="badge bg-info">{{ $survey->votes_count }}</span></td>
                            <td>
                                @if($survey->is_live)
                                    <span class="badge bg-success">🟢 চলমান</span>
                                @elseif($survey->is_ended)
                                    <span class="badge bg-secondary">শেষ হয়েছে</span>
                                @elseif($survey->is_upcoming)
                                    <span class="badge bg-warning text-dark">আসন্ন</span>
                                @else
                                    <span class="badge bg-dark">নিষ্ক্রিয়</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $survey->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $survey->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.surveys.show', $survey->id) }}" class="btn btn-sm btn-outline-primary" title="ফলাফল"><i class="fas fa-chart-bar"></i></a>
                                    <a href="{{ route('admin.surveys.edit', $survey->id) }}" class="btn btn-sm btn-outline-warning" title="এডিট"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.surveys.toggle-status', $survey->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm {{ $survey->is_active ? 'btn-outline-secondary' : 'btn-outline-success' }}" title="{{ $survey->is_active ? 'নিষ্ক্রিয় করুন' : 'সক্রিয় করুন' }}">
                                            <i class="fas {{ $survey->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.surveys.votes', $survey->id) }}" class="btn btn-sm btn-outline-info" title="ভোটার তালিকা"><i class="fas fa-users"></i></a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">কোনো সার্ভে নেই।</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($surveys->hasPages())
        <div class="card-footer bg-white">{{ $surveys->links() }}</div>
    @endif
</div>
@endsection
