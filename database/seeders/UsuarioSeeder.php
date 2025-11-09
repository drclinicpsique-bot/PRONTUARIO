<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

/**
 * SEEDER: UsuarioSeeder
 * 
 * Cria usuários administrativos e de teste.
 */
class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // Usuário Admin
        Usuario::create([
            'nome_completo' => 'Administrador do Sistema',
            'email' => 'admin@drclinic.com',
            'password' => Hash::make('admin123'),
            'tipo_usuario' => 'admin',
            'ativo' => true,
        ]);

        // Recepcionista
        Usuario::create([
            'nome_completo' => 'Maria Recepcionista',
            'email' => 'recepcao@drclinic.com',
            'password' => Hash::make('recepcao123'),
            'tipo_usuario' => 'recepcionista',
            'telefone' => '(12) 98765-4321',
            'ativo' => true,
        ]);

        $this->command->info('✅ Usuários criados: admin@drclinic.com / recepcao@drclinic.com');
    }
}