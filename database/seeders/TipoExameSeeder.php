<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoExame;

/**
 * SEEDER: TipoExameSeeder
 * 
 * Popula catálogo de tipos de exames comuns.
 */
class TipoExameSeeder extends Seeder
{
    public function run(): void
    {
        $exames = [
            // Exames Laboratoriais
            ['nome' => 'Hemograma Completo', 'categoria' => 'laboratorial', 'preco' => 35.00, 'prazo' => 1],
            ['nome' => 'Glicemia em Jejum', 'categoria' => 'laboratorial', 'preco' => 20.00, 'prazo' => 1],
            ['nome' => 'Colesterol Total e Frações', 'categoria' => 'laboratorial', 'preco' => 45.00, 'prazo' => 2],
            ['nome' => 'Triglicerídeos', 'categoria' => 'laboratorial', 'preco' => 25.00, 'prazo' => 1],
            ['nome' => 'Creatinina', 'categoria' => 'laboratorial', 'preco' => 22.00, 'prazo' => 1],
            ['nome' => 'Ureia', 'categoria' => 'laboratorial', 'preco' => 20.00, 'prazo' => 1],
            ['nome' => 'TSH (Hormônio Tireoidiano)', 'categoria' => 'laboratorial', 'preco' => 40.00, 'prazo' => 2],
            ['nome' => 'Hemoglobina Glicada (HbA1c)', 'categoria' => 'laboratorial', 'preco' => 55.00, 'prazo' => 2],
            
            // Exames de Imagem
            ['nome' => 'Raio-X de Tórax', 'categoria' => 'imagem', 'preco' => 80.00, 'prazo' => 1],
            ['nome' => 'Ultrassonografia Abdominal', 'categoria' => 'imagem', 'preco' => 150.00, 'prazo' => 3],
            ['nome' => 'Tomografia Computadorizada de Crânio', 'categoria' => 'imagem', 'preco' => 450.00, 'prazo' => 5],
            ['nome' => 'Ressonância Magnética Lombar', 'categoria' => 'imagem', 'preco' => 800.00, 'prazo' => 7],
            
            // Exames Cardiológicos
            ['nome' => 'Eletrocardiograma (ECG)', 'categoria' => 'cardiologico', 'preco' => 60.00, 'prazo' => 0],
            ['nome' => 'Ecocardiograma', 'categoria' => 'cardiologico', 'preco' => 250.00, 'prazo' => 3],
            ['nome' => 'Teste Ergométrico', 'categoria' => 'cardiologico', 'preco' => 180.00, 'prazo' => 5],
            
            // Outros
            ['nome' => 'Endoscopia Digestiva Alta', 'categoria' => 'endoscopico', 'preco' => 350.00, 'prazo' => 7, 'preparacao' => 'Jejum de 12 horas'],
            ['nome' => 'Colonoscopia', 'categoria' => 'endoscopico', 'preco' => 500.00, 'prazo' => 10, 'preparacao' => 'Dieta líquida e laxantes conforme prescrição'],
        ];

        foreach ($exames as $exame) {
            TipoExame::create([
                'nome' => $exame['nome'],
                'categoria' => $exame['categoria'],
                'preco_referencia' => $exame['preco'] ?? null,
                'prazo_entrega_dias' => $exame['prazo'] ?? null,
                'preparacao_necessaria' => $exame['preparacao'] ?? null,
                'ativo' => true,
            ]);
        }

        $this->command->info('✅ Catálogo de tipos de exames criado (17 exames)');
    }
}