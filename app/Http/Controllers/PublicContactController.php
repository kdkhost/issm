<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class PublicContactController extends Controller
{
    public function index()
    {
        $settings = [
            'contact_email' => Setting::get('contact_email', ''),
            'contact_phone' => Setting::get('contact_phone', ''),
            'contact_address' => Setting::get('contact_address', ''),
            'contact_map_embed' => Setting::get('contact_map_embed', ''),
            'social_facebook' => Setting::get('social_facebook', ''),
            'social_instagram' => Setting::get('social_instagram', ''),
            'social_youtube' => Setting::get('social_youtube', ''),
            'social_whatsapp' => Setting::get('social_whatsapp', ''),
            'social_linkedin' => Setting::get('social_linkedin', ''),
            'social_twitter' => Setting::get('social_twitter', ''),
        ];

        $cmsData = $this->loadCmsPage('contato');
        return view('contact.index', array_merge(compact('settings'), $cmsData));
    }
}
