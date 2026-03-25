@extends('admin.layouts.app')

@section('title', 'জ্বালানি তেল আপডেট - রিপোর্ট')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h1><i class="fas fa-gas-pump me-2"></i>⛽ জ্বালানি তেল আপডেট</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.fuel.stations') }}" class="btn btn-outline-secondary">
            <i class="fas fa-building me-1"></i> পাম্পসমূহ
        </a>
        <a href="{{ route('admin.fuel.settings') }}" class="btn btn-outline-primary">
            <i class="fas fa-cog me-1"></i> সেটিংস
        </a>
        <form action="{{ route('admin.fuel.toggle') }}" method="POST" class="d-inline" id="toggleForm">
            @csrf
            <button type="submit" class="btn {{ $settings['is_enabled'] == '1' ? 'btn-danger' : 'btn-success' }}" id="toggleBtn">
                <i class="fas fa-power-off me-1"></i>
                {{ $settings['is_enabled'] == '1' ? 'বন্ধ করুন' : 'চালু করুন' }}
            </button>
        </form>
    </div>
</div>

<!-- Status Alert -->
<div class="alert {{ $settings['is_enabled'] == '1' ? 'alert-success' : 'alert-warning' }} mb-4">
    <i class="fas {{ $settings['is_enabled'] == '1' ? 'fa-check-circle' : 'fa-exclamation-triangle' }} me-2"></i>
    জ্বালানি তেল আপডেট ফিচার বর্তমানে <strong>{{ $settings['is_enabled'] == '1' ? 'চালু' : 'বন্ধ' }}</strong> আছে।
    @if($settings['is_enabled'] == '1')
        <a href="{{ route('fuel.index') }}" target="_blank" class="alert-link ms-2">
            <i class="fas fa-external-link-alt"></i> দেখুন
        </a>
    @endif
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 col-6 mb-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body text-center">
                <div class="display-6 mb-2">{{ number_format($stats['total_reports']) }}</div>
                <div class="small">মোট রিপোর্ট</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body text-center">
                <div class="display-6 mb-2">{{ number_format($stats['today_reports']) }}</div>
                <div class="small">আজকের রিপোর্ট</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card bg-info text-white h-100">
            <div class="card-body text-center">
                <div class="display-6 mb-2">{{ number_format($stats['unique_reporters']) }}</div>
                <div class="small">রিপোর্টকারী</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card bg-warning text-dark h-100">
            <div class="card-body text-center">
                <div class="display-6 mb-2">{{ number_format($stats['stations_with_fuel']) }}</div>
                <div class="small">তেল আছে (আজ)</div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.fuel.reports') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">ফোন দিয়ে খুঁজুন</label>
                <input type="text" name="phone" class="form-control" placeholder="01XXXXXXXXX" value="{{ request('phone') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">পাম্প</label>
                <select name="station" class="form-select">
                    <option value="">সব পাম্প</option>
                    @foreach($stations as $station)
                        <option value="{{ $station->id }}" {{ request('station') == $station->id ? 'selected' : '' }}>
                            {{ $station->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">উপজেলা</label>
                <select name="upazila" class="form-select">
                    <option value="">সব উপজেলা</option>
                    @foreach($upazilas as $upazila)
                        <option value="{{ $upazila->id }}" {{ request('upazila') == $upazila->id ? 'selected' : '' }}>
                            {{ $upazila->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="show_all" value="1" id="showAll" {{ request('show_all') == '1' ? 'checked' : '' }}>
                    <label class="form-check-label" for="showAll">সব রিপোর্ট</label>
                </div>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search me-1"></i> খুঁজুন
                </button>
                <a href="{{ route('admin.fuel.reports') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Reports Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-list me-2"></i>রিপোর্টসমূহ ({{ $reports->total() }})</span>
        <span class="badge {{ request('show_all') == '1' ? 'bg-warning' : 'bg-success' }}">
            {{ request('show_all') == '1' ? 'সব রিপোর্ট দেখানো হচ্ছে' : 'শুধু সর্বশেষ আপডেট' }}
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>পাম্প</th>
                        <th>রিপোর্টকারী</th>
                        <th>পেট্রোল</th>
                        <th>ডিজেল</th>
                        <th>অকটেন</th>
                        <th>লাইন</th>
                        <th>সময়</th>
                        <th>অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td>{{ $report->id }}</td>
                            <td>
                                <strong>{{ $report->fuelStation->name }}</strong><br>
                                <small class="text-muted">{{ $report->fuelStation->upazila->name }}</small>
                            </td>
                            <td>
                                {{ $report->reporter_name }}<br>
                                <small class="text-muted">
                                    <a href="#" onclick="showReportsByPhone('{{ $report->reporter_phone }}'); return false;">
                                        {{ $report->reporter_phone }}
                                    </a>
                                </small>
                                @if($report->reporter_email)
                                    <br><small class="text-muted">{{ $report->reporter_email }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $report->petrol_available ? 'bg-success' : 'bg-danger' }}">
                                    {{ $report->petrol_available ? 'আছে' : 'নেই' }}
                                </span>
                                @if($report->petrol_price)
                                    <br><small>৳{{ number_format($report->petrol_price, 0) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $report->diesel_available ? 'bg-success' : 'bg-danger' }}">
                                    {{ $report->diesel_available ? 'আছে' : 'নেই' }}
                                </span>
                                @if($report->diesel_price)
                                    <br><small>৳{{ number_format($report->diesel_price, 0) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $report->octane_available ? 'bg-success' : 'bg-danger' }}">
                                    {{ $report->octane_available ? 'আছে' : 'নেই' }}
                                </span>
                                @if($report->octane_price)
                                    <br><small>৳{{ number_format($report->octane_price, 0) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $report->queue_status == 'none' ? 'success' : ($report->queue_status == 'short' ? 'info' : ($report->queue_status == 'medium' ? 'warning' : 'danger')) }}">
                                    {{ $report->queue_status_bangla }}
                                </span>
                            </td>
                            <td>
                                <small>{{ $report->created_at->format('d M Y') }}</small><br>
                                <small class="text-muted">{{ $report->created_at->format('h:i A') }}</small>
                            </td>
                            <td>
                                <button class="btn btn-sm {{ $report->is_verified ? 'btn-success' : 'btn-outline-success' }}" 
                                        onclick="verifyReport({{ $report->id }})" title="{{ $report->is_verified ? 'ভেরিফাইড' : 'ভেরিফাই করুন' }}">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteReport({{ $report->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">কোন রিপোর্ট পাওয়া যায়নি</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($reports->hasPages())
        <div class="card-footer">
            {{ $reports->links() }}
        </div>
    @endif
</div>

<!-- Phone Reports Modal -->
<div class="modal fade" id="phoneReportsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-phone me-2"></i>রিপোর্টকারীর সব রিপোর্ট</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="phoneReportsContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showReportsByPhone(phone) {
    const modal = new bootstrap.Modal(document.getElementById('phoneReportsModal'));
    document.getElementById('phoneReportsContent').innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>';
    modal.show();
    
    fetch(`/admin/fuel/reports/phone/${encodeURIComponent(phone)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.reports.length > 0) {
                let html = `<h6 class="mb-3">ফোন: ${phone} | মোট রিপোর্ট: ${data.reports.length}</h6>`;
                html += '<table class="table table-sm"><thead><tr><th>পাম্প</th><th>পেট্রোল</th><th>ডিজেল</th><th>অকটেন</th><th>তারিখ</th></tr></thead><tbody>';
                data.reports.forEach(r => {
                    html += `<tr>
                        <td>${r.station} (${r.upazila})</td>
                        <td><span class="badge ${r.petrol === 'আছে' ? 'bg-success' : 'bg-danger'}">${r.petrol}</span></td>
                        <td><span class="badge ${r.diesel === 'আছে' ? 'bg-success' : 'bg-danger'}">${r.diesel}</span></td>
                        <td><span class="badge ${r.octane === 'আছে' ? 'bg-success' : 'bg-danger'}">${r.octane}</span></td>
                        <td>${r.date}</td>
                    </tr>`;
                });
                html += '</tbody></table>';
                document.getElementById('phoneReportsContent').innerHTML = html;
            } else {
                document.getElementById('phoneReportsContent').innerHTML = '<p class="text-center text-muted">কোন রিপোর্ট পাওয়া যায়নি</p>';
            }
        })
        .catch(error => {
            document.getElementById('phoneReportsContent').innerHTML = '<p class="text-center text-danger">একটি ত্রুটি হয়েছে</p>';
        });
}

function verifyReport(id) {
    fetch(`/admin/fuel/reports/${id}/verify`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function deleteReport(id) {
    if (confirm('আপনি কি এই রিপোর্টটি মুছে ফেলতে চান?')) {
        fetch(`/admin/fuel/reports/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}
</script>
@endpush
@endsection
