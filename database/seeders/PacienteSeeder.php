<?php

namespace Database\Seeders;

use App\Models\Paciente;
use Illuminate\Database\Seeder;

class PacienteSeeder extends Seeder
{
    public function run(): void
    {
        $pacientes = [
            [
                'nome_completo' => 'Maria Silva Santos',
                'cpf' => '123.456.789-01',
                'data_nascimento' => '1980-05-15',
                'sexo' => 'feminino',
                'telefone' => '(13) 99999-0001',
                'email' => 'maria.silva@email.com',
                'endereco' => 'Rua A, 100 - Apto 201',
                'cidade' => 'Caraguatatuba',
                'estado' => 'SP',
                'cep' => '11670-000',
                'observacoes_gerais' => 'Paciente regularizado',
                'ativo' => true,
            ],
            [
                'nome_completo' => 'João Oliveira Pereira',
                'cpf' => '123.456.789-02',
                'data_nascimento' => '1975-10-22',
                'sexo' => 'masculino',
                'telefone' => '(13) 99999-0002',
                'email' => 'joao.oliveira@email.com',
                'endereco' => 'Rua B, 200 - Apto 102',
                'cidade' => 'Caraguatatuba',
                'estado' => 'SP',
                'cep' => '11670-001',
                'ativo' => true,
            ],
            [
                'nome_completo' => 'Ana Costa Ferreira',
                'cpf' => '123.456.789-03',
                'data_nascimento' => '1990-03-08',
                'sexo' => 'feminino',
                'telefone' => '(13) 99999-0003',
                'email' => 'ana.costa@email.com',
                'endereco' => 'Rua C, 300',
                'cidade' => 'Caraguatatuba',
                'estado' => 'SP',
                'cep' => '11670-002',
                'ativo' => true,
            ],
            [
                'nome_completo' => 'Carlos Roberto Mendes',
                'cpf' => '123.456.789-04',
                'data_nascimento' => '1965-07-30',
                'sexo' => 'masculino',
                'telefone' => '(13) 99999-0004',
                'email' => 'carlos.mendes@email.com',
                'endereco' => 'Rua D, 400 - Apto 305',
                'cidade' => 'Caraguatatuba',
                'estado' => 'SP',
                'cep' => '11670-003',
                'observacoes_gerais' => 'Paciente idoso - atenção especial',
                'ativo' => true,
            ],
            [
                'nome_completo' => 'Beatriz Alves Gomes',
                'cpf' => '123.456.789-05',
                'data_nascimento' => '1985-12-14',
                'sexo' => 'feminino',
                'telefone' => '(13) 99999-0005',
                'email' => 'beatriz.alves@email.com',
                'endereco' => 'Rua E, 500',
                'cidade' => 'Caraguatatuba',
                'estado' => 'SP',
                'cep' => '11670-004',
                'ativo' => true,
            ],
            [
                'nome_completo' => 'Fernando Rodrigues Lima',
                'cpf' => '123.456.789-06',
                'data_nascimento' => '1972-01-25',
                'sexo' => 'masculino',
                'telefone' => '(13) 99999-0006',
                'email' => 'fernando.lima@email.com',
                'endereco' => 'Rua F, 600 - Apto 401',
                'cidade' => 'Caraguatatuba',
                'estado' => 'SP',
                'cep' => '11670-005',
                'ativo' => true,
            ],
            [
                'nome_completo' => 'Gabriela Santos Martins',
                'cpf' => '123.456.789-07',
                'data_nascimento' => '1995-06-18',
                'sexo' => 'feminino',
                'telefone' => '(13) 99999-0007',
                'email' => 'gabriela.martins@email.com',
                'endereco' => 'Rua G, 700',
                'cidade' => 'Caraguatatuba',
                'estado' => 'SP',
                'cep' => '11670-006',
                'ativo' => true,
            ],
            [
                'nome_completo' => 'Henrique Barbosa Souza',
                'cpf' => '123.456.789-08',
                'data_nascimento' => '1968-09-05',
                'sexo' => 'masculino',
                'telefone' => '(13) 99999-0008',
                'email' => 'henrique.souza@email.com',
                'endereco' => 'Rua H, 800 - Apto 102',
                'cidade' => 'Caraguatatuba',
                'estado' => 'SP',
                'cep' => '11670-007',
                'ativo' => true,
            ],
            [
                'nome_completo' => 'Isabela Nunes Teixeira',
                'cpf' => '123.456.789-09',
                'data_nascimento' => '1988-11-12',
                'sexo' => 'feminino',
                'telefone' => '(13) 99999-0009',
                'email' => 'isabela.teixeira@email.com',
                'endereco' => 'Rua I, 900',
                'cidade' => 'Caraguatatuba',
                'estado' => 'SP',
                'cep' => '11670-008',
                'ativo' => true,
            ],
            [
                'nome_completo' => 'Julio Cesar Rocha Dias',
                'cpf' => '123.456.789-10',
                'data_nascimento' => '1970-04-20',
                'sexo' => 'masculino',
                'telefone' => '(13) 99999-0010',
                'email' => 'julio.dias@email.com',
                'endereco' => 'Rua J, 1000 - Apto 501',
                'cidade' => 'Caraguatatuba',
                'estado' => 'SP',
                'cep' => '11670-009',
                'ativo' => true,
            ],
        ];

        foreach ($pacientes as $paciente) {
            Paciente::create($paciente);
        }

        $this->command->info('✅ 10 pacientes criados com sucesso!');
    }
}