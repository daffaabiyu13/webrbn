<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $rows = [];

        foreach (['home', 'catalog', 'about'] as $page) {
            $rows[] = ['key' => "hero_overlay_color_{$page}", 'value' => '#2D6A27', 'created_at' => $now, 'updated_at' => $now];
            $rows[] = ['key' => "hero_overlay_opacity_{$page}", 'value' => '80', 'created_at' => $now, 'updated_at' => $now];
        }

        foreach ($rows as $row) {
            $exists = DB::table('settings')->where('key', $row['key'])->exists();
            if (! $exists) {
                DB::table('settings')->insert($row);
            }
        }
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'hero_overlay_color_home',
            'hero_overlay_color_catalog',
            'hero_overlay_color_about',
            'hero_overlay_opacity_home',
            'hero_overlay_opacity_catalog',
            'hero_overlay_opacity_about',
        ])->delete();
    }
};
