@php
    $cardId = $cardId ?? 'celebrationCard';
    $cardName = $cardName ?? 'আপনার নাম';
    $cardDesignation = $cardDesignation ?? 'আপনার পদবি';
@endphp

@php($cardPhoto = $cardPhoto ?? null)

    <div id="{{ $cardId }}" class="celebration-card-art" role="img" aria-label="{{ $settings->headline }}">
    <div class="celebration-card-surface">
        <img src="{{ $cardPhoto ?? '' }}" alt="" class="celebration-card-photo" style="{{ $cardPhoto ? '' : 'display: none;' }}" crossorigin="anonymous">
        @if($settings->template_image_path)
            <img src="{{ asset('storage/' . $settings->template_image_path) }}" alt="" class="celebration-card-template-image" crossorigin="anonymous">
            <div class="celebration-template-person">
                <div class="celebration-person-name celebration-template-person-name">{{ $cardName }}</div>
                <div class="celebration-person-designation celebration-template-person-designation">{{ $cardDesignation }}</div>
            </div>
        @else
        <svg class="celebration-card-ribbons" viewBox="0 0 1080 1080" preserveAspectRatio="none" aria-hidden="true">
            <path d="M-80 20 C210 120 285 220 460 300 C640 382 825 378 1160 215 L1160 0 L-80 0 Z" fill="#eee6da" />
            <path d="M-100 410 C220 650 720 690 1180 405" fill="none" stroke="#ffffff" stroke-width="110" opacity=".96" />
            <path d="M-100 465 C240 710 730 750 1180 465" fill="none" stroke="#dfbf56" stroke-width="10" opacity=".9" />
            <path d="M-110 505 C240 760 720 805 1190 500" fill="none" stroke="#ffffff" stroke-width="18" opacity=".85" />
            <path d="M-120 900 C160 785 360 790 525 875 C715 975 870 980 1190 810 L1190 1080 L-120 1080 Z" fill="#eee2cf" opacity=".8" />
            <circle cx="943" cy="877" r="4" fill="#d1aa3b" opacity=".7" />
            <circle cx="978" cy="900" r="3" fill="#d1aa3b" opacity=".55" />
            <circle cx="1014" cy="924" r="4" fill="#d1aa3b" opacity=".7" />
            <circle cx="1050" cy="948" r="3" fill="#d1aa3b" opacity=".55" />
        </svg>

        <div class="celebration-card-content">
            <div class="celebration-brand">
                <img src="{{ asset('icons/app-logo-192.png') }}" alt="" class="celebration-brand-logo">
                <div class="celebration-brand-copy">
                    <div class="celebration-brand-name">{{ $settings->brand_name }}</div>
                    @if($settings->brand_tagline)
                        <div class="celebration-brand-tagline">{{ $settings->brand_tagline }}</div>
                    @endif
                </div>
            </div>

            <div class="celebration-headline">{{ $settings->headline }}</div>
            <div class="celebration-rule" aria-hidden="true"><span></span></div>

            <div class="celebration-person">
                <div class="celebration-person-name">{{ $cardName }}</div>
                <div class="celebration-person-designation">{{ $cardDesignation }}</div>
            </div>

            @if($settings->footer_text)
                <div class="celebration-footer">{{ $settings->footer_text }}</div>
            @endif
        </div>
        @endif
    </div>
</div>
