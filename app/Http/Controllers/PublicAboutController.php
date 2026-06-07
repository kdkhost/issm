<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\TeamMember;

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

        $cmsData = $this->loadCmsPage('sobre');

        if ($cmsData['cmsPage']) {
            return view('public.cms.page', array_merge(
                [
                    'page' => $cmsData['cmsPage'],
                    'sections' => $cmsData['cmsSections'],
                ],
                compact('settings', 'teamMembers'),
                $cmsData
            ));
        }

        return view('about.index', array_merge(
            compact('settings', 'teamMembers'), $cmsData
        ));
    }
}
