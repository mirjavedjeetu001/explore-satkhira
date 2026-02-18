@extends('admin.layouts.app')

@section('title', 'MP Questions')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-question-circle me-2"></i>MP Questions</h1>
</div>

<!-- Filters -->
<div class="admin-form mb-4">
    <form action="{{ route('admin.mp-questions.index') }}" method="GET" class="row g-3">
        <div class="col-md-3">
            <label class="form-label">MP / Constituency</label>
            <select name="mp_profile_id" class="form-select">
                <option value="">All MPs</option>
                @foreach(\App\Models\MpProfile::active()->orderBy('constituency')->get() as $mp)
                    <option value="{{ $mp->id }}" {{ request('mp_profile_id') == $mp->id ? 'selected' : '' }}>
                        {{ $mp->name }} - {{ $mp->constituency }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="answered" {{ request('status') == 'answered' ? 'selected' : '' }}>Answered</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-1"></i>Filter</button>
            <a href="{{ route('admin.mp-questions.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>
</div>

<div class="admin-table">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Question</th>
                    <th>MP / Constituency</th>
                    <th>User</th>
                    <th>Status</th>
                    <th>Answer</th>
                    <th>Date</th>
                    <th width="150">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($questions ?? [] as $question)
                    <tr>
                        <td>{{ $question->id }}</td>
                        <td>{{ Str::limit($question->question, 50) }}</td>
                        <td>
                            @if($question->mpProfile)
                                <strong>{{ $question->mpProfile->name }}</strong>
                                <br><small class="text-muted">{{ $question->mpProfile->constituency }}</small>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>{{ $question->user->name ?? 'Anonymous' }}</td>
                        <td>
                            @if($question->status == 'pending')
                                <span class="badge badge-pending">Pending</span>
                            @elseif($question->status == 'approved')
                                <span class="badge bg-info">Approved</span>
                            @elseif($question->status == 'answered')
                                <span class="badge badge-approved">Answered</span>
                            @else
                                <span class="badge badge-rejected">Rejected</span>
                            @endif
                        </td>
                        <td>
                            @if($question->answer)
                                <span class="text-success"><i class="fas fa-check"></i> Yes</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $question->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                @if($question->status == 'pending')
                                    <form action="{{ route('admin.mp-questions.approve', $question) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.mp-questions.reject', $question) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-warning" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.mp-questions.edit', $question) }}" class="btn btn-primary" title="Edit/Answer">
                                    <i class="fas fa-reply"></i>
                                </a>
                                <form action="{{ route('admin.mp-questions.destroy', $question) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No questions found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if(isset($questions) && $questions->hasPages())
        <div class="p-3 border-top">{{ $questions->links() }}</div>
    @endif
</div>
@endsection
