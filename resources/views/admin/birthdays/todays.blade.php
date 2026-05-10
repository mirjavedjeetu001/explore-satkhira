@extends('layouts.admin')

@section('title', 'আজকের জন্মদিন')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="fas fa-heart text-danger"></i> আজকের জন্মদিন
            </h1>
            <p class="text-muted mt-2">{{ now()->format('d F Y') }}</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.birthdays.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> সব ব্যবহারকারী
            </a>
        </div>
    </div>

    @if($birthdays->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <strong>কোন জন্মদিন নেই</strong> - আজ কোন Admin, Moderator বা Upazila Moderator এর জন্মদিন নেই।
        </div>
    @else
        <div class="row">
            @foreach($birthdays as $birthday)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-birthday-cake"></i>
                                {{ $birthday->user->name }}
                            </h5>
                            <small>{{ $birthday->user->date_of_birth->format('d F Y') }}</small>
                        </div>
                        <div class="card-body">
                            <p class="mb-2">
                                <strong>বয়স:</strong> {{ now()->diffInYears($birthday->user->date_of_birth) }} বছর
                            </p>
                            <p class="mb-2">
                                <strong>ভূমিকা:</strong><br>
                                @if($birthday->user->isAdmin())
                                    <span class="badge bg-danger">Admin</span>
                                @elseif($birthday->user->isModerator())
                                    <span class="badge bg-warning text-dark">Moderator</span>
                                @elseif($birthday->user->is_upazila_moderator)
                                    <span class="badge bg-info">Upazila Moderator</span>
                                @endif
                            </p>
                            <p class="mb-2">
                                <strong>ফোন:</strong> {{ $birthday->user->phone }}
                            </p>
                            <hr>
                            <p class="small text-muted mb-0">
                                <strong>শুভেচ্ছা সংখ্যা:</strong> {{ $birthday->comments->count() }}
                            </p>
                        </div>
                        <div class="card-footer bg-light">
                            <a href="{{ route('birthday-cards.show', $birthday->id) }}" target="_blank" class="btn btn-sm btn-primary w-100">
                                <i class="fas fa-eye"></i> জন্মদিন কার্ড দেখুন
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card mt-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">সব শুভেচ্ছা</h5>
            </div>
            <div class="card-body">
                @php
                    $allComments = $birthdays->flatMap(fn($b) => $b->comments);
                @endphp

                @if($allComments->isEmpty())
                    <p class="text-muted mb-0">এখনও কোন শুভেচ্ছা পাওয়া যায়নি।</p>
                @else
                    <div class="row">
                        @foreach($allComments as $comment)
                            <div class="col-md-6 mb-3">
                                <div class="p-3 border rounded">
                                    <p class="mb-1">
                                        <strong>{{ $comment->visitor_name }}</strong>
                                        <span class="badge bg-secondary">{{ $comment->visitor_phone }}</span>
                                    </p>
                                    <p class="mb-0">{{ $comment->wish_message }}</p>
                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
