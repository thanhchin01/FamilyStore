<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'sku')) {
                $table->string('sku')->nullable()->unique()->after('slug');
            }
            if (!Schema::hasColumn('products', 'list_price')) {
                $table->integer('list_price')->nullable()->after('price');
            }
            if (!Schema::hasColumn('products', 'sale_price')) {
                $table->integer('sale_price')->nullable()->after('list_price');
            }
            if (!Schema::hasColumn('products', 'thumbnail_image')) {
                $table->string('thumbnail_image')->nullable()->after('image');
            }
            if (!Schema::hasColumn('products', 'short_description')) {
                $table->string('short_description')->nullable()->after('is_active');
            }
            if (!Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('short_description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['sku']);
            $table->dropColumn([
                'sku',
                'list_price',
                'sale_price',
                'thumbnail_image',
                'short_description',
                'is_featured',
            ]);
        });
    }
};
