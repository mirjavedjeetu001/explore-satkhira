@extends('frontend.layouts.app')

@section('title', 'শুভেচ্ছা কার্ড')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm text-center p-5">
                <div class="display-3 mb-3">🎨</div>
                <h1 class="h3 mb-3">শুভেচ্ছা কার্ড ফিচারটি এখন বন্ধ আছে</h1>
                <p class="text-muted mb-4">অ্যাডমিন আবার চালু করলে আপনি নাম ও পদবি দিয়ে কার্ড তৈরি করতে পারবেন।</p>
                <a href="{{ route('home') }}" class="btn btn-success px-4"><i class="fas fa-home me-2"></i>হোমে ফিরে যান</a>
            </div>
        </div>
    </div>
</div>
@endsection
