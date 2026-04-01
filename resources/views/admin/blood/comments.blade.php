@extends('admin.layouts.app')

@section('title', '🩸 রক্তদাতা মন্তব্য')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-comments me-2 text-info"></i>রক্তদাতা মন্তব্যসমূহ</h1>
    <a href="{{ route('admin.blood.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>ফিরে যান</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><i class="fas fa-list me-2"></i>মন্তব্য তালিকা ({{ $comments->total() }})</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>ডোনর</th>
                        <th>মন্তব্যকারী</th>
                        <th>মন্তব্য</th>
                        <th>স্ট্যাটাস</th>
                        <th>তারিখ</th>
                        <th>অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($comments as $comment)
                        <tr>
                            <td>{{ $comment->id }}</td>
                            <td>
                                @if($comment->donor)
                                    <a href="{{ route('admin.blood.show', $comment->blood_donor_id) }}">
                                        <span class="badge bg-danger">{{ $comment->donor->blood_group }}</span>
                                        {{ $comment->donor->name }}
                                    </a>
                                @else
                                    <span class="text-muted">মুছে ফেলা হয়েছে</span>
                                @endif
                            </td>
                            <td>
                                {{ $comment->name }}
                                @if($comment->phone) <br><small class="text-muted">{{ $comment->phone }}</small> @endif
                            </td>
                            <td style="max-width: 300px;">{{ Str::limit($comment->comment, 100) }}</td>
                            <td><span class="badge {{ $comment->status === 'approved' ? 'bg-success' : ($comment->status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">{{ $comment->status }}</span></td>
                            <td>{{ $comment->created_at->format('d M, Y') }}<br><small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small></td>
                            <td>
                                <form action="{{ route('admin.blood.comments.delete', $comment->id) }}" method="POST" onsubmit="return confirm('মন্তব্য মুছে ফেলতে চান?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">কোনো মন্তব্য পাওয়া যায়নি।</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($comments->hasPages())
        <div class="card-footer bg-white">{{ $comments->links() }}</div>
    @endif
</div>
@endsection
