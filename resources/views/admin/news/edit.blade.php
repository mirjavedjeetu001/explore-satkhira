@extends('admin.layouts.app')

@section('title', 'Edit News')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-edit me-2"></i>Edit News</h1>
    <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to List
    </a>
</div>

<div class="admin-form">
    <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">News Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Title (English) <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                   value="{{ old('title', $news->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Title (Bengali)</label>
                            <input type="text" name="title_bn" class="form-control @error('title_bn') is-invalid @enderror" 
                                   value="{{ old('title_bn', $news->title_bn) }}">
                            @error('title_bn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Excerpt</label>
                            <textarea name="excerpt" class="form-control @error('excerpt') is-invalid @enderror" 
                                      rows="3">{{ old('excerpt', $news->excerpt) }}</textarea>
                            @error('excerpt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Content <span class="text-danger">*</span></label>
                            <textarea name="content" class="form-control @error('content') is-invalid @enderror" 
                                      rows="10" required>{{ old('content', $news->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="news" {{ old('type', $news->type) == 'news' ? 'selected' : '' }}>News</option>
                                <option value="notice" {{ old('type', $news->type) == 'notice' ? 'selected' : '' }}>Notice</option>
                                <option value="event" {{ old('type', $news->type) == 'event' ? 'selected' : '' }}>Event</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Event Date</label>
                            <input type="datetime-local" name="event_date" class="form-control @error('event_date') is-invalid @enderror" 
                                   value="{{ old('event_date', $news->event_date ? $news->event_date->format('Y-m-d\TH:i') : '') }}">
                            @error('event_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        @if($news->image)
                            <div class="mb-3">
                                <label class="form-label">Current Image</label>
                                <img src="{{ asset('storage/' . $news->image) }}" alt="" class="img-thumbnail d-block" style="max-width: 200px;">
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <label class="form-label">Change Featured Image</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Add Gallery Images</label>
                            <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
                            <small class="text-muted">You can select multiple images</small>
                        </div>
                        
                        @if($news->gallery && count($news->gallery) > 0)
                            <div class="mb-3">
                                <label class="form-label">Current Gallery</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($news->gallery as $img)
                                        <img src="{{ asset('storage/' . $img) }}" alt="" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="is_featured" class="form-check-input" id="is_featured" 
                                       value="1" {{ old('is_featured', $news->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">Featured</label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="is_active" class="form-check-input" id="is_active" 
                                       value="1" {{ old('is_active', $news->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Published</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-success w-100">
                    <i class="fas fa-save me-2"></i>Update News
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
