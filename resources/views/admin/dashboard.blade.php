@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center">
            <div class="icon me-3" style="background: linear-gradient(135deg, #28a745, #20c997);">
                <i class="fas fa-list"></i>
            </div>
            <div>
                <h3>{{ $stats['total_listings'] ?? 0 }}</h3>
                <p>Total Listings</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center">
            <div class="icon me-3" style="background: linear-gradient(135deg, #007bff, #6610f2);">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <h3>{{ $stats['total_users'] ?? 0 }}</h3>
                <p>Total Users</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center">
            <div class="icon me-3" style="background: linear-gradient(135deg, #ffc107, #fd7e14);">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <h3>{{ $stats['pending_listings'] ?? 0 }}</h3>
                <p>Pending Listings</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center">
            <div class="icon me-3" style="background: linear-gradient(135deg, #dc3545, #e83e8c);">
                <i class="fas fa-user-clock"></i>
            </div>
            <div>
                <h3>{{ $stats['pending_users'] ?? 0 }}</h3>
                <p>Pending Users</p>
            </div>
        </div>
    </div>
</div>

<!-- Second Row Stats -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center">
            <div class="icon me-3" style="background: linear-gradient(135deg, #17a2b8, #0056b3);">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div>
                <h3>{{ $stats['total_upazilas'] ?? 0 }}</h3>
                <p>Upazilas</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center">
            <div class="icon me-3" style="background: linear-gradient(135deg, #6f42c1, #e83e8c);">
                <i class="fas fa-th-large"></i>
            </div>
            <div>
                <h3>{{ $stats['total_categories'] ?? 0 }}</h3>
                <p>Categories</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center">
            <div class="icon me-3" style="background: linear-gradient(135deg, #20c997, #28a745);">
                <i class="fas fa-question-circle"></i>
            </div>
            <div>
                <h3>{{ $stats['pending_questions'] ?? 0 }}</h3>
                <p>Pending Questions</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center">
            <div class="icon me-3" style="background: linear-gradient(135deg, #fd7e14, #ffc107);">
                <i class="fas fa-comments"></i>
            </div>
            <div>
                <h3>{{ $stats['pending_comments'] ?? 0 }}</h3>
                <p>Pending Comments</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Listings -->
    <div class="col-lg-6">
        <div class="admin-table">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-list me-2 text-success"></i>Recent Listings</h5>
                <a href="{{ route('admin.listings.index') }}" class="btn btn-sm btn-outline-success">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentListings ?? [] as $listing)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.listings.edit', $listing) }}" class="text-decoration-none">
                                        {{ Str::limit($listing->title, 25) }}
                                    </a>
                                </td>
                                <td><span class="badge bg-secondary">{{ $listing->category->name ?? 'N/A' }}</span></td>
                                <td>
                                    @if($listing->status == 'pending')
                                        <span class="badge badge-pending">Pending</span>
                                    @elseif($listing->status == 'approved')
                                        <span class="badge badge-approved">Approved</span>
                                    @else
                                        <span class="badge badge-rejected">Rejected</span>
                                    @endif
                                </td>
                                <td>{{ $listing->created_at->format('d M') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No listings found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Recent Users -->
    <div class="col-lg-6">
        <div class="admin-table">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-users me-2 text-primary"></i>Recent Users</h5>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentUsers ?? [] as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->status == 'pending')
                                        <span class="badge badge-pending">Pending</span>
                                    @elseif($user->status == 'active')
                                        <span class="badge badge-active">Active</span>
                                    @else
                                        <span class="badge badge-suspended">Suspended</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d M') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No users found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Pending Approvals Section -->
<div class="row g-4 mt-2">
    <!-- Pending Questions -->
    <div class="col-lg-6">
        <div class="admin-table">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-question-circle me-2 text-warning"></i>Pending MP Questions</h5>
                <a href="{{ route('admin.mp-questions.index') }}" class="btn btn-sm btn-outline-warning">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>User</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingQuestions ?? [] as $question)
                            <tr>
                                <td>{{ Str::limit($question->question, 30) }}</td>
                                <td>{{ $question->user->name ?? 'Anonymous' }}</td>
                                <td>{{ $question->created_at->format('d M') }}</td>
                                <td>
                                    <a href="{{ route('admin.mp-questions.edit', $question) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No pending questions</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Contact Messages -->
    <div class="col-lg-6">
        <div class="admin-table">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-envelope me-2 text-danger"></i>Recent Contact Messages</h5>
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-outline-danger">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentContacts ?? [] as $contact)
                            <tr>
                                <td>{{ $contact->name }}</td>
                                <td>{{ Str::limit($contact->subject, 20) }}</td>
                                <td>{{ $contact->created_at->format('d M') }}</td>
                                <td>
                                    @if($contact->is_read)
                                        <span class="badge bg-secondary">Read</span>
                                    @else
                                        <span class="badge bg-danger">Unread</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No messages found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
