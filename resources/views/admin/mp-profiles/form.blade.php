<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Name (English) <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
               value="{{ old('name', $mpProfile->name ?? '') }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="col-md-6">
        <label class="form-label">Name (Bangla)</label>
        <input type="text" name="name_bn" class="form-control @error('name_bn') is-invalid @enderror" 
               value="{{ old('name_bn', $mpProfile->name_bn ?? '') }}">
        @error('name_bn')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="col-md-6">
        <label class="form-label">Designation</label>
        <input type="text" name="designation" class="form-control @error('designation') is-invalid @enderror" 
               value="{{ old('designation', $mpProfile->designation ?? 'সংসদ সদস্য') }}" placeholder="সংসদ সদস্য">
        @error('designation')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="col-md-6">
        <label class="form-label">Constituency (আসন) <span class="text-danger">*</span></label>
        <input type="text" name="constituency" class="form-control @error('constituency') is-invalid @enderror" 
               value="{{ old('constituency', $mpProfile->constituency ?? '') }}" 
               placeholder="যেমন: সাতক্ষীরা-১, সাতক্ষীরা-২" required>
        <small class="text-muted">এই আসনের MP হিসেবে দেখাবে</small>
        @error('constituency')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="col-12">
        <label class="form-label">Bio / পরিচিতি</label>
        <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" rows="4">{{ old('bio', $mpProfile->bio ?? '') }}</textarea>
        @error('bio')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="col-md-6">
        <label class="form-label">Photo</label>
        @if(isset($mpProfile) && $mpProfile->image)
            <div class="mb-2">
                <img src="{{ asset('storage/' . $mpProfile->image) }}" alt="Current Photo" 
                     width="100" height="100" class="rounded" style="object-fit: cover;">
            </div>
        @endif
        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
        <small class="text-muted">Recommended: 200x200 pixels</small>
        @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="col-md-6">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
               value="{{ old('phone', $mpProfile->phone ?? '') }}">
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
               value="{{ old('email', $mpProfile->email ?? '') }}">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="col-md-6">
        <label class="form-label">Facebook URL</label>
        <input type="url" name="facebook" class="form-control @error('facebook') is-invalid @enderror" 
               value="{{ old('facebook', $mpProfile->facebook ?? '') }}" placeholder="https://facebook.com/...">
        @error('facebook')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="col-md-6">
        <label class="form-label">Twitter URL</label>
        <input type="url" name="twitter" class="form-control @error('twitter') is-invalid @enderror" 
               value="{{ old('twitter', $mpProfile->twitter ?? '') }}" placeholder="https://twitter.com/...">
        @error('twitter')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="col-md-6">
        <label class="form-label">Elected Date</label>
        <input type="date" name="elected_date" class="form-control @error('elected_date') is-invalid @enderror" 
               value="{{ old('elected_date', isset($mpProfile) && $mpProfile->elected_date ? $mpProfile->elected_date->format('Y-m-d') : '') }}">
        @error('elected_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="col-12">
        <label class="form-label">Address</label>
        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $mpProfile->address ?? '') }}</textarea>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="col-md-6">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                   {{ old('is_active', $mpProfile->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Active (Show in frontend)</label>
        </div>
    </div>
    
    <div class="col-12">
        <hr>
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save me-2"></i>{{ isset($mpProfile) ? 'Update MP Profile' : 'Create MP Profile' }}
        </button>
        <a href="{{ route('admin.mp-profiles.index') }}" class="btn btn-secondary">
            <i class="fas fa-times me-2"></i>Cancel
        </a>
    </div>
</div>
