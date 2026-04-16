<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('import_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('import_receipt_id')->constrained('import_receipts')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->integer('quantity');
            $table->integer('unit_cost')->nullable();
            $table->integer('line_cost')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['import_receipt_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_items');
    }
};
