@extends('frontend.layouts.app')

@section('title', 'FIFA World Cup 2026')
@section('meta_description', 'FIFA World Cup 2026 - United States, Mexico & Canada। লাইভ স্কোর, ম্যাচ সময়সূচী, গ্রুপ টেবিল ও দলের তথ্য।')

@php
    $todayStr = now()->format('Y-m-d');
    $todayGames = array_filter($games, function($g) use ($todayStr) {
        $bdDt = $g['bd_time'] ?? null;
        if (!$bdDt) return false;
        $isToday = $bdDt->format('Y-m-d') === $todayStr;
        $finished = ($g['finished'] ?? '') === 'TRUE';
        $elapsed = $g['time_elapsed'] ?? 'notstarted';
        $isFinished = $finished || $elapsed === 'FT' || $elapsed === 'finished';
        return $isToday && !$isFinished;
    });
    $liveGames = array_filter($games, function($g) {
        $elapsed = $g['time_elapsed'] ?? 'notstarted';
        $finished = ($g['finished'] ?? '') === 'TRUE';
        return !$finished && $elapsed !== 'FT' && $elapsed !== 'finished' && $elapsed !== 'notstarted';
    });
    $upcomingGames = array_filter($games, function($g) {
        $elapsed = $g['time_elapsed'] ?? 'notstarted';
        $finished = ($g['finished'] ?? '') === 'TRUE';
        return !$finished && $elapsed === 'notstarted';
    });
    $recentGames = array_filter($games, function($g) {
        $finished = ($g['finished'] ?? '') === 'TRUE';
        $elapsed = $g['time_elapsed'] ?? '';
        return $finished || $elapsed === 'FT' || $elapsed === 'finished';
    });
    $todayGames = array_values($todayGames);
    $liveGames = array_values($liveGames);
    $upcomingGames = array_values($upcomingGames);
    $recentGames = array_slice(array_values($recentGames), -6);

    // Group upcoming matches by Bangladesh date, then take the earliest day's full set
    $upcomingByDay = [];
    foreach ($upcomingGames as $g) {
        $bdDt = $g['bd_time'] ?? null;
        if (!$bdDt) continue;
        $upcomingByDay[$bdDt->format('Y-m-d')][] = $g;
    }
    ksort($upcomingByDay);
    $nextDayKey = array_key_first($upcomingByDay);
    $nextDayGames = $nextDayKey ? $upcomingByDay[$nextDayKey] : [];
    $nextDayLabel = $nextDayKey ? \Carbon\Carbon::parse($nextDayKey)->format('d M') : '';
@endphp

@section('content')

{{-- Hero / Today's Matches --}}
<section class="wc-hero py-4">
    <div class="container">
        <div class="text-center mb-4">
            <h1 class="fw-bold mb-2"><i class="fas fa-futbol me-2"></i>FIFA World Cup 2026</h1>
            <p class="mb-0 opacity-75">United States · Mexico · Canada <span class="d-inline-block mx-2">|</span> বাংলাদেশ সময় অনুযায়ী</p>
            <div class="mt-2">
                <span class="badge bg-white text-success fw-bold">
                    <i class="fas fa-eye me-1"></i>{{ number_format($visitCount) }} বার দেখা হয়েছে
                </span>
            </div>
        </div>

        @if(count($todayGames) > 0)
            <div class="row g-3 mb-2">
                <div class="col-12 text-center">
                    <span class="badge bg-warning text-dark fs-6 px-3 py-2 mb-2">
                        <i class="fas fa-calendar-day me-1"></i>আজকের ম্যাচ
                    </span>
                </div>
                @foreach($todayGames as $game)
                    @include('frontend.world-cup._match_card', ['game' => $game, 'type' => ($game['finished'] ?? '') === 'TRUE' ? 'recent' : ((($game['time_elapsed'] ?? 'notstarted') !== 'notstarted' && ($game['time_elapsed'] ?? '') !== 'FT' && ($game['time_elapsed'] ?? '') !== 'finished') ? 'live' : 'upcoming')])
                @endforeach
            </div>
        @elseif(count($liveGames) > 0)
            <div class="row g-3 mb-2">
                <div class="col-12 text-center">
                    <span class="badge bg-danger fs-6 px-3 py-2 mb-2 animate-pulse">
                        <i class="fas fa-circle me-1" style="font-size:0.5rem; vertical-align:middle;"></i>লাইভ ম্যাচ
                    </span>
                </div>
                @foreach($liveGames as $game)
                    @include('frontend.world-cup._match_card', ['game' => $game, 'type' => 'live'])
                @endforeach
            </div>
        @elseif(count($nextDayGames) > 0)
            <div class="row g-3 mb-2">
                <div class="col-12 text-center">
                    <span class="badge bg-success fs-6 px-3 py-2 mb-2">
                        <i class="fas fa-calendar-alt me-1"></i>আসন্ন ম্যাচ{{ $nextDayLabel ? ' · ' . $nextDayLabel : '' }}
                    </span>
                </div>
                @foreach($nextDayGames as $game)
                    @include('frontend.world-cup._match_card', ['game' => $game, 'type' => 'upcoming'])
                @endforeach
            </div>
        @elseif(count($recentGames) > 0)
            <div class="row g-3 mb-2">
                <div class="col-12 text-center">
                    <span class="badge bg-secondary fs-6 px-3 py-2 mb-2">
                        <i class="fas fa-history me-1"></i>সম্প্রতি শেষ হওয়া ম্যাচ
                    </span>
                </div>
                @foreach(array_slice($recentGames, -3) as $game)
                    @include('frontend.world-cup._match_card', ['game' => $game, 'type' => 'recent'])
                @endforeach
            </div>
        @endif
    </div>
</section>

{{-- Recent Finished Matches --}}
@if(count($recentGames) > 0)
<section class="py-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <div class="container">
        <div class="text-center mb-3">
            <h4 class="fw-bold text-secondary"><i class="fas fa-history me-2"></i>সম্প্রতি শেষ হওয়া ম্যাচ</h4>
        </div>
        <div class="row g-3">
            @foreach(array_slice($recentGames, -6) as $game)
                @include('frontend.world-cup._match_card', ['game' => $game, 'type' => 'recent'])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Full Schedule --}}
