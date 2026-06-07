<?php

namespace App\Http\Middleware;

use App\Models\Visit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisit
{
    /**
     * Paths that should NOT be tracked
     */
    protected array $excludedPaths = [
        'admin',
        'admin/*',
        'login',
        'logout',
        'register',
        'password/*',
        'sanctum/*',
        '_debugbar/*',
        'api/*',
        'livewire/*',
        'build/*',
        'media/*',
        'storage/*',
        'favicon.ico',
        'robots.txt',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track GET requests that return HTML
        if ($request->method() !== 'GET') return $response;
        if ($request->ajax() || $request->wantsJson()) return $response;
        if ($this->isExcluded($request->path())) return $response;

        // Only track successful pages
        if ($response->getStatusCode() >= 400) return $response;

        try {
            $ua       = $request->userAgent();
            $referrer = $request->header('referer');
            $utmSource = $request->query('utm_source');

            Visit::create([
                'ip_address'      => $request->ip(),
                'user_agent'      => $ua ? substr($ua, 0, 500) : null,
                'url'             => substr($request->fullUrl(), 0, 2048),
                'path'            => '/'.ltrim($request->path(), '/'),
                'referrer'        => $referrer ? substr($referrer, 0, 2048) : null,
                'referrer_domain' => $referrer ? (parse_url($referrer, PHP_URL_HOST) ?? null) : null,
                'source'          => Visit::detectSource($referrer, $utmSource),
                'utm_source'      => $request->query('utm_source'),
                'utm_medium'      => $request->query('utm_medium'),
                'utm_campaign'    => $request->query('utm_campaign'),
                'device_type'     => Visit::detectDevice($ua),
                'browser'         => Visit::detectBrowser($ua),
                'os'              => Visit::detectOS($ua),
                'country'         => null, // pode integrar IP geolocation depois
                'city'            => null,
                'session_id'      => session()->getId(),
                'is_bot'          => Visit::isBot($ua),
            ]);
        } catch (\Throwable $e) {
            // Nunca interrompe o request por causa do tracking
            report($e);
        }

        return $response;
    }

    protected function isExcluded(string $path): bool
    {
        foreach ($this->excludedPaths as $pattern) {
            if ($path === $pattern) return true;
            if (str_contains($pattern, '*') && fnmatch($pattern, $path)) return true;
        }
        return false;
    }
}
