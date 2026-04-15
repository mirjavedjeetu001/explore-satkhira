@extends('admin.layouts.app')
@section('title', 'ভোটার তালিকা - ' . $survey->title)

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h1><i class="fas fa-users me-2"></i>ভোটার তালিকা — {{ $survey->title }}</h1>
    <a href="{{ route('admin.surveys.show', $survey->id) }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>ফলাফলে ফিরুন</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between">
        <span><i class="fas fa-list me-2"></i>মোট ভোটার: {{ $votes->total() }}</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>নাম</th>
                        <th>ফোন</th>
                        <th>ক্লাস</th>
                        <th>বিভাগ/ডিপার্টমেন্ট</th>
                        <th>বর্ষ</th>
                        <th>সেশন</th>
                        <th>ভোট</th>
                        <th>মতামত</th>
                        <th>সময়</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($votes as $vote)
                        <tr>
                            <td>{{ $loop->iteration + ($votes->currentPage() - 1) * $votes->perPage() }}</td>
                            <td>{{ $vote->name }}</td>
                            <td>{{ $vote->phone }}</td>
                            <td>
                                @if($vote->class_type == 'intermediate')
                                    <span class="badge bg-info">ইন্টারমিডিয়েট</span>
                                @elseif($vote->class_type == 'honours')
                                    <span class="badge bg-primary">অনার্স</span>
                                @else
                                    {{ $vote->class_type }}
                                @endif
                            </td>
                            <td>{{ $vote->department ?? '-' }}</td>
                            <td>{{ $vote->year ?? '-' }}</td>
                            <td>{{ $vote->session ?? '-' }}</td>
                            <td><span class="badge bg-dark">{{ $vote->selected_option }}</span></td>
                            <td>{{ $vote->comment ? Str::limit($vote->comment, 30) : '-' }}</td>
                            <td><small>{{ $vote->created_at->format('d/m/y h:i A') }}</small></td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="text-center py-4 text-muted">কোনো ভোট পাওয়া যায়নি।</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($votes->hasPages())
        <div class="card-footer bg-white">{{ $votes->links() }}</div>
    @endif
</div>
@endsection
