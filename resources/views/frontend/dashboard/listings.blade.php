@extends('frontend.layouts.app')

@section('title', 'আমার তথ্যসমূহ - Satkhira Portal')

@section('content')
<!-- Page Header -->
<section class="page-header py-4" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="text-white mb-0">আমার তথ্যসমূহ</h3>
                <p class="text-white-50 mb-0">আপনার জমা দেওয়া সকল তথ্য এখানে দেখুন এবং পরিচালনা করুন</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('dashboard.listings.create') }}" class="btn btn-light">
                    <i class="fas fa-plus me-1"></i>নতুন তথ্য যোগ করুন
                </a>
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
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=28a745&color=fff&size=100" 
                             alt="{{ auth()->user()->name }}" class="rounded-circle mb-3" width="100" height="100">
                        <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                        <p class="text-muted small mb-0">{{ auth()->user()->email }}</p>
                        <span class="badge bg-success mt-2">{{ auth()->user()->role->display_name ?? 'User' }}</span>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-tachometer-alt me-2"></i>ড্যাশবোর্ড
                        </a>
                        <a href="{{ route('dashboard.listings') }}" class="list-group-item list-group-item-action active">
                            <i class="fas fa-list me-2"></i>আমার তথ্যসমূহ
                        </a>
                        <a href="{{ route('dashboard.my-questions') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-question-circle me-2"></i>আমার প্রশ্নসমূহ
                        </a>
                        <a href="{{ route('dashboard.profile') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-user-edit me-2"></i>প্রোফাইল সম্পাদনা
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-list text-success me-2"></i>আমার সকল তথ্য ({{ $listings->total() }})</h5>
                    </div>
                    <div class="card-body p-0">
                        @if($listings->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 60px;">ছবি</th>
                                            <th>শিরোনাম</th>
                                            <th>ক্যাটাগরি</th>
                                            <th>উপজেলা</th>
                                            <th>স্ট্যাটাস</th>
                                            <th>তারিখ</th>
                                            <th style="width: 120px;">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($listings as $listing)
                                            <tr>
                                                <td>
                                                    <img src="{{ $listing->image ? asset('storage/' . $listing->image) : 'https://picsum.photos/seed/' . $listing->id . '/60/60' }}" 
                                                         alt="{{ $listing->title }}" class="rounded" width="50" height="50" style="object-fit: cover;">
                                                </td>
                                                <td>
                                                    @if($listing->status == 'approved')
                                                        <a href="{{ route('listings.show', $listing) }}" class="text-decoration-none">
                                                            {{ Str::limit($listing->title, 30) }}
                                                        </a>
                                                    @else
                                                        {{ Str::limit($listing->title, 30) }}
                                                    @endif
                                                </td>
                                                <td><span class="badge bg-secondary">{{ $listing->category->name ?? 'N/A' }}</span></td>
                                                <td>{{ $listing->upazila->name ?? 'N/A' }}</td>
                                                <td>
                                                    @if($listing->status == 'pending')
                                                        <span class="badge bg-warning text-dark">অপেক্ষমাণ</span>
                                                    @elseif($listing->status == 'approved')
                                                        <span class="badge bg-success">প্রকাশিত</span>
                                                    @else
                                                        <span class="badge bg-danger">বাতিল</span>
                                                    @endif
                                                </td>
                                                <td>{{ $listing->created_at->format('d M Y') }}</td>
                                                <td>
                                                    <a href="{{ route('dashboard.listings.edit', $listing) }}" class="btn btn-sm btn-outline-primary" title="সম্পাদনা">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('dashboard.listings.destroy', $listing) }}" method="POST" class="d-inline" 
                                                          onsubmit="return confirm('আপনি কি এই তথ্যটি মুছে ফেলতে চান?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="মুছুন">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="p-3">
                                {{ $listings->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                                <h5>কোন তথ্য পাওয়া যায়নি</h5>
                                <p class="text-muted">আপনি এখনও কোন তথ্য যোগ করেননি</p>
                                <a href="{{ route('dashboard.listings.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus me-1"></i>প্রথম তথ্য যোগ করুন
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
