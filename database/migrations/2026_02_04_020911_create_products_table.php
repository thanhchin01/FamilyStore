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
            $table->string('slug')->unique();
            $table->string('image')->nullable(); // Hình ảnh
            $table->string('brand')->nullable(); //Hãng
            $table->string('model')->nullable(); // Model
            $table->integer('price');  // Giá
            $table->integer('warranty_months')->default(0); // Thời gian bảo hành (tháng)
            $table->integer('stock')->default(0); // Số lượng tồn kho hiện tại
            $table->integer('is_active')->default(1); // Trạng thái (1: active, 0: inactive)
            $table->text('description')->nullable(); // Mô tả sản phẩm
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
