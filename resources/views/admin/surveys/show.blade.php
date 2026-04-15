@extends('admin.layouts.app')
@section('title', 'সার্ভে ফলাফল - ' . $survey->title)

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h1><i class="fas fa-chart-bar me-2"></i>{{ $survey->title }}</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.surveys.votes.pdf', $survey->id) }}" class="btn btn-danger"><i class="fas fa-file-pdf me-1"></i>PDF</a>
        <a href="{{ route('admin.surveys.votes.excel', $survey->id) }}" class="btn btn-success"><i class="fas fa-file-excel me-1"></i>Excel</a>
        <a href="{{ route('admin.surveys.votes', $survey->id) }}" class="btn btn-outline-info"><i class="fas fa-users me-1"></i>ভোটার তালিকা ({{ $totalVotes }})</a>
        <a href="{{ route('admin.surveys.edit', $survey->id) }}" class="btn btn-outline-warning"><i class="fas fa-edit me-1"></i>এডিট</a>
        <a href="{{ route('admin.surveys.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>ফিরে যান</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-question-circle me-2 text-primary"></i>{{ $survey->question }}</h5>
            </div>
            <div class="card-body">
                @if($survey->image)
                    <img src="{{ asset('storage/' . $survey->image) }}" class="img-fluid rounded mb-3" style="max-height: 300px;">
                @endif

                @foreach($results as $result)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <strong>{{ $result['option'] }}</strong>
                            <span>{{ $result['count'] }} ভোট ({{ $result['percentage'] }}%)</span>
                        </div>
                        <div class="progress" style="height: 30px;">
                            <div class="progress-bar {{ $loop->index == 0 ? 'bg-success' : ($loop->index == 1 ? 'bg-danger' : ($loop->index == 2 ? 'bg-warning' : 'bg-info')) }}" 
                                 style="width: {{ $result['percentage'] }}%">
                                {{ $result['percentage'] }}%
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="text-center mt-4">
                    <h4><span class="badge bg-primary">মোট ভোট: {{ $totalVotes }}</span></h4>
                </div>
            </div>
        </div>

        <!-- Chart -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-chart-pie me-2 text-success"></i>গ্রাফ</h5></div>
            <div class="card-body">
                <canvas id="surveyChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white"><h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>তথ্য</h6></div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr>
                        <th>অবস্থা</th>
                        <td>
                            @if($survey->is_live)
                                <span class="badge bg-success">🟢 চলমান</span>
                            @elseif($survey->is_ended)
                                <span class="badge bg-secondary">শেষ হয়েছে</span>
                            @elseif($survey->is_upcoming)
                                <span class="badge bg-warning text-dark">আসন্ন</span>
                            @else
                                <span class="badge bg-dark">নিষ্ক্রিয়</span>
                            @endif
                        </td>
                    </tr>
                    <tr><th>শুরু</th><td>{{ $survey->start_time->format('d M Y, h:i A') }}</td></tr>
                    <tr><th>শেষ</th><td>{{ $survey->end_time->format('d M Y, h:i A') }}</td></tr>
                    <tr><th>মোট ভোট</th><td><strong>{{ $totalVotes }}</strong></td></tr>
                    <tr><th>হোমপেজ</th><td>{{ $survey->show_on_homepage ? '✅ হ্যা' : '❌ না' }}</td></tr>
                </table>
            </div>
        </div>

        @if($classBreakdown->isNotEmpty())
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white"><h6 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>ক্লাস অনুযায়ী</h6></div>
            <div class="card-body">
                @foreach($classBreakdown as $type => $count)
                    <div class="d-flex justify-content-between">
                        <span>{{ $type == 'intermediate' ? 'ইন্টারমিডিয়েট' : ($type == 'honours' ? 'অনার্স' : $type) }}</span>
                        <strong>{{ $count }}</strong>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Comments from অন্যান্য -->
        @php $comments = $survey->votes->whereNotNull('comment')->where('comment', '!=', ''); @endphp
        @if($comments->count() > 0)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h6 class="mb-0"><i class="fas fa-comments me-2"></i>মতামত ({{ $comments->count() }})</h6></div>
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                @foreach($comments as $vote)
                    <div class="bg-light rounded p-2 mb-2">
                        <small class="text-muted">{{ $vote->created_at->diffForHumans() }}</small>
                        <p class="mb-0 small">{{ $vote->comment }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <a href="{{ route('survey.show', $survey->id) }}" class="btn btn-primary w-100 mt-3" target="_blank">
            <i class="fas fa-external-link-alt me-1"></i>সার্ভে পেজ দেখুন
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('surveyChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(collect($results)->pluck('option')) !!},
            datasets: [{
                data: {!! json_encode(collect($results)->pluck('count')) !!},
                backgroundColor: ['#28a745', '#dc3545', '#ffc107', '#17a2b8', '#6f42c1', '#fd7e14'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 14 } } },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const pct = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                            return context.label + ': ' + context.parsed + ' (' + pct + '%)';
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
