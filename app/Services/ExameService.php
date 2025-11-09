<?php

namespace App\Services;

use App\Models\ExameSolicitado;
use App\Models\ResultadoExame;
use App\Models\TipoExame;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * SERVICE: ExameService
 * 
 * Gerencia lógica de negócio para Exames (Solicitação + Resultados).
 */
class ExameService
{
    /**
     * Lista tipos de exame disponíveis.
     * 
     * @return Collection
     */
    public function listarTiposExame(): Collection
    {
        return TipoExame::ativos()->orderBy('nome', 'asc')->get();
    }

    /**
     * Cria uma solicitação de exame.
     * 
     * @param array $dados
     * @return ExameSolicitado
     */
    public function solicitarExame(array $dados): ExameSolicitado
    {
        return ExameSolicitado::create($dados);
    }

    /**
     * Lista exames solicitados com filtros.
     * 
     * @param int $porPagina
     * @param array $filtros ['status', 'prontuario_id']
     * @return LengthAwarePaginator
     */
    public function listarExamesSolicitados(int $porPagina = 15, array $filtros = []): LengthAwarePaginator
    {
        $query = ExameSolicitado::query()
            ->with(['tipoExame', 'prontuario.paciente', 'resultado'])
            ->orderBy('data_solicitacao', 'desc');

        if (!empty($filtros['status'])) {
            $query->porStatus($filtros['status']);
        }

        if (!empty($filtros['prontuario_id'])) {
            $query->where('prontuario_id', $filtros['prontuario_id']);
        }

        return $query->paginate($porPagina);
    }

    /**
     * Cadastra resultado de um exame solicitado.
     * 
     * ⚠️ LGPD: Dados sensíveis.
     * 
     * @param array $dados
     * @return ResultadoExame
     */
    public function cadastrarResultado(array $dados): ResultadoExame
    {
        $resultado = ResultadoExame::create($dados);

        // Atualiza status do exame solicitado para 'concluido'
        ExameSolicitado::find($dados['exame_solicitado_id'])->update(['status' => 'concluido']);

        return $resultado;
    }

    /**
     * Busca exames solicitados de um prontuário.
     * 
     * @param int $prontuarioId
     * @return Collection
     */
    public function buscarExamesPorProntuario(int $prontuarioId): Collection
    {
        return ExameSolicitado::with(['tipoExame', 'resultado'])
            ->where('prontuario_id', $prontuarioId)
            ->orderBy('data_solicitacao', 'desc')
            ->get();
    }
}