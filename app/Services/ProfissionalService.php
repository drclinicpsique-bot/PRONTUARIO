<?php

namespace App\Services;

use App\Models\Profissional;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * SERVICE: ProfissionalService
 * 
 * Gerencia lógica de negócio para Profissionais.
 * Cria simultaneamente Usuario e Profissional (transação).
 */
class ProfissionalService
{
    /**
     * Lista profissionais com paginação.
     * 
     * @param int $porPagina
     * @param string|null $especialidade Filtro por especialidade
     * @return LengthAwarePaginator
     */
    public function listarProfissionais(int $porPagina = 15, ?string $especialidade = null): LengthAwarePaginator
    {
        $query = Profissional::query()
            ->with('usuario')
            ->ativos()
            ->orderBy('especialidade', 'asc');

        if ($especialidade) {
            $query->porEspecialidade($especialidade);
        }

        return $query->paginate($porPagina);
    }

    /**
     * Busca profissional por ID.
     * 
     * @param int $id
     * @return Profissional
     */
    public function buscarProfissionalPorId(int $id): Profissional
    {
        return Profissional::with([
            'usuario',
            'agendamentos' => function ($q) {
                $q->where('data_hora_agendamento', '>', now())
                  ->orderBy('data_hora_agendamento', 'asc')
                  ->limit(10);
            },
            'prontuarios' => function ($q) {
                $q->orderBy('data_atendimento', 'desc')
                  ->limit(10);
            }
        ])->findOrFail($id);
    }

    /**
     * Cria um novo profissional (com usuário).
     * 
     * ATENÇÃO: Cria simultaneamente Usuario e Profissional em transação.
     * 
     * @param array $dados
     * @return Profissional
     * @throws \Exception
     */
    public function criarProfissional(array $dados): Profissional
    {
        return DB::transaction(function () use ($dados) {
            // 1. Cria o Usuário
            $usuario = Usuario::create([
                'nome_completo' => $dados['nome_completo'],
                'email' => $dados['email'],
                'password' => Hash::make($dados['password']),
                'tipo_usuario' => 'medico',
                'crm' => $dados['crm'],
                'telefone' => $dados['telefone'] ?? null,
                'ativo' => true,
            ]);

            // 2. Cria o Profissional associado
            $profissional = Profissional::create([
                'usuario_id' => $usuario->id,
                'crm' => $dados['crm'],
                'especialidade' => $dados['especialidade'],
                'telefone_consultorio' => $dados['telefone_consultorio'] ?? null,
                'formacao_academica' => $dados['formacao_academica'] ?? null,
                'observacoes' => $dados['observacoes'] ?? null,
                'ativo' => true,
            ]);

            return $profissional->load('usuario');
        });
    }

    /**
     * Atualiza um profissional existente (e seu usuário).
     * 
     * @param Profissional $profissional
     * @param array $dados
     * @return Profissional
     */
    public function atualizarProfissional(Profissional $profissional, array $dados): Profissional
    {
        return DB::transaction(function () use ($profissional, $dados) {
            // 1. Atualiza o Usuário
            $profissional->usuario->update([
                'nome_completo' => $dados['nome_completo'],
                'email' => $dados['email'],
                'telefone' => $dados['telefone'] ?? null,
            ]);

            // 2. Atualiza o Profissional
            $profissional->update([
                'crm' => $dados['crm'],
                'especialidade' => $dados['especialidade'],
                'telefone_consultorio' => $dados['telefone_consultorio'] ?? null,
                'formacao_academica' => $dados['formacao_academica'] ?? null,
                'observacoes' => $dados['observacoes'] ?? null,
                'ativo' => $dados['ativo'] ?? true,
            ]);

            return $profissional->fresh(['usuario']);
        });
    }

    /**
     * Exclui (soft delete) um profissional.
     * 
     * ⚠️ ATENÇÃO: Não permite exclusão se houver prontuários cadastrados.
     * 
     * @param Profissional $profissional
     * @return bool
     * @throws \Exception
     */
    public function excluirProfissional(Profissional $profissional): bool
    {
        // Verifica se há prontuários cadastrados
        if ($profissional->prontuarios()->count() > 0) {
            throw new \Exception('Não é possível excluir profissional com prontuários cadastrados. Desative o profissional ao invés de excluir.');
        }

        return DB::transaction(function () use ($profissional) {
            $profissional->usuario->delete(); // Soft delete do usuário
            return $profissional->delete(); // Soft delete do profissional
        });
    }

    /**
     * Lista especialidades únicas cadastradas.
     * Útil para filtros e select boxes.
     * 
     * @return Collection
     */
    public function listarEspecialidades(): Collection
    {
        return Profissional::ativos()
            ->distinct()
            ->pluck('especialidade')
            ->sort()
            ->values();
    }
}