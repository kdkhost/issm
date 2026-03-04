<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Setting;

class PublicPageController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::active()->where('slug', $slug)->firstOrFail();
        $settings = ['site_name' => Setting::get('site_name', 'ISSM')];
        return view('pages.show', compact('page', 'settings'));
    }
}
