@php
$bdDt = $game['bd_time'] ?? null;
$dateStr = $bdDt ? $bdDt->format('d M') : '---';
$timeStr = $bdDt ? $bdDt->format('h:i A') : '---';
$finished = ($game['finished'] ?? '') === 'TRUE';
$elapsed = $game['time_elapsed'] ?? 'notstarted';
$isLive = !$finished && $elapsed !== 'FT' && $elapsed !== 'finished' && $elapsed !== 'notstarted';
$cardClass = $type === 'live' ? 'match-card-live' : ($type === 'upcoming' ? 'match-card-upcoming' : 'match-card-recent');
$badgeClass = $type === 'live' ? 'bg-danger' : ($type === 'upcoming' ? 'bg-success' : 'bg-secondary');
@endphp

<div class="col-lg-4 col-md-6">
    <div class="card shadow-sm border-0 {{ $cardClass }} h-100 wc-card">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex justify-content-between align-items-center mb-2 mb-md-3">
                <span class="badge {{ $badgeClass }}">{{ $game['status_label'] ?? '---' }}</span>
                <small class="text-muted"><i class="far fa-clock me-1"></i>{{ $dateStr }} · {{ $timeStr }}</small>
            </div>

            <div class="d-flex justify-content-between align-items-center my-2 my-md-3">
                <div class="text-center flex-fill" style="min-width:0;">
                    <div class="team-name text-truncate">{{ $game['home_team_name_en'] ?? 'TBD' }}</div>
                    @if(!empty($teamMap[$game['home_team_id'] ?? '']['flag']))
                        <img src="{{ $teamMap[$game['home_team_id']]['flag'] }}" alt="" class="wc-flag">
                    @endif
                </div>

                <div class="px-2 px-md-3 text-center">
                    @if($finished || $elapsed !== 'notstarted')
                        <div class="score-big">{{ $game['home_score'] ?? 0 }} - {{ $game['away_score'] ?? 0 }}</div>
                    @else
                        <div class="score-big text-muted">VS</div>
                    @endif
                    @if($isLive)
                        <small class="text-danger fw-bold d-block mt-1">{{ $elapsed }}'</small>
                    @endif
                </div>

                <div class="text-center flex-fill" style="min-width:0;">
                    <div class="team-name text-truncate">{{ $game['away_team_name_en'] ?? 'TBD' }}</div>
                    @if(!empty($teamMap[$game['away_team_id'] ?? '']['flag']))
                        <img src="{{ $teamMap[$game['away_team_id']]['flag'] }}" alt="" class="wc-flag">
                    @endif
                </div>
            </div>

            <div class="text-center">
                <small class="text-muted">
                    <i class="fas fa-map-marker-alt me-1"></i>
                    {{ $stadiumMap[$game['stadium_id'] ?? '']['name_en'] ?? '---' }}
                    <span class="d-none d-sm-inline"> · </span><span class="d-sm-none"><br></span>গ্রুপ {{ $game['group'] ?? '---' }}
                </small>
            </div>

            @if(!empty($game['home_scorers']) && $game['home_scorers'] !== 'null')
                <div class="mt-2 pt-2 border-top">
                    <small class="text-muted d-block text-truncate"><strong>{{ $game['home_team_name_en'] }}:</strong> {{ $game['home_scorers'] }}</small>
                </div>
            @endif
            @if(!empty($game['away_scorers']) && $game['away_scorers'] !== 'null')
                <div class="mt-1">
                    <small class="text-muted d-block text-truncate"><strong>{{ $game['away_team_name_en'] }}:</strong> {{ $game['away_scorers'] }}</small>
                </div>
            @endif
        </div>
    </div>
</div>
