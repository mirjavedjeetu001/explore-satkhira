@extends('admin.layouts.app')

@section('title', 'User Details')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-user me-2"></i>User Details</h1>
    <div>
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i>Edit User
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Users
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Basic Info -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-info-circle me-2"></i>Basic Information
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Full Name</label>
                        <p class="fw-semibold mb-0">{{ $user->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Email</label>
                        <p class="fw-semibold mb-0">{{ $user->email }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Phone</label>
                        <p class="fw-semibold mb-0">{{ $user->phone ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">NID Number</label>
                        <p class="fw-semibold mb-0">{{ $user->nid_number ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Upazila</label>
                        <p class="fw-semibold mb-0">{{ $user->upazila->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Role</label>
                        <p class="fw-semibold mb-0">
                            <span class="badge bg-primary">{{ $user->role->display_name ?? 'User' }}</span>
                        </p>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="text-muted small">Address</label>
                        <p class="fw-semibold mb-0">{{ $user->address ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Registration Purpose -->
        @if($user->registration_purpose)
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-question-circle me-2"></i>Registration Purpose
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $user->registration_purpose }}</p>
            </div>
        </div>
        @endif
        
        <!-- MP Questions Interest -->
        @if($user->wants_mp_questions)
        <div class="card mb-4 border-primary">
            <div class="card-body">
                <i class="fas fa-comments fa-2x text-primary me-2 float-start"></i>
                <h5 class="mb-1">সাংসদকে প্রশ্ন করতে চান</h5>
                <p class="text-muted mb-0">This user registered to ask questions to MP (Member of Parliament)</p>
            </div>
        </div>
        @endif
        
        <!-- Category Permissions -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-th-large me-2"></i>Category Permissions</span>
            </div>
            <div class="card-body">
                @if($user->categoryPermissions->count() > 0)
                    <h6 class="text-muted mb-3">Requested Categories:</h6>
                    <div class="row">
                        @foreach($user->categoryPermissions as $cat)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center p-3 border rounded {{ $cat->pivot->is_approved ? 'border-success bg-success-subtle' : 'border-warning bg-warning-subtle' }}">
                                <i class="{{ $cat->icon ?? 'fas fa-folder' }} fa-2x me-3 {{ $cat->pivot->is_approved ? 'text-success' : 'text-warning' }}"></i>
                                <div class="flex-grow-1">
                                    <strong>{{ $cat->name_bn ?? $cat->name }}</strong>
                                    <br>
                                    @if($cat->pivot->is_approved)
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>Approved</span>
                                    @else
                                        <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Pending</span>
                                        <div class="mt-2">
                                            <form action="{{ route('admin.users.approve-category', [$user, $cat]) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.users.reject-category', [$user, $cat]) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Reject this category?')">
                                                    <i class="fas fa-times"></i> Reject
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">No category permissions requested.</p>
                @endif
                
                <hr>
                
                <h6 class="text-muted mb-3">Available Categories (Admin can grant):</h6>
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="name" value="{{ $user->name }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">
                    <input type="hidden" name="role_id" value="{{ $user->role_id }}">
                    <input type="hidden" name="status" value="{{ $user->status }}">
                    
                    <div class="row">
                        @php
                            $approvedCategoryIds = $user->approvedCategories->pluck('id')->toArray();
                        @endphp
                        @foreach($categories as $cat)
                        <div class="col-md-4 col-lg-3 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="approved_categories[]" 
                                       value="{{ $cat->id }}" 
                                       id="cat_{{ $cat->id }}"
                                       {{ in_array($cat->id, $approvedCategoryIds) ? 'checked' : '' }}>
                                <label class="form-check-label" for="cat_{{ $cat->id }}">
                                    <i class="{{ $cat->icon ?? 'fas fa-folder' }} me-1 text-muted"></i>
                                    {{ $cat->name_bn ?? $cat->name }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="fas fa-save me-2"></i>Update Category Permissions
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Status Card -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-shield-alt me-2"></i>Account Status
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    @if($user->status === 'active')
                        <span class="badge bg-success p-3 fs-6"><i class="fas fa-check-circle me-2"></i>Active</span>
                    @elseif($user->status === 'pending')
                        <span class="badge bg-warning p-3 fs-6 text-dark"><i class="fas fa-clock me-2"></i>Pending Approval</span>
                    @else
                        <span class="badge bg-danger p-3 fs-6"><i class="fas fa-ban me-2"></i>Suspended</span>
                    @endif
                </div>
                
                <hr>
                
                <div class="mb-2">
                    <small class="text-muted">Registered:</small>
                    <br>{{ $user->created_at->format('d M, Y h:i A') }}
                </div>
                
                @if($user->approved_at)
                <div class="mb-2">
                    <small class="text-muted">Approved:</small>
                    <br>{{ $user->approved_at->format('d M, Y h:i A') }}
                </div>
                @endif
                
                @if($user->approvedBy)
                <div class="mb-2">
                    <small class="text-muted">Approved By:</small>
                    <br>{{ $user->approvedBy->name }}
                </div>
                @endif
                
                <hr>
                
                <!-- Quick Actions -->
                @if($user->status === 'pending')
                    <form action="{{ route('admin.users.approve', $user) }}" method="POST" class="mb-2">
                        @csrf
                        <input type="hidden" name="approve_type" value="user_only">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-user-check me-2"></i>Approve User Only
                        </button>
                    </form>
                    <form action="{{ route('admin.users.approve', $user) }}" method="POST" class="mb-2">
                        @csrf
                        <input type="hidden" name="approve_type" value="all">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-check-double me-2"></i>Approve User + All Categories
                        </button>
                    </form>
                @endif
                
                @if($user->status !== 'suspended' && $user->id !== auth()->id())
                    <form action="{{ route('admin.users.suspend', $user) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure?')">
                            <i class="fas fa-ban me-2"></i>Suspend User
                        </button>
                    </form>
                @endif
                
                @if($user->status === 'suspended')
                    <form action="{{ route('admin.users.approve', $user) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check me-2"></i>Reactivate User
                        </button>
                    </form>
                @endif
            </div>
        </div>
        
        <!-- Stats -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-bar me-2"></i>User Statistics
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Listings:</span>
                    <strong>{{ $user->listings()->count() }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Approved Listings:</span>
                    <strong>{{ $user->listings()->where('status', 'published')->count() }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Pending Listings:</span>
                    <strong>{{ $user->listings()->where('status', 'pending')->count() }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>MP Questions:</span>
                    <strong>{{ $user->mpQuestions()->count() }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
