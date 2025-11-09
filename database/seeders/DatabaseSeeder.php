<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * SEEDER PRINCIPAL
 * 
 * Executa todos os seeders em ordem lógica.
 * Para executar: php artisan db:seed
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsuarioSeeder::class,
            ProfissionalSeeder::class,
            PacienteSeeder::class,
            TipoExameSeeder::class,
            // AgendamentoSeeder::class, // Opcional: dados de teste
            // ProntuarioSeeder::class,   // Opcional: dados de teste
        ]);

        $this->command->info('✅ Seeders executados com sucesso!');
    }
}