<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@alsham.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Admin@12345'), // كلمة مرور قوية
                'role' => 'admin', // الدور المخصص للإدارة حسب مودل User لديك
                'gender' => 'male',
                'joined' => now(),
            ]
        );
    }
}
