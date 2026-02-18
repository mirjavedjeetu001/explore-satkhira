@extends('admin.layouts.app')

@section('title', 'Contact Messages')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-envelope me-2"></i>Contact Messages</h1>
</div>

<div class="admin-table">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts ?? [] as $contact)
                    <tr class="{{ !$contact->is_read ? 'table-warning' : '' }}">
                        <td>{{ $contact->id }}</td>
                        <td>
                            <strong>{{ $contact->name }}</strong>
                            @if($contact->phone)
                                <small class="text-muted d-block">{{ $contact->phone }}</small>
                            @endif
                        </td>
                        <td>{{ $contact->email }}</td>
                        <td>{{ Str::limit($contact->subject, 30) }}</td>
                        <td>
                            @if($contact->is_read)
                                <span class="badge bg-secondary">Read</span>
                            @else
                                <span class="badge bg-danger">Unread</span>
                            @endif
                        </td>
                        <td>{{ $contact->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-info view-message" 
                                        data-bs-toggle="modal" data-bs-target="#messageModal"
                                        data-id="{{ $contact->id }}"
                                        data-name="{{ $contact->name }}"
                                        data-email="{{ $contact->email }}"
                                        data-phone="{{ $contact->phone }}"
                                        data-subject="{{ $contact->subject }}"
                                        data-message="{{ $contact->message }}"
                                        data-date="{{ $contact->created_at->format('d M, Y h:i A') }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @if(!$contact->is_read)
                                    <form action="{{ route('admin.contacts.markRead', $contact) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" title="Mark as Read">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="d-inline"
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
                        <td colspan="7" class="text-center py-4 text-muted">No messages found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if(isset($contacts) && $contacts->hasPages())
        <div class="p-3 border-top">{{ $contacts->links() }}</div>
    @endif
</div>

<!-- Message Modal -->
<div class="modal fade" id="messageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>From:</strong> <span id="msgName"></span></p>
                <p><strong>Email:</strong> <span id="msgEmail"></span></p>
                <p><strong>Phone:</strong> <span id="msgPhone"></span></p>
                <p><strong>Date:</strong> <span id="msgDate"></span></p>
                <hr>
                <p><strong>Subject:</strong> <span id="msgSubject"></span></p>
                <div class="bg-light p-3 rounded mt-3">
                    <p class="mb-0" id="msgMessage"></p>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="replyLink" class="btn btn-success"><i class="fas fa-reply me-1"></i>Reply via Email</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.view-message').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('msgName').textContent = this.dataset.name;
        document.getElementById('msgEmail').textContent = this.dataset.email;
        document.getElementById('msgPhone').textContent = this.dataset.phone || 'N/A';
        document.getElementById('msgSubject').textContent = this.dataset.subject;
        document.getElementById('msgMessage').textContent = this.dataset.message;
        document.getElementById('msgDate').textContent = this.dataset.date;
        document.getElementById('replyLink').href = 'mailto:' + this.dataset.email + '?subject=Re: ' + this.dataset.subject;
    });
});
</script>
@endpush
