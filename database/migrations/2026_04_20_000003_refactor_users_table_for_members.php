<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'birthday')) {
                $table->date('birthday')->nullable()->after('gender');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('address');
            }
            // Loại bỏ cột role khỏi bảng users nếu không còn dùng đa quyền trên cùng 1 bảng
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['gender', 'birthday', 'avatar']);
            $table->enum('role', ['admin', 'customer'])->default('customer')->after('password');
        });
    }
};
