<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Setting::firstOrCreate(
            ['key' => 'hero_overlay_color_projects'],
            ['value' => '#416423']
        );
        Setting::firstOrCreate(
            ['key' => 'hero_overlay_opacity_projects'],
            ['value' => '75']
        );
    }

    public function down(): void
    {
        Setting::whereIn('key', [
            'hero_background_projects',
            'hero_overlay_color_projects',
            'hero_overlay_opacity_projects',
        ])->delete();
    }
};
