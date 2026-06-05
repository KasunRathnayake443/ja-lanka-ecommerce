<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@jalanka.com',
            'password' => Hash::make('password123'),
            'is_super_admin' => true,
            'is_active' => true,
        ]);

        $this->command->info('Admin user created: admin@jalanka.com / password123');
    }
}
