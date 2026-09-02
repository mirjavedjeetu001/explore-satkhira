@extends('frontend.layouts.app')

@php
    $initialName = '';
    $initialDesignation = '';
    $initialPhoto = null;
@endphp

@section('title', $settings->title)
@section('meta_description', $settings->description)

@section('content')
<div class="celebration-card-page">
    <section class="celebration-card-hero">
        <div class="container py-5">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <span class="celebration-eyebrow"><i class="fas fa-sparkles me-2"></i>সামাজিক মাধ্যমে শেয়ার করার জন্য</span>
                    <h1 class="display-5 fw-bold mt-3 mb-3">{{ $settings->title }}</h1>
                    <p class="lead mb-0">{{ $settings->description }}</p>
                </div>
                <div class="col-lg-5 text-lg-end">
                    <div class="celebration-hero-badge">
                        <i class="fas fa-image"></i>
                        <span>নাম ও পদবি দিয়ে<br>নিজের কার্ড বানান</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <div class="row g-4 align-items-start">
            <div class="col-lg-4">
                <div class="celebration-panel">
                    <div class="celebration-panel-heading">
                        <span class="celebration-step">১</span>
                        <div>
                            <h2 class="h5 mb-1">আপনার তথ্য দিন</h2>
                            <p class="text-muted small mb-0">কার্ডের নাম ও পদবি এখানে লিখুন</p>
                        </div>
                    </div>

                    <form id="celebrationCardForm" novalidate>
                        <div class="mb-3">
                            <label for="celebrationName" class="form-label fw-semibold">নাম <span class="text-danger">*</span></label>
                            <input id="celebrationName" type="text" class="form-control form-control-lg" maxlength="100" value="{{ $initialName }}" placeholder="Mir Javed Jeetu" autocomplete="name" required>
                            <div class="form-text">কার্ডে যে নামটি দেখাতে চান</div>
                        </div>
                        <div class="mb-4">
                            <label for="celebrationDesignation" class="form-label fw-semibold">পদবি</label>
                            <input id="celebrationDesignation" type="text" class="form-control form-control-lg" maxlength="100" value="{{ $initialDesignation }}" placeholder="Developer" autocomplete="organization-title">
                            <div class="form-text">পদবি না দিলে এই অংশটি দেখাবে না</div>
                        </div>

                        <div class="celebration-tip">
                            <i class="fas fa-lightbulb me-2"></i>
                            <span>নাম ও পদবি লেখার সঙ্গে সঙ্গে ডান পাশে কার্ডের preview বদলে যাবে।</span>
                        </div>
                        <div class="mb-4">
                            <label for="celebrationPhoto" class="form-label fw-semibold">Visitor photo</label>
                            <input id="celebrationPhoto" type="file" class="form-control form-control-lg" accept="image/jpeg,image/png">
                            <div class="form-text">যেকোনো সাইজের JPG বা PNG ছবি দিন—ছবিটি সুন্দরভাবে auto-fit হয়ে card-এর curve-এর মধ্যে বসবে।</div>
                        </div>
                    </form>
                </div>

                <div class="celebration-panel mt-4">
                    <div class="celebration-panel-heading mb-3">
                        <span class="celebration-step">২</span>
                        <div>
                            <h2 class="h5 mb-1">ডাউনলোড বা শেয়ার</h2>
                            <p class="text-muted small mb-0">সামাজিক মাধ্যমে পোস্ট করুন</p>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success btn-lg" id="downloadPngBtn" disabled>
                            <i class="fas fa-download me-2"></i>PNG ডাউনলোড
                        </button>
                        <button type="button" class="btn btn-outline-dark btn-lg" id="downloadJpgBtn" disabled>
                            <i class="fas fa-file-image me-2"></i>JPG ডাউনলোড
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-lg" id="shareCardBtn" disabled>
                            <i class="fas fa-share-nodes me-2"></i>সরাসরি শেয়ার করুন
                        </button>
                    </div>
                    <p class="text-muted small mt-3 mb-0"><i class="fas fa-shield-heart me-1"></i>PNG/JPG download করলে নাম, পদবি ও upload করা photo admin panel-এর download history-তে save হবে।</p>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="celebration-preview-panel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="text-uppercase text-muted small fw-bold letter-spacing-1">লাইভ প্রিভিউ</span>
                            <h2 class="h4 mb-0">আপনার শুভেচ্ছা কার্ড</h2>
                        </div>
                        <span class="badge rounded-pill text-bg-light"><i class="fas fa-square me-1"></i>1080 × 1080</span>
                    </div>
                    <div class="celebration-preview-stage">
                        @include('partials.celebration-card-art', [
                            'settings' => $settings,
                            'cardId' => 'celebrationCard',
                            'cardName' => $initialName ?: 'Mir Javed Jeetu',
                            'cardDesignation' => $initialDesignation ?: 'Developer',
                            'cardPhoto' => $initialPhoto,
                        ])
                    </div>
                    <p class="text-muted text-center small mt-3 mb-0"><i class="fas fa-arrows-rotate me-1"></i>তথ্য পরিবর্তন করলে preview স্বয়ংক্রিয়ভাবে আপডেট হবে</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .celebration-card-page { background: #f7f8fa; min-height: 70vh; overflow-x: hidden; }
    .celebration-card-page .container,
    .celebration-panel,
    .celebration-preview-panel { min-width: 0; }
    .celebration-card-hero {
        color: #fff;
        background:
            radial-gradient(circle at 86% 15%, rgba(255, 221, 119, .28), transparent 24%),
            linear-gradient(135deg, #6f1d2c 0%, #8f3c38 48%, #c9973e 100%);
    }
    .celebration-card-hero h1 { letter-spacing: -.02em; }
    .celebration-card-hero p { color: rgba(255,255,255,.82); max-width: 650px; }
    .celebration-eyebrow {
        display: inline-flex; align-items: center; padding: .45rem .85rem; border-radius: 999px;
        color: #fff3c4; background: rgba(255,255,255,.12); font-weight: 600; font-size: .9rem;
    }
    .celebration-hero-badge {
        display: inline-flex; align-items: center; gap: .85rem; text-align: left; padding: 1rem 1.25rem;
        border: 1px solid rgba(255,255,255,.25); border-radius: 1rem; background: rgba(255,255,255,.1);
        font-weight: 600; line-height: 1.35;
    }
    .celebration-hero-badge i { color: #ffe08a; font-size: 1.7rem; }
    .celebration-panel, .celebration-preview-panel {
        border: 0; border-radius: 1.25rem; background: #fff; padding: 1.35rem;
        box-shadow: 0 .5rem 1.5rem rgba(36, 27, 25, .08);
    }
    .celebration-preview-panel > .d-flex { gap: .75rem; flex-wrap: wrap; }
    .celebration-preview-panel > .d-flex > div { min-width: 0; }
    .celebration-preview-panel h2 { overflow-wrap: anywhere; }
    .celebration-panel-heading { display: flex; align-items: center; gap: .8rem; margin-bottom: 1.35rem; }
    .celebration-step {
        display: inline-flex; align-items: center; justify-content: center; flex: 0 0 2.25rem; height: 2.25rem;
        color: #fff; border-radius: 50%; background: linear-gradient(135deg, #8c2f39, #c9973e); font-weight: 700;
    }
    .celebration-tip { display: flex; gap: .55rem; padding: .8rem .9rem; border-radius: .8rem; color: #765f25; background: #fff8df; font-size: .88rem; line-height: 1.45; }
    .celebration-tip i { color: #c9973e; margin-top: .18rem; }
    .celebration-preview-stage { padding: clamp(.5rem, 2vw, 1.5rem); border-radius: 1rem; background: radial-gradient(circle at top, #fffdf7 0%, #f0f2f5 72%); }
    .celebration-card-art { width: min(100%, 620px); aspect-ratio: 1 / 1; margin: 0 auto; color: #341712; }
    .celebration-card-surface { position: relative; width: 100%; height: 100%; overflow: hidden; border-radius: .75rem; background: #f4ecdf; box-shadow: 0 1rem 2.5rem rgba(51, 34, 20, .18); }
    .celebration-card-photo { position: absolute; z-index: 3; top: 5.5%; left: 50%; width: 34%; height: 34%; transform: translateX(-50%); object-fit: contain; object-position: center center; background: rgba(255, 255, 255, .72); border: clamp(5px, .75vw, 9px) solid #dc6b73; border-radius: 50%; box-shadow: 0 .55rem 1.4rem rgba(51, 34, 20, .24); }
    .celebration-card-template-image { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
    .celebration-template-person { position: absolute; z-index: 3; left: 8%; right: 8%; bottom: 4%; display: flex; flex-direction: column; align-items: center; gap: .2rem; text-align: center; }
    .celebration-person-name,
    .celebration-template-person-name { display: block; max-width: 90%; overflow: hidden; padding: .1em .62em; border: 2px solid rgba(182, 138, 45, .72); border-bottom: 4px solid #7f2330; border-radius: 999px; color: #7f2330; background: rgba(255, 252, 244, .95); box-shadow: 0 3px 12px rgba(80, 20, 15, .16); font-family: Georgia, 'Times New Roman', 'Hind Siliguri', serif; font-size: clamp(1.05rem, 2.8vw, 2.15rem); font-weight: 700; line-height: 1.15; white-space: nowrap; text-overflow: ellipsis; }
    .celebration-template-person-designation { display: block; max-width: 90%; min-width: 0; overflow: hidden; padding: .06em .62em; border: 2px solid rgba(182, 138, 45, .58); border-radius: 999px; color: #111; background: rgba(255, 252, 244, .95); box-shadow: 0 2px 7px rgba(80, 20, 15, .1); font-family: Georgia, 'Times New Roman', 'Hind Siliguri', serif; font-size: clamp(.82rem, 1.9vw, 1.35rem); font-weight: 600; line-height: 1.2; white-space: nowrap; text-overflow: clip; }
    .celebration-card-ribbons { position: absolute; inset: 0; width: 100%; height: 100%; }
    .celebration-card-content { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; padding: 9% 8% 7%; text-align: center; }
    .celebration-brand { display: flex; align-items: center; justify-content: center; gap: 1.25%; margin-top: 4%; max-width: 80%; }
    .celebration-brand-logo { width: 9%; min-width: 34px; aspect-ratio: 1; object-fit: contain; filter: drop-shadow(0 2px 2px rgba(0,0,0,.12)); }
    .celebration-brand-copy { line-height: 1.05; }
    .celebration-brand-name { color: #28221d; font-size: clamp(1rem, 3.4vw, 2rem); font-weight: 800; letter-spacing: .02em; }
    .celebration-brand-tagline { color: #6f6258; font-size: clamp(.62rem, 1.55vw, .95rem); margin-top: .4rem; }
    .celebration-headline { max-width: 86%; margin-top: 17%; color: #b10f14; font-size: clamp(1.2rem, 4.4vw, 3rem); font-weight: 800; line-height: 1.25; text-shadow: 0 1px 0 rgba(255,255,255,.65); }
    .celebration-rule { width: 38%; height: 5px; margin: 4% 0 2%; border-radius: 99px; background: #c99f46; position: relative; }
    .celebration-rule span { position: absolute; width: 22%; height: 100%; left: 39%; border-radius: inherit; background: #8c2f39; }
    .celebration-person { margin-top: 1%; min-height: 19%; display: flex; flex-direction: column; align-items: center; justify-content: center; }
    .celebration-person-name { display: block; max-width: 90%; overflow: hidden; padding: .1em .62em; border: 2px solid rgba(182, 138, 45, .72); border-bottom: 4px solid #7f2330; border-radius: 999px; color: #7f2330; background: rgba(255, 252, 244, .95); box-shadow: 0 3px 12px rgba(80, 20, 15, .16); font-family: Georgia, 'Times New Roman', 'Hind Siliguri', serif; font-size: clamp(1.05rem, 3.2vw, 2.4rem); font-weight: 700; line-height: 1.15; white-space: nowrap; text-overflow: ellipsis; }
    .celebration-person-name.celebration-person-long,
    .celebration-template-person-name.celebration-person-long { font-size: clamp(.95rem, 2.45vw, 1.95rem); }
    .celebration-person-designation { display: block; max-width: 90%; min-width: 0; overflow: hidden; padding: .06em .62em; border: 2px solid rgba(182, 138, 45, .58); border-radius: 999px; color: #111; background: rgba(255, 252, 244, .95); box-shadow: 0 2px 7px rgba(80, 20, 15, .1); font-family: Georgia, 'Times New Roman', 'Hind Siliguri', serif; font-size: clamp(.82rem, 1.9vw, 1.35rem); font-weight: 600; line-height: 1.2; white-space: nowrap; text-overflow: clip; }
    .celebration-footer { margin-top: auto; color: #766052; font-size: clamp(.7rem, 1.8vw, 1.1rem); font-weight: 600; }
    .letter-spacing-1 { letter-spacing: .12em; }
    @media (max-width: 575.98px) {
        .celebration-card-hero .container { padding-top: 2.5rem !important; padding-bottom: 2.5rem !important; }
        .celebration-card-hero h1 { font-size: clamp(1.7rem, 8vw, 2.35rem); }
        .celebration-card-hero .lead { font-size: 1rem; line-height: 1.55; }
        .celebration-hero-badge { margin-top: .5rem; max-width: 100%; }
        .celebration-panel, .celebration-preview-panel { padding: 1rem; border-radius: 1rem; }
        .celebration-preview-panel > .d-flex { align-items: flex-start !important; margin-bottom: .75rem !important; }
        .celebration-preview-panel > .d-flex .badge { margin-left: auto; font-size: .68rem; white-space: nowrap; }
        .celebration-preview-panel h2 { font-size: 1.1rem; }
        .celebration-panel .form-control-lg { font-size: 1rem; min-height: 3rem; }
        .celebration-panel .btn-lg { min-height: 3rem; font-size: 1rem; touch-action: manipulation; }
        .celebration-tip { font-size: .82rem; }
        .celebration-preview-stage { padding: .4rem; }
        .celebration-card-surface { border-radius: .5rem; }
    }
    @media (max-width: 359.98px) {
        .celebration-card-page > .container { padding-left: .75rem; padding-right: .75rem; }
        .celebration-panel, .celebration-preview-panel { padding: .8rem; }
        .celebration-preview-panel > .d-flex .badge { font-size: .62rem; }
    }
</style>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const card = document.getElementById('celebrationCard');
    const nameInput = document.getElementById('celebrationName');
    const designationInput = document.getElementById('celebrationDesignation');
    const photoInput = document.getElementById('celebrationPhoto');
    const photoElement = card.querySelector('.celebration-card-photo');
    const pngButton = document.getElementById('downloadPngBtn');
    const jpgButton = document.getElementById('downloadJpgBtn');
    const shareButton = document.getElementById('shareCardBtn');
    let busy = false;

    function fitTextToBadge(element, minimumScale = .56) {
        if (!element || element.style.display === 'none') return;

        element.style.fontSize = '';
        element.style.whiteSpace = 'nowrap';
        element.style.overflowWrap = 'normal';
        element.style.textOverflow = 'clip';
        element.style.lineHeight = '';

        let fontSize = parseFloat(getComputedStyle(element).fontSize);
        const minimumFontSize = Math.max(12, fontSize * minimumScale);

        while (element.scrollWidth > element.clientWidth + 1 && fontSize > minimumFontSize) {
            fontSize = Math.max(minimumFontSize, fontSize - .5);
            element.style.fontSize = `${fontSize}px`;
        }

        // Extremely long Bangla/English text remains visible instead of being clipped.
        if (element.scrollWidth > element.clientWidth + 1) {
            element.style.whiteSpace = 'normal';
            element.style.overflowWrap = 'anywhere';
            element.style.lineHeight = '1.08';
        }
    }

    function setCardText() {
        const name = nameInput.value.trim();
        const designation = designationInput.value.trim();
        const nameElement = card.querySelector('.celebration-person-name');
        nameElement.textContent = name || 'Mir Javed Jeetu';
        nameElement.classList.toggle('celebration-person-long', Array.from(name).length > 24);
        fitTextToBadge(nameElement, .58);
        const designationElement = card.querySelector('.celebration-person-designation');
        designationElement.textContent = designation;
        designationElement.style.display = designation ? '' : 'none';
        fitTextToBadge(designationElement, .52);
        const enabled = Boolean(name);
        [pngButton, jpgButton, shareButton].forEach(button => button.disabled = !enabled || busy);
    }

    function setCardPhoto(event) {
        const file = event.target.files[0];
        if (!file) {
            photoElement.removeAttribute('src');
            photoElement.style.display = 'none';
            return;
        }

        if (!['image/jpeg', 'image/png'].includes(file.type) || file.size > 2 * 1024 * 1024) {
            alert('Please choose a JPG or PNG image smaller than 2MB.');
            event.target.value = '';
            photoElement.removeAttribute('src');
            photoElement.style.display = 'none';
            return;
        }

        const reader = new FileReader();
        reader.onload = () => {
            photoElement.src = reader.result;
            photoElement.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }

    async function waitForImage(image) {
        if (!image || !image.src || image.style.display === 'none') return;
        if (!image.complete) {
            await new Promise(resolve => {
                image.addEventListener('load', resolve, { once: true });
                image.addEventListener('error', resolve, { once: true });
            });
        }
        if (image.naturalWidth && image.decode) await image.decode().catch(() => {});
    }

    async function renderCard() {
        if (!window.html2canvas) throw new Error('Card renderer is not ready');
        await document.fonts?.ready;
        await Promise.all(Array.from(card.querySelectorAll('img')).map(waitForImage));
        const cardWidth = card.getBoundingClientRect().width;
        return window.html2canvas(card, {
            // Keep the downloaded image social-media friendly at exactly 1080x1080.
            scale: 1080 / cardWidth,
            useCORS: true,
            backgroundColor: null,
            logging: false,
            imageTimeout: 15000
        });
    }

    function filename(extension) {
        const safeName = (nameInput.value.trim() || 'shubhechha-card').replace(/[^\p{L}\p{N}\s_-]/gu, '').trim().replace(/\s+/g, '-');
        return `${safeName || 'shubhechha-card'}-explore-satkhira.${extension}`;
    }

    async function logGeneration(format, canvas) {
        const payload = new FormData();
        payload.append('name', nameInput.value.trim());
        payload.append('designation', designationInput.value.trim());
        payload.append('download_format', format);
        if (photoInput.files[0]) payload.append('photo', photoInput.files[0]);
        const cardBlob = await new Promise(resolve => canvas.toBlob(resolve, format === 'jpg' ? 'image/jpeg' : 'image/png', .95));
        if (!cardBlob) throw new Error('Unable to create the downloaded card image');
        payload.append('card_image', cardBlob, `celebration-card.${format}`);

        const response = await fetch(@js(route('celebration-card.generations.store')), {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: payload
        });

        if (!response.ok) throw new Error('Unable to save download history');
    }

    async function downloadCard(type) {
        if (!nameInput.value.trim() || busy) return;
        busy = true;
        setCardText();
        const button = type === 'png' ? pngButton : jpgButton;
        const original = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>তৈরি হচ্ছে...';
        try {
            const canvas = await renderCard();
            const mime = type === 'jpg' ? 'image/jpeg' : 'image/png';
            await logGeneration(type, canvas);
            const link = document.createElement('a');
            link.download = filename(type);
            link.href = canvas.toDataURL(mime, .95);
            link.click();
        } catch (error) {
            alert('কার্ড তৈরি বা history-তে save করা যাচ্ছে না। আবার চেষ্টা করুন।');
        } finally {
            busy = false;
            button.innerHTML = original;
            setCardText();
        }
    }

    async function shareCard() {
        if (!nameInput.value.trim() || busy) return;
        busy = true;
        setCardText();
        const original = shareButton.innerHTML;
        shareButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>প্রস্তুত হচ্ছে...';
        try {
            const canvas = await renderCard();
            const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/png'));
            if (!blob) throw new Error('Unable to create card image');
            const file = new File([blob], filename('png'), { type: 'image/png' });
            const text = `${nameInput.value.trim()}${designationInput.value.trim() ? ' — ' + designationInput.value.trim() : ''}`;
            if (navigator.share && (!navigator.canShare || navigator.canShare({ files: [file] }))) {
                await navigator.share({ title: @js($settings->title), text, files: [file] });
            } else {
                await navigator.clipboard?.writeText(`${text}\n${@js(url('/celebration-card'))}`);
                alert('এই ডিভাইসে সরাসরি image share নেই। PNG ডাউনলোড করে Facebook/WhatsApp-এ পোস্ট করুন।');
            }
        } catch (error) {
            if (error.name !== 'AbortError') alert('শেয়ার করা যায়নি। আগে PNG ডাউনলোড করে শেয়ার করুন।');
        } finally {
            busy = false;
            shareButton.innerHTML = original;
            setCardText();
        }
    }

    [nameInput, designationInput].forEach(input => input.addEventListener('input', setCardText));
    photoInput.addEventListener('change', setCardPhoto);
    pngButton.addEventListener('click', () => downloadCard('png'));
    jpgButton.addEventListener('click', () => downloadCard('jpg'));
    shareButton.addEventListener('click', shareCard);
    setCardText();
    document.fonts?.ready.then(setCardText);
    window.addEventListener('resize', setCardText, { passive: true });
});
</script>
@endpush
