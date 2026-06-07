<?php

namespace App\Http\Controllers;

use App\Models\Ods;
use Illuminate\Http\Request;

class PublicOdsController extends Controller
{
    public function index()
    {
        $odsList = Ods::active()->orderBy('number')->get();
        return view('ods.index', compact('odsList'));
    }
}
