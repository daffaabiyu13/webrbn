<?php

namespace App\Http\Controllers;

use App\Models\Setting;

class AboutController extends Controller
{
    public function index()
    {
        $heroBackground = Setting::get('hero_background_about');
        if ($heroBackground && ! str_starts_with($heroBackground, 'http')) {
            $heroBackground = asset('storage/' . $heroBackground);
        }

        return view('pages.about', compact('heroBackground'));
    }
}
