<?php

namespace App\Services;

use App\Models\Paciente;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * SERVICE: PacienteService
 * 
 * Centraliza lógica de negócio relacionada a Pacientes.
 * Mantém Controllers enxutos e promove reutilização de código.
 */
class PacienteService
{
    /**
     * Lista pacientes com paginação e busca opcional.
     * 
     * @param int $porPagina Quantidade de registros por página
     * @param string|null $busca Termo de busca (nome ou CPF)
     * @return LengthAwarePaginator
     */
    public function listarPacientes(int $porPagina = 15, ?string $busca = null): LengthAwarePaginator
    {
        $query = Paciente::query()
            ->with(['agendamentos' => function ($q) {
                $q->where('data_hora_agendamento', '>', now())
                    ->where('status', '!=', 'cancelado')
                    ->orderBy('data_hora_agendamento', 'asc')
                    ->limit(1);
            }])
            ->withCount('prontuarios') // Adiciona prontuarios_count
            ->ativos()
            ->orderBy('nome_completo', 'asc');

        // Busca por nome ou CPF
        if ($busca) {
            $query->where(function ($q) use ($busca) {
                $q->where('nome_completo', 'like', "%{$busca}%")
                    ->orWhere('cpf', 'like', "%{$busca}%");
            });
        }

        return $query->paginate($porPagina);
    }

    /**
     * Busca paciente por ID com relacionamentos carregados.
     * 
     * @param int $id
     * @return Paciente
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function buscarPacientePorId(int $id): Paciente
    {
        return Paciente::with([
            'agendamentos' => function ($q) {
                $q->orderBy('data_hora_agendamento', 'desc')->limit(5);
            },
            'prontuarios' => function ($q) {
                $q->with(['profissional.usuario', 'paciente'])
                    ->orderBy('data_atendimento', 'desc');
            }
        ])
            ->withCount('prontuarios')
            ->findOrFail($id);
    }


    /**
     * Cria um novo paciente.
     * 
     * @param array $dados
     * @return Paciente
     */
    public function criarPaciente(array $dados): Paciente
    {
        return Paciente::create($dados);
    }

    /**
     * Atualiza um paciente existente.
     * 
     * @param Paciente $paciente
     * @param array $dados
     * @return Paciente
     */
    public function atualizarPaciente(Paciente $paciente, array $dados): Paciente
    {
        $paciente->update($dados);
        return $paciente->fresh(); // Recarrega do banco
    }

    /**
     * Exclui (soft delete) um paciente.
     * 
     * ⚠️ ATENÇÃO: Soft delete apenas. Dados nunca são excluídos fisicamente (LGPD).
     * 
     * @param Paciente $paciente
     * @return bool
     */
    public function excluirPaciente(Paciente $paciente): bool
    {
        // Verifica se há agendamentos futuros
        if ($paciente->temAgendamentosFuturos()) {
            throw new \Exception('Não é possível excluir paciente com agendamentos futuros. Cancele os agendamentos primeiro.');
        }

        return $paciente->delete(); // Soft delete
    }

    /**
     * Busca pacientes aniversariantes do mês atual.
     * Útil para campanhas de aniversário.
     * 
     * @return Collection
     */
    public function buscarAniversariantesDoMes(): Collection
    {
        $mesAtual = now()->month;

        return Paciente::ativos()
            ->whereMonth('data_nascimento', $mesAtual)
            ->orderByRaw('DAY(data_nascimento)')
            ->get();
    }
}
