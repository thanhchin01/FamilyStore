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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();   // Tên khách (không bắt buộc)
            $table->string('phone')->nullable()->index();  // Số điện thoại – dùng để tra bảo hành
            $table->string('address')->nullable();     // Địa chỉ
            $table->string('relative_name')->nullable(); // Người thân để nhớ
            $table->text('note')->nullable();          // Ghi chú thêm
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
