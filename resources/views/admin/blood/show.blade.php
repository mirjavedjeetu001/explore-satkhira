@extends('admin.layouts.app')

@section('title', 'ডোনর বিস্তারিত - ' . $donor->name)

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h1><i class="fas fa-user me-2 text-danger"></i>{{ $donor->name }}</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.blood.edit', $donor->id) }}" class="btn btn-warning"><i class="fas fa-edit me-1"></i>এডিট</a>
        <a href="{{ route('admin.blood.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>ফিরে যান</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold"><i class="fas fa-info-circle me-2"></i>ডোনর তথ্য</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th width="40%">রক্তের গ্রুপ</th><td><span class="badge bg-danger fs-6">{{ $donor->blood_group }}</span></td></tr>
                    <tr><th>নাম</th><td>{{ $donor->name }}</td></tr>
                    <tr><th>ফোন</th><td>{{ $donor->phone }} @if($donor->hide_phone) <span class="badge bg-warning text-dark">লুকানো</span> @endif</td></tr>
                    <tr><th>হোয়াটসঅ্যাপ</th><td>{{ $donor->whatsapp_number ?? '-' }}</td></tr>
                    <tr><th>ইমেইল</th><td>{{ $donor->email ?? '-' }}</td></tr>
                    <tr><th>ধরন</th><td>{{ $donor->type === 'organization' ? 'সংগঠন' : 'ব্যক্তিগত' }}</td></tr>
                    @if($donor->type === 'organization')
                    <tr><th>সংগঠন</th><td>{{ $donor->organization_name }}</td></tr>
                    @endif
                    <tr><th>উপজেলা</th><td>{{ $donor->upazila ? ($donor->upazila->name_bn ?? $donor->upazila->name ?? '-') : ($donor->outside_area ? '🌍 ' . $donor->outside_area : '-') }}</td></tr>
                    <tr><th>ঠিকানা</th><td>{{ $donor->address ?? '-' }}</td></tr>
                    <tr><th>সর্বশেষ দান</th><td>{{ $donor->last_donation_date ? $donor->last_donation_date->format('d M, Y') : '-' }}</td></tr>
                    <tr><th>বিকল্প যোগাযোগ</th><td>{{ $donor->alternative_contact ?? '-' }}</td></tr>
                    <tr><th>স্ট্যাটাস</th><td><span class="badge {{ $donor->status === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ $donor->status }}</span></td></tr>
                    <tr><th>উপলব্ধতা</th><td>
                        @if($donor->is_currently_available)
                            <span class="badge bg-success">Available</span>
                        @elseif($donor->next_available_date)
                            <span class="badge bg-warning text-dark">{{ $donor->next_available_date->format('d M, Y') }} পর্যন্ত</span>
                        @else
                            <span class="badge bg-secondary">Not Available</span>
                        @endif
                    </td></tr>
                    <tr><th>Not Reachable</th><td>
                        {{ $donor->not_reachable_count }}
                        @if($donor->not_reachable_count > 0)
                            <form action="{{ route('admin.blood.reset-reachable', $donor->id) }}" method="POST" class="d-inline ms-2">
                                @csrf
                                <button class="btn btn-sm btn-outline-warning"><i class="fas fa-redo me-1"></i>রিসেট</button>
                            </form>
                        @endif
                    </td></tr>
                    <tr><th>রেজিস্ট্রেশন</th><td>{{ $donor->created_at->format('d M, Y h:i A') }}</td></tr>
                </table>
            </div>
        </div>

        {{-- Available Areas --}}
        @if(!empty($donor->available_areas))
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-white fw-bold"><i class="fas fa-map-marker-alt me-2"></i>সেবা এলাকা</div>
            <div class="card-body">
                @foreach($donor->available_areas as $areaId)
                    @php $area = \App\Models\Upazila::find($areaId); @endphp
                    @if($area)
                        <span class="badge bg-light text-dark border me-1 mb-1">{{ $area->name_bn ?? $area->name }}</span>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        {{-- Available For --}}
        @if(!empty($donor->available_for))
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-white fw-bold"><i class="fas fa-stethoscope me-2"></i>রোগীর ধরন</div>
            <div class="card-body">
                @foreach($donor->available_for as $disease)
                    <span class="badge bg-light text-dark border me-1 mb-1">{{ $disease }}</span>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-6">
        {{-- Comments --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold"><i class="fas fa-comments me-2"></i>মন্তব্যসমূহ ({{ $donor->comments->count() }})</div>
            <div class="card-body">
                @forelse($donor->comments as $comment)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong>{{ $comment->name }}</strong>
                                @if($comment->phone) <small class="text-muted ms-2">{{ $comment->phone }}</small> @endif
                                <span class="badge {{ $comment->status === 'approved' ? 'bg-success' : ($comment->status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }} ms-1">{{ $comment->status }}</span>
                            </div>
                            <div class="d-flex gap-1">
                                <form action="{{ route('admin.blood.comments.delete', $comment->id) }}" method="POST" onsubmit="return confirm('মন্তব্য মুছে ফেলতে চান?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                        <p class="mb-1 mt-1">{{ $comment->comment }}</p>
                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                    </div>
                @empty
                    <p class="text-muted text-center py-3">কোনো মন্তব্য নেই।</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
