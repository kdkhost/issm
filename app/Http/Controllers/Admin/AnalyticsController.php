<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', '30');
        $startDate = match ($period) {
            '7'   => now()->subDays(7),
            '30'  => now()->subDays(30),
            '90'  => now()->subDays(90),
            '365' => now()->subDays(365),
            'today' => now()->startOfDay(),
            default => now()->subDays(30),
        };

        $base = Visit::notBot()->where('created_at', '>=', $startDate);

        // ─── Summary cards ───
        $totalVisits   = (clone $base)->count();
        $uniqueVisitors = (clone $base)->distinct('ip_address')->count('ip_address');
        $todayVisits   = Visit::notBot()->today()->count();
        $todayUnique   = Visit::notBot()->today()->distinct('ip_address')->count('ip_address');

        // ─── Visits per day chart ───
        $visitsPerDay = (clone $base)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        // Fill gaps
        $filledDays = [];
        $cursor = $startDate->copy()->startOfDay();
        $end    = now()->endOfDay();
        while ($cursor <= $end) {
            $key = $cursor->format('Y-m-d');
            $filledDays[$key] = $visitsPerDay[$key] ?? 0;
            $cursor->addDay();
        }

        // ─── Top Sources ───
        $topSources = (clone $base)
            ->select('source', DB::raw('COUNT(*) as total'))
            ->groupBy('source')
            ->orderByDesc('total')
            ->limit(12)
            ->get();

        // ─── Top Pages ───
        $topPages = (clone $base)
            ->select('path', DB::raw('COUNT(*) as total'))
            ->groupBy('path')
            ->orderByDesc('total')
            ->limit(15)
            ->get();

        // ─── Devices ───
        $devices = (clone $base)
            ->select('device_type', DB::raw('COUNT(*) as total'))
            ->groupBy('device_type')
            ->orderByDesc('total')
            ->get();

        // ─── Browsers ───
        $browsers = (clone $base)
            ->select('browser', DB::raw('COUNT(*) as total'))
            ->groupBy('browser')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        // ─── OS ───
        $osList = (clone $base)
            ->select('os', DB::raw('COUNT(*) as total'))
            ->groupBy('os')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        // ─── Top Referrers (domínio) ───
        $topReferrers = (clone $base)
            ->whereNotNull('referrer_domain')
            ->select('referrer_domain', DB::raw('COUNT(*) as total'))
            ->groupBy('referrer_domain')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // ─── Últimas visitas ───
        $recentVisits = Visit::notBot()
            ->latest()
            ->limit(30)
            ->get();

        // ─── Bots count ───
        $botsCount = Visit::where('is_bot', true)
            ->where('created_at', '>=', $startDate)
            ->count();

        // ─── Source labels/colors for UI ───
        $sourceLabels = [
            'direct'    => ['label' => 'Acesso Direto',  'color' => '#6b7280', 'icon' => '🌐'],
            'google'    => ['label' => 'Google',          'color' => '#4285f4', 'icon' => '🔍'],
            'instagram' => ['label' => 'Instagram',       'color' => '#e1306c', 'icon' => '📸'],
            'facebook'  => ['label' => 'Facebook',        'color' => '#1877f2', 'icon' => '👤'],
            'whatsapp'  => ['label' => 'WhatsApp',        'color' => '#25d366', 'icon' => '💬'],
            'telegram'  => ['label' => 'Telegram',        'color' => '#0088cc', 'icon' => '✈️'],
            'twitter'   => ['label' => 'Twitter / X',     'color' => '#1da1f2', 'icon' => '🐦'],
            'linkedin'  => ['label' => 'LinkedIn',        'color' => '#0a66c2', 'icon' => '💼'],
            'youtube'   => ['label' => 'YouTube',         'color' => '#ff0000', 'icon' => '▶️'],
            'tiktok'    => ['label' => 'TikTok',          'color' => '#000000', 'icon' => '🎵'],
            'pinterest' => ['label' => 'Pinterest',       'color' => '#e60023', 'icon' => '📌'],
            'reddit'    => ['label' => 'Reddit',          'color' => '#ff4500', 'icon' => '🤖'],
            'bing'      => ['label' => 'Bing',            'color' => '#008373', 'icon' => '🔎'],
            'yahoo'     => ['label' => 'Yahoo',           'color' => '#6001d2', 'icon' => '🔎'],
            'email'     => ['label' => 'E-mail',          'color' => '#ea4335', 'icon' => '📧'],
            'other'     => ['label' => 'Outro',           'color' => '#9ca3af', 'icon' => '🔗'],
        ];

        return view('admin.analytics.index', compact(
            'period',
            'totalVisits',
            'uniqueVisitors',
            'todayVisits',
            'todayUnique',
            'filledDays',
            'topSources',
            'topPages',
            'devices',
            'browsers',
            'osList',
            'topReferrers',
            'recentVisits',
            'botsCount',
            'sourceLabels',
        ));
    }
}
