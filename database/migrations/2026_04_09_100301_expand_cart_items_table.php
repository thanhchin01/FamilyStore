<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('cart_id')->nullable()->after('id')->constrained('carts')->nullOnDelete();
            $table->integer('unit_price_snapshot')->nullable()->after('quantity');
            $table->unique(['cart_id', 'product_id'], 'cart_items_cart_id_product_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropUnique('cart_items_cart_id_product_id_unique');
            $table->dropForeign(['cart_id']);
            $table->dropColumn(['cart_id', 'unit_price_snapshot']);
        });
    }
};
