<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Setting;

class PublicNewsController extends Controller
{
    public function index()
    {
        $news = News::active()->latest('published_at')->paginate(9);
        $featured = News::active()->featured()->latest('published_at')->take(3)->get();
        $latestNews = $featured;
        $settings = ['site_name' => Setting::get('site_name', 'ISSM')];
        $cmsData = $this->loadCmsPage('noticias');
        $cms = $this->extractCmsContent($cmsData['cmsSections']);
        return view('news.index', array_merge(compact('news', 'featured', 'latestNews', 'settings'), $cmsData, compact('cms')));
    }

    public function show(string $slug)
    {
        $item = News::active()->where('slug', $slug)->firstOrFail();
        $related = News::active()->where('id', '!=', $item->id)->latest('published_at')->take(3)->get();
        $settings = ['site_name' => Setting::get('site_name', 'ISSM')];
        return view('news.show', compact('item', 'related', 'settings'));
    }
}
