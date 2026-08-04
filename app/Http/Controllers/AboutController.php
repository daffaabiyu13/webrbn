<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Support\HeroStyle;

class AboutController extends Controller
{
    public function index()
    {
        $hero = HeroStyle::forPage('about');

        $certificate = Setting::get('certificate_image');
        if ($certificate && ! str_starts_with($certificate, 'http')) {
            $certificate = asset('storage/' . $certificate);
        }

        return view('pages.about', compact('hero', 'certificate'));
    }
}
