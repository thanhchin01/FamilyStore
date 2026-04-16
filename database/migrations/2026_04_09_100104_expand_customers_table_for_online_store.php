<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('customer_code')->nullable()->unique()->after('user_id');
            $table->string('email')->nullable()->after('phone');
            $table->string('default_address')->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique(['customer_code']);
            $table->dropColumn(['customer_code', 'email', 'default_address']);
        });
    }
};
