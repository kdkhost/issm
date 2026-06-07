<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Contact;
use App\Models\Gallery;
use App\Models\News;
use App\Models\Partner;
use App\Models\Project;
use App\Models\Setting;
use App\Models\TeamMember;
use Illuminate\Http\Request;

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
            'gallery' => Gallery::count(),
        ];

        $recentContacts = Contact::latest()->take(5)->get();
        $recentNews = News::latest()->take(5)->get();
        $maintenanceMode = Setting::get('maintenance_mode', '0');

        return view('admin.dashboard', compact('stats', 'recentContacts', 'recentNews', 'maintenanceMode'));
    }
}
