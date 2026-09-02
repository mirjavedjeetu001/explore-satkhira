@extends('admin.layouts.app')

@section('title', 'শুভেচ্ছা কার্ড ম্যানেজমেন্ট')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-wand-magic-sparkles text-warning me-2"></i>শুভেচ্ছা কার্ড ম্যানেজমেন্ট</h1>
            <p class="text-muted mb-0">Visitor নাম ও পদবি দিয়ে social-media-ready card বানাতে পারবে।</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('celebration-card.index') }}" target="_blank" class="btn btn-outline-primary">
                <i class="fas fa-external-link-alt me-1"></i> Public page
            </a>
            <form action="{{ route('admin.celebration-card.toggle-status') }}" method="POST">
                @csrf
                <button type="submit" class="btn {{ $settings->is_enabled ? 'btn-danger' : 'btn-success' }}">
                    <i class="fas fa-power-off me-1"></i>{{ $settings->is_enabled ? 'ফিচার বন্ধ করুন' : 'ফিচার চালু করুন' }}
                </button>
            </form>
        </div>
    </div>

    <div class="alert {{ $settings->is_enabled ? 'alert-success' : 'alert-warning' }} border-0 shadow-sm mb-4">
        <i class="fas {{ $settings->is_enabled ? 'fa-circle-check' : 'fa-triangle-exclamation' }} me-2"></i>
        শুভেচ্ছা কার্ড ফিচার বর্তমানে <strong>{{ $settings->is_enabled ? 'চালু' : 'বন্ধ' }}</strong> আছে।
        @if($settings->is_enabled)
            Public menu এবং নিচের ডান পাশের floating button-এ এটি দেখাবে।
        @else
            Visitor-রা card maker দেখতে পাবে না।
        @endif
    </div>

    <div class="row g-4 mb-4 align-items-start">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h5 mb-1"><i class="fas fa-clock-rotate-left me-2 text-success"></i>Visitor download history</h2>
                        <p class="text-muted small mb-0">Visitor PNG/JPG download করলে নাম, পদবি ও upload করা photo এখানে save হবে। একই visitor বারবার download করলে আলাদা record হবে।</p>
                    </div>
                    <span class="badge text-bg-light">{{ $generations->count() }} downloads</span>
                </div>
                <div class="card-body">
                    @forelse($generations as $generation)
                        <div class="visitor-download-row d-flex align-items-center gap-3 {{ !$loop->last ? 'border-bottom pb-3 mb-3' : '' }}">
                            @if($generation->photo_path)
                                <img src="{{ asset('storage/' . $generation->photo_path) }}" alt="{{ $generation->name }}" class="recipient-thumb">
                            @else
                                <div class="recipient-thumb recipient-thumb-placeholder"><i class="fas fa-user"></i></div>
                            @endif
                            <div class="flex-grow-1 min-w-0">
                                <div class="fw-bold text-dark text-truncate">{{ $generation->name }}</div>
                                <div class="small text-muted text-truncate">{{ $generation->designation ?: 'No designation' }}</div>
                                <div class="small text-muted">{{ strtoupper($generation->download_format) }} · {{ $generation->created_at->format('d M Y, h:i A') }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-download fs-2 mb-2"></i>
                            <p class="mb-0">কোনো visitor download এখনো হয়নি।</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 align-items-start">
        <div class="col-xl-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h2 class="h5 mb-0"><i class="fas fa-sliders me-2 text-primary"></i>Feature settings</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.celebration-card.update-settings') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">Page title</label>
                            <input id="title" name="title" value="{{ old('title', $settings->title) }}" class="form-control @error('title') is-invalid @enderror" maxlength="255" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Page description</label>
                            <textarea id="description" name="description" rows="3" class="form-control @error('description') is-invalid @enderror" maxlength="1000">{{ old('description', $settings->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="template_image" class="form-label fw-semibold">Exact template image</label>
                            <input id="template_image" name="template_image" type="file" accept="image/png,image/jpeg" class="form-control @error('template_image') is-invalid @enderror">
                            <div class="form-text">তোমার দেওয়া blank template PNG/JPG এখানে upload করো। Upload করলে card-এ ওই ছবিটাই থাকবে, শুধু visitor-এর নাম ও পদবি বসবে।</div>
                            @error('template_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @if($settings->template_image_path)
                                <div class="small text-success mt-2"><i class="fas fa-circle-check me-1"></i>Exact template uploaded</div>
                            @endif
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="brand_name" class="form-label fw-semibold">Brand name</label>
                                <input id="brand_name" name="brand_name" value="{{ old('brand_name', $settings->brand_name) }}" class="form-control @error('brand_name') is-invalid @enderror" maxlength="100" required>
                                @error('brand_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="brand_tagline" class="form-label fw-semibold">Brand tagline</label>
                                <input id="brand_tagline" name="brand_tagline" value="{{ old('brand_tagline', $settings->brand_tagline) }}" class="form-control @error('brand_tagline') is-invalid @enderror" maxlength="150">
                                @error('brand_tagline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="headline" class="form-label fw-semibold">Card headline</label>
                            <input id="headline" name="headline" value="{{ old('headline', $settings->headline) }}" class="form-control @error('headline') is-invalid @enderror" maxlength="180" required>
                            @error('headline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-4">
                            <label for="footer_text" class="form-label fw-semibold">Footer text</label>
                            <input id="footer_text" name="footer_text" value="{{ old('footer_text', $settings->footer_text) }}" class="form-control @error('footer_text') is-invalid @enderror" maxlength="180">
                            @error('footer_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>সেটিংস সংরক্ষণ করুন</button>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h3 class="h6"><i class="fas fa-circle-info text-info me-2"></i>এই feature কীভাবে কাজ করে?</h3>
                    <ul class="text-muted small mb-0 ps-3">
                        <li>Visitor নাম ও পদবি লিখলে live preview তৈরি হবে।</li>
                        <li>PNG বা JPG হিসেবে 1080×1080 card download করা যাবে।</li>
                        <li>Mobile-এ native share দিয়ে image সরাসরি social app-এ পাঠানো যাবে।</li>
                        <li>PNG/JPG download করলে visitor-এর নাম, পদবি ও photo history-তে সংরক্ষণ হবে।</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h2 class="h5 mb-0"><i class="fas fa-eye me-2 text-success"></i>Template preview</h2>
                    <span class="badge text-bg-light">1080 × 1080</span>
                </div>
                <div class="card-body preview-wrap">
                    @include('partials.celebration-card-art', [
                        'settings' => $settings,
                        'cardId' => 'adminCelebrationCard',
                        'cardName' => 'Mir Javed Jeetu',
                        'cardDesignation' => 'Developer',
                    ])
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .recipient-thumb { width: 58px; height: 58px; flex: 0 0 58px; object-fit: cover; border: 3px solid #fff; border-radius: 50%; box-shadow: 0 .25rem .75rem rgba(51, 34, 20, .16); }
    .recipient-thumb-placeholder { display: inline-flex; align-items: center; justify-content: center; color: #b10f19; background: #fff8df; font-size: 1.25rem; }
    .min-w-0 { min-width: 0; }
    .preview-wrap { background: #f3f5f7; padding: clamp(1rem, 3vw, 2.25rem); }
    .preview-wrap .celebration-card-art { width: min(100%, 620px); aspect-ratio: 1 / 1; margin: auto; color: #341712; }
    .preview-wrap .celebration-card-surface { position: relative; width: 100%; height: 100%; overflow: hidden; border-radius: .75rem; background: #f4ecdf; box-shadow: 0 1rem 2.5rem rgba(51, 34, 20, .18); }
    .preview-wrap .celebration-card-template-image { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
    .preview-wrap .celebration-template-person { position: absolute; left: 8%; right: 8%; bottom: 2.5%; display: flex; flex-direction: column; align-items: center; gap: .15rem; text-align: center; }
    .preview-wrap .celebration-template-person-name,
    .preview-wrap .celebration-person-name { display: block; max-width: 90%; overflow: hidden; padding: .08em .5em; border: 1px solid rgba(182, 138, 45, .62); border-bottom: 2px solid #b68a2d; border-radius: 999px; color: #7f2330; background: rgba(255, 252, 244, .86); box-shadow: 0 2px 8px rgba(80, 20, 15, .1); font-family: Georgia, 'Times New Roman', 'Hind Siliguri', serif; font-size: clamp(.95rem, 2.2vw, 1.7rem); font-weight: 700; line-height: 1.15; white-space: nowrap; text-overflow: ellipsis; }
    .preview-wrap .celebration-template-person-designation,
    .preview-wrap .celebration-person-designation { padding: .04em .6em; border: 1px solid rgba(182, 138, 45, .48); border-radius: 999px; color: #a36c17; background: rgba(255, 252, 244, .86); box-shadow: 0 2px 6px rgba(80, 20, 15, .08); font-family: Georgia, 'Times New Roman', 'Hind Siliguri', serif; font-size: clamp(.68rem, 1.35vw, 1rem); font-weight: 600; line-height: 1.2; }
    .preview-wrap .celebration-card-ribbons { position: absolute; inset: 0; width: 100%; height: 100%; }
    .preview-wrap .celebration-card-content { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; padding: 9% 8% 7%; text-align: center; }
    .preview-wrap .celebration-brand { display: flex; align-items: center; justify-content: center; gap: 1.25%; margin-top: 4%; max-width: 80%; }
    .preview-wrap .celebration-brand-logo { width: 9%; min-width: 34px; aspect-ratio: 1; object-fit: contain; }
    .preview-wrap .celebration-brand-copy { line-height: 1.05; }
    .preview-wrap .celebration-brand-name { color: #28221d; font-size: clamp(1rem, 3.4vw, 2rem); font-weight: 800; letter-spacing: .02em; }
    .preview-wrap .celebration-brand-tagline { color: #6f6258; font-size: clamp(.62rem, 1.55vw, .95rem); margin-top: .4rem; }
    .preview-wrap .celebration-headline { max-width: 86%; margin-top: 17%; color: #b10f14; font-size: clamp(1.2rem, 4.4vw, 3rem); font-weight: 800; line-height: 1.25; }
    .preview-wrap .celebration-rule { width: 38%; height: 5px; margin: 4% 0 2%; border-radius: 99px; background: #c99f46; position: relative; }
    .preview-wrap .celebration-rule span { position: absolute; width: 22%; height: 100%; left: 39%; border-radius: inherit; background: #8c2f39; }
    .preview-wrap .celebration-person { margin-top: 1%; min-height: 19%; display: flex; flex-direction: column; align-items: center; justify-content: center; }
    .preview-wrap .celebration-person-name { display: block; max-width: 90%; overflow: hidden; padding: .08em .5em; border: 1px solid rgba(182, 138, 45, .62); border-bottom: 2px solid #b68a2d; border-radius: 999px; color: #7f2330; background: rgba(255, 252, 244, .86); box-shadow: 0 2px 8px rgba(80, 20, 15, .1); font-family: Georgia, 'Times New Roman', 'Hind Siliguri', serif; font-size: clamp(.95rem, 2.2vw, 1.7rem); font-weight: 700; line-height: 1.15; white-space: nowrap; text-overflow: ellipsis; }
    .preview-wrap .celebration-person-designation { padding: .04em .6em; border: 1px solid rgba(182, 138, 45, .48); border-radius: 999px; color: #a36c17; background: rgba(255, 252, 244, .86); box-shadow: 0 2px 6px rgba(80, 20, 15, .08); font-family: Georgia, 'Times New Roman', 'Hind Siliguri', serif; font-size: clamp(.68rem, 1.35vw, 1rem); font-weight: 600; line-height: 1.2; }
    .preview-wrap .celebration-footer { margin-top: auto; color: #766052; font-size: clamp(.7rem, 1.8vw, 1.1rem); font-weight: 600; }
</style>
@endsection