<section class="py-5 bg-light">
    <div class="container">
        <h3 class="fw-bold text-center mb-4 wc-title"><i class="fas fa-list-alt me-2"></i>সব ম্যাচের সময়সূচী</h3>
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark wc-table-head">
                            <tr>
                                <th class="ps-4">তারিখ</th>
                                <th>সময়</th>
                                <th>পর্ব</th>
                                <th>খেলা</th>
                                <th>ফলাফল</th>
                                <th>স্থিতি</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($games as $game)
                                @php
                                    $bdDt = $game['bd_time'] ?? null;
                                    $dateStr = $bdDt ? $bdDt->format('d M') : '---';
                                    $timeStr = $bdDt ? $bdDt->format('h:i A') : '---';
                                    $group = $game['group'] ?? '';
                                    $stageLabel = $group;
                                    if (in_array($group, ['R32'])) $stageLabel = 'Round of 32';
                                    if (in_array($group, ['R16'])) $stageLabel = 'Round of 16';
                                    if ($group === 'QF') $stageLabel = 'Quarter Final';
                                    if ($group === 'SF') $stageLabel = 'Semi Final';
                                    if ($group === '3RD') $stageLabel = '3rd Place';
                                    if ($group === 'FINAL') $stageLabel = 'Final';
                                    $finished = ($game['finished'] ?? '') === 'TRUE';
                                    $isToday = $bdDt && $bdDt->format('Y-m-d') === $todayStr;
                                    $rowClass = $isToday ? 'table-success fw-semibold' : '';
                                @endphp
                                <tr class="{{ $rowClass }}">
                                    <td class="ps-4">{{ $dateStr }}</td>
                                    <td><small>{{ $timeStr }}</small></td>
                                    <td><span class="badge bg-secondary">{{ $stageLabel }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span class="fw-semibold">{{ $game['home_team_name_en'] ?? 'TBD' }}</span>
                                            <span class="text-muted small">vs</span>
                                            <span class="fw-semibold">{{ $game['away_team_name_en'] ?? 'TBD' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($finished || ($game['time_elapsed'] ?? '') !== 'notstarted')
                                            <span class="badge bg-dark">{{ $game['home_score'] ?? 0 }} - {{ $game['away_score'] ?? 0 }}</span>
                                        @else
                                            <span class="text-muted">---</span>
                                        @endif
                                    </td>
                                    <td class="{{ $finished ? 'text-muted' : 'fw-bold text-success' }}">{{ $game['status_label'] ?? '---' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Groups Section --}}
@if(count($groups) > 0)
<section class="py-5">
    <div class="container">
        <h3 class="fw-bold text-center mb-4 wc-title"><i class="fas fa-table me-2"></i>গ্রুপ টেবিল</h3>
        <div class="row g-4">
            @foreach($groups as $group)
                <div class="col-lg-4 col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header text-white fw-bold wc-group-header">
                            <i class="fas fa-users me-2"></i>গ্রুপ {{ $group['name'] ?? '?' }}
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-3">দল</th>
                                            <th class="text-center">খেলা</th>
                                            <th class="text-center">জয়</th>
                                            <th class="text-center">পয়েন্ট</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $groupName = $group['name'] ?? '';
                                            $groupTeams = array_filter($teams, fn($t) => ($t['group_name'] ?? '') === $groupName);
                                            // Sort by points descending
                                            usort($groupTeams, function($a, $b) use ($teamStats) {
                                                $pa = $teamStats[$a['id'] ?? '']['points'] ?? 0;
                                                $pb = $teamStats[$b['id'] ?? '']['points'] ?? 0;
                                                return $pb <=> $pa;
                                            });
                                        @endphp
                                        @foreach($groupTeams as $team)
                                            @php
                                                $tid = $team['id'] ?? '';
                                                $stats = $teamStats[$tid] ?? ['played'=>0,'won'=>0,'drawn'=>0,'lost'=>0,'gf'=>0,'ga'=>0,'points'=>0];
                                            @endphp
                                            <tr>
                                                <td class="ps-3">
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if(!empty($team['flag']))
                                                            <img src="{{ $team['flag'] }}" alt="" class="wc-flag-sm">
                                                        @else
                                                            <span class="d-inline-block bg-secondary wc-flag-sm-placeholder"></span>
                                                        @endif
                                                        <span>{{ $team['name'] ?? '---' }}</span>
                                                    </div>
                                                </td>
                                                <td class="text-center">{{ $stats['played'] > 0 ? $stats['played'] : '-' }}</td>
                                                <td class="text-center">{{ $stats['won'] > 0 ? $stats['won'] : '-' }}</td>
                                                <td class="text-center fw-bold">{{ $stats['points'] > 0 ? $stats['points'] : '-' }}</td>
                                            </tr>
                                        @endforeach
                                        @if(count($groupTeams) === 0)
                                            <tr><td colspan="4" class="text-center text-muted py-3">দলের তথ্য আসন্ন</td></tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Stadiums --}}
@if(count($stadiums) > 0)
<section class="py-5 bg-light">
    <div class="container">
        <h3 class="fw-bold text-center mb-4 wc-title"><i class="fas fa-map-marker-alt me-2"></i>স্টেডিয়াম</h3>
        <div class="row g-3">
            @foreach($stadiums as $stadium)
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="card shadow-sm border-0 h-100 text-center p-3 wc-stadium-card">
                        <div class="mb-2">
                            <i class="fas fa-stadium text-success" style="font-size: 1.5rem;"></i>
                        </div>
                        <h6 class="fw-bold mb-1">{{ $stadium['name_en'] ?? '---' }}</h6>
                        <small class="text-muted d-block">{{ $stadium['city_en'] ?? '' }}, {{ $stadium['country_en'] ?? '' }}</small>
                        <small class="text-success fw-semibold">{{ number_format($stadium['capacity'] ?? 0) }} আসন</small>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

@push('styles')
<style>
    .wc-hero {
        background: linear-gradient(135deg, #0f5132 0%, #146c43 50%, #198754 100%);
        color: #fff;
    }
    .wc-hero h1 {
        font-size: 1.8rem;
    }
    @media (min-width: 768px) {
        .wc-hero h1 { font-size: 2.5rem; }
    }
    .wc-title {
        color: #0f5132;
    }
    .wc-table-head {
        background: #0f5132 !important;
    }
    .wc-table-head th {
        background: #0f5132 !important;
        color: #fff;
    }
    .wc-group-header {
        background: #146c43 !important;
    }
    .wc-flag-sm {
        width: 24px;
        height: 16px;
        object-fit: cover;
        border-radius: 2px;
    }
    .wc-flag-sm-placeholder {
        width: 24px;
        height: 16px;
        border-radius: 2px;
    }
    .wc-stadium-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .wc-stadium-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important;
    }
    .wc-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border-radius: 12px;
    }
    .wc-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08) !important;
    }
    .wc-flag {
        width: 36px;
        height: 24px;
        object-fit: cover;
        border-radius: 3px;
        margin-top: 4px;
    }
    @media (min-width: 768px) {
        .wc-flag { width: 40px; height: 28px; }
    }
    .match-card-live {
        background: linear-gradient(135deg, #fff 0%, #fff8f0 100%);
        border-left: 4px solid #dc3545;
    }
    .match-card-upcoming {
        background: linear-gradient(135deg, #fff 0%, #f0fff4 100%);
        border-left: 4px solid #198754;
    }
    .match-card-recent {
        background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
        border-left: 4px solid #6c757d;
    }
    .score-big {
        font-size: 1.8rem;
        font-weight: 700;
        color: #0f5132;
    }
    @media (min-width: 768px) {
        .score-big { font-size: 2rem; }
    }
    .team-name {
        font-weight: 600;
        font-size: 0.9rem;
    }
    @media (min-width: 768px) {
        .team-name { font-size: 1rem; }
    }
    .animate-pulse {
        animation: pulse-badge 1.5s infinite;
    }
    @keyframes pulse-badge {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
</style>
@endpush

@push('scripts')
<script>
(function() {
    const POLL_INTERVAL = 30000; // 30 seconds
    let lastUpdate = Date.now();

    function updateLiveScores() {
        fetch('{{ route("world-cup.api.games") }}')
            .then(r => r.json())
            .then(data => {
                if (!data.games) return;
                let anyUpdated = false;
                data.games.forEach(game => {
                    const card = document.querySelector('[data-game-id="' + game.id + '"]');
                    if (!card) return;

                    const finished = (game.finished || '') === 'TRUE';
                    const elapsed = game.time_elapsed || 'notstarted';
                    const isLive = !finished && elapsed !== 'FT' && elapsed !== 'finished' && elapsed !== 'notstarted';

                    // Update status badge
                    const badge = card.querySelector('.badge');
                    if (badge) {
                        let label = game.status_label || '---';
                        let newClass = 'bg-secondary';
                        if (isLive) newClass = 'bg-danger';
                        else if (finished || elapsed === 'FT' || elapsed === 'finished') newClass = 'bg-secondary';
                        else if (elapsed === 'notstarted') newClass = 'bg-success';

                        if (badge.textContent.trim() !== label) {
                            badge.textContent = label;
                            anyUpdated = true;
                        }
                        badge.className = 'badge ' + newClass;
                    }

                    // Update score
                    const scoreDiv = card.querySelector('.score-big');
                    if (scoreDiv && (finished || elapsed !== 'notstarted')) {
                        const newScore = (game.home_score || 0) + ' - ' + (game.away_score || 0);
                        if (scoreDiv.textContent.trim() !== newScore && scoreDiv.textContent.trim() !== 'VS') {
                            scoreDiv.textContent = newScore;
                            scoreDiv.classList.remove('text-muted');
                            scoreDiv.style.color = '#0f5132';
                            anyUpdated = true;
                            // Flash animation on score change
                            scoreDiv.style.transition = 'transform 0.3s, color 0.3s';
                            scoreDiv.style.transform = 'scale(1.3)';
                            setTimeout(() => scoreDiv.style.transform = 'scale(1)', 300);
                        }
                    }

                    // Update elapsed time
                    const elapsedSmall = card.querySelector('small.text-danger.fw-bold.d-block.mt-1');
                    if (isLive) {
                        if (elapsedSmall) {
                            if (elapsedSmall.textContent.trim() !== elapsed + "'") {
                                elapsedSmall.textContent = elapsed + "'";
                                anyUpdated = true;
                            }
                        } else {
                            // Insert elapsed time after score
                            if (scoreDiv && scoreDiv.nextElementSibling) {
                                const newSmall = document.createElement('small');
                                newSmall.className = 'text-danger fw-bold d-block mt-1';
                                newSmall.textContent = elapsed + "'";
                                scoreDiv.parentNode.appendChild(newSmall);
                                anyUpdated = true;
                            }
                        }
                    } else if (elapsedSmall) {
                        elapsedSmall.remove();
                    }
                });

                if (anyUpdated) {
                    lastUpdate = Date.now();
                    showToast('লাইভ স্কোর আপডেট হয়েছে ⚽');
                }
            })
            .catch(() => {});
    }

    function showToast(msg) {
        let toast = document.getElementById('wc-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'wc-toast';
            toast.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;background:#0f5132;color:#fff;padding:12px 20px;border-radius:8px;box-shadow:0 4px 15px rgba(0,0,0,0.2);font-weight:600;transform:translateX(120%);transition:transform 0.4s ease;pointer-events:none;';
            document.body.appendChild(toast);
        }
        toast.textContent = msg;
        toast.style.transform = 'translateX(0)';
        setTimeout(() => { toast.style.transform = 'translateX(120%)'; }, 3000);
    }

    // Poll every 30s
    setInterval(updateLiveScores, POLL_INTERVAL);

    // Also update on page visibility change
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden && Date.now() - lastUpdate > POLL_INTERVAL) {
            updateLiveScores();
        }
    });
})();
</script>
@endpush
