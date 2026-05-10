@extends('layouts.admin')

@section('title', 'জন্মদিন ব্যবস্থাপনা')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="fas fa-birthday-cake"></i> জন্মদিন ব্যবস্থাপনা
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.birthdays.todays') }}" class="btn btn-info">
                <i class="fas fa-heart"></i> আজকের জন্মদিন
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
            <h5 class="mb-0">Admin, Moderator এবং Upazila Moderators (মোট: {{ $users->total() }})</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>নাম</th>
                        <th>ভূমিকা</th>
                        <th>ফোন</th>
                        <th>জন্মতারিখ</th>
                        <th>অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <strong>{{ $user->name }}</strong>
                            </td>
                            <td>
                                @if($user->isAdmin())
                                    <span class="badge bg-danger">Admin</span>
                                @elseif($user->isModerator())
                                    <span class="badge bg-warning text-dark">Moderator</span>
                                @elseif($user->is_upazila_moderator)
                                    <span class="badge bg-info">Upazila Moderator</span>
                                @endif
                            </td>
                            <td>{{ $user->phone }}</td>
                            <td>
                                @if($user->date_of_birth)
                                    <span class="badge bg-success">
                                        {{ $user->date_of_birth->format('d-m-Y') }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">যোগ করা হয়নি</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.birthdays.edit', $user) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <p class="text-muted">কোন ব্যবহারকারী পাওয়া যায়নি</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
