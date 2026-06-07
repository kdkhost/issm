<?php

namespace App\Services\Security;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * @autor marcelo-brad rj
 * @contato Tel: 21 981325441
 * Email: contato@kdkhost.com.br
 * Telegram: @MARCELO_BRAD
 * Instagram: @marcelobradrj
 * WhatsApp: 21981325441
 */
class RequestSecurityService
{
    protected array $sqlPatterns = [
        '/\bUNION\b.*\bSELECT\b/i',
        '/\bSELECT\b.*\bFROM\b/i',
        '/\bINSERT\b.*\bINTO\b/i',
        '/\bDELETE\b.*\bFROM\b/i',
        '/\bUPDATE\b.*\bSET\b/i',
        '/\bDROP\b.*\bTABLE\b/i',
        '/\bALTER\b.*\bTABLE\b/i',
        '/\bCREATE\b.*\bTABLE\b/i',
        '/\bTRUNCATE\b/i',
        '/\bEXEC\b.*\(/i',
        '/\bEXECUTE\b.*\(/i',
        '/\bLOAD_FILE\s*\(/i',
        '/\bINTO\s+OUTFILE\b/i',
        '/\bINTO\s+DUMPFILE\b/i',
        '/\bCHAR\s*\(/i',
        '/\bCONCAT\s*\(/i',
        '/\bDATABASE\s*\(/i',
        '/\bVERSION\s*\(/i',
        '/\bSLEEP\s*\(/i',
        '/\bBENCHMARK\s*\(/i',
        '/\bOR\b.*=.*\bOR\b/i',
        '/\'\s*OR\s*\'[^\']*\'/i',
        '/\'\s*OR\s*1\s*=\s*1/i',
        '/\bUNION\s*.*\s*ALL\s*\bSELECT\b/i',
        '/\bWAITFOR\b.*\bDELAY\b/i',
    ];

    protected array $xssPatterns = [
        '/<script\b[^>]*>/i',
        '/<iframe\b[^>]*>/i',
        '/<object\b[^>]*>/i',
        '/<embed\b[^>]*>/i',
        '/<applet\b[^>]*>/i',
        '/<meta\b[^>]*>/i',
        '/onerror\s*=/i',
        '/onclick\s*=/i',
        '/onload\s*=/i',
        '/onmouseover\s*=/i',
        '/onfocus\s*=/i',
        '/javascript\s*:/i',
        '/vbscript\s*:/i',
        '/data\s*:\s*text\/html/i',
        '/expression\s*\(/i',
        '/document\.cookie/i',
        '/document\.location/i',
        '/window\.location/i',
        '/String\.fromCharCode/i',
        '/eval\s*\(/i',
        '/<[^\s>]*\s+style\s*=/i',
    ];

    protected array $pathTraversalPatterns = [
        '/\.\.\//',
        '/\.\.\\\\/',
        '/\.\.\%2f/i',
        '/\.\.\%5c/i',
        '/\%2e\%2e/i',
        '/\%252e\%252e/i',
        '/\.\.\\x00/',
    ];

    protected array $nullBytePatterns = [
        '/%00/',
        '/\\\x00/',
        '/\\\\0/',
    ];

    protected array $botPatterns = [
        'Googlebot', 'Bingbot', 'Slurp', 'DuckDuckBot', 'Baiduspider',
        'YandexBot', 'Sogou', 'Exabot', 'facebot', 'facebookexternalhit',
        'Twitterbot', 'LinkedInBot', 'WhatsApp', 'TelegramBot',
        'Discordbot', 'Slackbot', 'SkypeUriPreview', 'Pinterest',
        'SemrushBot', 'AhrefsBot', 'MJ12bot', 'DotBot',
        'rogerbot', 'trendictionbot', 'Yeti', 'YandexImages',
        'CloudFlare-AlwaysOnline', 'Google-PageSpeed',
        'Google-Structured-Data-Testing-Tool', 'W3C_Validator',
        'Nutch', 'GrapeshotCrawler', 'SeznamBot',
        'BLEXBot', 'archive.org_bot', 'TurnitinBot',
    ];

    public function isSuspiciousRequest(Request $request): bool
    {
        $inputs = $request->all();
        if ($this->containsSuspiciousContent($inputs)) {
            return true;
        }

        $queryString = $request->getQueryString() ?? '';
        if ($this->matchesPatterns($queryString, $this->sqlPatterns) ||
            $this->matchesPatterns($queryString, $this->xssPatterns) ||
            $this->matchesPatterns($queryString, $this->pathTraversalPatterns) ||
            $this->matchesPatterns($queryString, $this->nullBytePatterns)) {
            return true;
        }

        $uri = $request->getRequestUri();
        if ($this->matchesPatterns($uri, $this->pathTraversalPatterns) ||
            $this->matchesPatterns($uri, $this->nullBytePatterns)) {
            return true;
        }

        if ($this->isNullByteInjected($request)) {
            return true;
        }

        return false;
    }

