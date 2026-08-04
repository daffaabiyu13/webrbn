<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'hero_background_home' => null,
            'hero_background_catalog' => null,
            'hero_background_about' => null,
            'hero_overlay_color_home' => '#416423',
            'hero_overlay_color_catalog' => '#416423',
            'hero_overlay_color_about' => '#416423',
            'hero_overlay_opacity_home' => '80',
            'hero_overlay_opacity_catalog' => '80',
            'hero_overlay_opacity_about' => '80',
            'certificate_image' => null,
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
