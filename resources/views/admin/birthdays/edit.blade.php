@extends('layouts.admin')

@section('title', 'জন্মদিন সম্পাদনা - ' . $user->name)

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="fas fa-birthday-cake"></i> জন্মদিন সম্পাদনা
            </h1>
            <p class="text-muted mt-2">ব্যবহারকারী: <strong>{{ $user->name }}</strong></p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.birthdays.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> ফিরে যান
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">জন্মতারিখ তথ্য</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.birthdays.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="date_of_birth" class="form-label">জন্মতারিখ <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                        id="date_of_birth" name="date_of_birth" value="{{ $user->date_of_birth?->format('Y-m-d') }}">
                    @error('date_of_birth')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted d-block mt-2">
                        <i class="fas fa-info-circle"></i> শুধুমাত্র অতীত তারিখ গ্রহণ করা হয়
                    </small>
                </div>

                <hr class="my-4">

                <h6 class="mb-3"><i class="fas fa-palette me-2 text-warning"></i>আজকের কার্ড কনটেন্ট</h6>

                <div class="mb-3">
                    <label for="card_title" class="form-label">কার্ড শিরোনাম</label>
                    <input type="text" class="form-control @error('card_title') is-invalid @enderror"
                        id="card_title" name="card_title"
                        value="{{ old('card_title', $birthdayCard?->english_message ?? 'জন্মদিন মোবারক!') }}"
                        placeholder="যেমন: জন্মদিন মোবারক!">
                    @error('card_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted d-block mt-1">এই টেক্সট কার্ডের বড় শিরোনামে দেখাবে।</small>
                </div>

                <div class="mb-3">
                    <label for="bengali_message" class="form-label">শুভেচ্ছা বার্তা</label>
                    <textarea class="form-control @error('bengali_message') is-invalid @enderror"
                        id="bengali_message" name="bengali_message" rows="4"
                        placeholder="বাংলায় শুভেচ্ছা বার্তা লিখুন">{{ old('bengali_message', $birthdayCard?->bengali_message) }}</textarea>
                    @error('bengali_message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted d-block mt-1">হোমপেজ ও ডিটেইল কার্ডে এই বার্তা দেখাবে।</small>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>নোট:</strong> যখন এই ব্যবহারকারীর জন্মদিন আসবে, তখন হোমপেজে একটি বিশেষ জন্মদিন কার্ড প্রদর্শন করা হবে যেখানে দর্শকরা শুভেচ্ছা জানাতে পারবেন।
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> সংরক্ষণ করুন
                    </button>
                    @if($user->date_of_birth)
                        <button type="reset" class="btn btn-secondary">
                            <i class="fas fa-undo"></i> বাতিল করুন
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if($user->date_of_birth)
        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">জন্মদিনের তথ্য</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>সম্পূর্ণ জন্মদিন:</strong><br>{{ $user->date_of_birth->format('d F Y (l)') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>বয়স:</strong><br>{{ now()->diffInYears($user->date_of_birth) }} বছর</p>
                    </div>
                </div>
                @if($user->date_of_birth->format('m-d') === now()->format('m-d'))
                    <div class="alert alert-success">
                        <i class="fas fa-star"></i> আজ এই ব্যবহারকারীর জন্মদিন! 🎉
                    </div>
                @else
                    @php
                        $nextBirthday = $user->date_of_birth->copy()->year(now()->year);
                        if ($nextBirthday < now()) {
                            $nextBirthday->addYear();
                        }
                        $daysUntil = now()->diffInDays($nextBirthday);
                    @endphp
                    <p><strong>পরবর্তী জন্মদিন:</strong> {{ $daysUntil }} দিন পর ({{ $nextBirthday->format('d F Y') }})</p>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
