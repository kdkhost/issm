<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\TeamMember;

class PublicAboutController extends Controller
{
    public function index()
    {
        $cmsData = $this->loadCmsPage('sobre');

        if ($cmsData['cmsPage'] && $cmsData['cmsSections']->isNotEmpty()) {
            return view('public.cms.page', [
                'page' => $cmsData['cmsPage'],
                'sections' => $cmsData['cmsSections'],
            ]);
        }

        $settings = [
            'about_text' => Setting::get('about_text', ''),
            'mission' => Setting::get('mission', ''),
            'vision' => Setting::get('vision', ''),
            'values' => Setting::get('values', ''),
        ];
        
        $teamMembers = TeamMember::active()->orderBy('order')->get();

        return view('about.index', array_merge(
            compact('settings', 'teamMembers'), $cmsData
        ));
    }
}
