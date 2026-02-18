@extends('admin.layouts.app')

@section('title', 'Categories Management')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-th-large me-2"></i>Categories</h1>
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#categoryModal">
        <i class="fas fa-plus me-2"></i>Add Category
    </button>
</div>

<div class="admin-table">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Icon</th>
                    <th>Name (Bengali)</th>
                    <th>Slug</th>
                    <th>Listings</th>
                    <th>Status</th>
                    <th>Order</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories ?? [] as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td><i class="{{ $category->icon ?? 'fas fa-folder' }} fa-lg text-success"></i></td>
                        <td>{{ $category->name }}</td>
                        <td><code>{{ $category->slug }}</code></td>
                        <td><span class="badge bg-info">{{ $category->listings_count ?? 0 }}</span></td>
                        <td>
                            @if($category->is_active)
                                <span class="badge badge-active">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $category->order }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-primary edit-category" 
                                        data-id="{{ $category->id }}" 
                                        data-name="{{ $category->name }}" 
                                        data-slug="{{ $category->slug }}"
                                        data-icon="{{ $category->icon }}"
                                        data-description="{{ $category->description }}"
                                        data-order="{{ $category->order }}"
                                        data-is_active="{{ $category->is_active }}"
                                        data-bs-toggle="modal" data-bs-target="#categoryModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline"
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
                        <td colspan="8" class="text-center py-4 text-muted">No categories found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="categoryForm" method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div id="methodField"></div>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name (Bengali) <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="catName" class="form-control" required placeholder="যেমন: হাসপাতাল">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" id="catSlug" class="form-control" placeholder="hospital">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icon (Font Awesome)</label>
                        <input type="text" name="icon" id="catIcon" class="form-control" placeholder="fas fa-hospital">
                        <small class="text-muted">Example: fas fa-hospital, fas fa-school, fas fa-hotel</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="catDescription" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Order</label>
                            <input type="number" name="order" id="catOrder" class="form-control" value="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="is_active" id="catIsActive" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('categoryModal');
    const form = document.getElementById('categoryForm');
    
    // Reset form when modal is opened for new category
    document.querySelector('[data-bs-target="#categoryModal"]:not(.edit-category)')?.addEventListener('click', function() {
        form.action = "{{ route('admin.categories.store') }}";
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('modalTitle').textContent = 'Add Category';
        form.reset();
    });
    
    // Edit category
    document.querySelectorAll('.edit-category').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            form.action = `/admin/categories/${id}`;
            document.getElementById('methodField').innerHTML = '@method("PUT")';
            document.getElementById('modalTitle').textContent = 'Edit Category';
            document.getElementById('catName').value = this.dataset.name;
            document.getElementById('catSlug').value = this.dataset.slug;
            document.getElementById('catIcon').value = this.dataset.icon;
            document.getElementById('catDescription').value = this.dataset.description;
            document.getElementById('catOrder').value = this.dataset.order;
            document.getElementById('catIsActive').value = this.dataset.is_active;
        });
    });
});
</script>
@endpush
