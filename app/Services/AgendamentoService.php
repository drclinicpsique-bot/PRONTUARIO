<?php

namespace App\Services;

use App\Models\Agendamento;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * SERVICE: AgendamentoService
 * 
 * Gerencia lógica de negócio para Agendamentos.
 * Inclui validação de disponibilidade e regras de conflito de horários.
 */
class AgendamentoService
{
    /**
     * Lista agendamentos com paginação e filtros.
     * 
     * @param int $porPagina
     * @param array $filtros ['data_inicio', 'data_fim', 'profissional_id', 'status']
     * @return LengthAwarePaginator
     */
    public function listarAgendamentos(int $porPagina = 15, array $filtros = []): LengthAwarePaginator
    {
        $query = Agendamento::query()
            ->with(['paciente', 'profissional.usuario'])
            ->orderBy('data_hora_agendamento', 'desc');

        // Filtros opcionais
        if (!empty($filtros['data_inicio'])) {
            $query->whereDate('data_hora_agendamento', '>=', $filtros['data_inicio']);
        }

        if (!empty($filtros['data_fim'])) {
            $query->whereDate('data_hora_agendamento', '<=', $filtros['data_fim']);
        }

        if (!empty($filtros['profissional_id'])) {
            $query->where('profissional_id', $filtros['profissional_id']);
        }

        if (!empty($filtros['status'])) {
            $query->where('status', $filtros['status']);
        }

        return $query->paginate($porPagina);
    }

    /**
     * Busca agendamento por ID.
     * 
     * @param int $id
     * @return Agendamento
     */
    public function buscarAgendamentoPorId(int $id): Agendamento
    {
        return Agendamento::with(['paciente', 'profissional.usuario', 'prontuario'])
            ->findOrFail($id);
    }

    /**
     * Cria um novo agendamento.
     * 
     * ⚠️ VALIDAÇÃO: Verifica conflito de horários antes de criar.
     * 
     * @param array $dados
     * @return Agendamento
     * @throws \Exception
     */
    public function criarAgendamento(array $dados): Agendamento
    {
        // Valida disponibilidade de horário
        $this->validarDisponibilidade(
            $dados['profissional_id'],
            $dados['data_hora_agendamento'],
            $dados['duracao_minutos']
        );

        return Agendamento::create($dados);
    }

    /**
     * Atualiza um agendamento existente.
     * 
     * @param Agendamento $agendamento
     * @param array $dados
     * @return Agendamento
     * @throws \Exception
     */
    /**
     * Atualiza um agendamento existente.
     * 
     * @param int $id
     * @param array $dados
     * @return Agendamento
     * @throws \Exception
     */
    public function atualizarAgendamento(int $id, array $dados): Agendamento
    {
        $agendamento = $this->buscarAgendamentoPorId($id);

        // Se mudou horário ou profissional, valida disponibilidade
        if (
            isset($dados['duracao_minutos'])
        ) {
            $this->validarDisponibilidade(
                $dados['profissional_id'] ?? $agendamento->profissional_id,
                $dados['data_hora_agendamento'] ?? $agendamento->data_hora_agendamento,
                $dados['duracao_minutos'] ?? $agendamento->duracao_minutos,
                $agendamento->id // Ignora o próprio agendamento
            );
        }

        $agendamento->update($dados);
        return $agendamento->fresh(['paciente', 'profissional.usuario']);
    }
    /**
     * Cancela um agendamento.
     * 
     * @param int $id
     * @param string $motivoCancelamento
     * @return Agendamento
     * @throws \Exception
     */
    public function cancelarAgendamento(int $id, string $motivoCancelamento): Agendamento
    {
        $agendamento = $this->buscarAgendamentoPorId($id);

        if ($agendamento->status === 'cancelado') {
            throw new \Exception('Este agendamento já está cancelado.');
        }

        if ($agendamento->status === 'concluido') {
            throw new \Exception('Não é possível cancelar um agendamento já concluído.');
        }

        $agendamento->update([
            'status' => 'cancelado',
            'cancelado_em' => now(),
            'motivo_cancelamento' => $motivoCancelamento,
        ]);

        return $agendamento->fresh(['paciente', 'profissional.usuario']);
    }
    /**
     * Marca agendamento como concluído.
     * 
     * @param Agendamento $agendamento
     * @return Agendamento
     */
    public function concluirAgendamento(Agendamento $agendamento): Agendamento
    {
        $agendamento->update(['status' => 'concluido']);
        return $agendamento->fresh();
    }

    /**
     * VALIDAÇÃO: Verifica se o profissional está disponível no horário solicitado.
     * 
     * Evita double booking (mesmo profissional, mesmo horário).
     * 
     * @param int $profissionalId
     * @param string $dataHora
     * @param int $duracaoMinutos
     * @param int|null $ignorarAgendamentoId Ignora um agendamento específico (útil no update)
     * @return void
     * @throws \Exception
     */
    private function validarDisponibilidade(
        int $profissionalId,
        string $dataHora,
        int $duracaoMinutos,
        ?int $ignorarAgendamentoId = null
    ): void {
        $inicio = Carbon::parse($dataHora);
        $fim = $inicio->copy()->addMinutes($duracaoMinutos);

        // Busca agendamentos conflitantes
        $query = Agendamento::where('profissional_id', $profissionalId)
            ->whereIn('status', ['agendado', 'confirmado', 'em_atendimento'])
            ->where(function ($q) use ($inicio, $fim) {
                // Detecta sobreposição de horários
                $q->whereBetween('data_hora_agendamento', [$inicio, $fim])
                    ->orWhereRaw('DATE_ADD(data_hora_agendamento, INTERVAL duracao_minutos MINUTE) BETWEEN ? AND ?', [$inicio, $fim])
                    ->orWhere(function ($q2) use ($inicio, $fim) {
                        $q2->where('data_hora_agendamento', '<=', $inicio)
                            ->whereRaw('DATE_ADD(data_hora_agendamento, INTERVAL duracao_minutos MINUTE) >= ?', [$fim]);
                    });
            });

        if ($ignorarAgendamentoId) {
            $query->where('id', '!=', $ignorarAgendamentoId);
        }

        if ($query->exists()) {
            throw new \Exception('O profissional já possui um agendamento neste horário. Escolha outro horário disponível.');
        }
    }

    /**
     * Retorna agendamentos de hoje para um profissional.
     * 
     * @param int $profissionalId
     * @return Collection
     */
    public function agendamentosDeHojePorProfissional(int $profissionalId): Collection
    {
        return Agendamento::with('paciente')
            ->porProfissional($profissionalId)
            ->hoje()
            ->orderBy('data_hora_agendamento', 'asc')
            ->get();
    }
}
