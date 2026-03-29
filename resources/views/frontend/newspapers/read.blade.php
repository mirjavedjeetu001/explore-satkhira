@extends('frontend.layouts.app')

@section('title', ($newspaper->title_bn ?? $newspaper->title) . ' - ' . $edition->edition_date->format('d/m/Y'))

@section('content')
<!-- Compact Header -->
<div class="bg-dark text-white py-2">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('newspapers.show', $newspaper) }}" class="btn btn-sm btn-outline-light">
                    <i class="fas fa-arrow-left me-1"></i>ফিরে যান
                </a>
                <h6 class="mb-0">
                    {{ $newspaper->title_bn ?? $newspaper->title }}
                    <small class="text-white-50 ms-2">{{ $edition->edition_date->translatedFormat('d F Y, l') }}</small>
                </h6>
            </div>
            <div class="d-flex gap-2">
                @if($edition->pdf_file)
                    <a href="{{ asset('storage/' . $edition->pdf_file) }}" target="_blank" class="btn btn-sm btn-danger">
                        <i class="fas fa-file-pdf me-1"></i>PDF ডাউনলোড
                    </a>
                @endif
                <button onclick="toggleFullscreen()" class="btn btn-sm btn-outline-light" title="ফুলস্ক্রিন">
                    <i class="fas fa-expand"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reader -->
<section class="newspaper-reader py-3" id="readerSection" style="background: #f0f0f0; min-height: 80vh;">
    <div class="container">
        @if($edition->title || $edition->description)
            <div class="text-center mb-3">
                @if($edition->title)
                    <h4 class="fw-bold">{{ $edition->title }}</h4>
                @endif
                @if($edition->description)
                    <p class="text-muted">{{ $edition->description }}</p>
                @endif
            </div>
        @endif

        @if($edition->pages && count($edition->pages) > 0)
            <!-- Page Navigation -->
            <div class="text-center mb-3">
                <div class="btn-group" role="group">
                    @foreach($edition->pages as $index => $page)
                        <button class="btn btn-sm {{ $index === 0 ? 'btn-primary' : 'btn-outline-primary' }} page-btn" onclick="showPage({{ $index }})">
                            {{ $index + 1 }}
                        </button>
                    @endforeach
                </div>
                <span class="ms-2 text-muted small">মোট {{ count($edition->pages) }} পৃষ্ঠা</span>
            </div>

            <!-- Page Display -->
            <div class="text-center" id="pageContainer">
                @foreach($edition->pages as $index => $page)
                    <div class="newspaper-page {{ $index === 0 ? '' : 'd-none' }}" data-page="{{ $index }}">
                        <img src="{{ asset('storage/' . $page) }}" alt="পৃষ্ঠা {{ $index + 1 }}" class="img-fluid shadow-lg rounded" style="max-width: 100%; cursor: zoom-in;" onclick="zoomImage(this)">
                    </div>
                @endforeach
            </div>

            <!-- Prev/Next Buttons -->
            <div class="d-flex justify-content-between mt-3">
                <button class="btn btn-outline-primary" id="prevBtn" onclick="changePage(-1)" disabled>
                    <i class="fas fa-chevron-left me-1"></i>আগের পৃষ্ঠা
                </button>
                <span class="align-self-center text-muted" id="pageInfo">পৃষ্ঠা ১ / {{ count($edition->pages) }}</span>
                <button class="btn btn-outline-primary" id="nextBtn" onclick="changePage(1)">
                    পরের পৃষ্ঠা <i class="fas fa-chevron-right ms-1"></i>
                </button>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-image fa-3x text-muted mb-3"></i>
                <p class="text-muted">এই সংস্করণে কোনো পৃষ্ঠা নেই</p>
            </div>
        @endif
    </div>
</section>

<!-- Zoom Modal -->
<div class="modal fade" id="zoomModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body d-flex align-items-center justify-content-center p-0" style="overflow: auto;">
                <img src="" id="zoomImg" class="img-fluid" style="max-width: none; cursor: zoom-out;" onclick="this.closest('.modal').querySelector('.btn-close').click()">
            </div>
        </div>
    </div>
</div>

<script>
    let currentPage = 0;
    const totalPages = {{ count($edition->pages ?? []) }};
    
    function showPage(index) {
        document.querySelectorAll('.newspaper-page').forEach(p => p.classList.add('d-none'));
        document.querySelector(`.newspaper-page[data-page="${index}"]`).classList.remove('d-none');
        
        document.querySelectorAll('.page-btn').forEach((btn, i) => {
            btn.classList.toggle('btn-primary', i === index);
            btn.classList.toggle('btn-outline-primary', i !== index);
        });
        
        currentPage = index;
        document.getElementById('prevBtn').disabled = currentPage === 0;
        document.getElementById('nextBtn').disabled = currentPage === totalPages - 1;
        document.getElementById('pageInfo').textContent = `পৃষ্ঠা ${currentPage + 1} / ${totalPages}`;
        
        window.scrollTo({ top: document.getElementById('pageContainer').offsetTop - 100, behavior: 'smooth' });
    }
    
    function changePage(dir) {
        const next = currentPage + dir;
        if (next >= 0 && next < totalPages) showPage(next);
    }
    
    function zoomImage(img) {
        document.getElementById('zoomImg').src = img.src;
        new bootstrap.Modal(document.getElementById('zoomModal')).show();
    }
    
    function toggleFullscreen() {
        const el = document.getElementById('readerSection');
        if (!document.fullscreenElement) {
            el.requestFullscreen?.() || el.webkitRequestFullscreen?.();
        } else {
            document.exitFullscreen?.() || document.webkitExitFullscreen?.();
        }
    }
    
    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') changePage(-1);
        if (e.key === 'ArrowRight') changePage(1);
    });
</script>
@endsection
