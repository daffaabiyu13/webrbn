<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\HeroStyle;
use App\Support\ImageCompressor;
use App\Support\UploadLimit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    private const HERO_PAGES = [
        'home' => 'Home',
        'catalog' => 'Catalog',
        'projects' => 'Projects',
        'articles' => 'Articles',
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

        $certificate = [
            'image' => Setting::get('certificate_image'),
            'valid_text' => Setting::get('cert_valid_text', ''),
            'signed_text' => Setting::get('cert_signed_text', ''),
        ];

        return view('admin.settings.edit', compact('heroes', 'certificate'));
    }

    /**
     * Save every hero + certificate change in a single request. Overlay color
     * and opacity are always written; images are (a) replaced when a new file
     * is uploaded, (b) removed when the "remove_image" flag is set, or (c) left
     * untouched. New uploads win over a remove flag.
     */
    public function saveAll(Request $request)
    {
        $limit = UploadLimit::forHeroBackground();
        $human = $limit->human();
        $maxKb = $limit->maxKb();

        $rules = [];
        $messages = [];

        foreach (self::HERO_PAGES as $key => $label) {
            $rules["heroes.{$key}.image"] = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', "max:{$maxKb}"];
            $rules["heroes.{$key}.remove_image"] = ['sometimes', 'boolean'];
            $rules["heroes.{$key}.overlay_color"] = ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'];
            $rules["heroes.{$key}.overlay_opacity"] = ['nullable', 'integer', 'between:0,100'];

            $messages["heroes.{$key}.image.uploaded"] = "Gambar hero {$label} gagal diunggah — kemungkinan file lebih besar dari batas server (max {$human}).";
            $messages["heroes.{$key}.image.max"] = "Gambar hero {$label} terlalu besar. Maksimal {$human}.";
            $messages["heroes.{$key}.overlay_color.regex"] = "Warna overlay {$label} harus format hex (contoh #416423).";
        }

        $rules['certificate.image'] = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', "max:{$maxKb}"];
        $rules['certificate.remove_image'] = ['sometimes', 'boolean'];
        $rules['certificate.valid_text'] = ['nullable', 'string', 'max:500'];
        $rules['certificate.signed_text'] = ['nullable', 'string', 'max:1000'];

        $messages['certificate.image.uploaded'] = "Gambar sertifikat gagal diunggah (max {$human}).";
        $messages['certificate.image.max'] = "Gambar sertifikat terlalu besar. Maksimal {$human}.";

        $data = $request->validate($rules, $messages);

        // Heroes
        foreach (self::HERO_PAGES as $key => $label) {
            $heroInput = $data['heroes'][$key] ?? [];

            if (($heroInput['overlay_color'] ?? null) !== null) {
                Setting::set("hero_overlay_color_{$key}", HeroStyle::normalizeHex($heroInput['overlay_color']));
            }
            if (($heroInput['overlay_opacity'] ?? null) !== null) {
                Setting::set("hero_overlay_opacity_{$key}", (string) HeroStyle::clampOpacity((int) $heroInput['overlay_opacity']));
            }

            $settingKey = "hero_background_{$key}";
            $current = Setting::get($settingKey);
            $file = $request->file("heroes.{$key}.image");
            $wantRemove = $request->boolean("heroes.{$key}.remove_image");

            if ($file) {
                if ($current && ! str_starts_with($current, 'http')) {
                    Storage::disk('public')->delete($current);
                }
                $path = ImageCompressor::forHeroBackground()
                    ->storeCompressed($file, 'public', 'settings');
                Setting::set($settingKey, $path);
            } elseif ($wantRemove && $current) {
                if (! str_starts_with($current, 'http')) {
                    Storage::disk('public')->delete($current);
                }
                Setting::set($settingKey, null);
            }
        }

        // Certificate
        $certInput = $data['certificate'] ?? [];
        Setting::set('cert_valid_text', trim((string) ($certInput['valid_text'] ?? '')));
        Setting::set('cert_signed_text', trim((string) ($certInput['signed_text'] ?? '')));

        $current = Setting::get('certificate_image');
        $file = $request->file('certificate.image');
        $wantRemove = $request->boolean('certificate.remove_image');

        if ($file) {
            if ($current && ! str_starts_with($current, 'http')) {
                Storage::disk('public')->delete($current);
            }
            $path = ImageCompressor::forHeroBackground()
                ->storeCompressed($file, 'public', 'settings');
            Setting::set('certificate_image', $path);
        } elseif ($wantRemove && $current) {
            if (! str_starts_with($current, 'http')) {
                Storage::disk('public')->delete($current);
            }
            Setting::set('certificate_image', null);
        }

        return back()->with('status', 'Semua perubahan tersimpan.');
    }
}
