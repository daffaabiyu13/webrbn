<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('projects', 'image')) {
            return;
        }

        $now = now();

        DB::table('projects')
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->orderBy('id')
            ->select('id', 'image')
            ->chunkById(200, function ($rows) use ($now) {
                foreach ($rows as $row) {
                    $exists = DB::table('project_images')
                        ->where('project_id', $row->id)
                        ->where('path', $row->image)
                        ->exists();
                    if ($exists) {
                        continue;
                    }
                    DB::table('project_images')->insert([
                        'project_id' => $row->id,
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
        // Non-destructive: the source column is kept intact by the sibling
        // drop-column migration, and rolling that one back restores schema.
    }
};
