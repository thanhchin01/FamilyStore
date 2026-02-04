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
        Schema::create('debt__transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')   // Khách hàng
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('sale_id')       // Lần bán liên quan
                ->nullable()
                ->constrained('sales')
                ->nullOnDelete();
            $table->enum('type', [             // Loại giao dịch
                'increase', // Mua chịu
                'payment',  // Trả tiền
                'adjust'    // Điều chỉnh
            ]);
            $table->bigInteger('amount');      // Số tiền phát sinh
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debt__transactions');
    }
};
