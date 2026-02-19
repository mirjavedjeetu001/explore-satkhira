@extends('admin.layouts.app')

@section('title', isset($user) ? 'Edit User' : 'Add New User')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-user me-2"></i>{{ isset($user) ? 'Edit User' : 'Add New User' }}</h1>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Users
    </a>
</div>

<div class="admin-form">
    <form action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST">
        @csrf
        @if(isset($user))
            @method('PUT')
        @endif
        
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name', $user->name ?? '') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                       value="{{ old('email', $user->email ?? '') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                       value="{{ old('phone', $user->phone ?? '') }}">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Password {{ isset($user) ? '(Leave blank to keep current)' : '' }} <span class="text-danger">{{ isset($user) ? '' : '*' }}</span></label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                       {{ isset($user) ? '' : 'required' }}>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Role <span class="text-danger">*</span></label>
                <select name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                    <option value="">Select Role</option>
                    @foreach($roles ?? [] as $role)
                        <option value="{{ $role->id }}" {{ old('role_id', $user->role_id ?? '') == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="pending" {{ old('status', $user->status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="active" {{ old('status', $user->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="suspended" {{ old('status', $user->status ?? '') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-12">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $user->address ?? '') }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            @if(isset($user))
                <!-- Upazila Permissions -->
                <div class="col-12">
                    <label class="form-label"><i class="fas fa-map-marker-alt text-success me-2"></i>Upazila Permissions</label>
                    <p class="text-muted small mb-2">Select which upazilas this user can add listings to (Admin/Super Admin has access to all):</p>
                    
                    <div class="row">
                        @php
                            $assignedUpazilaIds = isset($user) ? $user->assignedUpazilas->pluck('id')->toArray() : [];
                        @endphp
                        @foreach($upazilas ?? [] as $upazila)
                            <div class="col-md-4 col-lg-3 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           name="assigned_upazilas[]" 
                                           value="{{ $upazila->id }}" 
                                           id="upazila_{{ $upazila->id }}"
                                           {{ in_array($upazila->id, old('assigned_upazilas', $assignedUpazilaIds)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="upazila_{{ $upazila->id }}">
                                        <i class="fas fa-map-pin me-1 text-muted"></i>
                                        {{ $upazila->name_bn ?? $upazila->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if($user->registration_purpose)
                <div class="col-12">
                    <label class="form-label">Registration Purpose</label>
                    <div class="alert alert-light border">
                        <i class="fas fa-quote-left text-muted me-2"></i>
                        {{ $user->registration_purpose }}
                    </div>
                </div>
                @endif
                
                @if($user->categoryPermissions->count() > 0 || isset($categories))
                <div class="col-12">
                    <label class="form-label">Category Permissions</label>
                    <p class="text-muted small mb-2">Select which categories this user can add listings to:</p>
                    
                    @if($user->categoryPermissions->count() > 0)
                    <div class="alert alert-info mb-2">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Requested Categories:</strong>
                        @foreach($user->categoryPermissions as $cat)
                            <span class="badge {{ $cat->pivot->is_approved ? 'bg-success' : 'bg-warning' }} ms-1">
                                {{ $cat->name_bn ?? $cat->name }}
                                @if($cat->pivot->is_approved)
                                    <i class="fas fa-check ms-1"></i>
                                @endif
                            </span>
                        @endforeach
                    </div>
                    @endif
                    
                    <div class="row">
                        @php
                            $userCategoryIds = $user->categoryPermissions->pluck('id')->toArray();
                            $approvedCategoryIds = $user->approvedCategories->pluck('id')->toArray();
                        @endphp
                        @foreach($categories ?? [] as $cat)
                            <div class="col-md-4 col-lg-3 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           name="approved_categories[]" 
                                           value="{{ $cat->id }}" 
                                           id="cat_{{ $cat->id }}"
                                           {{ in_array($cat->id, old('approved_categories', $approvedCategoryIds)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cat_{{ $cat->id }}">
                                        <i class="{{ $cat->icon ?? 'fas fa-folder' }} me-1 text-muted"></i>
                                        {{ $cat->name_bn ?? $cat->name }}
                                        @if(in_array($cat->id, $userCategoryIds))
                                            <span class="badge bg-secondary">Requested</span>
                                        @endif
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            @endif
            
            <div class="col-12">
                <hr>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-2"></i>{{ isset($user) ? 'Update User' : 'Create User' }}
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
