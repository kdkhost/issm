<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Visit extends Model
{
    protected $fillable = [
        'ip_address',
        'user_agent',
        'url',
        'path',
        'referrer',
        'referrer_domain',
        'source',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'device_type',
        'browser',
        'os',
        'country',
        'city',
        'session_id',
        'is_bot',
    ];

    protected $casts = [
        'is_bot' => 'boolean',
    ];

    /* ── Scopes ── */

    public function scopeToday(Builder $q): Builder
    {
        return $q->whereDate('created_at', today());
    }

    public function scopeThisWeek(Builder $q): Builder
    {
        return $q->where('created_at', '>=', now()->startOfWeek());
    }

    public function scopeThisMonth(Builder $q): Builder
    {
        return $q->where('created_at', '>=', now()->startOfMonth());
    }

    public function scopeNotBot(Builder $q): Builder
    {
        return $q->where('is_bot', false);
    }

    public function scopeFromSource(Builder $q, string $source): Builder
    {
        return $q->where('source', $source);
    }

    public function scopeHumans(Builder $q): Builder
    {
        return $q->where('is_bot', false);
    }

    /* ── Helpers ── */

    /**
     * Detect the traffic source from referrer URL or UTM params
     */
    public static function detectSource(?string $referrer, ?string $utmSource): string
    {
        if ($utmSource) {
            $utmLower = strtolower($utmSource);
            $map = [
                'instagram' => 'instagram',
                'whatsapp'  => 'whatsapp',
                'telegram'  => 'telegram',
                'facebook'  => 'facebook',
                'fb'        => 'facebook',
                'google'    => 'google',
                'twitter'   => 'twitter',
                'x.com'     => 'twitter',
                'linkedin'  => 'linkedin',
                'youtube'   => 'youtube',
                'tiktok'    => 'tiktok',
                'email'     => 'email',
                'newsletter'=> 'email',
            ];
            foreach ($map as $key => $source) {
                if (str_contains($utmLower, $key)) return $source;
            }
        }

        if (!$referrer) return 'direct';

        $host = strtolower(parse_url($referrer, PHP_URL_HOST) ?? '');
        $host = preg_replace('/^(www|m|l|lm)\./', '', $host);

        $domainMap = [
            'instagram.com'  => 'instagram',
            'l.instagram.com'=> 'instagram',
            'whatsapp.com'   => 'whatsapp',
            'web.whatsapp.com'=> 'whatsapp',
            'wa.me'          => 'whatsapp',
            't.me'           => 'telegram',
            'telegram.org'   => 'telegram',
            'telegram.me'    => 'telegram',
            'facebook.com'   => 'facebook',
            'fb.com'         => 'facebook',
            'fb.me'          => 'facebook',
            'google.com'     => 'google',
            'google.com.br'  => 'google',
            'google.co'      => 'google',
            'twitter.com'    => 'twitter',
            'x.com'          => 'twitter',
            't.co'           => 'twitter',
            'linkedin.com'   => 'linkedin',
            'youtube.com'    => 'youtube',
            'youtu.be'       => 'youtube',
            'tiktok.com'     => 'tiktok',
            'pinterest.com'  => 'pinterest',
            'reddit.com'     => 'reddit',
            'bing.com'       => 'bing',
            'yahoo.com'      => 'yahoo',
            'duckduckgo.com' => 'duckduckgo',
            'baidu.com'      => 'baidu',
        ];

        foreach ($domainMap as $domain => $source) {
            if ($host === $domain || str_ends_with($host, '.'.$domain)) {
                return $source;
            }
        }

        return 'other';
    }

    /**
     * Detect device type from user agent
     */
    public static function detectDevice(?string $ua): string
    {
        if (!$ua) return 'desktop';
        $ua = strtolower($ua);

        if (preg_match('/tablet|ipad|playbook|silk|(android(?!.*mobi))/i', $ua)) return 'tablet';
        if (preg_match('/mobile|iphone|ipod|android.*mobi|opera mini|iemobile|wpdesktop|windows phone/i', $ua)) return 'mobile';
        return 'desktop';
    }

    /**
     * Detect browser from user agent
     */
    public static function detectBrowser(?string $ua): string
    {
        if (!$ua) return 'Unknown';

        if (str_contains($ua, 'Edg/'))    return 'Edge';
        if (str_contains($ua, 'OPR/') || str_contains($ua, 'Opera')) return 'Opera';
        if (str_contains($ua, 'Chrome/')) return 'Chrome';
        if (str_contains($ua, 'Firefox/')) return 'Firefox';
        if (str_contains($ua, 'Safari/') && !str_contains($ua, 'Chrome')) return 'Safari';
        if (str_contains($ua, 'MSIE') || str_contains($ua, 'Trident/')) return 'IE';

        return 'Other';
    }

    /**
     * Detect OS from user agent
     */
    public static function detectOS(?string $ua): string
    {
        if (!$ua) return 'Unknown';

        if (str_contains($ua, 'Windows'))    return 'Windows';
        if (str_contains($ua, 'Macintosh') || str_contains($ua, 'Mac OS')) return 'macOS';
        if (str_contains($ua, 'Linux') && !str_contains($ua, 'Android')) return 'Linux';
        if (str_contains($ua, 'Android'))    return 'Android';
        if (preg_match('/iPhone|iPad|iPod/', $ua)) return 'iOS';
        if (str_contains($ua, 'CrOS'))       return 'Chrome OS';

        return 'Other';
    }

    /**
     * Check if user agent is a bot
     */
    public static function isBot(?string $ua): bool
    {
        if (!$ua) return false;
        return (bool) preg_match('/bot|crawl|spider|slurp|bingpreview|mediapartners|facebookexternalhit|twitterbot|linkedinbot|telegrambot|whatsapp|semrush|ahrefs|mj12bot|dotbot|rogerbot|yandex|baidu|duckduckbot|ia_archiver|pingdom|uptimerobot|gtmetrix|google-inspectiontool|lighthouse/i', $ua);
    }
}
