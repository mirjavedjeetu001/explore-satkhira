@extends('frontend.layouts.app')

@section('title', 'প্রোফাইল সম্পাদনা - Satkhira Portal')

@section('content')
<!-- Page Header -->
<section class="page-header py-4" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
    <div class="container">
        <h3 class="text-white mb-0">প্রোফাইল সম্পাদনা</h3>
        <p class="text-white-50 mb-0">আপনার প্রোফাইল তথ্য আপডেট করুন</p>
    </div>
</section>

<!-- Dashboard Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-4">
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=28a745&color=fff&size=100' }}" 
                             alt="{{ $user->name }}" class="rounded-circle mb-3" width="100" height="100" style="object-fit: cover;">
                        <h5 class="mb-1">{{ $user->name }}</h5>
                        <p class="text-muted small mb-0">{{ $user->email }}</p>
                        @if($user->is_upazila_moderator)
                            <span class="badge bg-warning text-dark mt-2"><i class="fas fa-shield-alt me-1"></i>উপজেলা মডারেটর</span>
                        @elseif($user->is_own_business_moderator)
                            <span class="badge bg-info mt-2"><i class="fas fa-store me-1"></i>নিজস্ব ব্যবসা মডারেটর</span>
                        @else
                            <span class="badge bg-success mt-2">{{ $user->role->display_name ?? 'User' }}</span>
                        @endif
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-tachometer-alt me-2"></i>ড্যাশবোর্ড
                        </a>
                        @if(!auth()->user()->comment_only)
                            <a href="{{ route('dashboard.listings') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-list me-2"></i>আমার তথ্যসমূহ
                            </a>
                        @endif
                        @if(auth()->user()->wants_mp_questions)
                            <a href="{{ route('dashboard.my-questions') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-question-circle me-2"></i>আমার প্রশ্নসমূহ
                            </a>
                        @endif
                        <a href="{{ route('dashboard.profile') }}" class="list-group-item list-group-item-action active">
                            <i class="fas fa-user-edit me-2"></i>প্রোফাইল সম্পাদনা
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9">
                <!-- Profile Form -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-user text-success me-2"></i>প্রোফাইল তথ্য</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('dashboard.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">নাম <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">ইমেইল <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">মোবাইল নম্বর</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="upazila" class="form-label">উপজেলা</label>
                                    <input type="text" class="form-control" value="{{ $user->upazila->name_bn ?? $user->upazila->name ?? 'নির্ধারিত নয়' }}" disabled>
                                    <small class="text-muted">উপজেলা পরিবর্তন করতে অ্যাডমিনের সাথে যোগাযোগ করুন</small>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label">ঠিকানা</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="2">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="bio" class="form-label">সংক্ষিপ্ত পরিচিতি</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror" 
                                          id="bio" name="bio" rows="3">{{ old('bio', $user->bio) }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="avatar" class="form-label">প্রোফাইল ছবি</label>
                                <input type="file" class="form-control @error('avatar') is-invalid @enderror" 
                                       id="avatar" name="avatar" accept="image/*">
                                <small class="text-muted">সর্বোচ্চ 2MB, JPG/PNG ফরম্যাট</small>
                                @error('avatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i>প্রোফাইল আপডেট করুন
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Password Change -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-lock text-warning me-2"></i>পাসওয়ার্ড পরিবর্তন</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('dashboard.password') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="current_password" class="form-label">বর্তমান পাসওয়ার্ড <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                           id="current_password" name="current_password" required>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="password" class="form-label">নতুন পাসওয়ার্ড <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="password_confirmation" class="form-label">পাসওয়ার্ড নিশ্চিত করুন <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-key me-1"></i>পাসওয়ার্ড পরিবর্তন করুন
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
