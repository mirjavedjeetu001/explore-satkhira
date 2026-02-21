@extends('admin.layouts.app')

@section('title', 'Users Management')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-users me-2"></i>Users Management</h1>
    <a href="{{ route('admin.users.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-2"></i>Add New User
    </a>
</div>

<!-- Filters -->
<div class="admin-form mb-4">
    <form action="{{ route('admin.users.index') }}" method="GET">
        <div class="row g-3">
            <div class="col-lg-3 col-md-6">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Name, Email or Phone">
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="form-label">Role</label>
                <select name="role" class="form-select">
                    <option value="">All Roles</option>
                    @foreach($roles ?? [] as $role)
                        <option value="{{ $role->slug }}" {{ request('role') == $role->slug ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="form-label">মডারেটর টাইপ</label>
                <select name="moderator_type" class="form-select">
                    <option value="">All Types</option>
                    <option value="upazila_moderator" {{ request('moderator_type') == 'upazila_moderator' ? 'selected' : '' }}>✅ উপজেলা মডারেটর</option>
                    <option value="own_business_moderator" {{ request('moderator_type') == 'own_business_moderator' ? 'selected' : '' }}>✅ ব্যবসা মডারেটর</option>
                    <option value="wants_upazila" {{ request('moderator_type') == 'wants_upazila' ? 'selected' : '' }}>⏳ উপজেলা আবেদন</option>
                    <option value="wants_own_business" {{ request('moderator_type') == 'wants_own_business' ? 'selected' : '' }}>⏳ ব্যবসা আবেদন</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="form-label">Upazila</label>
                <select name="upazila" class="form-select">
                    <option value="">All Upazilas</option>
                    @foreach($upazilas ?? [] as $upazila)
                        <option value="{{ $upazila->id }}" {{ request('upazila') == $upazila->id ? 'selected' : '' }}>{{ $upazila->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-1 col-md-12 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i></button>
            </div>
        </div>
        <div class="mt-2 text-end">
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">Reset Filters</a>
        </div>
    </form>
</div>

<!-- Users Table -->
<div class="admin-table">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>অনুমোদিত মডারেটর</th>
                    <th>Upazila</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th width="150">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users ?? [] as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=28a745&color=fff&size=35' }}" 
                                     alt="{{ $user->name }}" class="rounded-circle me-2" width="35" height="35">
                                {{ $user->name }}
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-info">{{ $user->role->name ?? 'N/A' }}</span>
                        </td>
                        <td>
                            @if($user->is_upazila_moderator)
                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>উপজেলা মডারেটর</span>
                            @elseif($user->wants_upazila_moderator)
                                <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>উপজেলা আবেদন</span>
                            @endif
                            @if($user->is_own_business_moderator)
                                <span class="badge bg-primary"><i class="fas fa-check-circle me-1"></i>ব্যবসা মডারেটর</span>
                            @elseif($user->wants_own_business_moderator)
                                <span class="badge bg-info text-dark"><i class="fas fa-clock me-1"></i>ব্যবসা আবেদন</span>
                            @endif
                            @if($user->comment_only)
                                <span class="badge bg-secondary"><i class="fas fa-comment-dots me-1"></i>শুধু মন্তব্য</span>
                            @endif
                            @if(!$user->is_upazila_moderator && !$user->wants_upazila_moderator && !$user->is_own_business_moderator && !$user->wants_own_business_moderator && !$user->comment_only)
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $user->upazila->name ?? 'N/A' }}</td>
                        <td>
                            @if($user->status == 'pending')
                                <span class="badge badge-pending">Pending</span>
                            @elseif($user->status == 'active')
                                <span class="badge badge-active">Active</span>
                            @else
                                <span class="badge badge-suspended">Suspended</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($user->status == 'pending')
                                    <form action="{{ route('admin.users.approve', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->id != auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this user?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="fas fa-users fa-3x mb-3 d-block"></i>
                            No users found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if(isset($users) && $users->hasPages())
        <div class="p-3 border-top">
            {{ $users->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
