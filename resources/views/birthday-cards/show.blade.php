@extends('frontend.layouts.app')

@section('title', $birthdayCard->user->name . ' এর জন্মদিন')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            @php
                $profilePhoto = $birthdayCard->user->avatar
                    ? asset('storage/' . $birthdayCard->user->avatar)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($birthdayCard->user->name) . '&background=16a34a&color=fff&size=300';
                $cardTitle = $birthdayCard->english_message ?: 'জন্মদিন মোবারক!';
                $wishMessage = $birthdayCard->bengali_message ?: 'আপনার এই বিশেষ দিনে এক্সপ্লোর সাতক্ষীরার পক্ষ থেকে রইলো আন্তরিক শুভেচ্ছা, সুস্বাস্থ্য ও সফলতার প্রার্থনা।';

                $roleLabel = 'সম্মানিত সদস্য';
                if ($birthdayCard->user->id === 4) {
                    $roleLabel = 'প্রতিষ্ঠাতা';
                } elseif ($birthdayCard->user->isSuperAdmin()) {
                    $roleLabel = 'সুপার অ্যাডমিন';
                } elseif ($birthdayCard->user->isAdmin()) {
                    $roleLabel = 'অ্যাডমিন';
                } elseif ($birthdayCard->user->isModerator()) {
                    $roleLabel = 'মডারেটর';
                } elseif ($birthdayCard->user->is_upazila_moderator) {
                    $roleLabel = 'উপজেলা মডারেটর';
                }
            @endphp

            <!-- Birthday Card -->
            <div class="birthday-print-wrap mb-4" id="birthdayCard">
                <div class="birthday-brand-row">
                    <div class="d-flex align-items-center gap-2">
                        <img src="{{ asset('icons/app-logo-96.png') }}" alt="Explore Satkhira" class="birthday-brand-logo">
                        <div>
                            <h6 class="mb-0 fw-bold">Explore Satkhira</h6>
                            <small>এক্সপ্লোর সাতক্ষীরা</small>
                        </div>
                    </div>
                    <span class="birthday-badge">Official Birthday Wish</span>
                </div>

                <div class="text-center mt-3">
                    <img src="{{ $profilePhoto }}" alt="{{ $birthdayCard->user->name }}" class="birthday-profile-photo mb-3">
                    <h3 class="fw-bold mb-1">{{ $birthdayCard->user->name }}</h3>
                    <span class="badge text-bg-light border mb-2">{{ $roleLabel }}</span>
                    @if($birthdayCard->user->teamMember)
                        <p class="text-dark fw-semibold mb-2" style="font-size: 0.95rem;">{{ $birthdayCard->user->teamMember->designation_display ?: $birthdayCard->user->teamMember->designation_bn ?: $birthdayCard->user->teamMember->designation ?: '' }}</p>
                    @endif
                    <p class="text-muted mb-0">{{ now()->format('d F Y') }}</p>
                </div>

                <div class="birthday-title-box mt-4">
                    <h1 class="mb-2">{{ $cardTitle }}</h1>
                    <div class="title-underline"></div>
                </div>

                <div class="birthday-message-box mt-4">
                    <p class="mb-0">{{ $wishMessage }}</p>
                </div>

                <div class="birthday-footer mt-4">
                    <span><i class="fas fa-globe-asia me-1"></i>www.exploresatkhira.com</span>
                    <span><i class="fas fa-heart text-danger me-1"></i>ভালোবাসায়, এক্সপ্লোর সাতক্ষীরা</span>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 mb-4">
                <button class="btn btn-warning btn-lg" onclick="downloadCardPng()">
                    <i class="fas fa-download me-1"></i> PNG ডাউনলোড করুন
                </button>
                <a href="{{ route('birthday-cards.todays') }}" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-heart me-1"></i> সব শুভেচ্ছা দেখুন
                </a>
            </div>

            <!-- Comments Section -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-heart"></i> শুভেচ্ছা জানান ({{ $birthdayCard->comments->count() }})
                    </h5>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Comments List -->
                    @if($birthdayCard->comments->count() > 0)
                        <div class="mb-4">
                            <h6 class="mb-3">সর্বশেষ শুভেচ্ছা</h6>
                            @foreach($birthdayCard->comments as $comment)
                                <div class="card mb-3 border-light">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="card-title mb-1">{{ $comment->visitor_name }}</h6>
                                                <small class="text-muted">{{ $comment->visitor_phone }}</small>
                                            </div>
                                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="card-text">{{ $comment->wish_message }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Comment Form -->
                    <div class="mt-4 pt-4 border-top">
                        <h6 class="mb-3">আপনার শুভেচ্ছা জানান</h6>

                        <form action="{{ route('birthday-cards.comment', $birthdayCard->id) }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="visitor_name" class="form-label">আপনার নাম <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('visitor_name') is-invalid @enderror"
                                    id="visitor_name" name="visitor_name" placeholder="আপনার নাম লিখুন"
                                    value="{{ old('visitor_name') }}" required>
                                @error('visitor_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="visitor_phone" class="form-label">আপনার ফোন নম্বর <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('visitor_phone') is-invalid @enderror"
                                    id="visitor_phone" name="visitor_phone" placeholder="+880 1XXX XXXXXX"
                                    value="{{ old('visitor_phone') }}" required>
                                @error('visitor_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted d-block mt-1">
                                    <i class="fas fa-info-circle"></i> আপনি এই ফোন নম্বর দিয়ে শুধু একবার শুভেচ্ছা জানাতে পারবেন।
                                </small>
                            </div>

                            <div class="mb-3">
                                <label for="wish_message" class="form-label">আপনার শুভেচ্ছা <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('wish_message') is-invalid @enderror"
                                    id="wish_message" name="wish_message" rows="4"
                                    placeholder="আপনার শুভেচ্ছা লিখুন..." required>{{ old('wish_message') }}</textarea>
                                @error('wish_message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted d-block mt-1">সর্বোচ্চ ৫০০ অক্ষর</small>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-heart"></i> শুভেচ্ছা পাঠান
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
    function downloadCardPng() {
        const element = document.getElementById('birthdayCard');
        if (!element) return;

        html2canvas(element, {
            backgroundColor: '#ffffff',
            scale: 2,
            useCORS: true,
            logging: false
        }).then(canvas => {
            const link = document.createElement('a');
            link.download = 'birthday-card-{{ $birthdayCard->user->name }}.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    }
</script>

<style>
    .birthday-print-wrap {
        background: linear-gradient(145deg, #ffffff 0%, #fff8ee 40%, #eefbf3 100%);
        border: 1px solid #f1e4c9;
        border-radius: 18px;
        padding: 28px;
        box-shadow: 0 14px 34px rgba(0, 0, 0, 0.10);
    }
    .birthday-brand-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding-bottom: 12px;
        border-bottom: 1px dashed #e5e7eb;
    }
    .birthday-brand-logo {
        width: 42px;
        height: 42px;
        border-radius: 12px;
    }
    .birthday-badge {
        background: #0ea5e9;
        color: #fff;
        border-radius: 999px;
        padding: 6px 12px;
        font-size: 0.8rem;
        font-weight: 700;
        white-space: nowrap;
    }
    .birthday-profile-photo {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #fff;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.2);
    }
    .birthday-title-box h1 {
        font-size: 2rem;
        font-weight: 800;
        text-align: center;
        color: #b45309;
    }
    .title-underline {
        width: 120px;
        height: 4px;
        margin: 0 auto;
        border-radius: 999px;
        background: linear-gradient(90deg, #f59e0b, #ef4444);
    }
    .birthday-message-box {
        background: #fff;
        border: 1px solid #f3e8cf;
        border-left: 4px solid #16a34a;
        border-radius: 12px;
        padding: 16px;
        font-size: 1.05rem;
        line-height: 1.8;
        color: #374151;
    }
    .birthday-footer {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        font-size: 0.9rem;
        color: #4b5563;
    }

    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
        .birthday-print-wrap {
            padding: 18px;
            border-radius: 12px;
            margin: 0 -12px;
        }

        .birthday-brand-row {
            gap: 8px;
            padding-bottom: 8px;
        }

        .birthday-brand-row h6 {
            font-size: 0.9rem;
        }

        .birthday-brand-row small {
            font-size: 0.75rem;
        }

        .birthday-badge {
            padding: 4px 8px;
            font-size: 0.7rem;
        }

        .birthday-profile-photo {
            width: 90px;
            height: 90px;
            border: 3px solid #fff;
        }

        .birthday-title-box h1 {
            font-size: 1.5rem;
            margin-top: 1rem;
        }

        .title-underline {
            width: 80px;
            height: 3px;
        }

        .birthday-message-box {
            padding: 12px;
            font-size: 0.95rem;
            border-left-width: 3px;
        }

        .birthday-footer {
            gap: 8px;
            font-size: 0.8rem;
            text-align: center;
            flex-direction: column;
        }

        .birthday-footer span {
            display: block;
            width: 100%;
        }

        /* Button Responsive */
        .d-flex.flex-wrap.gap-2.mb-4 {
            gap: 0.5rem !important;
        }

        .d-flex.flex-wrap.gap-2.mb-4 .btn {
            flex: 1;
            min-width: 150px;
            font-size: 0.9rem;
        }
    }

    @media (max-width: 480px) {
        .container.py-5 {
            padding: 1rem 0 !important;
        }

        .birthday-print-wrap {
            padding: 12px;
            border-radius: 8px;
        }

        .birthday-brand-row {
            gap: 4px;
        }

        .birthday-brand-logo {
            width: 36px;
            height: 36px;
        }

        .birthday-profile-photo {
            width: 80px;
            height: 80px;
            margin-bottom: 1rem !important;
        }

        .birthday-title-box h1 {
            font-size: 1.3rem;
        }

        .birthday-title-box {
            margin-top: 0.8rem !important;
        }

        .birthday-message-box {
            padding: 10px;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .birthday-footer {
            font-size: 0.75rem;
        }

        .d-flex.flex-wrap.gap-2.mb-4 {
            flex-direction: column;
            gap: 0.5rem !important;
        }

        .d-flex.flex-wrap.gap-2.mb-4 .btn {
            width: 100%;
            padding: 0.6rem !important;
            font-size: 0.85rem;
        }
    }

    @media print {
        .navbar, .footer, .btn, form, .card-header, .card-body .mt-4.pt-4.border-top { display: none !important; }
        body { background: #fff !important; }
        .birthday-print-wrap { 
            box-shadow: none; 
            border: 1px solid #ddd;
            padding: 20px;
            margin: 0;
        }
        .container.py-5 {
            padding: 0 !important;
        }
        .row { margin: 0 !important; }
        .col-lg-8 { max-width: 100% !important; }
    }
</style>
@endsection
