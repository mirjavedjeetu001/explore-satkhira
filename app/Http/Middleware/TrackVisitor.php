<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    public function handle(Request $request, Closure $next): Response
    {
        // Skip admin routes and API routes
        if ($request->is('admin/*') || $request->is('api/*')) {
            return $next($request);
        }

        // Skip bots and crawlers
        $userAgent = $request->userAgent() ?? '';
        if ($this->isBot($userAgent)) {
            return $next($request);
        }

        // Track the visit
        $this->trackVisit($request);

        return $next($request);
    }

    protected function trackVisit(Request $request): void
    {
        $ipAddress = $request->ip();
        $today = now()->toDateString();

        // Check if this IP already visited today (to avoid duplicate entries)
        $existingVisit = Visitor::where('ip_address', $ipAddress)
            ->whereDate('visit_date', $today)
            ->first();

        if (!$existingVisit) {
            // Parse user agent for device info
            $userAgent = $request->userAgent() ?? '';
            $browser = $this->getBrowser($userAgent);
            $platform = $this->getPlatform($userAgent);
            $device = $this->getDevice($userAgent);

            Visitor::create([
                'ip_address' => $ipAddress,
                'user_agent' => substr($userAgent, 0, 255),
                'page_url' => $request->fullUrl(),
                'referrer' => $request->header('referer'),
                'device' => $device,
                'browser' => $browser,
                'platform' => $platform,
                'user_id' => auth()->id(),
                'visit_date' => $today,
            ]);
        }
    }

    protected function isBot(string $userAgent): bool
    {
        $bots = [
            'bot', 'crawl', 'spider', 'slurp', 'googlebot', 'bingbot',
            'yahoo', 'baidu', 'yandex', 'facebook', 'twitter', 'linkedin',
            'pinterest', 'curl', 'wget', 'python', 'java', 'php'
        ];

        $userAgentLower = strtolower($userAgent);
        foreach ($bots as $bot) {
            if (str_contains($userAgentLower, $bot)) {
                return true;
            }
        }

        return false;
    }

    protected function getBrowser(string $userAgent): string
    {
        if (str_contains($userAgent, 'Firefox')) return 'Firefox';
        if (str_contains($userAgent, 'Edg')) return 'Edge';
        if (str_contains($userAgent, 'Chrome')) return 'Chrome';
        if (str_contains($userAgent, 'Safari')) return 'Safari';
        if (str_contains($userAgent, 'Opera') || str_contains($userAgent, 'OPR')) return 'Opera';
        if (str_contains($userAgent, 'MSIE') || str_contains($userAgent, 'Trident')) return 'IE';
        return 'Other';
    }

    protected function getPlatform(string $userAgent): string
    {
        if (str_contains($userAgent, 'Windows')) return 'Windows';
        if (str_contains($userAgent, 'Mac')) return 'macOS';
        if (str_contains($userAgent, 'Linux')) return 'Linux';
        if (str_contains($userAgent, 'Android')) return 'Android';
        if (str_contains($userAgent, 'iPhone') || str_contains($userAgent, 'iPad')) return 'iOS';
        return 'Other';
    }

    protected function getDevice(string $userAgent): string
    {
        if (str_contains($userAgent, 'Mobile') || str_contains($userAgent, 'Android')) {
            if (str_contains($userAgent, 'Tablet') || str_contains($userAgent, 'iPad')) {
                return 'Tablet';
            }
            return 'Mobile';
        }
        return 'Desktop';
    }
}
