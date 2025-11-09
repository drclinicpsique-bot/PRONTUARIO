<?php

namespace Database\Seeders;

use App\Models\Usuario;
use App\Models\Profissional;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProfissionalSeeder extends Seeder
{
    public function run(): void
    {
        $profissionais = [
            [
                'nome_completo' => 'Dra. Fernanda Silva Psyche',
                'email' => 'fernanda.psyche@email.com',
                'crm' => '123456-SP',
                'telefone' => '(13) 98888-0001',
                'especialidade' => 'Psiquiatria',
                'formacao_academica' => 'Médica - USP (2005)\nEspecialista em Psiquiatria - UNIFESP (2010)\nMestrado em Saúde Mental - USP (2015)',
                'observacoes' => 'Especialista em Saúde Mental com 15 anos de experiência clínica',
            ],
            [
                'nome_completo' => 'Dr. Ricardo Cardoso Coração',
                'email' => 'ricardo.cardiologia@email.com',
                'crm' => '234567-SP',
                'telefone' => '(13) 98888-0002',
                'especialidade' => 'Cardiologia',
                'formacao_academica' => 'Médico - UNICAMP (2003)\nEspecialista em Cardiologia - INCOR (2008)\nEspecialização em Arritmias - USP (2012)',
                'observacoes' => 'Cardiologista clínico com especialização em arritmias e insuficiência cardíaca',
            ],
            [
                'nome_completo' => 'Dra. Marta Oliveira Vida',
                'email' => 'marta.geriatria@email.com',
                'crm' => '345678-SP',
                'telefone' => '(13) 98888-0003',
                'especialidade' => 'Geriatria',
                'formacao_academica' => 'Médica - PUC (2002)\nEspecialista em Geriatria - Santa Casa (2007)\nPós-Graduação em Gerontologia - USP (2014)',
                'observacoes' => 'Geriatra com foco em qualidade de vida do idoso e prevenção de doenças',
            ],
        ];

        foreach ($profissionais as $prof) {
            // Criar usuário
            $usuario = Usuario::create([
                'nome_completo' => $prof['nome_completo'],
                'email' => $prof['email'],
                'password' => Hash::make('senha123'),
                'tipo_usuario' => 'medico',
                'telefone' => $prof['telefone'],
                'ativo' => true,
            ]);

            // Criar profissional vinculado
            Profissional::create([
                'usuario_id' => $usuario->id,
                'crm' => $prof['crm'],
                'especialidade' => $prof['especialidade'],
                'telefone_consultorio' => $prof['telefone'],
                'formacao_academica' => $prof['formacao_academica'],
                'observacoes' => $prof['observacoes'],
                'ativo' => true,
            ]);
        }

        $this->command->info('✅ 3 profissionais médicos criados com sucesso!');
    }
}