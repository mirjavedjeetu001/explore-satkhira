@extends('frontend.layouts.app')

@section('title', 'আজকের জন্মদিন')

@section('content')
<section id="birthday-todays-page" class="py-5">
    <div class="container">
        <div class="birthday-top-banner mb-4 mb-lg-5">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h1 class="mb-2"><i class="fas fa-cake-candles me-2 text-danger"></i>আজকের জন্মদিন</h1>
                    <p class="mb-0 text-muted">{{ now()->format('d F Y, l') }} • এক্সপ্লোর সাতক্ষীরার পক্ষ থেকে আন্তরিক শুভেচ্ছা</p>
                </div>
                <button type="button" class="btn btn-success" onclick="shareTodaysBirthdayPage()">
                    <i class="fas fa-share-alt me-2"></i>এই লিংক শেয়ার করুন
                </button>
            </div>
        </div>

        @if($birthdayCards->isEmpty())
            <div class="alert alert-info text-center rounded-4 shadow-sm">
                <i class="fas fa-info-circle me-1"></i>
                <strong>আজ কোন জন্মদিন নেই।</strong> নতুন আপডেটের জন্য আবার দেখুন।
            </div>
        @else
            <div class="row g-4">
                @foreach($birthdayCards as $card)
                    @php
                        $profilePhoto = $card->user->avatar
                            ? asset('storage/' . $card->user->avatar)
                            : 'https://ui-avatars.com/api/?name=' . urlencode($card->user->name) . '&background=16a34a&color=fff&size=300';

                        $roleLabel = 'সম্মানিত সদস্য';
                        if ($card->user->isSuperAdmin()) {
                            $roleLabel = 'সুপার অ্যাডমিন';
                        } elseif ($card->user->isAdmin()) {
                            $roleLabel = 'অ্যাডমিন';
                        } elseif ($card->user->isModerator()) {
                            $roleLabel = 'মডারেটর';
                        } elseif ($card->user->is_upazila_moderator) {
                            $roleLabel = 'উপজেলা মডারেটর';
                        }
                    @endphp

                    <div class="col-xl-6">
                        <div class="birthday-card-shell h-100">
                            <div class="birthday-preview-card" id="birthdayCard-{{ $card->id }}">
                                <div class="birthday-head d-flex justify-content-between align-items-center gap-2 mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ asset('icons/app-logo-96.png') }}" alt="Explore Satkhira" class="es-logo-sm">
                                        <div>
                                            <h6 class="mb-0 fw-bold">Explore Satkhira</h6>
                                            <small class="text-muted">এক্সপ্লোর সাতক্ষীরা</small>
                                        </div>
                                    </div>
                                    <span class="badge rounded-pill text-bg-danger"><i class="fas fa-heart me-1"></i>শুভেচ্ছা</span>
                                </div>

                                <div class="text-center mb-3">
                                    <img src="{{ $profilePhoto }}" alt="{{ $card->user->name }}" class="birthday-photo mb-2">
                                    <h4 class="fw-bold mb-1">{{ $card->user->name }}</h4>
                                    <span class="badge text-bg-light border">{{ $roleLabel }}</span>
                                </div>

                                <div class="title-wrap text-center mb-3">
                                    <h5 class="mb-1 fw-bold text-warning">{{ $card->english_message ?: 'জন্মদিন মোবারক!' }}</h5>
                                    <div class="line"></div>
                                </div>

                                <div class="wish-box mb-3">
                                    <p class="mb-0">{{ $card->bengali_message ?: 'আপনার এই বিশেষ দিনে এক্সপ্লোর সাতক্ষীরার পক্ষ থেকে রইলো আন্তরিক শুভেচ্ছা, সুস্বাস্থ্য ও সফলতার প্রার্থনা।' }}</p>
                                </div>

                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <small class="text-muted"><i class="fas fa-comments me-1"></i>{{ $card->comments->count() }} টি শুভেচ্ছা</small>
                                    <small class="text-muted"><i class="fas fa-globe-asia me-1"></i>www.exploresatkhira.com</small>
                                </div>
                            </div>

                            <div class="birthday-actions d-flex flex-wrap gap-2 mt-3">
                                <button class="btn btn-warning" onclick="downloadCardAsPng('birthdayCard-{{ $card->id }}', '{{ $card->user->name }}')">
                                    <i class="fas fa-download me-1"></i> PNG ডাউনলোড
                                </button>
                                <button class="btn btn-outline-success" onclick="shareBirthdayCard('{{ route('birthday-cards.show', $card->id) }}', '{{ $card->user->name }}')">
                                    <i class="fas fa-share-alt me-1"></i> শেয়ার
                                </button>
                                <a href="{{ route('birthday-cards.show', $card->id) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i> বিস্তারিত + শুভেচ্ছা
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
    function downloadCardAsPng(cardId, userName) {
        const element = document.getElementById(cardId);
        if (!element) return;

        html2canvas(element, {
            backgroundColor: '#ffffff',
            scale: 2,
            useCORS: true,
            logging: false
        }).then(canvas => {
            const link = document.createElement('a');
            link.download = `birthday-card-${userName}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    }

    function copyTextToClipboard(text) {
        if (navigator.clipboard && window.isSecureContext) {
            return navigator.clipboard.writeText(text);
        }

        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        document.execCommand('copy');
        textArea.remove();
        return Promise.resolve();
    }

    function shareTodaysBirthdayPage() {
        const shareUrl = window.location.href;
        const shareTitle = 'আজকের জন্মদিন - Explore Satkhira';
        const shareText = 'এক্সপ্লোর সাতক্ষীরার আজকের জন্মদিনের শুভেচ্ছা পেইজ দেখুন';

        if (navigator.share) {
            navigator.share({
                title: shareTitle,
                text: shareText,
                url: shareUrl,
            }).catch(() => {});
            return;
        }

        copyTextToClipboard(shareUrl).then(() => {
            alert('লিংক কপি হয়েছে, এখন শেয়ার করতে পারবেন।');
        });
    }

    function shareBirthdayCard(url, name) {
        const shareTitle = `${name} এর জন্মদিন শুভেচ্ছা`;
        const shareText = `${name} এর জন্য এক্সপ্লোর সাতক্ষীরার জন্মদিন শুভেচ্ছা কার্ড`;

        if (navigator.share) {
            navigator.share({
                title: shareTitle,
                text: shareText,
                url: url,
            }).catch(() => {});
            return;
        }

        copyTextToClipboard(url).then(() => {
            alert('কার্ডের লিংক কপি হয়েছে, এখন শেয়ার করতে পারবেন।');
        });
    }
</script>

<style>
    #birthday-todays-page .birthday-top-banner {
        background: linear-gradient(135deg, #f8fff9 0%, #f7f4ff 100%);
        border: 1px solid #e6e9ef;
        border-radius: 16px;
        padding: 18px 20px;
    }

    #birthday-todays-page .birthday-card-shell {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #e8edf3;
        padding: 14px;
    }

    #birthday-todays-page .birthday-preview-card {
        background: linear-gradient(145deg, #ffffff 0%, #fff8ee 45%, #eefbf3 100%);
        border: 1px solid #f1e4c9;
        border-radius: 18px;
        padding: 20px;
        box-shadow: 0 12px 30px rgba(0,0,0,0.08);
    }

    #birthday-todays-page .birthday-actions .btn {
        min-height: 42px;
    }

    #birthday-todays-page .es-logo-sm {
        width: 36px;
        height: 36px;
        border-radius: 10px;
    }

    #birthday-todays-page .birthday-photo {
        width: 92px;
        height: 92px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.18);
    }

    #birthday-todays-page .title-wrap .line {
        width: 90px;
        height: 4px;
        border-radius: 999px;
        margin: 0 auto;
        background: linear-gradient(90deg, #f59e0b, #ef4444);
    }

    #birthday-todays-page .wish-box {
        background: #fff;
        border: 1px solid #f3e8cf;
        border-left: 4px solid #16a34a;
        border-radius: 10px;
        padding: 12px;
        color: #374151;
        line-height: 1.6;
        min-height: 88px;
    }

    @media (max-width: 576px) {
        #birthday-todays-page .birthday-top-banner h1 {
            font-size: 1.45rem;
        }
        #birthday-todays-page .birthday-actions {
            flex-direction: column;
        }
        #birthday-todays-page .birthday-actions .btn,
        #birthday-todays-page .birthday-actions a {
            width: 100%;
        }
    }
</style>
@endsection
