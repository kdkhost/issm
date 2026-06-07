<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use App\Models\CmsBlock;
use App\Models\Setting;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class PublicAboutController extends Controller
{
    public function index()
    {
        $settings = [
            'about_text' => Setting::get('about_text', ''),
            'mission' => Setting::get('mission', ''),
            'vision' => Setting::get('vision', ''),
            'values' => Setting::get('values', ''),
        ];
        
        $teamMembers = TeamMember::active()->orderBy('order')->get();

        $cmsBlocks = collect();
        $cmsPage = CmsPage::active()->published()->where('slug', 'sobre')->first();
        if ($cmsPage) {
            $cmsBlocks = CmsBlock::where('cms_page_id', $cmsPage->id)
                ->active()
                ->published()
                ->orderBy('sort_order')
                ->get();
        }
        
        return view('about.index', compact('settings', 'teamMembers', 'cmsBlocks'));
    }
}
