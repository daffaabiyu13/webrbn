<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
            $table->text('short_description_en')->nullable()->after('short_description');
            $table->longText('full_description_en')->nullable()->after('full_description');
            $table->json('specifications_en')->nullable()->after('specifications');
            $table->json('features_en')->nullable()->after('features');
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
            $table->text('description_en')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'short_description_en', 'full_description_en', 'specifications_en', 'features_en']);
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'description_en']);
        });
    }
};
