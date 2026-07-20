<?php

namespace App\Http\Controllers;

use App\Support\HeroStyle;

class AboutController extends Controller
{
    public function index()
    {
        $hero = HeroStyle::forPage('about');

        return view('pages.about', compact('hero'));
    }
}
