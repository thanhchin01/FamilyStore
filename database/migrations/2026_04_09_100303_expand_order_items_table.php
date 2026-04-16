<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('product_name_snapshot')->nullable()->after('product_id');
            $table->string('product_sku_snapshot')->nullable()->after('product_name_snapshot');
            $table->string('product_image_snapshot')->nullable()->after('product_sku_snapshot');
            $table->integer('unit_price')->nullable()->after('quantity');
            $table->integer('line_total')->default(0)->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn([
                'product_name_snapshot',
                'product_sku_snapshot',
                'product_image_snapshot',
                'unit_price',
                'line_total',
            ]);
        });
    }
};
