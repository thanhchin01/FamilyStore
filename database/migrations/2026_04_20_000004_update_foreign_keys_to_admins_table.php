<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Danh sách các bảng cần cập nhật foreign key created_by
        $tables = ['import_receipts', 'sale_invoices', 'inventory_movements', 'debt_transactions'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                // Xóa foreign key cũ trỏ tới users
                $table->dropForeign(['created_by']);
                
                // Cập nhật lại foreign key trỏ tới admins
                $table->foreign('created_by')
                    ->references('id')
                    ->on('admins')
                    ->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        $tables = ['import_receipts', 'sale_invoices', 'inventory_movements', 'debt_transactions'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->dropForeign(['created_by']);
                
                $table->foreign('created_by')
                    ->references('id')
                    ->on('users')
                    ->onDelete('set null');
            });
        }

    }
};
