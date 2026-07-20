<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $old = DB::table('settings')->where('key', 'hero_background')->first();
        if (! $old) {
            return;
        }

        $new = DB::table('settings')->where('key', 'hero_background_home')->first();

        if ($new) {
            // Destination key already exists (e.g. seeder ran first). Prefer the
            // old row's value if the new one is empty, then drop the legacy row.
            if ($old->value !== null && ($new->value === null || $new->value === '')) {
                DB::table('settings')->where('id', $new->id)->update(['value' => $old->value]);
            }
            DB::table('settings')->where('id', $old->id)->delete();

            return;
        }

        DB::table('settings')->where('id', $old->id)->update(['key' => 'hero_background_home']);
    }

    public function down(): void
    {
        DB::table('settings')
            ->where('key', 'hero_background_home')
            ->update(['key' => 'hero_background']);
    }
};
