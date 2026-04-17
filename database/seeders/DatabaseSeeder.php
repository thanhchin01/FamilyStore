<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Chạy AdminUserSeeder
        $this->call(AdminUserSeeder::class);

        // Tài khoản Khách hàng
        User::updateOrCreate(
            ['email' => 'client@example.com'],
            [
                'name' => 'Nguyễn Khách Hàng',
                'username' => 'khachhang',
                'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
                'role' => 'client',
                'status' => 'active',
            ]
        );
    }
}
