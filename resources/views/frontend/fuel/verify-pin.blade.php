@extends('frontend.layouts.app')

@section('title', 'PIN যাচাই করুন')

@section('content')
<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-body p-4 text-center">
                        <div class="mb-4">
                            <i class="fas fa-lock fa-3x text-primary"></i>
                        </div>
                        <h4 class="fw-bold mb-3">PIN দিয়ে যাচাই করুন</h4>
                        <p class="text-muted mb-4">
                            <strong>{{ $report->fuelStation->name }}</strong> এর আপডেট এডিট করতে রিপোর্ট করার সময় দেওয়া ৪ সংখ্যার PIN দিন।
                        </p>

                        @if(session('error'))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('fuel.verify-pin.submit', $report->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <input type="text" name="pin" class="form-control form-control-lg text-center @error('pin') is-invalid @enderror" 
                                       placeholder="• • • •" maxlength="4" pattern="[0-9]{4}" 
                                       style="font-size: 2em; letter-spacing: 15px;" autofocus required>
                                @error('pin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-check me-2"></i>যাচাই করুন
                                </button>
                                <a href="{{ route('fuel.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>ফিরে যান
                                </a>
                            </div>
                        </form>

                        <div class="mt-4 pt-3 border-top">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                PIN মনে নেই? নতুন আপডেট দিন।
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
