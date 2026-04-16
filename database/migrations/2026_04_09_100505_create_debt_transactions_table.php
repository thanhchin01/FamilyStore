<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('debt_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('sale_invoice_id')->nullable()->constrained('sale_invoices')->nullOnDelete();
            $table->enum('type', ['increase', 'payment', 'adjustment', 'refund']);
            $table->integer('amount');
            $table->text('description')->nullable();
            $table->timestamp('occurred_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['customer_id', 'occurred_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('debt_transactions');
    }
};
