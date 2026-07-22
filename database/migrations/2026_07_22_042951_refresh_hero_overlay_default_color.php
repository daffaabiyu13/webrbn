<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Move rows still holding the old default (#2D6A27) to the new brand default.
        // Anything user has customised (non-default) stays untouched.
        DB::table('settings')
            ->whereIn('key', ['hero_overlay_color_home', 'hero_overlay_color_catalog', 'hero_overlay_color_about'])
            ->where('value', '#2D6A27')
            ->update(['value' => '#416423']);
    }

    public function down(): void
    {
        DB::table('settings')
            ->whereIn('key', ['hero_overlay_color_home', 'hero_overlay_color_catalog', 'hero_overlay_color_about'])
            ->where('value', '#416423')
            ->update(['value' => '#2D6A27']);
    }
};
