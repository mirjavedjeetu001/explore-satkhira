@extends('admin.layouts.app')

@section('title', 'Site Settings')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-cog me-2"></i>Site Settings</h1>
</div>

<div class="row">
    <div class="col-lg-3 mb-4">
        <div class="list-group">
            <a href="{{ route('admin.settings.general') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.settings.general') ? 'active' : '' }}">
                <i class="fas fa-sliders-h me-2"></i>General Settings
            </a>
            <a href="{{ route('admin.settings.contact') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.settings.contact') ? 'active' : '' }}">
                <i class="fas fa-address-book me-2"></i>Contact Info
            </a>
            <a href="{{ route('admin.settings.social') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.settings.social') ? 'active' : '' }}">
                <i class="fas fa-share-alt me-2"></i>Social Links
            </a>
        </div>
    </div>
    
    <div class="col-lg-9">
        <div class="admin-form">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                @if(request()->routeIs('admin.settings.general'))
                    <h5 class="mb-4"><i class="fas fa-sliders-h text-success me-2"></i>General Settings</h5>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Site Name</label>
                            <input type="text" name="settings[site_name]" class="form-control" 
                                   value="{{ $settings['site_name'] ?? 'Explore Satkhira' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Site Tagline</label>
                            <input type="text" name="settings[site_tagline]" class="form-control" 
                                   value="{{ $settings['site_tagline'] ?? '' }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Site Description (Meta)</label>
                            <textarea name="settings[site_description]" class="form-control" rows="2">{{ $settings['site_description'] ?? '' }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Site Logo</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                            @if(!empty($settings['site_logo']))
                                <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo" class="mt-2" height="50">
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Favicon</label>
                            <input type="file" name="favicon" class="form-control" accept="image/*">
                        </div>
                        <div class="col-12">
                            <label class="form-label">About Us (Short)</label>
                            <textarea name="settings[about_short]" class="form-control" rows="3">{{ $settings['about_short'] ?? '' }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Footer Text</label>
                            <input type="text" name="settings[footer_text]" class="form-control" 
                                   value="{{ $settings['footer_text'] ?? '' }}">
                        </div>
                    </div>
                    
                @elseif(request()->routeIs('admin.settings.contact'))
                    <h5 class="mb-4"><i class="fas fa-address-book text-success me-2"></i>Contact Information</h5>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="settings[contact_email]" class="form-control" 
                                   value="{{ $settings['contact_email'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="settings[contact_phone]" class="form-control" 
                                   value="{{ $settings['contact_phone'] ?? '' }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea name="settings[contact_address]" class="form-control" rows="2">{{ $settings['contact_address'] ?? '' }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">WhatsApp Number</label>
                            <input type="text" name="settings[whatsapp]" class="form-control" 
                                   value="{{ $settings['whatsapp'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Google Map Embed URL</label>
                            <input type="text" name="settings[google_map]" class="form-control" 
                                   value="{{ $settings['google_map'] ?? '' }}" placeholder="https://www.google.com/maps/embed?...">
                        </div>
                    </div>
                    
                @elseif(request()->routeIs('admin.settings.social'))
                    <h5 class="mb-4"><i class="fas fa-share-alt text-success me-2"></i>Social Media Links</h5>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label"><i class="fab fa-facebook text-primary me-1"></i>Facebook</label>
                            <input type="url" name="settings[facebook]" class="form-control" 
                                   value="{{ $settings['facebook'] ?? '' }}" placeholder="https://facebook.com/...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="fab fa-twitter text-info me-1"></i>Twitter</label>
                            <input type="url" name="settings[twitter]" class="form-control" 
                                   value="{{ $settings['twitter'] ?? '' }}" placeholder="https://twitter.com/...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="fab fa-youtube text-danger me-1"></i>YouTube</label>
                            <input type="url" name="settings[youtube]" class="form-control" 
                                   value="{{ $settings['youtube'] ?? '' }}" placeholder="https://youtube.com/...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="fab fa-instagram text-warning me-1"></i>Instagram</label>
                            <input type="url" name="settings[instagram]" class="form-control" 
                                   value="{{ $settings['instagram'] ?? '' }}" placeholder="https://instagram.com/...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="fab fa-linkedin text-primary me-1"></i>LinkedIn</label>
                            <input type="url" name="settings[linkedin]" class="form-control" 
                                   value="{{ $settings['linkedin'] ?? '' }}" placeholder="https://linkedin.com/...">
                        </div>
                    </div>
                @endif
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
