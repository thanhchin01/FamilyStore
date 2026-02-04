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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                ->constrained()
                ->cascadeOnDelete(); // Sản phẩm thuộc loại nào
            $table->string('name'); //Tên sản phẩm
            $table->string('brand0')->nullable(); //Hãng
            $table->string('price');  // Giá
            $table->integer('warranty_months')->default(0); // Thời gian bảo hành (tháng)
            $table->integer('stock')->default(0); // Số lượng tồn kho hiện tại
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
