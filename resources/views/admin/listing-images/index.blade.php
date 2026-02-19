@extends('admin.layouts.app')

@section('title', 'Listing Images Management')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-images me-2"></i>Listing Images (Offers, Promotions, Banners)</h1>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-warning text-dark">
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
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-success text-white">
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
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-danger text-white">
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
                    <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Type</label>
            <select name="type" class="form-select">
                <option value="">All Types</option>
                @foreach($types as $value => $label)
                    <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-1"></i>Filter</button>
            <a href="{{ route('admin.listing-images.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- Bulk Actions -->
<form id="bulkForm" action="" method="POST">
    @csrf
    
    <div class="mb-3" id="bulkActions" style="display: none;">
        <button type="button" class="btn btn-success btn-sm" onclick="bulkAction('approve')">
            <i class="fas fa-check me-1"></i>Approve Selected
        </button>
        <button type="button" class="btn btn-danger btn-sm" onclick="bulkAction('reject')">
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
                                <i class="fas fa-arrows-alt me-1"></i>{{ \App\Models\ListingImage::getPositions()[$image->position] ?? $image->position }}
                            </p>
                            <p class="card-text small text-muted mb-1">
                                <i class="fas fa-expand-arrows-alt me-1"></i>{{ \App\Models\ListingImage::getDisplaySizes()[$image->display_size] ?? $image->display_size }}
                            </p>
                            <p class="card-text small text-muted mb-1">
                                <i class="fas fa-store me-1"></i>{{ Str::limit($image->listing->title ?? 'N/A', 20) }}
                            </p>
                            <p class="card-text small text-muted mb-2">
                                <i class="fas fa-user me-1"></i>{{ $image->user->name ?? 'N/A' }}
                            </p>
                            <div class="d-flex gap-1">
                                @if($image->status == 'pending')
                                    <form action="{{ route('admin.listing-images.approve', $image) }}" method="POST" class="flex-fill">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm w-100" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.listing-images.reject', $image) }}" method="POST" class="flex-fill">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm w-100" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.listing-images.destroy', $image) }}" method="POST" 
                                      onsubmit="return confirm('Delete this image?')" class="flex-fill">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm w-100" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image Modal -->
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
                                    <form action="{{ route('admin.listing-images.approve', $image) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check me-1"></i>Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.listing-images.reject', $image) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-times me-1"></i>Reject
                                        </button>
                                    </form>
                                @endif
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
</form>

<div class="mt-4">
    {{ $images->appends(request()->query())->links() }}
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
    
    if (checked.length > 0) {
        bulkActions.style.display = 'block';
        selectedCount.textContent = checked.length + ' selected';
    } else {
        bulkActions.style.display = 'none';
    }
}

function bulkAction(action) {
    const form = document.getElementById('bulkForm');
    if (action === 'approve') {
        form.action = "{{ route('admin.listing-images.bulk-approve') }}";
    } else {
        form.action = "{{ route('admin.listing-images.bulk-reject') }}";
    }
    
    if (confirm('Are you sure you want to ' + action + ' selected images?')) {
        form.submit();
    }
}
</script>
@endpush
@endsection
