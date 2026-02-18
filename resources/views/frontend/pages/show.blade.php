@extends('frontend.layouts.app')

@section('title', $page->title_bn ?? $page->title)

@section('content')
    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <h1>{{ $page->title_bn ?? $page->title }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">হোম</a></li>
                    <li class="breadcrumb-item active">{{ $page->title_bn ?? $page->title }}</li>
                </ol>
            </nav>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card border-0 shadow-sm" data-aos="fade-up">
                        @if($page->image)
                            <img src="{{ asset('storage/' . $page->image) }}" class="card-img-top" alt="{{ $page->title }}" style="max-height: 400px; object-fit: cover;">
                        @endif
                        
                        <div class="card-body p-5">
                            <div class="page-content">
                                {!! $page->content !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<style>
    .page-content {
        font-size: 1.1rem;
        line-height: 1.8;
    }
    .page-content p {
        margin-bottom: 1.5rem;
    }
    .page-content h2, .page-content h3, .page-content h4 {
        margin-top: 2rem;
        margin-bottom: 1rem;
        color: #1a5f2a;
    }
    .page-content img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        margin: 1rem 0;
    }
    .page-content ul, .page-content ol {
        margin-bottom: 1.5rem;
        padding-left: 1.5rem;
    }
    .page-content li {
        margin-bottom: 0.5rem;
    }
</style>
