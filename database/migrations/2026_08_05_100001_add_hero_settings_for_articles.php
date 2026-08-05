<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $rows = [
            ['key' => 'hero_background_articles',      'value' => null],
            ['key' => 'hero_overlay_color_articles',   'value' => '#416423'],
            ['key' => 'hero_overlay_opacity_articles', 'value' => '80'],
        ];

        foreach ($rows as $row) {
            $exists = DB::table('settings')->where('key', $row['key'])->exists();
            if (! $exists) {
                DB::table('settings')->insert([
                    'key' => $row['key'],
                    'value' => $row['value'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'hero_background_articles',
            'hero_overlay_color_articles',
            'hero_overlay_opacity_articles',
        ])->delete();
    }
};
