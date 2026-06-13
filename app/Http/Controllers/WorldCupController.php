<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WorldCupController extends Controller
{
    private const API_BASE = 'https://worldcup26.ir';
    private const CACHE_TTL = 60; // 1 minute for live scores
    private const API_TIMEOUT = 25; // API can be slow (~16s)

    /**
     * Fetch + cache an endpoint with a persistent "last known good" fallback.
     * If the live fetch fails or returns empty, we serve the last good copy
     * so the page never goes blank.
     */
    private function fetchWithFallback(string $cacheKey, string $endpoint, string $jsonKey, int $ttl): array
    {
        return Cache::remember($cacheKey, $ttl, function () use ($endpoint, $jsonKey, $cacheKey) {
            try {
                $response = Http::timeout(self::API_TIMEOUT)->get(self::API_BASE . $endpoint);
                $data = $response->json($jsonKey, []);
                if (!empty($data)) {
                    // Save persistent backup (forever)
                    Cache::forever($cacheKey . '_backup', $data);
                    return $data;
                }
            } catch (\Exception $e) {
                // fall through to backup
            }
            // Serve last known good copy if available
            return Cache::get($cacheKey . '_backup', []);
        });
    }

    /**
     * Timezone offsets for stadium regions during June (DST).
     * Bangladesh is UTC+6. Offset = hours behind UTC + 6.
     */
    private const REGION_BD_OFFSETS = [
        'Western' => 13,  // UTC-7 (PDT) → +13h to BD
        'Central' => 11,  // UTC-5 (CDT) → +11h to BD
        'Eastern' => 10,  // UTC-4 (EDT) → +10h to BD
    ];

    private const STADIUM_REGIONS = [
        '1'  => 'Central',   // Mexico City
        '2'  => 'Central',   // Guadalajara
        '3'  => 'Central',   // Monterrey
        '4'  => 'Central',   // Dallas
        '5'  => 'Central',   // Houston
        '6'  => 'Central',   // Kansas City
        '7'  => 'Eastern',   // Atlanta
        '8'  => 'Eastern',   // Miami
        '9'  => 'Eastern',   // Boston
        '10' => 'Eastern',   // Philadelphia
        '11' => 'Eastern',   // New York
        '12' => 'Eastern',   // Toronto
        '13' => 'Western',   // Vancouver
        '14' => 'Western',   // Seattle
        '15' => 'Western',   // San Francisco
        '16' => 'Western',   // Los Angeles
    ];

    public function index()
    {
        // Track page visits (persisted in DB via WorldCupSetting, cached for 30,000s)
        $visitKey = 'wc_page_visits';
        $visitCount = Cache::remember($visitKey, 30000, function () {
            return (int) \App\Models\WorldCupSetting::get('visit_count', 0);
        }) + 1;
        \App\Models\WorldCupSetting::set('visit_count', $visitCount);
        Cache::put($visitKey, $visitCount, 30000);

        $games = $this->fetchGames();
        $teams = $this->fetchTeams();
        $groups = $this->fetchGroups();
        $stadiums = $this->fetchStadiums();

        // Enrich games with Bangladesh time and stadium region
        $games = array_map(function ($game) use ($stadiums) {
            $game['bd_time'] = $this->toBangladeshTime($game);
            $game['bd_date_formatted'] = $this->formatBdTime($game['bd_time'] ?? null);
            $game['status_label'] = $this->getStatusLabel($game);
            return $game;
        }, $games);

        // Sort by BD time
        usort($games, function ($a, $b) {
            $ta = $a['bd_time'] ?? null;
            $tb = $b['bd_time'] ?? null;
            if (!$ta || !$tb) return 0;
            return $ta <=> $tb;
        });

        // Normalize team fields (API uses name_en / groups)
        $teams = array_map(function ($t) {
            $t['name'] = $t['name_en'] ?? $t['name'] ?? '---';
            $t['group_name'] = $t['groups'] ?? $t['group_name'] ?? '';
            return $t;
        }, $teams);

        // Build team lookup
        $teamMap = [];
        foreach ($teams as $team) {
            $teamMap[$team['id']] = $team;
        }

        // Build stadium lookup
        $stadiumMap = [];
        foreach ($stadiums as $s) {
            $stadiumMap[$s['id']] = $s;
        }

        // Calculate team stats from finished games
        $teamStats = [];
        foreach ($teams as $team) {
            $teamStats[$team['id']] = [
                'played' => 0,
                'won'    => 0,
                'drawn'  => 0,
                'lost'   => 0,
                'gf'     => 0,
                'ga'     => 0,
                'points' => 0,
            ];
        }

        foreach ($games as $game) {
            $finished = ($game['finished'] ?? '') === 'TRUE';
            if (!$finished) continue;

            $homeId = $game['home_team_id'] ?? null;
            $awayId = $game['away_team_id'] ?? null;
            $homeScore = (int) ($game['home_score'] ?? 0);
            $awayScore = (int) ($game['away_score'] ?? 0);

            if (!$homeId || !$awayId) continue;

            // Update played
            if (isset($teamStats[$homeId])) $teamStats[$homeId]['played']++;
            if (isset($teamStats[$awayId])) $teamStats[$awayId]['played']++;

            // Update goals
            if (isset($teamStats[$homeId])) {
                $teamStats[$homeId]['gf'] += $homeScore;
                $teamStats[$homeId]['ga'] += $awayScore;
            }
            if (isset($teamStats[$awayId])) {
                $teamStats[$awayId]['gf'] += $awayScore;
                $teamStats[$awayId]['ga'] += $homeScore;
            }

            // Update result
            if ($homeScore > $awayScore) {
                if (isset($teamStats[$homeId])) { $teamStats[$homeId]['won']++; $teamStats[$homeId]['points'] += 3; }
                if (isset($teamStats[$awayId])) { $teamStats[$awayId]['lost']++; }
            } elseif ($homeScore < $awayScore) {
                if (isset($teamStats[$awayId])) { $teamStats[$awayId]['won']++; $teamStats[$awayId]['points'] += 3; }
                if (isset($teamStats[$homeId])) { $teamStats[$homeId]['lost']++; }
            } else {
                if (isset($teamStats[$homeId])) { $teamStats[$homeId]['drawn']++; $teamStats[$homeId]['points'] += 1; }
                if (isset($teamStats[$awayId])) { $teamStats[$awayId]['drawn']++; $teamStats[$awayId]['points'] += 1; }
            }
        }

        return view('frontend.world-cup.index', compact('games', 'teams', 'groups', 'stadiums', 'teamMap', 'stadiumMap', 'visitCount', 'teamStats'));
    }

    public function apiGames()
    {
        $games = $this->fetchGames();
        $teams = $this->fetchTeams();

        // Build quick team lookup by id
        $teamFlags = [];
        foreach ($teams as $team) {
            $teamFlags[$team['id']] = [
                'name_en' => $team['name_en'] ?? null,
                'name_fa' => $team['name_fa'] ?? null,
                'flag'    => $team['flag'] ?? null,
                'groups'  => $team['groups'] ?? null,
            ];
        }

        $games = array_map(function ($game) use ($teamFlags) {
            $bdDt = $this->toBangladeshTime($game);
            $game['bd_time'] = $bdDt ? $bdDt->format('Y-m-d H:i:s') : null;
            $game['bd_date_formatted'] = $this->formatBdTime($bdDt);
            $game['status_label'] = $this->getStatusLabel($game);

            // Attach flags for home/away teams
            $homeTeamId = $game['home_team_id'] ?? null;
            $awayTeamId = $game['away_team_id'] ?? null;
            if ($homeTeamId && isset($teamFlags[$homeTeamId])) {
                $game['home_team_flag'] = $teamFlags[$homeTeamId]['flag'];
                if (empty($game['home_team_name_en'])) {
                    $game['home_team_name_en'] = $teamFlags[$homeTeamId]['name_en'];
                }
            }
            if ($awayTeamId && isset($teamFlags[$awayTeamId])) {
                $game['away_team_flag'] = $teamFlags[$awayTeamId]['flag'];
                if (empty($game['away_team_name_en'])) {
                    $game['away_team_name_en'] = $teamFlags[$awayTeamId]['name_en'];
                }
            }
            return $game;
        }, $games);

        usort($games, function ($a, $b) {
            $ta = $a['bd_time'] ?? null;
            $tb = $b['bd_time'] ?? null;
            if (!$ta || !$tb) return 0;
            return strcmp($ta, $tb);
        });

        return response()->json(['games' => $games]);
    }

    public function apiTeams()
    {
        return response()->json(['teams' => $this->fetchTeams()]);
    }

    public function apiGroups()
    {
        return response()->json(['groups' => $this->fetchGroups()]);
    }

    public function apiStadiums()
    {
        return response()->json(['stadiums' => $this->fetchStadiums()]);
    }

    private function fetchGames(): array
    {
        return $this->fetchWithFallback('wc_games', '/get/games', 'games', self::CACHE_TTL);
    }

    private function fetchTeams(): array
    {
        return $this->fetchWithFallback('wc_teams', '/get/teams', 'teams', 300);
    }

    private function fetchGroups(): array
    {
        return $this->fetchWithFallback('wc_groups', '/get/groups', 'groups', 300);
    }

    private function fetchStadiums(): array
    {
        return $this->fetchWithFallback('wc_stadiums', '/get/stadiums', 'stadiums', 300);
    }

    private function toBangladeshTime(array $game): ?\DateTimeImmutable
    {
        $localDate = $game['local_date'] ?? null;
        $stadiumId = $game['stadium_id'] ?? null;
        if (!$localDate) return null;

        // Parse MM/DD/YYYY HH:mm
        $dt = \DateTimeImmutable::createFromFormat('m/d/Y H:i', $localDate, new \DateTimeZone('UTC'));
        if (!$dt) return null;

        $region = self::STADIUM_REGIONS[$stadiumId] ?? 'Eastern';
        $offset = self::REGION_BD_OFFSETS[$region] ?? 10;

        return $dt->modify("+{$offset} hours");
    }

    private function formatBdTime(?\DateTimeImmutable $dt): string
    {
        if (!$dt) return '---';
        return $dt->format('d M, h:i A');
    }

    private function getStatusLabel(array $game): string
    {
        $finished = ($game['finished'] ?? '') === 'TRUE';
        $elapsed = $game['time_elapsed'] ?? 'notstarted';

        if ($finished || $elapsed === 'FT' || $elapsed === 'finished') {
            return 'শেষ';
        }
        if ($elapsed === 'notstarted') {
            return 'আসন্ন';
        }
        if ($elapsed === 'HT') {
            return 'হাফটাইম';
        }
        return 'লাইভ';
    }
}
