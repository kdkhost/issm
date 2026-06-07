<?php

namespace App\Http\Controllers;

use App\Models\Ods;
use Illuminate\Http\Request;

class PublicOdsController extends Controller
{
    public function index()
    {
        $odsList = Ods::active()->orderBy('number')->get();
        $cmsData = $this->loadCmsPage('ods');

        if ($cmsData['cmsPage']) {
            return view('public.cms.page', array_merge(
                ['page' => $cmsData['cmsPage'], 'sections' => $cmsData['cmsSections']],
                compact('odsList'), $cmsData
            ));
        }

        return view('ods.index', array_merge(compact('odsList'), $cmsData));
    }
}
