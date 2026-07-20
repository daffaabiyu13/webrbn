<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')
            ->where('key', 'hero_background')
            ->update(['key' => 'hero_background_home']);
    }

    public function down(): void
    {
        DB::table('settings')
            ->where('key', 'hero_background_home')
            ->update(['key' => 'hero_background']);
    }
};
