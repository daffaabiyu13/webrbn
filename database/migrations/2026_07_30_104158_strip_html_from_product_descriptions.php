<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('products')
            ->orderBy('id')
            ->chunkById(200, function ($rows) {
                foreach ($rows as $row) {
                    $updates = [];
                    foreach (['full_description', 'full_description_en'] as $field) {
                        $raw = $row->{$field} ?? null;
                        if ($raw !== null && $raw !== '') {
                            $updates[$field] = self::htmlToPlain($raw);
                        }
                    }
                    if ($updates) {
                        DB::table('products')->where('id', $row->id)->update($updates);
                    }
                }
            });
    }

    public function down(): void
    {
        // Irreversible — HTML markup is not recovered from plain text.
    }

    private static function htmlToPlain(string $html): string
    {
        // Normalise paragraph breaks
        $s = preg_replace('/<\/(p|div|h[1-6]|li)\s*>/i', "\n\n", $html);
        $s = preg_replace('/<br\s*\/?>/i', "\n", $s);
        $s = strip_tags($s);
        $s = html_entity_decode($s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        // Collapse 3+ blank lines to 2
        $s = preg_replace("/\n{3,}/", "\n\n", $s);
        return trim($s);
    }
};
