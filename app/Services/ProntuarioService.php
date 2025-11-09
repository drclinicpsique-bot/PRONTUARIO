<?php

namespace App\Services;

use App\Models\Prontuario;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * SERVICE: ProntuarioService
 * 
 * Gerencia lógica de negócio para Prontuários.
 * 
 * ⚠️ LGPD: TODOS os dados manipulados são altamente sensíveis.
 * Considerar implementação de logs de acesso (auditoria).
 */
class ProntuarioService
{
    /**
     * Lista prontuários com paginação e filtros.
     * 
     * @param int $porPagina
     * @param array $filtros ['paciente_id', 'profissional_id', 'finalizado']
     * @return LengthAwarePaginator
     */
    public function listarProntuarios(int $porPagina = 15, array $filtros = []): LengthAwarePaginator
    {
        $query = Prontuario::query()
            ->with(['paciente', 'profissional.usuario', 'agendamento'])
            ->orderBy('data_atendimento', 'desc');

        if (!empty($filtros['paciente_id'])) {
            $query->porPaciente($filtros['paciente_id']);
        }

        if (!empty($filtros['profissional_id'])) {
            $query->porProfissional($filtros['profissional_id']);
        }

        if (isset($filtros['finalizado'])) {
            $query->where('finalizado', $filtros['finalizado']);
        }

        return $query->paginate($porPagina);
    }

    /**
     * Busca prontuário por ID.
     * 
     * ⚠️ LGPD: Registrar acesso ao prontuário (auditoria).
     * 
     * @param int $id
     * @return Prontuario
     */
    public function buscarProntuarioPorId(int $id): Prontuario
    {
        $prontuario = Prontuario::with([
            'paciente',
            'profissional.usuario',
            'agendamento',
            'examesSolicitados.tipoExame'
        ])->findOrFail($id);

        // TODO: Implementar log de acesso LGPD
        // Log::info('Prontuário acessado', [
        //     'prontuario_id' => $id,
        //     'usuario_id' => auth()->id(),
        //     'ip' => request()->ip(),
        //     'timestamp' => now()
        // ]);

        return $prontuario;
    }

    /**
     * Cria um novo prontuário.
     * 
     * @param array $dados
     * @return Prontuario
     * @throws \Exception
     */
    public function criarProntuario(array $dados): Prontuario
    {
        try {
            return Prontuario::create($dados);
        } catch (\Exception $e) {
            throw new \Exception('Erro ao criar prontuário: ' . $e->getMessage());
        }
    }

    /**
     * Atualiza um prontuário existente.
     * 
     * ⚠️ ATENÇÃO: Apenas o profissional criador deveria poder editar (usar Policy).
     * 
     * @param int $id
     * @param array $dados
     * @return Prontuario
     * @throws \Exception
     */
    public function atualizarProntuario(int $id, array $dados): Prontuario
    {
        $prontuario = $this->buscarProntuarioPorId($id);

        if ($prontuario->finalizado) {
            throw new \Exception('Este prontuário não pode ser editado. Está finalizado.');
        }

        try {
            $prontuario->update($dados);
            return $prontuario->fresh(['paciente', 'profissional.usuario', 'agendamento']);
        } catch (\Exception $e) {
            throw new \Exception('Erro ao atualizar prontuário: ' . $e->getMessage());
        }
    }

    /**
     * Finaliza um prontuário (torna somente leitura).
     * 
     * @param int $id
     * @return Prontuario
     * @throws \Exception
     */
    public function finalizarProntuario(int $id): Prontuario
    {
        $prontuario = $this->buscarProntuarioPorId($id);

        if ($prontuario->finalizado) {
            throw new \Exception('Este prontuário já está finalizado.');
        }

        try {
            $prontuario->update(['finalizado' => true]);
            return $prontuario->fresh(['paciente', 'profissional.usuario']);
        } catch (\Exception $e) {
            throw new \Exception('Erro ao finalizar prontuário: ' . $e->getMessage());
        }
    }

    /**
     * Deleta um prontuário (soft delete).
     * 
     * @param int $id
     * @return void
     * @throws \Exception
     */
    public function deletarProntuario(int $id): void
    {
        try {
            $prontuario = $this->buscarProntuarioPorId($id);
            $prontuario->delete();
        } catch (\Exception $e) {
            throw new \Exception('Erro ao deletar prontuário: ' . $e->getMessage());
        }
    }

    /**
     * Lista prontuários de um paciente específico.
     * 
     * @param int $pacienteId
     * @param int $porPagina
     * @return LengthAwarePaginator
     */
    public function listarProntuariosPorPaciente(int $pacienteId, int $porPagina = 15): LengthAwarePaginator
    {
        return Prontuario::with(['profissional.usuario', 'agendamento', 'examesSolicitados'])
            ->porPaciente($pacienteId)
            ->orderBy('data_atendimento', 'desc')
            ->paginate($porPagina);
    }

    /**
     * Lista prontuários de um profissional específico.
     * 
     * @param int $profissionalId
     * @param int $porPagina
     * @return LengthAwarePaginator
     */
    public function listarProntuariosPorProfissional(int $profissionalId, int $porPagina = 15): LengthAwarePaginator
    {
        return Prontuario::with(['paciente', 'agendamento'])
            ->porProfissional($profissionalId)
            ->orderBy('data_atendimento', 'desc')
            ->paginate($porPagina);
    }

    /**
     * Retorna prontuários finalizados.
     * 
     * @param int $porPagina
     * @return LengthAwarePaginator
     */
    public function listarFinalizados(int $porPagina = 15): LengthAwarePaginator
    {
        return Prontuario::with(['paciente', 'profissional.usuario'])
            ->finalizados()
            ->orderBy('data_atendimento', 'desc')
            ->paginate($porPagina);
    }

    /**
     * Retorna prontuários em aberto (não finalizados).
     * 
     * @param int $porPagina
     * @return LengthAwarePaginator
     */
    public function listarEmAberto(int $porPagina = 15): LengthAwarePaginator
    {
        return Prontuario::with(['paciente', 'profissional.usuario'])
            ->emAberto()
            ->orderBy('data_atendimento', 'desc')
            ->paginate($porPagina);
    }

    /**
     * Conta prontuários por status.
     * 
     * @return array
     */
    public function contarPorStatus(): array
    {
        return [
            'total' => Prontuario::count(),
            'finalizados' => Prontuario::where('finalizado', true)->count(),
            'em_aberto' => Prontuario::where('finalizado', false)->count(),
        ];
    }

    /**
     * Busca prontuários recentes de um paciente.
     * 
     * @param int $pacienteId
     * @param int $limite
     * @return \Illuminate\Support\Collection
     */
    public function prontuariosRecentesPorPaciente(int $pacienteId, int $limite = 5)
    {
        return Prontuario::with(['profissional.usuario'])
            ->where('paciente_id', $pacienteId)
            ->orderBy('data_atendimento', 'desc')
            ->limit($limite)
            ->get();
    }

    /**
     * Busca prontuários por período.
     * 
     * @param string $dataInicio (Y-m-d)
     * @param string $dataFim (Y-m-d)
     * @param int $porPagina
     * @return LengthAwarePaginator
     */
    public function listarPorPeriodo(string $dataInicio, string $dataFim, int $porPagina = 15): LengthAwarePaginator
    {
        return Prontuario::with(['paciente', 'profissional.usuario'])
            ->whereBetween('data_atendimento', [$dataInicio . ' 00:00:00', $dataFim . ' 23:59:59'])
            ->orderBy('data_atendimento', 'desc')
            ->paginate($porPagina);
    }
}