    public function sanitizeInput(array $input, array $rules = []): array
    {
        $sanitized = [];

        foreach ($input as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = $this->sanitizeInput($value, $rules[$key] ?? []);
            } elseif (is_string($value)) {
                $sanitized[$key] = $this->sanitizeString($value, $rules[$key] ?? '');
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }

    public function detectBot(Request $request): bool
    {
        $userAgent = $request->userAgent();

        if (empty($userAgent)) {
            return false;
        }

        foreach ($this->botPatterns as $pattern) {
            if (stripos($userAgent, $pattern) !== false) {
                return true;
            }
        }

        return false;
    }

    public function isHoneypotFilled(Request $request, string $field = '_website'): bool
    {
        $value = $request->input($field);

        if (is_null($value)) {
            return false;
        }

        if (is_string($value) && trim($value) !== '') {
            return true;
        }

        return false;
    }

    public function blockIp(string $ip, int $minutes = 60): void
    {
        $expiresAt = now()->addMinutes($minutes);
        Cache::put('blocked_ip:' . $ip, true, $expiresAt);
    }

    public function isIpBlocked(string $ip): bool
    {
        return Cache::has('blocked_ip:' . $ip);
    }

    public function getClientIp(Request $request): string
    {
        $ip = $request->header('HTTP_X_FORWARDED_FOR');
        if ($ip) {
            $ips = explode(',', $ip);
            $ip = trim($ips[0]);
        }

        if ($ip = $request->header('X-Forwarded-For')) {
            $ips = explode(',', $ip);
            $ip = trim($ips[0]);
        } elseif ($ip = $request->header('HTTP_X_REAL_IP')) {
            $ip = trim($ip);
        } elseif ($ip = $request->header('X-Real-IP')) {
            $ip = trim($ip);
        } elseif ($ip = $request->header('HTTP_CLIENT_IP')) {
            $ip = trim($ip);
        } elseif ($request->header('CF-Connecting-IP')) {
            $ip = $request->header('CF-Connecting-IP');
        } else {
            $ip = $request->ip();
        }

        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            $ip = $request->ip();
        }

        return $ip;
    }

    public function checkRateLimit(string $key, int $maxAttempts = 5, int $decayMinutes = 1): array
    {
        $cacheKey = 'rate_limit:' . $key;
        $attempts = (int) Cache::get($cacheKey, 0);

        if ($attempts >= $maxAttempts) {
            $ttl = Cache::get($cacheKey . ':ttl', 0);
            $retryAfter = max(0, $ttl - time());

            return [
                'allowed' => false,
                'remaining' => 0,
                'retryAfter' => $retryAfter > 0 ? $retryAfter : $decayMinutes * 60,
            ];
        }

        if ($attempts === 0) {
            Cache::put($cacheKey, 1, now()->addMinutes($decayMinutes));
            Cache::put($cacheKey . ':ttl', now()->addMinutes($decayMinutes)->timestamp, now()->addMinutes($decayMinutes));
        } else {
            Cache::increment($cacheKey);
        }

        $remaining = $maxAttempts - ($attempts + 1);

        return [
            'allowed' => true,
            'remaining' => max(0, $remaining),
            'retryAfter' => null,
        ];
    }

    protected function containsSuspiciousContent(array $data): bool
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if ($this->containsSuspiciousContent($value)) {
                    return true;
                }
            } elseif (is_string($value)) {
                if ($this->matchesPatterns($value, $this->sqlPatterns) ||
                    $this->matchesPatterns($value, $this->xssPatterns) ||
                    $this->matchesPatterns($value, $this->pathTraversalPatterns) ||
                    $this->matchesPatterns($value, $this->nullBytePatterns)) {
                    return true;
                }
            }
        }

        return false;
    }

    protected function matchesPatterns(string $value, array $patterns): bool
    {
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $value)) {
                return true;
            }
        }

        return false;
    }

    protected function isNullByteInjected(Request $request): bool
    {
        $content = file_get_contents('php://input');
        if ($content !== false && strpos($content, "\x00") !== false) {
            return true;
        }

        foreach ($request->all() as $value) {
            if (is_string($value) && strpos($value, "\x00") !== false) {
                return true;
            }
        }

        return false;
    }

    protected function sanitizeString(string $value, string $rule = ''): string
    {
        $value = strip_tags($value);

        $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);

        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value);

        $value = trim($value);

        return $value;
    }
}
