@extends('admin.layouts.app')

@section('title', 'Comments Management')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-comments me-2"></i>Comments</h1>
</div>

<!-- Filters -->
<div class="admin-form mb-4">
    <form action="{{ route('admin.comments.index') }}" method="GET" class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-1"></i>Filter</button>
            <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>
</div>

<div class="admin-table">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Comment</th>
                    <th>User</th>
                    <th>Listing / Location</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th width="150">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($comments ?? [] as $comment)
                    <tr>
                        <td>{{ $comment->id }}</td>
                        <td>
                            <div style="max-width: 200px;">
                                {{ Str::limit($comment->content, 50) }}
                            </div>
                        </td>
                        <td>
                            <strong>{{ $comment->name ?? ($comment->user->name ?? 'Anonymous') }}</strong>
                            @if($comment->email)
                                <br><small class="text-muted">{{ $comment->email }}</small>
                            @endif
                        </td>
                        <td>
                            @if($comment->commentable_type == 'App\\Models\\Listing' && $comment->commentable)
                                <a href="{{ route('listings.show', $comment->commentable) }}" target="_blank" class="text-decoration-none">
                                    <strong>{{ Str::limit($comment->commentable->title, 30) }}</strong>
                                </a>
                                <br>
                                <small class="text-primary">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $comment->commentable->upazila->name ?? 'N/A' }}
                                </small>
                                <small class="text-success ms-2">
                                    <i class="fas fa-folder me-1"></i>{{ $comment->commentable->category->name ?? 'N/A' }}
                                </small>
                            @elseif($comment->commentable_type == 'App\\Models\\News')
                                <span class="badge bg-info">News</span>
                                @if($comment->commentable)
                                    <br><small>{{ Str::limit($comment->commentable->title, 30) }}</small>
                                @endif
                            @else
                                <span class="badge bg-secondary">Other</span>
                            @endif
                        </td>
                        <td>
                            @if($comment->status == 'pending')
                                <span class="badge badge-pending">Pending</span>
                            @elseif($comment->status == 'approved')
                                <span class="badge badge-approved">Approved</span>
                            @else
                                <span class="badge badge-rejected">Rejected</span>
                            @endif
                        </td>
                        <td>{{ $comment->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                @if($comment->status == 'pending')
                                    <form action="{{ route('admin.comments.approve', $comment) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.comments.reject', $comment) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-warning" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No comments found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if(isset($comments) && $comments->hasPages())
        <div class="p-3 border-top">{{ $comments->links() }}</div>
    @endif
</div>
@endsection
