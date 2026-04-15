@extends('admin.layouts.app')
@section('title', 'নতুন সার্ভে তৈরি করুন')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-plus-circle me-2"></i>নতুন সার্ভে তৈরি করুন</h1>
    <a href="{{ route('admin.surveys.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>ফিরে যান</a>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.surveys.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label fw-semibold">সার্ভে শিরোনাম <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="যেমন: সাতক্ষীরা সরকারি কলেজ">
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-semibold">প্রশ্ন <span class="text-danger">*</span></label>
                    <textarea name="question" class="form-control" rows="2" required placeholder="যেমন: আপনি কি সাতক্ষীরা সরকারি কলেজের মাঠে ধানচাষের পক্ষে?">{{ old('question') }}</textarea>
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-semibold">অপশনসমূহ <span class="text-danger">*</span></label>
                    <div id="options-container">
                        @if(old('options'))
                            @foreach(old('options') as $i => $opt)
                                <div class="input-group mb-2 option-row">
                                    <input type="text" name="options[]" class="form-control" value="{{ $opt }}" required>
                                    <button type="button" class="btn btn-outline-danger remove-option"><i class="fas fa-times"></i></button>
                                </div>
                            @endforeach
                        @else
                            <div class="input-group mb-2 option-row">
                                <input type="text" name="options[]" class="form-control" value="হ্যা" required>
                                <button type="button" class="btn btn-outline-danger remove-option"><i class="fas fa-times"></i></button>
                            </div>
                            <div class="input-group mb-2 option-row">
                                <input type="text" name="options[]" class="form-control" value="না" required>
                                <button type="button" class="btn btn-outline-danger remove-option"><i class="fas fa-times"></i></button>
                            </div>
                            <div class="input-group mb-2 option-row">
                                <input type="text" name="options[]" class="form-control" value="মতামত নেই" required>
                                <button type="button" class="btn btn-outline-danger remove-option"><i class="fas fa-times"></i></button>
                            </div>
                            <div class="input-group mb-2 option-row">
                                <input type="text" name="options[]" class="form-control" value="অন্যান্য" required>
                                <button type="button" class="btn btn-outline-danger remove-option"><i class="fas fa-times"></i></button>
                            </div>
                        @endif
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="add-option"><i class="fas fa-plus me-1"></i>অপশন যোগ করুন</button>
                </div>

                <div class="col-md-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="has_comment_option" value="1" id="hasComment" {{ old('has_comment_option', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="hasComment">শেষ অপশনে ক্লিক করলে কমেন্ট বক্স দেখাবে (অন্যান্য)</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">শুরুর সময় <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="start_time" class="form-control" value="{{ old('start_time') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">শেষের সময় <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="end_time" class="form-control" value="{{ old('end_time') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">ছবি</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small class="text-muted">সর্বোচ্চ ৫ MB</small>
                </div>

                <div class="col-md-6 d-flex align-items-end gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">সক্রিয়</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="show_on_homepage" value="1" id="showHomepage" {{ old('show_on_homepage', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="showHomepage">হোমপেজে দেখাবে</label>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i>সার্ভে তৈরি করুন</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('add-option').addEventListener('click', function() {
        const container = document.getElementById('options-container');
        const div = document.createElement('div');
        div.className = 'input-group mb-2 option-row';
        div.innerHTML = '<input type="text" name="options[]" class="form-control" required><button type="button" class="btn btn-outline-danger remove-option"><i class="fas fa-times"></i></button>';
        container.appendChild(div);
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-option')) {
            const rows = document.querySelectorAll('.option-row');
            if (rows.length > 2) {
                e.target.closest('.option-row').remove();
            }
        }
    });
</script>
@endpush
