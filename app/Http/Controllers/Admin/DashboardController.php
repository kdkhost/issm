<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Contact;
use App\Models\GalleryPhoto;
use App\Models\News;
use App\Models\Partner;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Visit;
use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'news' => News::count(),
            'projects' => Project::count(),
            'contacts' => Contact::where('status', 'new')->count(),
            'banners' => Banner::count(),
            'team' => TeamMember::count(),
            'partners' => Partner::count(),
            'gallery' => GalleryPhoto::count(),
        ];

        $kpis = [
            $this->makeKpi(
                'Notícias',
                $stats['news'],
                News::query(),
                route('admin.noticias.index'),
                'Gerenciar notícias',
                '#16a34a',
                'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'
            ),
            $this->makeKpi(
                'Projetos',
                $stats['projects'],
                Project::query(),
                route('admin.projetos.index'),
                'Gerenciar projetos',
                '#2563eb',
                'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'
            ),
            $this->makeKpi(
                'Mensagens novas',
                $stats['contacts'],
                Contact::query()->where('status', 'new'),
                route('admin.contatos.index'),
                'Ver mensagens',
                '#dc2626',
                'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'
            ),
            $this->makeKpi(
                'Fotos na galeria',
                $stats['gallery'],
                GalleryPhoto::query(),
                route('admin.galeria.index'),
                'Organizar galeria',
                '#ca8a04',
                'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'
            ),
        ];

        $todayVisits = Visit::notBot()->today()->count();
        $todayUniqueVisitors = Visit::notBot()->today()->distinct('ip_address')->count('ip_address');
        $visitSeries = $this->dailySeries(Visit::notBot(), 7);
        $contentMix = [
            ['label' => 'Notícias', 'value' => $stats['news'], 'color' => '#16a34a'],
            ['label' => 'Projetos', 'value' => $stats['projects'], 'color' => '#2563eb'],
            ['label' => 'Fotos', 'value' => $stats['gallery'], 'color' => '#ca8a04'],
            ['label' => 'Parceiros', 'value' => $stats['partners'], 'color' => '#7c3aed'],
            ['label' => 'Equipe', 'value' => $stats['team'], 'color' => '#db2777'],
        ];

        $recentContacts = Contact::latest()->take(5)->get();
        $recentNews = News::latest()->take(5)->get();
        $maintenanceMode = Setting::get('maintenance_mode', '0');

        return view('admin.dashboard', compact(
            'stats',
            'kpis',
            'todayVisits',
            'todayUniqueVisitors',
            'visitSeries',
            'contentMix',
            'recentContacts',
            'recentNews',
            'maintenanceMode'
        ));
    }

    private function makeKpi(string $label, int $value, Builder $query, string $url, string $action, string $color, string $icon): array
    {
        $series = $this->dailySeries($query, 7);
        $today = $series[count($series) - 1]['value'] ?? 0;
        $yesterday = $series[count($series) - 2]['value'] ?? 0;
        $trend = $yesterday > 0
            ? round((($today - $yesterday) / $yesterday) * 100)
            : ($today > 0 ? 100 : 0);

        return [
            'label' => $label,
            'value' => $value,
            'today' => $today,
            'trend' => $trend,
            'series' => $series,
            'url' => $url,
            'action' => $action,
            'color' => $color,
            'icon' => $icon,
        ];
    }

    private function dailySeries(Builder $query, int $days): array
    {
        $start = now()->subDays($days - 1)->startOfDay();
        $rows = (clone $query)
            ->where('created_at', '>=', $start)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $series = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $key = $date->format('Y-m-d');
            $series[] = [
                'date' => $date->format('d/m'),
                'value' => (int) ($rows[$key] ?? 0),
            ];
        }

        return $series;
    }
}
