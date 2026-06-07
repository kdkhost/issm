<?php

namespace App\Http\Controllers;

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
            'about_ods_title' => Setting::get('about_ods_title', 'ODS 2030'),
            'about_ods_description' => Setting::get('about_ods_description', ''),
            'about_ods_button_text' => Setting::get('about_ods_button_text', 'Ver nossos ODS'),
        ];
        
        $teamMembers = TeamMember::active()->orderBy('order')->get();
        
        $cmsData = $this->loadCmsPage('sobre');
        $cms = $this->extractCmsContent($cmsData['cmsSections']);
        
        return view('about.index', array_merge(compact('settings', 'teamMembers'), $cmsData, compact('cms')));
    }
}
