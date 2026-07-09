<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlaceholderController extends Controller
{
    public function svg(Request $request)
    {
        $label = trim((string) $request->query('name', 'Product'));
        if ($label === '') {
            $label = 'Product';
        }
        if (mb_strlen($label) > 22) {
            $label = mb_substr($label, 0, 20) . '..';
        }
        $label = htmlspecialchars($label, ENT_QUOTES | ENT_XML1, 'UTF-8');

        $svg = '<?xml version="1.0" encoding="UTF-8"?>'
            . '<svg xmlns="http://www.w3.org/2000/svg" width="600" height="400" viewBox="0 0 600 400" preserveAspectRatio="xMidYMid slice">'
            . '<defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1">'
            . '<stop offset="0%" stop-color="#2D6A27"/>'
            . '<stop offset="100%" stop-color="#1e4d1b"/>'
            . '</linearGradient></defs>'
            . '<rect width="600" height="400" fill="url(#g)"/>'
            . '<circle cx="500" cy="80" r="140" fill="#6DBE45" fill-opacity="0.18"/>'
            . '<circle cx="90" cy="340" r="110" fill="#6DBE45" fill-opacity="0.12"/>'
            . '<text x="300" y="205" font-family="Helvetica,Arial,sans-serif" font-size="46" font-weight="700" fill="#ffffff" text-anchor="middle">' . $label . '</text>'
            . '<text x="300" y="245" font-family="Helvetica,Arial,sans-serif" font-size="16" font-weight="500" fill="#ffffff" fill-opacity="0.7" letter-spacing="2" text-anchor="middle">PT. RADIKA BINTANG NUSANTARA</text>'
            . '</svg>';

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
