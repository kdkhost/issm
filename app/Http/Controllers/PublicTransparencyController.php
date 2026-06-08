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

        return view('transparency.index', compact('documents'));
    }
}
