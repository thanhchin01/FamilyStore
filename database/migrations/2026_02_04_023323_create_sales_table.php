<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete(); // Bán sản phẩm nào
            $table->foreignId('customer_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete(); // Khách mua (có thể không lưu)
            $table->integer('quantity'); // Số lượng bán
            $table->integer('price');  // Giá bán tại thời điểm bán (quan trọng)
            $table->enum('payment_status', ['paid', 'debt', 'partial'])->default('paid');
            $table->integer('paid_amount')->default(0);
            $table->date('sold_at');  // Ngày bán
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
