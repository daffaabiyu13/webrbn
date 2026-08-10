<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('articles', 'image')) {
            return;
        }

        $now = now();

        DB::table('articles')
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->orderBy('id')
            ->select('id', 'image')
            ->chunkById(200, function ($rows) use ($now) {
                foreach ($rows as $row) {
                    $exists = DB::table('article_images')
                        ->where('article_id', $row->id)
                        ->where('path', $row->image)
                        ->exists();
                    if ($exists) {
                        continue;
                    }
                    DB::table('article_images')->insert([
                        'article_id' => $row->id,
                        'path' => $row->image,
                        'position' => 0,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            });
    }

    public function down(): void
    {
        // Non-destructive.
    }
};
