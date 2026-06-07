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
        return view('ods.index', array_merge(compact('odsList'), $cmsData));
    }
}
