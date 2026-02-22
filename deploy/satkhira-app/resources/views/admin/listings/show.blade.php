@extends('admin.layouts.app')

@section('title', 'View Listing')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">View Listing</h1>
        <div>
            @if($listing->status === 'approved')
                <a href="{{ route('listings.show', $listing) }}" class="btn btn-info" target="_blank">
                    <i class="fas fa-external-link-alt me-1"></i> View on Website
                </a>
            @endif
            <a href="{{ route('admin.listings.edit', $listing) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <a href="{{ route('admin.listings.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="mb-4">
        @if($listing->status == 'approved')
            <span class="badge bg-success fs-6"><i class="fas fa-check-circle me-1"></i> Approved</span>
            @if($listing->approved_at)
                <small class="text-muted ms-2">on {{ $listing->approved_at->format('d M Y, h:i A') }}</small>
            @endif
        @elseif($listing->status == 'pending')
            <span class="badge bg-warning fs-6"><i class="fas fa-clock me-1"></i> Pending Approval</span>
        @elseif($listing->status == 'rejected')
            <span class="badge bg-danger fs-6"><i class="fas fa-times-circle me-1"></i> Rejected</span>
            @if($listing->rejection_reason)
                <div class="alert alert-danger mt-2">
                    <strong>Rejection Reason:</strong> {{ $listing->rejection_reason }}
                </div>
            @endif
        @endif
        
        @if($listing->is_featured)
            <span class="badge bg-primary fs-6 ms-2"><i class="fas fa-star me-1"></i> Featured</span>
        @endif
    </div>

    <!-- Action Buttons for Pending -->
    @if($listing->status == 'pending')
    <div class="card mb-4 border-warning">
        <div class="card-body">
            <h5 class="card-title text-warning"><i class="fas fa-exclamation-triangle me-2"></i>Pending Actions</h5>
            <div class="d-flex gap-2">
                <form action="{{ route('admin.listings.approve', $listing) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i> Approve Listing
                    </button>
                </form>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="fas fa-times me-1"></i> Reject Listing
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Duplicate Check Section -->
    @php
        $duplicates = $listing->getDuplicates();
        $hasPhoneDuplicates = $duplicates['exact_phone']->isNotEmpty();
        $hasTitleDuplicates = $duplicates['similar_title']->isNotEmpty();
        $hasDuplicates = $hasPhoneDuplicates || $hasTitleDuplicates;
    @endphp
    
    @if($hasDuplicates)
    <div class="card mb-4 border-{{ $hasPhoneDuplicates ? 'danger' : 'warning' }}">
        <div class="card-header bg-{{ $hasPhoneDuplicates ? 'danger' : 'warning' }} text-{{ $hasPhoneDuplicates ? 'white' : 'dark' }}">
            <h5 class="mb-0">
                <i class="fas {{ $hasPhoneDuplicates ? 'fa-copy' : 'fa-question-circle' }} me-2"></i>
                {{ $hasPhoneDuplicates ? '⚠️ সম্ভাব্য ডুপ্লিকেট!' : 'একই রকম তথ্য পাওয়া গেছে' }}
            </h5>
        </div>
        <div class="card-body">
            @if($hasPhoneDuplicates)
            <div class="mb-3">
                <h6 class="text-danger"><i class="fas fa-phone me-1"></i> একই ফোন নম্বর ({{ $listing->phone }}):</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0">
                        <thead class="table-danger">
                            <tr>
                                <th>শিরোনাম</th>
                                <th>ক্যাটাগরি</th>
                                <th>উপজেলা</th>
                                <th>স্ট্যাটাস</th>
                                <th>যুক্ত করেছেন</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($duplicates['exact_phone'] as $dup)
                            <tr>
                                <td>{{ $dup->title }}</td>
                                <td>{{ $dup->category->name ?? '-' }}</td>
                                <td>{{ $dup->upazila->name ?? 'সকল' }}</td>
                                <td>
                                    @if($dup->status == 'approved')
                                        <span class="badge bg-success">অনুমোদিত</span>
                                    @elseif($dup->status == 'pending')
                                        <span class="badge bg-warning">পেন্ডিং</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $dup->status }}</span>
                                    @endif
                                </td>
                                <td>{{ $dup->user->name ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('admin.listings.show', $dup) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($dup->status == 'approved')
                                        <a href="{{ route('listings.show', $dup) }}" class="btn btn-sm btn-outline-success" target="_blank">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
            
            @if($hasTitleDuplicates)
            <div>
                <h6 class="text-warning"><i class="fas fa-heading me-1"></i> একই রকম শিরোনাম:</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0">
                        <thead class="table-warning">
                            <tr>
                                <th>শিরোনাম</th>
                                <th>ফোন</th>
                                <th>ক্যাটাগরি</th>
                                <th>স্ট্যাটাস</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($duplicates['similar_title'] as $dup)
                            <tr>
                                <td>{{ $dup->title }}</td>
                                <td>{{ $dup->phone ?? '-' }}</td>
                                <td>{{ $dup->category->name ?? '-' }}</td>
                                <td>
                                    @if($dup->status == 'approved')
                                        <span class="badge bg-success">অনুমোদিত</span>
                                    @elseif($dup->status == 'pending')
                                        <span class="badge bg-warning">পেন্ডিং</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $dup->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.listings.show', $dup) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
            
            @if($listing->status == 'pending' && $hasPhoneDuplicates)
            <div class="mt-3 pt-3 border-top">
                <p class="text-muted mb-2"><i class="fas fa-info-circle me-1"></i> এটি ডুপ্লিকেট হলে, "Duplicate/ডুপ্লিকেট" কারণ দিয়ে Reject করুন।</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Basic Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Basic Information</h5>
                </div>
                <div class="card-body">
                    <h3 class="mb-2">{{ $listing->title }}</h3>
                    @if($listing->title_bn)
                        <h5 class="text-muted mb-3">{{ $listing->title_bn }}</h5>
                    @endif
                    
                    @if($listing->short_description)
                        <p class="lead">{{ $listing->short_description }}</p>
                    @endif
                    
                    @if($listing->description)
                        <hr>
                        <div class="description">
                            {!! nl2br(e($listing->description)) !!}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Gallery Images -->
            @if($listing->image || ($listing->gallery && count($listing->gallery) > 0))
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-images me-2"></i>Images</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @if($listing->image)
                            <div class="col-md-6">
                                <img src="{{ asset('storage/' . $listing->image) }}" class="img-fluid rounded shadow" alt="Main Image">
                                <small class="text-muted d-block mt-1">Main Image</small>
                            </div>
                        @endif
                        @if($listing->gallery)
                            @foreach($listing->gallery as $img)
                                <div class="col-md-4">
                                    <img src="{{ asset('storage/' . $img) }}" class="img-fluid rounded shadow" alt="Gallery Image">
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Features -->
            @if($listing->features && count($listing->features) > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list-check me-2"></i>Features</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($listing->features as $feature)
                            <div class="col-md-6 mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>{{ $feature }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Comments -->
            @if($listing->comments && $listing->comments->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-comments me-2"></i>Recent Comments ({{ $listing->comments->count() }})</h5>
                </div>
                <div class="card-body">
                    @foreach($listing->comments as $comment)
                        <div class="d-flex mb-3 pb-3 border-bottom">
                            <div class="flex-shrink-0">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name ?? 'User') }}&background=random" 
                                     class="rounded-circle" width="40" height="40" alt="">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ $comment->user->name ?? 'Anonymous' }}</h6>
                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                <p class="mb-0 mt-2">{{ $comment->content }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Meta Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Category & Location</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="40%">Category:</th>
                            <td>
                                @if($listing->category)
                                    <span class="badge" style="background-color: {{ $listing->category->color ?? '#28a745' }}">
                                        <i class="{{ $listing->category->icon ?? 'fas fa-tag' }} me-1"></i>
                                        {{ $listing->category->name }}
                                    </span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Upazila:</th>
                            <td>{{ $listing->upazila->name ?? 'All Upazilas' }}</td>
                        </tr>
                        <tr>
                            <th>Views:</th>
                            <td><i class="fas fa-eye text-muted me-1"></i>{{ number_format($listing->views) }}</td>
                        </tr>
                        <tr>
                            <th>Created:</th>
                            <td>{{ $listing->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Updated:</th>
                            <td>{{ $listing->updated_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-address-card me-2"></i>Contact Information</h5>
                </div>
                <div class="card-body">
                    @if($listing->address)
                        <p class="mb-2"><i class="fas fa-map-marker-alt text-danger me-2"></i>{{ $listing->address }}</p>
                    @endif
                    @if($listing->phone)
                        <p class="mb-2"><i class="fas fa-phone text-success me-2"></i>{{ $listing->phone }}</p>
                    @endif
                    @if($listing->email)
                        <p class="mb-2"><i class="fas fa-envelope text-primary me-2"></i>{{ $listing->email }}</p>
                    @endif
                    @if($listing->website)
                        <p class="mb-2"><i class="fas fa-globe text-info me-2"></i><a href="{{ $listing->website }}" target="_blank">{{ $listing->website }}</a></p>
                    @endif
                    @if($listing->facebook)
                        <p class="mb-2"><i class="fab fa-facebook text-primary me-2"></i><a href="{{ $listing->facebook }}" target="_blank">Facebook</a></p>
                    @endif
                    @if($listing->youtube)
                        <p class="mb-2"><i class="fab fa-youtube text-danger me-2"></i><a href="{{ $listing->youtube }}" target="_blank">YouTube</a></p>
                    @endif
                    @if(!$listing->address && !$listing->phone && !$listing->email && !$listing->website && !$listing->facebook && !$listing->youtube)
                        <p class="text-muted mb-0">No contact information provided.</p>
                    @endif
                </div>
            </div>

            <!-- Submitted By -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Submitted By</h5>
                </div>
                <div class="card-body">
                    @if($listing->user)
                        <div class="d-flex align-items-center">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($listing->user->name) }}&background=28a745&color=fff" 
                                 class="rounded-circle me-3" width="50" height="50" alt="">
                            <div>
                                <h6 class="mb-0">{{ $listing->user->name }}</h6>
                                <small class="text-muted">{{ $listing->user->email }}</small>
                            </div>
                        </div>
                    @else
                        <p class="text-muted mb-0">Admin Created</p>
                    @endif
                </div>
            </div>

            <!-- Price Range -->
            @if($listing->price_from || $listing->price_to)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Price Range</h5>
                </div>
                <div class="card-body">
                    <h4 class="text-success mb-0">
                        @if($listing->price_from && $listing->price_to)
                            ৳{{ number_format($listing->price_from) }} - ৳{{ number_format($listing->price_to) }}
                        @elseif($listing->price_from)
                            From ৳{{ number_format($listing->price_from) }}
                        @else
                            Up to ৳{{ number_format($listing->price_to) }}
                        @endif
                    </h4>
                </div>
            </div>
            @endif

            <!-- Opening Hours -->
            @if($listing->opening_hours && count($listing->opening_hours) > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Opening Hours</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        @foreach($listing->opening_hours as $day => $hours)
                            <tr>
                                <th>{{ ucfirst($day) }}</th>
                                <td>{{ $hours }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.listings.reject', $listing) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reject Listing</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">দ্রুত কারণ নির্বাচন করুন:</label>
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <button type="button" class="btn btn-outline-danger btn-sm quick-reject" data-reason="ডুপ্লিকেট - এই তথ্য আগে থেকেই আমাদের পোর্টালে আছে।">
                                <i class="fas fa-copy me-1"></i> ডুপ্লিকেট
                            </button>
                            <button type="button" class="btn btn-outline-warning btn-sm quick-reject" data-reason="অসম্পূর্ণ তথ্য - অনুগ্রহ করে সকল প্রয়োজনীয় তথ্য দিন।">
                                <i class="fas fa-exclamation-circle me-1"></i> অসম্পূর্ণ
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm quick-reject" data-reason="ভুল তথ্য - দেওয়া তথ্যগুলো সঠিক নয়।">
                                <i class="fas fa-times-circle me-1"></i> ভুল তথ্য
                            </button>
                            <button type="button" class="btn btn-outline-info btn-sm quick-reject" data-reason="ভুল ক্যাটাগরি - অনুগ্রহ করে সঠিক ক্যাটাগরি নির্বাচন করুন।">
                                <i class="fas fa-folder-minus me-1"></i> ভুল ক্যাটাগরি
                            </button>
                            <button type="button" class="btn btn-outline-dark btn-sm quick-reject" data-reason="নিম্নমানের ছবি - অনুগ্রহ করে ভালো মানের স্পষ্ট ছবি দিন।">
                                <i class="fas fa-image me-1"></i> খারাপ ছবি
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Rejection Reason</label>
                        <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="3" 
                                  placeholder="Enter reason for rejection (optional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Listing</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.quick-reject').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('rejection_reason').value = this.dataset.reason;
    });
});
</script>
@endpush
@endsection
