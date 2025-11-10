<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        DB::table('usuarios')->updateOrInsert(
            ['email' => 'admin@drclinic.com'],
            [
                'nome_completo' => 'Administrador',
                'email' => 'admin@drclinic.com',
                'password' => Hash::make('Admin@123'),
                'tipo_usuario' => 'admin',
                'ativo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
