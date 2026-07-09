<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\ImageCompressor;
use App\Support\UploadLimit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function edit()
    {
        $heroBackground = Setting::get('hero_background');

        return view('admin.settings.edit', compact('heroBackground'));
    }

    public function update(Request $request)
    {
        $limit = UploadLimit::forHeroBackground();
        $human = $limit->human();

        $request->validate([
            'hero_background' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:' . $limit->maxKb()],
            'remove_hero_background' => ['sometimes', 'boolean'],
        ], [
            'hero_background.uploaded' => "Gambar gagal diunggah — kemungkinan besar file lebih besar dari batas server (max {$human}, upload_max_filesize={$limit->phpUpload()}, post_max_size={$limit->phpPost()}). Kompres gambarnya atau naikkan limit di php.ini.",
            'hero_background.max' => "Ukuran gambar terlalu besar. Maksimal {$human}.",
        ]);

        $current = Setting::get('hero_background');

        if ($request->boolean('remove_hero_background')) {
            if ($current && ! str_starts_with($current, 'http')) {
                Storage::disk('public')->delete($current);
            }
            Setting::set('hero_background', null);

            return back()->with('status', 'Hero background dihapus.');
        }

        if ($request->hasFile('hero_background')) {
            if ($current && ! str_starts_with($current, 'http')) {
                Storage::disk('public')->delete($current);
            }
            $path = ImageCompressor::forHeroBackground()
                ->storeCompressed($request->file('hero_background'), 'public', 'settings');
            Setting::set('hero_background', $path);

            return back()->with('status', 'Hero background berhasil diperbarui (otomatis dikompres).');
        }

        return back()->with('status', 'Tidak ada perubahan disimpan.');
    }
}
