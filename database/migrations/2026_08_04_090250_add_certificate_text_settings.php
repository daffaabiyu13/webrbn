<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $rows = [
            ['key' => 'cert_valid_text',  'value' => 'Berlaku 1 Januari 2026 s/d 31 Desember 2026'],
            ['key' => 'cert_signed_text', 'value' => 'Disahkan oleh Tonny Hendro Kusumo — Industry Business Vice President, PT Schneider Indonesia.'],
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
        DB::table('settings')->whereIn('key', ['cert_valid_text', 'cert_signed_text'])->delete();
    }
};
