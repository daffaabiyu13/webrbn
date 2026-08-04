<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Support\HeroStyle;

class AboutController extends Controller
{
    public function index()
    {
        $hero = HeroStyle::forPage('about');

        $certImage = Setting::get('certificate_image');
        if ($certImage && ! str_starts_with($certImage, 'http')) {
            $certImage = asset('storage/' . $certImage);
        }

        $certificate = [
            'image' => $certImage,
            'valid_text' => Setting::get('cert_valid_text') ?: __('site.about.cert_valid'),
            'signed_text' => Setting::get('cert_signed_text') ?: __('site.about.cert_signed'),
        ];

        return view('pages.about', compact('hero', 'certificate'));
    }
}
