<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // ✅ Added: dùng để gom nhiều dòng bán hàng thành 1 hóa đơn
            $table->uuid('invoice_code')->nullable()->index()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('invoice_code');
        });
    }
};

