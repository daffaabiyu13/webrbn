<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\HeroStyle;
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
        'projects' => 'Projects',
        'about' => 'About',
    ];

    public function edit()
    {
        $heroes = [];
        foreach (self::HERO_PAGES as $key => $label) {
            $style = HeroStyle::forPage($key);
            $heroes[$key] = [
                'key' => $key,
                'label' => $label,
                'value' => Setting::get("hero_background_{$key}"),
                'overlay_color' => $style['overlay_color'],
                'overlay_opacity' => $style['overlay_opacity'],
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
        $colorKey = "hero_overlay_color_{$page}";
        $opacityKey = "hero_overlay_opacity_{$page}";
        $pageLabel = self::HERO_PAGES[$page];

        $data = $request->validate([
            'hero_background' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:' . $limit->maxKb()],
            'remove_hero_background' => ['sometimes', 'boolean'],
            'overlay_color' => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'overlay_opacity' => ['nullable', 'integer', 'between:0,100'],
        ], [
            'hero_background.uploaded' => "Gambar gagal diunggah — kemungkinan besar file lebih besar dari batas server (max {$human}, upload_max_filesize={$limit->phpUpload()}, post_max_size={$limit->phpPost()}). Kompres gambarnya atau naikkan limit di php.ini.",
            'hero_background.max' => "Ukuran gambar terlalu besar. Maksimal {$human}.",
            'overlay_color.regex' => 'Warna overlay harus dalam format hex (contoh: #416423).',
        ]);

        // Overlay color + opacity always saved on submit (independent of image)
        if (array_key_exists('overlay_color', $data) && $data['overlay_color'] !== null) {
            Setting::set($colorKey, HeroStyle::normalizeHex($data['overlay_color']));
        }
        if (array_key_exists('overlay_opacity', $data) && $data['overlay_opacity'] !== null) {
            Setting::set($opacityKey, (string) HeroStyle::clampOpacity((int) $data['overlay_opacity']));
        }

        // Image handling (upload / remove / no-op)
        $current = Setting::get($settingKey);

        if ($request->boolean('remove_hero_background')) {
            if ($current && ! str_starts_with($current, 'http')) {
                Storage::disk('public')->delete($current);
            }
            Setting::set($settingKey, null);

            return back()->with('status', "Hero {$pageLabel}: gambar dihapus, overlay tersimpan.");
        }

        if ($request->hasFile('hero_background')) {
            if ($current && ! str_starts_with($current, 'http')) {
                Storage::disk('public')->delete($current);
            }
            $path = ImageCompressor::forHeroBackground()
                ->storeCompressed($request->file('hero_background'), 'public', 'settings');
            Setting::set($settingKey, $path);

            return back()->with('status', "Hero {$pageLabel} diperbarui (gambar dikompres, overlay tersimpan).");
        }

        return back()->with('status', "Hero {$pageLabel}: overlay tersimpan.");
    }
}
