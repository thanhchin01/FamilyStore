<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_code')->nullable()->unique()->after('id');
            $table->foreignId('customer_id')->nullable()->after('user_id')->constrained('customers')->nullOnDelete();
            $table->integer('subtotal_amount')->default(0)->after('status');
            $table->integer('shipping_fee')->default(0)->after('subtotal_amount');
            $table->integer('discount_amount')->default(0)->after('shipping_fee');
            $table->integer('grand_total')->default(0)->after('discount_amount');
            $table->text('note')->nullable()->after('shipping_address');
            $table->timestamp('placed_at')->nullable()->after('note');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropUnique(['order_code']);
            $table->dropColumn([
                'order_code',
                'customer_id',
                'subtotal_amount',
                'shipping_fee',
                'discount_amount',
                'grand_total',
                'note',
                'placed_at',
            ]);
        });
    }
};
