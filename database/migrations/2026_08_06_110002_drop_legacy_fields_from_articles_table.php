<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Articles no longer carry client / location / project_size / image.
 * Only judul + tahun + deskripsi + gambar (via article_images) survive.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            foreach (['image', 'client', 'location', 'project_size'] as $col) {
                if (Schema::hasColumn('articles', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (! Schema::hasColumn('articles', 'image')) {
                $table->string('image')->nullable()->after('description_en');
            }
            if (! Schema::hasColumn('articles', 'client')) {
                $table->string('client')->nullable();
            }
            if (! Schema::hasColumn('articles', 'location')) {
                $table->string('location')->nullable();
            }
            if (! Schema::hasColumn('articles', 'project_size')) {
                $table->string('project_size')->nullable();
            }
        });
    }
};
