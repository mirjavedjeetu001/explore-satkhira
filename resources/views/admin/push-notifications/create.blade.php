@extends('admin.layouts.app')

@section('title', 'নোটিফিকেশন পাঠান')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">📤 নতুন নোটিফিকেশন পাঠান</h4>
                <a href="{{ route('admin.push-notifications.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> ফিরে যান
                </a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @if($totalSubscribers == 0)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            এখনও কোনো সাবস্ক্রাইবার নেই। ইউজারদের ওয়েবসাইট ভিজিট বা অ্যাপ ইনস্টল করতে হবে।
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>{{ $totalSubscribers }}</strong> জন সাবস্ক্রাইবারের কাছে এই নোটিফিকেশন পৌঁছাবে।
                    </div>

                    <form action="{{ route('admin.push-notifications.send') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">শিরোনাম <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                   value="{{ old('title') }}" required maxlength="100"
                                   placeholder="যেমন: জরুরি আপডেট!">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">বিষয়বস্তু <span class="text-danger">*</span></label>
                            <textarea name="body" class="form-control @error('body') is-invalid @enderror" 
                                      rows="3" required maxlength="300"
                                      placeholder="নোটিফিকেশনের বিস্তারিত লিখুন...">{{ old('body') }}</textarea>
                            @error('body')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">সর্বোচ্চ ৩০০ অক্ষর</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">লিংক (ঐচ্ছিক)</label>
                            <input type="text" name="url" class="form-control @error('url') is-invalid @enderror" 
                                   value="{{ old('url', '/') }}" maxlength="500"
                                   placeholder="যেমন: /fuel বা /listings">
                            @error('url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">নোটিফিকেশনে ক্লিক করলে এই পেজে যাবে</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">ছবি (ঐচ্ছিক)</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">নোটিফিকেশনে একটি ছবি দেখাবে (সর্বোচ্চ 1MB)</small>
                        </div>

                        <!-- Preview -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted">প্রিভিউ:</label>
                            <div class="notification-preview p-3 bg-light rounded border">
                                <div class="d-flex align-items-start gap-3">
                                    <img src="{{ asset('icons/icon-96x96.png') }}" alt="icon" width="48" height="48" class="rounded">
                                    <div>
                                        <strong class="d-block preview-title">নোটিফিকেশন শিরোনাম</strong>
                                        <small class="text-muted preview-body">বিষয়বস্তু এখানে দেখাবে...</small>
                                        <br><small class="text-primary">exploresatkhira.com</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.push-notifications.index') }}" class="btn btn-outline-secondary">বাতিল</a>
                            <button type="submit" class="btn btn-success btn-lg" {{ $totalSubscribers == 0 ? 'disabled' : '' }}>
                                <i class="fas fa-paper-plane"></i> নোটিফিকেশন পাঠান ({{ $totalSubscribers }} জন)
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.querySelector('input[name="title"]');
    const bodyInput = document.querySelector('textarea[name="body"]');
    const previewTitle = document.querySelector('.preview-title');
    const previewBody = document.querySelector('.preview-body');
    
    titleInput.addEventListener('input', () => {
        previewTitle.textContent = titleInput.value || 'নোটিফিকেশন শিরোনাম';
    });
    bodyInput.addEventListener('input', () => {
        previewBody.textContent = bodyInput.value || 'বিষয়বস্তু এখানে দেখাবে...';
    });
});
</script>
@endsection
