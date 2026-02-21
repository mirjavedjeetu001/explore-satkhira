@extends('frontend.layouts.app')

@section('title', 'অতিরিক্ত অ্যাক্সেস চাই - Satkhira Portal')

@section('content')
<!-- Page Header -->
<section class="page-header py-4" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="text-white mb-0">অতিরিক্ত অ্যাক্সেস অনুরোধ</h3>
                <p class="text-white-50 mb-0">আরও ফিচার ব্যবহার করতে অনুরোধ পাঠান</p>
            </div>
        </div>
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
                        <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=28a745&color=fff&size=100' }}" 
                             alt="{{ auth()->user()->name }}" class="rounded-circle mb-3" width="100" height="100" style="object-fit: cover;">
                        <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                        <p class="text-muted small mb-0">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-tachometer-alt me-2"></i>ড্যাশবোর্ড
                        </a>
                        <a href="{{ route('dashboard.profile') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-user-edit me-2"></i>প্রোফাইল সম্পাদনা
                        </a>
                        <a href="{{ route('dashboard.request-access') }}" class="list-group-item list-group-item-action active">
                            <i class="fas fa-unlock-alt me-2"></i>অতিরিক্ত অ্যাক্সেস চাই
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <!-- Current Status -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <i class="fas fa-info-circle me-2"></i>আপনার বর্তমান অবস্থা
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-center p-3 border rounded">
                                    <i class="fas fa-comment-dots fa-2x mb-2 {{ auth()->user()->comment_only ? 'text-success' : 'text-muted' }}"></i>
                                    <h6>মন্তব্য করা</h6>
                                    <span class="badge {{ auth()->user()->comment_only ? 'bg-success' : 'bg-secondary' }}">
                                        {{ auth()->user()->comment_only ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 border rounded">
                                    <i class="fas fa-comments fa-2x mb-2 {{ auth()->user()->wants_mp_questions ? 'text-primary' : 'text-muted' }}"></i>
                                    <h6>সাংসদকে প্রশ্ন</h6>
                                    <span class="badge {{ auth()->user()->wants_mp_questions ? 'bg-primary' : 'bg-secondary' }}">
                                        {{ auth()->user()->wants_mp_questions ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 border rounded">
                                    <i class="fas fa-list fa-2x mb-2 {{ auth()->user()->approvedCategories()->count() > 0 ? 'text-warning' : 'text-muted' }}"></i>
                                    <h6>তথ্য যোগ করা</h6>
                                    <span class="badge {{ auth()->user()->approvedCategories()->count() > 0 ? 'bg-warning text-dark' : 'bg-secondary' }}">
                                        {{ auth()->user()->approvedCategories()->count() }} টি ক্যাটাগরি
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Pending Requests -->
                @if($pendingRequests->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <i class="fas fa-clock me-2"></i>অপেক্ষমাণ অনুরোধসমূহ
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($pendingRequests as $cat)
                                <span class="badge bg-warning text-dark p-2">
                                    <i class="{{ $cat->icon ?? 'fas fa-folder' }} me-1"></i>{{ $cat->name_bn ?? $cat->name }}
                                </span>
                            @endforeach
                        </div>
                        <p class="text-muted small mt-2 mb-0">এই ক্যাটাগরিগুলো অনুমোদনের অপেক্ষায় আছে</p>
                    </div>
                </div>
                @endif
                
                <!-- Request Form -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <i class="fas fa-paper-plane me-2"></i>নতুন অ্যাক্সেস অনুরোধ করুন
                    </div>
                    <div class="card-body">
                        <form action="{{ route('dashboard.request-access.store') }}" method="POST">
                            @csrf
                            
                            @if(!auth()->user()->wants_mp_questions)
                            <div class="mb-4">
                                <div class="form-check p-3 border rounded bg-light">
                                    <input type="checkbox" class="form-check-input" name="wants_mp_questions" value="1" id="wantsMpQuestions">
                                    <label class="form-check-label" for="wantsMpQuestions">
                                        <i class="fas fa-comments text-primary me-2"></i>
                                        <strong>সাংসদকে প্রশ্ন করতে চাই</strong>
                                        <br><small class="text-muted">মাননীয় সাংসদের কাছে সরাসরি প্রশ্ন পাঠাতে পারবেন</small>
                                    </label>
                                </div>
                            </div>
                            @endif
                            
                            <div class="mb-4">
                                <label class="form-label"><i class="fas fa-th-large me-2"></i>কোন ক্যাটাগরিতে তথ্য যোগ করতে চান?</label>
                                <p class="text-muted small">যে ক্যাটাগরিগুলোতে তথ্য যোগ করতে চান সেগুলো সিলেক্ট করুন</p>
                                
                                <div class="row g-2">
                                    @foreach($categories as $category)
                                        @php
                                            $isApproved = auth()->user()->approvedCategories()->where('categories.id', $category->id)->exists();
                                            $isPending = $pendingRequests->contains('id', $category->id);
                                        @endphp
                                        <div class="col-md-4 col-6">
                                            <div class="form-check p-2 border rounded {{ $isApproved ? 'bg-success bg-opacity-10 border-success' : ($isPending ? 'bg-warning bg-opacity-10 border-warning' : '') }}">
                                                <input type="checkbox" class="form-check-input" 
                                                       name="categories[]" value="{{ $category->id }}" 
                                                       id="cat_{{ $category->id }}"
                                                       {{ $isApproved || $isPending ? 'disabled' : '' }}>
                                                <label class="form-check-label small" for="cat_{{ $category->id }}">
                                                    <i class="{{ $category->icon ?? 'fas fa-folder' }} me-1"></i>{{ $category->name_bn ?? $category->name }}
                                                    @if($isApproved)
                                                        <span class="badge bg-success ms-1">অনুমোদিত</span>
                                                    @elseif($isPending)
                                                        <span class="badge bg-warning text-dark ms-1">অপেক্ষমাণ</span>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label"><i class="fas fa-edit me-2"></i>কেন এই অ্যাক্সেস চাইছেন? <span class="text-danger">*</span></label>
                                <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" 
                                          rows="4" required 
                                          placeholder="উদাহরণ: আমি সাতক্ষীরা সদরে একটি রেস্তোরাঁ চালাই এবং আমার ব্যবসার তথ্য পোর্টালে যোগ করতে চাই...">{{ old('reason') }}</textarea>
                                @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>অনুরোধ পাঠান
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
