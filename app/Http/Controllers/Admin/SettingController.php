<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
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
        $request->validate([
            'hero_background' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'remove_hero_background' => ['sometimes', 'boolean'],
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
            $path = $request->file('hero_background')->store('settings', 'public');
            Setting::set('hero_background', $path);

            return back()->with('status', 'Hero background berhasil diperbarui.');
        }

        return back()->with('status', 'Tidak ada perubahan disimpan.');
    }
}
