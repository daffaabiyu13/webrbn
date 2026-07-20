<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        DB::table('products')
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->orderBy('id')
            ->chunkById(200, function ($rows) use ($now) {
                $insert = [];
                foreach ($rows as $row) {
                    $insert[] = [
                        'product_id' => $row->id,
                        'path' => $row->image,
                        'position' => 0,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
                if ($insert) {
                    DB::table('product_images')->insert($insert);
                }
            });
    }

    public function down(): void
    {
        // Best-effort restore of primary image back onto products.image (if column still exists).
        if (! \Illuminate\Support\Facades\Schema::hasColumn('products', 'image')) {
            return;
        }

        DB::table('product_images')
            ->where('position', 0)
            ->orderBy('product_id')
            ->chunkById(200, function ($rows) {
                foreach ($rows as $row) {
                    DB::table('products')->where('id', $row->product_id)->update(['image' => $row->path]);
                }
            });
    }
};
