@extends('admin.layouts.app')

@section('title', 'News & Events')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-newspaper me-2"></i>News & Events</h1>
    <a href="{{ route('admin.news.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-2"></i>Add News
    </a>
</div>

<div class="admin-table">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($news ?? [] as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="" width="50" height="50" class="rounded" style="object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fas fa-newspaper text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ Str::limit($item->title, 40) }}</strong>
                        </td>
                        <td>
                            @if($item->type == 'news')
                                <span class="badge bg-primary">News</span>
                            @elseif($item->type == 'event')
                                <span class="badge bg-success">Event</span>
                            @else
                                <span class="badge bg-warning text-dark">Notice</span>
                            @endif
                        </td>
                        <td>
                            @if($item->is_active)
                                <span class="badge badge-active">Published</span>
                            @else
                                <span class="badge bg-secondary">Draft</span>
                            @endif
                        </td>
                        <td>{{ $item->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.news.edit', $item) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.news.destroy', $item) }}" method="POST" class="d-inline"
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
                        <td colspan="7" class="text-center py-4 text-muted">No news found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if(isset($news) && $news->hasPages())
        <div class="p-3 border-top">{{ $news->links() }}</div>
    @endif
</div>
@endsection
