@extends('admin.layouts.app')

@section('title', 'টিম মেম্বার ম্যানেজমেন্ট')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-users-cog me-2"></i>টিম মেম্বার ম্যানেজমেন্ট</h1>
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTeamMemberModal">
        <i class="fas fa-plus me-2"></i>নতুন মেম্বার যোগ করুন
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="admin-table">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>মেম্বার</th>
                    <th>ওয়েবসাইট রোল</th>
                    <th>পদবী</th>
                    <th>ক্রম</th>
                    <th>স্ট্যাটাস</th>
                    <th width="150">অ্যাকশন</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teamMembers as $member)
                    <tr>
                        <td>{{ $member->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($member->user->avatar)
                                    <img src="{{ asset('storage/' . $member->user->avatar) }}" 
                                         alt="{{ $member->user->name }}" 
                                         class="rounded-circle me-2" width="45" height="45" style="object-fit: cover;">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($member->user->name) }}&background=28a745&color=fff&size=45" 
                                         alt="{{ $member->user->name }}" class="rounded-circle me-2" width="45" height="45">
                                @endif
                                <div>
                                    <strong>{{ $member->user->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $member->user->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $member->website_role_bn }}</span>
                            <br>
                            <small class="text-muted">{{ $member->website_role }}</small>
                        </td>
                        <td>{{ $member->designation_bn ?: $member->designation ?: '-' }}</td>
                        <td>{{ $member->display_order }}</td>
                        <td>
                            @if($member->is_active)
                                <span class="badge bg-success">সক্রিয়</span>
                            @else
                                <span class="badge bg-secondary">নিষ্ক্রিয়</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-info" data-bs-toggle="modal" 
                                        data-bs-target="#editTeamMemberModal{{ $member->id }}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.team.toggle-active', $member) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn {{ $member->is_active ? 'btn-warning' : 'btn-success' }}" 
                                            title="{{ $member->is_active ? 'নিষ্ক্রিয় করুন' : 'সক্রিয় করুন' }}">
                                        <i class="fas {{ $member->is_active ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.team.destroy', $member) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('আপনি কি নিশ্চিত?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Edit Modal for this member -->
                    <div class="modal fade" id="editTeamMemberModal{{ $member->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form action="{{ route('admin.team.update', $member) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">টিম মেম্বার এডিট করুন</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">ওয়েবসাইট রোল (English) <span class="text-danger">*</span></label>
                                                <select name="website_role" class="form-select" required>
                                                    @foreach(\App\Models\TeamMember::getWebsiteRoles() as $key => $value)
                                                        <option value="{{ $key }}" {{ $member->website_role == $key ? 'selected' : '' }}>{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">ওয়েবসাইট রোল (বাংলা) <span class="text-danger">*</span></label>
                                                <input type="text" name="website_role_bn" class="form-control" 
                                                       value="{{ $member->website_role_bn }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">পদবী (English)</label>
                                                <input type="text" name="designation" class="form-control" 
                                                       value="{{ $member->designation }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">পদবী (বাংলা)</label>
                                                <input type="text" name="designation_bn" class="form-control" 
                                                       value="{{ $member->designation_bn }}">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">বায়ো (English)</label>
                                                <textarea name="bio" class="form-control" rows="2">{{ $member->bio }}</textarea>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">বায়ো (বাংলা)</label>
                                                <textarea name="bio_bn" class="form-control" rows="2">{{ $member->bio_bn }}</textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">ফোন নম্বর</label>
                                                <input type="text" name="phone" class="form-control" 
                                                       value="{{ $member->phone }}" placeholder="০১XXXXXXXXX">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">ইমেইল</label>
                                                <input type="email" name="email" class="form-control" 
                                                       value="{{ $member->email }}" placeholder="email@example.com">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Facebook URL</label>
                                                <input type="url" name="facebook_url" class="form-control" 
                                                       value="{{ $member->facebook_url }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Twitter URL</label>
                                                <input type="url" name="twitter_url" class="form-control" 
                                                       value="{{ $member->twitter_url }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">LinkedIn URL</label>
                                                <input type="url" name="linkedin_url" class="form-control" 
                                                       value="{{ $member->linkedin_url }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">প্রদর্শন ক্রম</label>
                                                <input type="number" name="display_order" class="form-control" 
                                                       value="{{ $member->display_order }}" min="0">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">স্ট্যাটাস</label>
                                                <select name="is_active" class="form-select">
                                                    <option value="1" {{ $member->is_active ? 'selected' : '' }}>সক্রিয়</option>
                                                    <option value="0" {{ !$member->is_active ? 'selected' : '' }}>নিষ্ক্রিয়</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                                        <button type="submit" class="btn btn-success">আপডেট করুন</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-users fa-3x mb-3 d-block"></i>
                            কোন টিম মেম্বার যোগ করা হয়নি
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Team Member Modal -->
<div class="modal fade" id="addTeamMemberModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.team.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">নতুন টিম মেম্বার যোগ করুন</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">ইউজার নির্বাচন করুন <span class="text-danger">*</span></label>
                            <select name="user_id" class="form-select" required>
                                <option value="">-- ইউজার নির্বাচন করুন --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            <small class="text-muted">শুধুমাত্র Active ইউজার যারা এখনো টিমে নেই</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ওয়েবসাইট রোল (English) <span class="text-danger">*</span></label>
                            <select name="website_role" class="form-select website-role-select" required>
                                <option value="">-- রোল নির্বাচন করুন --</option>
                                @foreach(\App\Models\TeamMember::getWebsiteRoles() as $key => $value)
                                    <option value="{{ $key }}" data-bn="{{ \App\Models\TeamMember::getWebsiteRolesBn()[$key] }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ওয়েবসাইট রোল (বাংলা) <span class="text-danger">*</span></label>
                            <input type="text" name="website_role_bn" class="form-control website-role-bn" required 
                                   placeholder="বাংলায় রোল লিখুন">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">পদবী (English)</label>
                            <input type="text" name="designation" class="form-control" placeholder="e.g. Senior Developer">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">পদবী (বাংলা)</label>
                            <input type="text" name="designation_bn" class="form-control" placeholder="যেমন: সিনিয়র ডেভেলপার">
                        </div>
                        <div class="col-12">
                            <label class="form-label">বায়ো (English)</label>
                            <textarea name="bio" class="form-control" rows="2" placeholder="Short bio in English"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">বায়ো (বাংলা)</label>
                            <textarea name="bio_bn" class="form-control" rows="2" placeholder="সংক্ষিপ্ত বায়ো বাংলায়"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ফোন নম্বর</label>
                            <input type="text" name="phone" class="form-control" placeholder="০১XXXXXXXXX">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ইমেইল</label>
                            <input type="email" name="email" class="form-control" placeholder="email@example.com">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Facebook URL</label>
                            <input type="url" name="facebook_url" class="form-control" placeholder="https://facebook.com/...">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Twitter URL</label>
                            <input type="url" name="twitter_url" class="form-control" placeholder="https://twitter.com/...">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">LinkedIn URL</label>
                            <input type="url" name="linkedin_url" class="form-control" placeholder="https://linkedin.com/...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">প্রদর্শন ক্রম</label>
                            <input type="number" name="display_order" class="form-control" value="0" min="0">
                            <small class="text-muted">ছোট সংখ্যা আগে দেখাবে</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success">যোগ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-fill Bengali role name when English role is selected
    document.querySelectorAll('.website-role-select').forEach(function(select) {
        select.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const bnValue = selectedOption.getAttribute('data-bn');
            const bnInput = this.closest('.row').querySelector('.website-role-bn');
            if (bnInput && bnValue) {
                bnInput.value = bnValue;
            }
        });
    });
});
</script>
@endsection
