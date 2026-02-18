@extends('admin.layouts.app')

@section('title', 'Answer Question')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-reply me-2"></i>Answer Question</h1>
    <a href="{{ route('admin.mp-questions.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Questions
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="admin-form">
            <!-- Question Details -->
            <div class="mb-4">
                <h5 class="text-muted">Question from {{ $question->user->name ?? 'Anonymous' }}</h5>
                <small class="text-muted">{{ $question->created_at->format('d M, Y h:i A') }}</small>
            </div>
            
            <div class="bg-light p-4 rounded mb-4">
                <strong class="text-success"><i class="fas fa-question me-2"></i>প্রশ্ন:</strong>
                <p class="lead mb-0 mt-2">{{ $question->question }}</p>
            </div>
            
            <!-- Answer Form -->
            <form action="{{ route('admin.mp-questions.update', $question) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="pending" {{ $question->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $question->status == 'approved' ? 'selected' : '' }}>Approved (Show in public)</option>
                        <option value="answered" {{ $question->status == 'answered' ? 'selected' : '' }}>Answered</option>
                        <option value="rejected" {{ $question->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Answer (উত্তর)</label>
                    <textarea name="answer" class="form-control" rows="5" placeholder="উত্তর লিখুন...">{{ old('answer', $question->answer) }}</textarea>
                </div>
                
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-2"></i>Save Answer
                </button>
            </form>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0">Question Info</h6>
            </div>
            <div class="card-body">
                <p><strong>User:</strong> {{ $question->user->name ?? 'Anonymous' }}</p>
                <p><strong>Email:</strong> {{ $question->user->email ?? 'N/A' }}</p>
                <p><strong>Status:</strong> 
                    @if($question->status == 'pending')
                        <span class="badge badge-pending">Pending</span>
                    @elseif($question->status == 'approved')
                        <span class="badge bg-info">Approved</span>
                    @elseif($question->status == 'answered')
                        <span class="badge badge-approved">Answered</span>
                    @else
                        <span class="badge badge-rejected">Rejected</span>
                    @endif
                </p>
                <p><strong>Created:</strong> {{ $question->created_at->format('d M, Y') }}</p>
                @if($question->answered_at)
                    <p><strong>Answered:</strong> {{ $question->answered_at->format('d M, Y') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
