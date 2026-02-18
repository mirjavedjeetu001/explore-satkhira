@extends('admin.layouts.app')

@section('title', 'Sliders Management')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-images me-2"></i>Sliders</h1>
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#sliderModal">
        <i class="fas fa-plus me-2"></i>Add Slider
    </button>
</div>

<div class="admin-table">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Button</th>
                    <th>Status</th>
                    <th>Order</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sliders ?? [] as $slider)
                    <tr>
                        <td>{{ $slider->id }}</td>
                        <td>
                            @if($slider->image)
                                <img src="{{ asset('storage/' . $slider->image) }}" alt="{{ $slider->title }}" 
                                     width="100" height="50" style="object-fit: cover;" class="rounded">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ Str::limit($slider->title, 30) }}</strong>
                            @if($slider->subtitle)
                                <small class="text-muted d-block">{{ Str::limit($slider->subtitle, 40) }}</small>
                            @endif
                        </td>
                        <td>
                            @if($slider->button_text)
                                <span class="badge bg-secondary">{{ $slider->button_text }}</span>
                            @endif
                        </td>
                        <td>
                            @if($slider->is_active)
                                <span class="badge badge-active">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $slider->order }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.sliders.edit', $slider) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST" class="d-inline"
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
                        <td colspan="7" class="text-center py-4 text-muted">No sliders found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Slider Modal -->
<div class="modal fade" id="sliderModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.sliders.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Slider</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Slider Image <span class="text-danger">*</span></label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                            <small class="text-muted">Recommended size: 1920x600 pixels</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" placeholder="স্বাগতম সাতক্ষীরায়">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Subtitle</label>
                            <input type="text" name="subtitle" class="form-control" placeholder="জেলার সকল তথ্য এক জায়গায়">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Button Text</label>
                            <input type="text" name="button_text" class="form-control" placeholder="আরও দেখুন">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Button Link</label>
                            <input type="text" name="button_link" class="form-control" placeholder="/upazilas">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Order</label>
                            <input type="number" name="order" class="form-control" value="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save Slider</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
