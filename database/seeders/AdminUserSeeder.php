<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Verificar se já existe
        $admin = Usuario::where('email', 'admin@drclinic.com')->first();

        if (!$admin) {
            Usuario::create([
                'nome_completo' => 'Administrador do Sistema',
                'email' => 'admin@drclinic.com',
                'password' => bcrypt('Admin@123'),  // ⬅️ USAR bcrypt()
                'telefone' => '(00) 00000-0000',
                'tipo_usuario' => 'admin',
                'ativo' => true,
                'email_verified_at' => now(),
            ]);

            $this->command->info('✅ Usuário admin criado com sucesso!');
            $this->command->info('📧 Email: admin@drclinic.com');
            $this->command->info('🔑 Senha: Admin@123');
            $this->command->warn('⚠️  IMPORTANTE: Troque a senha após o primeiro login!');
        } else {
            $this->command->info('ℹ️  Usuário admin já existe.');
        }
    }
}