<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('imports', function (Blueprint $table) {
            // ✅ Added: dùng để gom nhiều dòng nhập kho thành 1 phiếu nhập
            $table->uuid('receipt_code')->nullable()->index()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('imports', function (Blueprint $table) {
            $table->dropColumn('receipt_code');
        });
    }
};

