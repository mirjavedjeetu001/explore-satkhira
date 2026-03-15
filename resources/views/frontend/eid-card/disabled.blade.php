@extends('frontend.layouts.app')

@section('title', 'ঈদ গ্রিটিং কার্ড মেকার')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <span style="font-size: 5rem;">🌙</span>
                    </div>
                    <h2 class="mb-3">ঈদ গ্রিটিং কার্ড মেকার</h2>
                    <p class="text-muted mb-4">
                        এই ফিচারটি বর্তমানে বন্ধ আছে।<br>
                        ঈদের সময় আবার চালু হবে।
                    </p>
                    <a href="{{ route('home') }}" class="btn btn-success px-4">
                        <i class="fas fa-home me-2"></i>হোমপেজে যান
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
