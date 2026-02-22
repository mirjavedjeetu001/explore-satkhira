@extends('admin.layouts.app')

@section('title', 'Ads/Offers Management')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-images me-2"></i>Ads/Offers Management</h1>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <a href="{{ route('admin.listing-images.index', ['status' => 'pending']) }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm bg-warning text-dark {{ request()->query('status') === 'pending' ? 'border border-dark border-3' : '' }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Pending</h6>
                            <h3 class="mb-0">{{ $stats['pending'] }}</h3>
                        </div>
                        <i class="fas fa-clock fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('admin.listing-images.index', ['status' => 'approved']) }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm bg-success text-white {{ request()->query('status') === 'approved' ? 'border border-dark border-3' : '' }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Approved</h6>
                            <h3 class="mb-0">{{ $stats['approved'] }}</h3>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('admin.listing-images.index', ['status' => 'rejected']) }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm bg-danger text-white {{ request()->query('status') === 'rejected' ? 'border border-dark border-3' : '' }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Rejected</h6>
                            <h3 class="mb-0">{{ $stats['rejected'] }}</h3>
                        </div>
                        <i class="fas fa-times-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Filters -->
<div class="admin-form mb-4">
    <form action="{{ route('admin.listing-images.index') }}" method="GET" class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">All Status</option>
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}" @selected(request()->query('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Type</label>
            <select name="type" class="form-select">
                <option value="">All Types</option>
                @foreach($types as $value => $label)
                    <option value="{{ $value }}" @selected(request()->query('type') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-1"></i>Filter</button>
            <a href="{{ route('admin.listing-images.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- Bulk Actions Bar -->
<div class="mb-3" id="bulkActions" style="display: none;">
    <button type="button" class="btn btn-success btn-sm" onclick="bulkAction('approve')">
        <i class="fas fa-check me-1"></i>Approve Selected
    </button>
    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#bulkRejectModal">
        <i class="fas fa-times me-1"></i>Reject Selected
    </button>
    <span class="ms-2 text-muted" id="selectedCount">0 selected</span>
</div>

<div class="admin-table">
    <div class="row g-3">
        @forelse($images as $image)
            <div class="col-md-4 col-lg-3">
                <div class="card h-100 border shadow-sm">
                    <div class="position-relative">
                        <input type="checkbox" name="ids[]" value="{{ $image->id }}" 
                               class="form-check-input position-absolute top-0 start-0 m-2 image-checkbox"
                               style="z-index: 10;">
                        <img src="{{ asset('storage/' . $image->image) }}" 
                             alt="{{ $image->title }}" 
                             class="card-img-top" 
                             style="height: 150px; object-fit: cover; cursor: pointer;"
                             data-bs-toggle="modal" 
                             data-bs-target="#imageModal{{ $image->id }}">
                        <span class="position-absolute top-0 end-0 m-2">
                            @if($image->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($image->status == 'approved')
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </span>
                    </div>
                    <div class="card-body p-2">
                        <h6 class="card-title small mb-1 text-truncate">{{ $image->title ?? 'No Title' }}</h6>
                        <p class="card-text small text-muted mb-1">
                            {!! $image->type_badge !!}
                        </p>
                        <p class="card-text small text-muted mb-1">
                            <i class="fas fa-store me-1"></i>{{ Str::limit($image->listing->title ?? 'N/A', 20) }}
                        </p>
                        <p class="card-text small text-muted mb-2">
                            <i class="fas fa-user me-1"></i>{{ $image->user->name ?? 'N/A' }}
                        </p>
                        
                        @if($image->status == 'approved')
                        <!-- Homepage Toggle -->
                        <div class="mb-2">
                            <button type="button" 
                                    class="btn btn-sm w-100 {{ $image->show_on_homepage ? 'btn-primary' : 'btn-outline-secondary' }}" 
                                    onclick="toggleHomepage({{ $image->id }})"
                                    title="{{ $image->show_on_homepage ? 'Click to remove from homepage' : 'Click to show on homepage' }}">
                                <i class="fas fa-home me-1"></i>
                                {{ $image->show_on_homepage ? 'On Homepage' : 'Add to Homepage' }}
                            </button>
                        </div>
                        @endif
                        
                        <div class="d-flex gap-1">
                            @if($image->status == 'pending')
                                <button type="button" class="btn btn-success btn-sm flex-fill" title="Approve" onclick="approveImage({{ $image->id }})">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button type="button" class="btn btn-warning btn-sm flex-fill" title="Reject" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $image->id }}">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                            <button type="button" class="btn btn-danger btn-sm flex-fill" title="Delete" onclick="deleteImage({{ $image->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-images fa-4x text-muted mb-3"></i>
                    <h5>No images found</h5>
                    <p class="text-muted">No listing images match your criteria.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<div class="mt-4">
    {{ $images->appends(request()->query())->links() }}
</div>

<!-- Hidden Forms for Actions -->
<form id="approveForm" action="" method="POST" style="display: none;">
    @csrf
</form>

<form id="deleteForm" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<form id="toggleHomepageForm" action="" method="POST" style="display: none;">
    @csrf
</form>

<form id="bulkForm" action="" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="rejection_reason" id="bulkRejectionReason" value="">
</form>

<!-- Individual Reject Modals -->
@foreach($images as $image)
    <div class="modal fade" id="rejectModal{{ $image->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title"><i class="fas fa-times-circle me-2"></i>Reject Ad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.listing-images.reject', $image) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center mb-3">
                            <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $image->title }}" style="max-height: 150px; border-radius: 8px;">
                        </div>
                        <p class="text-muted mb-3">
                            <strong>{{ $image->title ?? 'No Title' }}</strong><br>
                            <small>{{ $image->listing->title ?? 'N/A' }} | {{ $image->user->name ?? 'N/A' }}</small>
                        </p>
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-comment me-1"></i>Rejection Reason <span class="text-danger">*</span></label>
                            <textarea name="rejection_reason" class="form-control" rows="4" required placeholder="Enter reason for rejection... (will be sent to user via email)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-times me-1"></i>Reject & Send Email
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Image Detail Modal -->
    <div class="modal fade" id="imageModal{{ $image->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $image->title ?? 'Image Details' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $image->title }}" class="img-fluid rounded">
                        </div>
                        <div class="col-md-4">
                            <table class="table table-sm">
                                <tr>
                                    <th>Type:</th>
                                    <td>{!! $image->type_badge !!}</td>
                                </tr>
                                <tr>
                                    <th>Position:</th>
                                    <td>{{ \App\Models\ListingImage::getPositions()[$image->position] ?? $image->position }}</td>
                                </tr>
                                <tr>
                                    <th>Size:</th>
                                    <td>{{ \App\Models\ListingImage::getDisplaySizes()[$image->display_size] ?? $image->display_size }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>{!! $image->status_badge !!}</td>
                                </tr>
                                <tr>
                                    <th>Listing:</th>
                                    <td>
                                        <a href="{{ route('admin.listings.edit', $image->listing_id) }}">
                                            {{ $image->listing->title ?? 'N/A' }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>User:</th>
                                    <td>{{ $image->user->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Date:</th>
                                    <td>{{ $image->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                @if($image->valid_from || $image->valid_until)
                                <tr>
                                    <th>Validity:</th>
                                    <td>
                                        {{ $image->valid_from?->format('d M Y') ?? 'N/A' }} - 
                                        {{ $image->valid_until?->format('d M Y') ?? 'N/A' }}
                                    </td>
                                </tr>
                                @endif
                            </table>
                            @if($image->description)
                                <h6>Description:</h6>
                                <p class="small text-muted">{{ $image->description }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @if($image->status == 'pending')
                        <button type="button" class="btn btn-success" onclick="approveImage({{ $image->id }})">
                            <i class="fas fa-check me-1"></i>Approve
                        </button>
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $image->id }}">
                            <i class="fas fa-times me-1"></i>Reject
                        </button>
                    @endif
                    @if($image->status == 'rejected' && $image->rejection_reason)
                        <div class="w-100 mb-2">
                            <div class="alert alert-warning mb-0">
                                <strong>Rejection Reason:</strong> {{ $image->rejection_reason }}
                            </div>
                        </div>
                    @endif
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- Bulk Reject Modal -->
<div class="modal fade" id="bulkRejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-times-circle me-2"></i>Reject Multiple Ads</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    You are about to reject <strong id="bulkRejectCount">0</strong> ads. Each user will receive an email notification.
                </div>
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-comment me-1"></i>Rejection Reason <span class="text-danger">*</span></label>
                    <textarea id="bulkRejectReasonInput" class="form-control" rows="4" required placeholder="Enter reason for rejection... (same reason will apply to all selected ads)"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="submitBulkReject()">
                    <i class="fas fa-times me-1"></i>Reject & Send Emails
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.image-checkbox').forEach(function(checkbox) {
    checkbox.addEventListener('change', updateSelection);
});

function updateSelection() {
    const checked = document.querySelectorAll('.image-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    const bulkRejectCount = document.getElementById('bulkRejectCount');
    
    if (checked.length > 0) {
        bulkActions.style.display = 'block';
        selectedCount.textContent = checked.length + ' selected';
        if (bulkRejectCount) {
            bulkRejectCount.textContent = checked.length;
        }
    } else {
        bulkActions.style.display = 'none';
    }
}

function approveImage(id) {
    if (confirm('Are you sure you want to approve this image?')) {
        const form = document.getElementById('approveForm');
        form.action = "{{ url('admin/listing-images') }}/" + id + "/approve";
        form.submit();
    }
}

function deleteImage(id) {
    if (confirm('Are you sure you want to delete this image?')) {
        const form = document.getElementById('deleteForm');
        form.action = "{{ url('admin/listing-images') }}/" + id;
        form.submit();
    }
}

function toggleHomepage(id) {
    const form = document.getElementById('toggleHomepageForm');
    form.action = "{{ url('admin/listing-images') }}/" + id + "/toggle-homepage";
    form.submit();
}

function bulkAction(action) {
    const checked = document.querySelectorAll('.image-checkbox:checked');
    if (checked.length === 0) {
        alert('Please select at least one image');
        return;
    }
    
    const form = document.getElementById('bulkForm');
    // Clear existing hidden inputs
    form.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
    
    // Add selected IDs
    checked.forEach(function(checkbox) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = checkbox.value;
        form.appendChild(input);
    });
    
    if (action === 'approve') {
        form.action = "{{ route('admin.listing-images.bulk-approve') }}";
        if (confirm('Are you sure you want to approve ' + checked.length + ' images?')) {
            form.submit();
        }
    }
}

function submitBulkReject() {
    const reason = document.getElementById('bulkRejectReasonInput').value.trim();
    if (!reason) {
        alert('Please enter a rejection reason');
        return;
    }
    
    const checked = document.querySelectorAll('.image-checkbox:checked');
    if (checked.length === 0) {
        alert('Please select at least one image');
        return;
    }
    
    const form = document.getElementById('bulkForm');
    // Clear existing hidden inputs
    form.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
    
    // Add selected IDs
    checked.forEach(function(checkbox) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = checkbox.value;
        form.appendChild(input);
    });
    
    document.getElementById('bulkRejectionReason').value = reason;
    form.action = "{{ route('admin.listing-images.bulk-reject') }}";
    form.submit();
}
</script>
@endpush
@endsection
