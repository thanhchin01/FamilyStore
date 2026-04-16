<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_invoices', function (Blueprint $table) {
            $table->id();
            $table->uuid('invoice_code')->unique();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('channel', ['pos', 'online_manual'])->default('pos');
            $table->enum('status', ['draft', 'completed', 'cancelled', 'refunded'])->default('completed');
            $table->integer('subtotal_amount')->default(0);
            $table->integer('discount_amount')->default(0);
            $table->integer('grand_total')->default(0);
            $table->integer('paid_amount')->default(0);
            $table->integer('debt_amount')->default(0);
            $table->text('note')->nullable();
            $table->timestamp('sold_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_invoices');
    }
};
