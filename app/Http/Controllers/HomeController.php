<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Gallery;
use App\Models\News;
use App\Models\Ods;
use App\Models\Partner;
use App\Models\Project;
use App\Models\Setting;
use App\Models\TeamMember;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Setting::get('show_banners', '1') ? Banner::active()->get() : collect();
        $featuredProjects = Setting::get('show_projects', '1') ? Project::active()->featured()->take(3)->get() : collect();
        $latestNews = Setting::get('show_news', '1') ? News::active()->latest('published_at')->take(3)->get() : collect();
        $odsList = Setting::get('show_ods', '1') ? Ods::active()->get() : collect();
        $teamMembers = Setting::get('show_team', '1') ? TeamMember::active()->take(8)->get() : collect();
        $partners = Setting::get('show_partners', '1') ? Partner::active()->get() : collect();
        $galleryItems = Setting::get('show_gallery', '1') ? Gallery::active()->take(8)->get() : collect();

        $settings = [
            'site_name' => Setting::get('site_name', 'ISSM'),
            'about_text' => Setting::get('about_text', ''),
            'mission' => Setting::get('mission', ''),
            'vision' => Setting::get('vision', ''),
            'values' => Setting::get('values', ''),
            'contact_email' => Setting::get('contact_email', ''),
            'contact_phone' => Setting::get('contact_phone', ''),
            'contact_address' => Setting::get('contact_address', ''),
            'contact_map_embed' => Setting::get('contact_map_embed', ''),
            'social_facebook' => Setting::get('social_facebook', ''),
            'social_instagram' => Setting::get('social_instagram', ''),
            'social_youtube' => Setting::get('social_youtube', ''),
            'social_whatsapp' => Setting::get('social_whatsapp', ''),
            'show_contact' => Setting::get('show_contact', '1'),
            'show_about' => Setting::get('show_about', '1'),
        ];

        return view('home', compact(
            'banners', 'featuredProjects', 'latestNews', 'odsList',
            'teamMembers', 'partners', 'galleryItems', 'settings'
        ));
    }
}
