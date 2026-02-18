@extends('admin.layouts.app')

@section('title', 'MP Profiles')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-user-tie me-2"></i>MP Profiles (সংসদ সদস্য)</h1>
    <a href="{{ route('admin.mp-profiles.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-2"></i>Add MP Profile
    </a>
</div>

<div class="admin-table">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Constituency (আসন)</th>
                    <th>Contact</th>
                    <th>Questions</th>
                    <th>Status</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($profiles ?? [] as $mp)
                    <tr>
                        <td>{{ $mp->id }}</td>
                        <td>
                            @if($mp->image)
                                <img src="{{ asset('storage/' . $mp->image) }}" alt="{{ $mp->name }}" 
                                     width="50" height="50" class="rounded-circle" style="object-fit: cover;">
                            @else
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px;">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $mp->name }}</strong>
                            <small class="text-muted d-block">{{ $mp->designation }}</small>
                        </td>
                        <td>{{ $mp->constituency ?? 'N/A' }}</td>
                        <td>
                            @if($mp->phone)
                                <small>{{ $mp->phone }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $mp->questions_count ?? 0 }}</span>
                        </td>
                        <td>
                            @if($mp->is_active)
                                <span class="badge badge-active">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.mp-profiles.edit', $mp) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.mp-profiles.destroy', $mp) }}" method="POST" class="d-inline"
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
                        <td colspan="8" class="text-center py-4 text-muted">No MP profiles found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if(isset($profiles) && $profiles->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $profiles->links() }}
        </div>
    @endif
</div>
@endsection
