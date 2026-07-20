<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\ImageCompressor;
use App\Support\UploadLimit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SettingController extends Controller
{
    private const HERO_PAGES = [
        'home' => 'Home',
        'catalog' => 'Catalog',
        'about' => 'About',
    ];

    public function edit()
    {
        $heroes = [];
        foreach (self::HERO_PAGES as $key => $label) {
            $heroes[$key] = [
                'key' => $key,
                'label' => $label,
                'value' => Setting::get("hero_background_{$key}"),
            ];
        }

        return view('admin.settings.edit', compact('heroes'));
    }

    public function updateHero(Request $request, string $page)
    {
        if (! isset(self::HERO_PAGES[$page])) {
            throw new NotFoundHttpException();
        }

        $limit = UploadLimit::forHeroBackground();
        $human = $limit->human();
        $settingKey = "hero_background_{$page}";
        $pageLabel = self::HERO_PAGES[$page];

        $request->validate([
            'hero_background' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:' . $limit->maxKb()],
            'remove_hero_background' => ['sometimes', 'boolean'],
        ], [
            'hero_background.uploaded' => "Gambar gagal diunggah — kemungkinan besar file lebih besar dari batas server (max {$human}, upload_max_filesize={$limit->phpUpload()}, post_max_size={$limit->phpPost()}). Kompres gambarnya atau naikkan limit di php.ini.",
            'hero_background.max' => "Ukuran gambar terlalu besar. Maksimal {$human}.",
        ]);

        $current = Setting::get($settingKey);

        if ($request->boolean('remove_hero_background')) {
            if ($current && ! str_starts_with($current, 'http')) {
                Storage::disk('public')->delete($current);
            }
            Setting::set($settingKey, null);

            return back()->with('status', "Hero background {$pageLabel} dihapus.");
        }

        if ($request->hasFile('hero_background')) {
            if ($current && ! str_starts_with($current, 'http')) {
                Storage::disk('public')->delete($current);
            }
            $path = ImageCompressor::forHeroBackground()
                ->storeCompressed($request->file('hero_background'), 'public', 'settings');
            Setting::set($settingKey, $path);

            return back()->with('status', "Hero background {$pageLabel} berhasil diperbarui (otomatis dikompres).");
        }

        return back()->with('status', 'Tidak ada perubahan disimpan.');
    }
}
