<?php

namespace App\Http\Controllers;

use App\Models\TransparencyDocument;
use Illuminate\Http\Request;

class PublicTransparencyController extends Controller
{
    public function index()
    {
        $documents = TransparencyDocument::where('active', true)
            ->orderBy('year', 'desc')
            ->orderBy('published_at', 'desc')
            ->get()
            ->groupBy(['year', 'category']);

        $cmsData = $this->loadCmsPage('transparencia');
        return view('transparency.index', array_merge(compact('documents'), $cmsData));
    }
}
