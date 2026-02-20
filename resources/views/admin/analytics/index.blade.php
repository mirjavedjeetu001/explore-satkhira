@extends('admin.layouts.app')

@section('title', 'Analytics & Statistics')

@push('styles')
<style>
    .analytics-card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        padding: 25px;
        margin-bottom: 20px;
        transition: transform 0.3s ease;
    }
    .analytics-card:hover {
        transform: translateY(-5px);
    }
    .stat-box {
        text-align: center;
        padding: 20px;
        border-radius: 12px;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    }
    .stat-box h2 {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
    }
    .stat-box p {
        margin: 5px 0 0;
        color: #6c757d;
        font-size: 0.9rem;
    }
    .stat-box.primary { background: linear-gradient(135deg, #007bff15, #007bff30); }
    .stat-box.primary h2 { color: #007bff; }
    .stat-box.success { background: linear-gradient(135deg, #28a74515, #28a74530); }
    .stat-box.success h2 { color: #28a745; }
    .stat-box.warning { background: linear-gradient(135deg, #ffc10715, #ffc10730); }
    .stat-box.warning h2 { color: #ffc107; }
    .stat-box.danger { background: linear-gradient(135deg, #dc354515, #dc354530); }
    .stat-box.danger h2 { color: #dc3545; }
    .stat-box.info { background: linear-gradient(135deg, #17a2b815, #17a2b830); }
    .stat-box.info h2 { color: #17a2b8; }
    .stat-box.purple { background: linear-gradient(135deg, #6f42c115, #6f42c130); }
    .stat-box.purple h2 { color: #6f42c1; }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 20px;
        color: #333;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .section-title i {
        color: #007bff;
    }

    .chart-container {
        position: relative;
        height: 300px;
    }

    .progress-stat {
        margin-bottom: 15px;
    }
    .progress-stat .label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
    }
    .progress-stat .progress {
        height: 10px;
        border-radius: 5px;
    }

    .visitor-table {
        font-size: 0.9rem;
    }
    .visitor-table th {
        background: #f8f9fa;
        font-weight: 600;
    }
    .device-badge {
        padding: 3px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
    }
    .device-mobile { background: #d4edda; color: #155724; }
    .device-desktop { background: #cce5ff; color: #004085; }
    .device-tablet { background: #fff3cd; color: #856404; }
</style>
@endpush

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><i class="fas fa-chart-line me-2 text-primary"></i>Analytics & Statistics</h4>
        <p class="text-muted mb-0">ওয়েবসাইট ট্র্যাফিক এবং কার্যকলাপ পরিসংখ্যান</p>
    </div>
    <div class="text-muted">
        <i class="fas fa-calendar-alt me-1"></i> {{ now()->format('d M Y') }}
    </div>
</div>

<!-- Visitor Stats -->
<div class="analytics-card">
    <h5 class="section-title"><i class="fas fa-eye"></i> ভিজিটর পরিসংখ্যান</h5>
    <div class="row g-3">
        <div class="col-6 col-md-3">
            <div class="stat-box primary">
                <h2>{{ $visitorStats['today'] }}</h2>
                <p>আজ</p>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-box success">
                <h2>{{ $visitorStats['this_week'] }}</h2>
                <p>এই সপ্তাহ</p>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-box warning">
                <h2>{{ $visitorStats['this_month'] }}</h2>
                <p>এই মাস</p>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-box info">
                <h2>{{ $visitorStats['total'] }}</h2>
                <p>সর্বমোট</p>
            </div>
        </div>
    </div>
</div>

<!-- User Registration Stats -->
<div class="analytics-card">
    <h5 class="section-title"><i class="fas fa-user-plus"></i> রেজিস্ট্রেশন পরিসংখ্যান</h5>
    <div class="row g-3">
        <div class="col-6 col-md-2">
            <div class="stat-box primary">
                <h2>{{ $userStats['today'] }}</h2>
                <p>আজ রেজিস্ট্রেশন</p>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-box success">
                <h2>{{ $userStats['this_week'] }}</h2>
                <p>এই সপ্তাহ</p>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-box warning">
                <h2>{{ $userStats['this_month'] }}</h2>
                <p>এই মাস</p>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-box info">
                <h2>{{ $userStats['total'] }}</h2>
                <p>সর্বমোট</p>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-box success">
                <h2>{{ $userStats['approved'] }}</h2>
                <p>অনুমোদিত</p>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-box danger">
                <h2>{{ $userStats['pending'] }}</h2>
                <p>অপেক্ষমাণ</p>
            </div>
        </div>
    </div>
</div>

<!-- Listing Stats -->
<div class="analytics-card">
    <h5 class="section-title"><i class="fas fa-list-alt"></i> লিস্টিং/ডাটা পরিসংখ্যান</h5>
    <div class="row g-3">
        <div class="col-6 col-md-2">
            <div class="stat-box primary">
                <h2>{{ $listingStats['today'] }}</h2>
                <p>আজ যোগ হয়েছে</p>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-box success">
                <h2>{{ $listingStats['this_week'] }}</h2>
                <p>এই সপ্তাহ</p>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-box warning">
                <h2>{{ $listingStats['this_month'] }}</h2>
                <p>এই মাস</p>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-box info">
                <h2>{{ $listingStats['total'] }}</h2>
                <p>সর্বমোট</p>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-box success">
                <h2>{{ $listingStats['approved'] }}</h2>
                <p>অনুমোদিত</p>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-box danger">
                <h2>{{ $listingStats['pending'] }}</h2>
                <p>অপেক্ষমাণ</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Visitor Trend Chart -->
    <div class="col-lg-8">
        <div class="analytics-card">
            <h5 class="section-title"><i class="fas fa-chart-area"></i> গত ৭ দিনের ট্রেন্ড</h5>
            <div class="chart-container">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Device Stats -->
    <div class="col-lg-4">
        <div class="analytics-card">
            <h5 class="section-title"><i class="fas fa-mobile-alt"></i> ডিভাইস পরিসংখ্যান</h5>
            @php $totalDevices = array_sum($deviceStats) ?: 1; @endphp
            @foreach($deviceStats as $device => $count)
            <div class="progress-stat">
                <div class="label">
                    <span>{{ $device ?: 'Unknown' }}</span>
                    <span>{{ $count }} ({{ round($count / $totalDevices * 100) }}%)</span>
                </div>
                <div class="progress">
                    <div class="progress-bar {{ $device == 'Mobile' ? 'bg-success' : ($device == 'Desktop' ? 'bg-primary' : 'bg-warning') }}" 
                         style="width: {{ $count / $totalDevices * 100 }}%"></div>
                </div>
            </div>
            @endforeach

            <h5 class="section-title mt-4"><i class="fas fa-globe"></i> ব্রাউজার</h5>
            @php $totalBrowsers = array_sum($browserStats) ?: 1; @endphp
            @foreach(array_slice($browserStats, 0, 5) as $browser => $count)
            <div class="progress-stat">
                <div class="label">
                    <span>{{ $browser ?: 'Unknown' }}</span>
                    <span>{{ $count }}</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-info" style="width: {{ $count / $totalBrowsers * 100 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Visitors -->
    <div class="col-lg-7">
        <div class="analytics-card">
            <h5 class="section-title"><i class="fas fa-users"></i> সাম্প্রতিক ভিজিটর</h5>
            <div class="table-responsive">
                <table class="table visitor-table">
                    <thead>
                        <tr>
                            <th>IP</th>
                            <th>পেজ</th>
                            <th>ডিভাইস</th>
                            <th>সময়</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentVisitors as $visitor)
                        <tr>
                            <td>{{ $visitor->ip_address }}</td>
                            <td>
                                <small class="text-muted">{{ Str::limit(str_replace(url('/'), '', $visitor->page_url), 30) }}</small>
                            </td>
                            <td>
                                <span class="device-badge device-{{ strtolower($visitor->device ?: 'desktop') }}">
                                    {{ $visitor->device ?: 'Unknown' }}
                                </span>
                            </td>
                            <td><small>{{ $visitor->created_at->diffForHumans() }}</small></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="fas fa-chart-bar fa-2x mb-2 d-block"></i>
                                এখনও কোনো ভিজিটর তথ্য নেই
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top Pages -->
    <div class="col-lg-5">
        <div class="analytics-card">
            <h5 class="section-title"><i class="fas fa-file-alt"></i> জনপ্রিয় পেজ</h5>
            <div class="table-responsive">
                <table class="table visitor-table">
                    <thead>
                        <tr>
                            <th>পেজ</th>
                            <th>ভিউ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topPages as $page)
                        <tr>
                            <td>
                                <small>{{ Str::limit(str_replace(url('/'), '', $page->page_url), 40) ?: '/' }}</small>
                            </td>
                            <td><span class="badge bg-primary">{{ $page->count }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-4">
                                <i class="fas fa-file fa-2x mb-2 d-block"></i>
                                এখনও কোনো পেজ ভিউ নেই
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Platform Stats -->
<div class="analytics-card">
    <h5 class="section-title"><i class="fas fa-laptop"></i> প্লাটফর্ম পরিসংখ্যান</h5>
    <div class="row g-3">
        @php $totalPlatforms = array_sum($platformStats) ?: 1; @endphp
        @foreach($platformStats as $platform => $count)
        <div class="col-6 col-md-2">
            <div class="stat-box {{ ['Windows' => 'primary', 'Android' => 'success', 'iOS' => 'info', 'macOS' => 'purple', 'Linux' => 'warning'][$platform] ?? 'info' }}">
                <h2>{{ $count }}</h2>
                <p>{{ $platform ?: 'Unknown' }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('trendChart').getContext('2d');
    
    const visitorData = @json($visitorTrend);
    const registrationData = @json($registrationTrend);
    const listingData = @json($listingTrend);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: visitorData.map(d => d.date),
            datasets: [
                {
                    label: 'ভিজিটর',
                    data: visitorData.map(d => d.count),
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'রেজিস্ট্রেশন',
                    data: registrationData.map(d => d.count),
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'লিস্টিং',
                    data: listingData.map(d => d.count),
                    borderColor: '#ffc107',
                    backgroundColor: 'rgba(255, 193, 7, 0.1)',
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endpush
