@extends('admin.layouts.app')

@section('title', 'Listings Management')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-list me-2"></i>Listings Management</h1>
    <a href="{{ route('admin.listings.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-2"></i>Add New Listing
    </a>
</div>

<!-- Filters -->
<div class="admin-form mb-4">
    <form action="{{ route('admin.listings.index') }}" method="GET" class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Search</label>
            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Title">
        </div>
        <div class="col-md-2">
            <label class="form-label">Category</label>
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                @foreach($categories ?? [] as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Upazila</label>
            <select name="upazila" class="form-select">
                <option value="">All Upazilas</option>
                @foreach($upazilas ?? [] as $upazila)
                    <option value="{{ $upazila->id }}" {{ request('upazila') == $upazila->id ? 'selected' : '' }}>{{ $upazila->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i>Filter</button>
            <a href="{{ route('admin.listings.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- Listings Table -->
<div class="admin-table">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Upazila</th>
                    <th>User</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th width="180">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($listings ?? [] as $listing)
                    <tr>
                        <td>{{ $listing->id }}</td>
                        <td>
                            @if($listing->image)
                                <img src="{{ asset('storage/' . $listing->image) }}" alt="{{ $listing->title }}" 
                                     width="50" height="50" class="rounded" style="object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ Str::limit($listing->title, 30) }}</strong>
                            @if($listing->is_featured)
                                <span class="badge bg-warning text-dark ms-1"><i class="fas fa-star"></i></span>
                            @endif
                        </td>
                        <td><span class="badge bg-info">{{ $listing->category->name ?? 'N/A' }}</span></td>
                        <td>{{ $listing->upazila->name ?? 'N/A' }}</td>
                        <td>{{ $listing->user->name ?? 'Admin' }}</td>
                        <td>
                            @if($listing->status == 'pending')
                                <span class="badge badge-pending">Pending</span>
                            @elseif($listing->status == 'approved')
                                <span class="badge badge-approved">Approved</span>
                            @else
                                <span class="badge badge-rejected">Rejected</span>
                            @endif
                        </td>
                        <td>{{ $listing->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                @if($listing->status == 'pending')
                                    <form action="{{ route('admin.listings.approve', $listing) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.listings.reject', $listing) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-warning" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('listings.show', $listing) }}" class="btn btn-info" title="View" target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.listings.edit', $listing) }}" class="btn btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.listings.destroy', $listing) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this listing?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="fas fa-list fa-3x mb-3 d-block"></i>
                            No listings found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if(isset($listings) && $listings->hasPages())
        <div class="p-3 border-top">
            {{ $listings->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
