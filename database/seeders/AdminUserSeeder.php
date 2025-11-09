<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@drclinic.com'],
            [
                'name' => 'Administrador',
                'email' => 'admin@drclinic.com',
                'password' => Hash::make('Admin@123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
