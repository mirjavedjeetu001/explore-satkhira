@extends('admin.layouts.app')

@section('title', 'Upazilas Management')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-map-marker-alt me-2"></i>Upazilas</h1>
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#upazilaModal">
        <i class="fas fa-plus me-2"></i>Add Upazila
    </button>
</div>

<div class="admin-table">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Name (Bengali)</th>
                    <th>Slug</th>
                    <th>Listings</th>
                    <th>Moderators</th>
                    <th>Status</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($upazilas ?? [] as $upazila)
                    <tr>
                        <td>{{ $upazila->id }}</td>
                        <td>
                            @if($upazila->image)
                                <img src="{{ asset('storage/' . $upazila->image) }}" alt="{{ $upazila->name }}" 
                                     width="50" height="50" class="rounded" style="object-fit: cover;">
                            @else
                                <div class="bg-success text-white rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                            @endif
                        </td>
                        <td><strong>{{ $upazila->name }}</strong></td>
                        <td><code>{{ $upazila->slug }}</code></td>
                        <td><span class="badge bg-info">{{ $upazila->listings_count ?? 0 }}</span></td>
                        <td><span class="badge bg-secondary">{{ $upazila->users_count ?? 0 }}</span></td>
                        <td>
                            @if($upazila->is_active)
                                <span class="badge badge-active">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-primary edit-upazila" 
                                        data-id="{{ $upazila->id }}" 
                                        data-name="{{ $upazila->name }}" 
                                        data-slug="{{ $upazila->slug }}"
                                        data-description="{{ $upazila->description }}"
                                        data-is_active="{{ $upazila->is_active }}"
                                        data-bs-toggle="modal" data-bs-target="#upazilaModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.upazilas.destroy', $upazila) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No upazilas found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Upazila Modal -->
<div class="modal fade" id="upazilaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="upazilaForm" method="POST" action="{{ route('admin.upazilas.store') }}" enctype="multipart/form-data">
                @csrf
                <div id="methodField"></div>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Upazila</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name (Bengali) <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="upName" class="form-control" required placeholder="যেমন: সাতক্ষীরা সদর">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" id="upSlug" class="form-control" placeholder="satkhira-sadar">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="upDescription" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="is_active" id="upIsActive" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save Upazila</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('upazilaForm');
    
    document.querySelector('[data-bs-target="#upazilaModal"]:not(.edit-upazila)')?.addEventListener('click', function() {
        form.action = "{{ route('admin.upazilas.store') }}";
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('modalTitle').textContent = 'Add Upazila';
        form.reset();
    });
    
    document.querySelectorAll('.edit-upazila').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            form.action = `/admin/upazilas/${id}`;
            document.getElementById('methodField').innerHTML = '@method("PUT")';
            document.getElementById('modalTitle').textContent = 'Edit Upazila';
            document.getElementById('upName').value = this.dataset.name;
            document.getElementById('upSlug').value = this.dataset.slug;
            document.getElementById('upDescription').value = this.dataset.description;
            document.getElementById('upIsActive').value = this.dataset.is_active;
        });
    });
});
</script>
@endpush
