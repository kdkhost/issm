<?php

namespace App\Http\Controllers;

use App\Models\Ods;
use Illuminate\Http\Request;

class PublicOdsController extends Controller
{
    public function index()
    {
        $cmsData = $this->loadCmsPage('ods');

        if ($cmsData['cmsPage'] && $cmsData['cmsSections']->isNotEmpty()) {
            return view('public.cms.page', [
                'page' => $cmsData['cmsPage'],
                'sections' => $cmsData['cmsSections'],
            ]);
        }

        $odsList = Ods::active()->orderBy('number')->get();
        return view('ods.index', array_merge(compact('odsList'), $cmsData));
    }
}
