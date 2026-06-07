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

        if ($cmsData['cmsPage']) {
            return view('public.cms.page', array_merge(
                ['page' => $cmsData['cmsPage'], 'sections' => $cmsData['cmsSections']],
                compact('documents'), $cmsData
            ));
        }

        return view('transparency.index', array_merge(compact('documents'), $cmsData));
    }
}
