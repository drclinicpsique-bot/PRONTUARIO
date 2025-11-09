<?php

namespace App\Services;

use App\Models\Agendamento;
use App\Models\Prontuario;
use Carbon\Carbon;

/**
 * SERVICE: ConsultaService
 * 
 * Gerencia todo o fluxo de consultas (iniciar, finalizar, etc).
 * Responsável pela lógica de negócio das consultas.
 */
class ConsultaService
{
    public function __construct(
        private ProntuarioService $prontuarioService
    ) {}

    /**
     * Inicia uma consulta e cria prontuário vinculado.
     * 
     * @param int $agendamentoId - ID do agendamento
     * @return Agendamento - Agendamento atualizado
     * @throws \Exception
     */
    public function iniciarConsulta(int $agendamentoId): Agendamento
    {
        $agendamento = Agendamento::findOrFail($agendamentoId);

        // Validar se pode iniciar
        if (!$agendamento->podeIniciarConsulta()) {
            throw new \Exception(
                'Este agendamento não pode iniciar consulta. Status atual: ' . $agendamento->status_label
            );
        }

        // Marcar início da consulta
        $agendamento->update([
            'data_inicio_consulta' => now(),
            'status' => 'em_atendimento',
        ]);

        // Criar prontuário vinculado ao agendamento
        $this->criarProntuarioVinculado($agendamento);

        return $agendamento->fresh();
    }

    /**
     * Finaliza uma consulta.
     * 
     * @param int $agendamentoId - ID do agendamento
     * @return Agendamento - Agendamento atualizado
     * @throws \Exception
     */
    public function finalizarConsulta(int $agendamentoId): Agendamento
    {
        $agendamento = Agendamento::findOrFail($agendamentoId);

        // Validar se pode finalizar
        if (!$agendamento->podeFinalizarConsulta()) {
            throw new \Exception('Esta consulta não pode ser finalizada.');
        }

        // Calcular duração real
        $duracaoReal = $agendamento->calcularDuracaoReal();

        // Atualizar agendamento
        $agendamento->update([
            'data_fim_consulta' => now(),
            'duracao_real_minutos' => $duracaoReal,
            'status' => 'concluido',
        ]);

        return $agendamento->fresh();
    }

    /**
     * Cria prontuário vinculado ao agendamento.
     * 
     * @param Agendamento $agendamento
     * @return Prontuario - Prontuário criado
     */
    private function criarProntuarioVinculado(Agendamento $agendamento): Prontuario
    {
        return Prontuario::create([
            'paciente_id' => $agendamento->paciente_id,
            'profissional_id' => $agendamento->profissional_id,
            'agendamento_id' => $agendamento->id,
            'data_atendimento' => $agendamento->data_inicio_consulta,
            'queixa_principal' => $agendamento->motivo_consulta,
            'observacoes_gerais' => $agendamento->observacoes,
            'finalizado' => false,
        ]);
    }

    /**
     * Retorna tempo decorrido da consulta em andamento (em minutos).
     * 
     * @param Agendamento $agendamento
     * @return int|null - Tempo em minutos ou null se não estiver em andamento
     */
    public function tempoDecorrido(Agendamento $agendamento): ?int
    {
        if ($agendamento->emAtendimento()) {
            return $agendamento->data_inicio_consulta->diffInMinutes(now());
        }
        return null;
    }

    /**
     * Retorna tempo decorrido formatado (HH:MM:SS).
     * 
     * @param Agendamento $agendamento
     * @return string|null - Tempo formatado ou null
     */
    public function tempoDecorridoFormatado(Agendamento $agendamento): ?string
    {
        if (!$agendamento->emAtendimento()) {
            return null;
        }

        $minutos = $this->tempoDecorrido($agendamento);
        $horas = intdiv($minutos, 60);
        $mins = $minutos % 60;
        $segs = 0; // Para ter formato HH:MM:SS

        return sprintf('%02d:%02d:%02d', $horas, $mins, $segs);
    }

    /**
     * Retorna hora de término estimada.
     * 
     * @param Agendamento $agendamento
     * @return Carbon|null
     */
    public function horaTerminoEstimada(Agendamento $agendamento): ?Carbon
    {
        if ($agendamento->data_inicio_consulta) {
            return $agendamento->data_inicio_consulta->addMinutes($agendamento->duracao_minutos);
        }
        return null;
    }

    /**
     * Verifica se consultoria está ultrapassando tempo estimado.
     * 
     * @param Agendamento $agendamento
     * @return bool
     */
    public function ultrapassouTempoEstimado(Agendamento $agendamento): bool
    {
        if ($agendamento->emAtendimento()) {
            $horaEstimada = $this->horaTerminoEstimada($agendamento);
            return now()->isAfter($horaEstimada);
        }
        return false;
    }

    /**
     * Retorna minutos restantes até o término estimado.
     * 
     * @param Agendamento $agendamento
     * @return int|null - Minutos restantes ou null
     */
    public function minutosRestantes(Agendamento $agendamento): ?int
    {
        if ($agendamento->emAtendimento()) {
            $horaEstimada = $this->horaTerminoEstimada($agendamento);
            if (now()->isBefore($horaEstimada)) {
                return now()->diffInMinutes($horaEstimada);
            }
            return 0;
        }
        return null;
    }

    /**
     * Retorna minutos restantes formatados.
     * 
     * @param Agendamento $agendamento
     * @return string|null
     */
    public function minutosRestantesFormatado(Agendamento $agendamento): ?string
    {
        if (!$agendamento->emAtendimento()) {
            return null;
        }

        $minutos = $this->minutosRestantes($agendamento);

        if ($minutos === 0) {
            return '<span class="text-red-600 font-bold">⏰ Tempo excedido!</span>';
        }

        $horas = intdiv($minutos, 60);
        $mins = $minutos % 60;

        if ($horas > 0) {
            return "{$horas}h {$mins}m restantes";
        }
        return "{$mins}m restantes";
    }

    /**
     * Lista consultas em andamento do profissional.
     * 
     * @param int $profissionalId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function consultasEmAndamentoProfissional(int $profissionalId)
    {
        return Agendamento::with(['paciente', 'profissional', 'prontuario'])
            ->where('profissional_id', $profissionalId)
            ->emAndamento()
            ->orderBy('data_inicio_consulta')
            ->get();
    }

    /**
     * Lista consultas finalizadas do profissional.
     * 
     * @param int $profissionalId
     * @param int $limite - Número máximo de registros
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function consultasFinalizadasProfissional(int $profissionalId, int $limite = 10)
    {
        return Agendamento::with(['paciente', 'profissional', 'prontuario'])
            ->where('profissional_id', $profissionalId)
            ->consultasFinalizadas()
            ->orderByDesc('data_fim_consulta')
            ->limit($limite)
            ->get();
    }

    /**
     * Retorna estatísticas de consultas do profissional.
     * 
     * @param int $profissionalId
     * @return array
     */
    public function estatisticasProfissional(int $profissionalId): array
    {
        $emAndamento = Agendamento::where('profissional_id', $profissionalId)
            ->emAndamento()
            ->count();

        $finalizadas = Agendamento::where('profissional_id', $profissionalId)
            ->consultasFinalizadas()
            ->whereDate('data_fim_consulta', today())
            ->count();

        $canceladas = Agendamento::where('profissional_id', $profissionalId)
            ->where('status', 'cancelado')
            ->whereDate('updated_at', today())
            ->count();

        $duracao_media = Agendamento::where('profissional_id', $profissionalId)
            ->consultasFinalizadas()
            ->whereDate('data_fim_consulta', today())
            ->avg('duracao_real_minutos') ?? 0;

        return [
            'em_andamento' => $emAndamento,
            'finalizadas_hoje' => $finalizadas,
            'canceladas_hoje' => $canceladas,
            'duracao_media_minutos' => round($duracao_media, 2),
        ];
    }

    /**
     * Retorna estatísticas gerais de consultas.
     * 
     * @return array
     */
    public function estatisticasGerais(): array
    {
        $emAndamento = Agendamento::emAndamento()->count();
        $finalizadasHoje = Agendamento::consultasFinalizadas()
            ->whereDate('data_fim_consulta', today())
            ->count();
        $canceladasHoje = Agendamento::where('status', 'cancelado')
            ->whereDate('updated_at', today())
            ->count();
        $duracao_media = Agendamento::consultasFinalizadas()
            ->whereDate('data_fim_consulta', today())
            ->avg('duracao_real_minutos') ?? 0;

        return [
            'em_andamento' => $emAndamento,
            'finalizadas_hoje' => $finalizadasHoje,
            'canceladas_hoje' => $canceladasHoje,
            'duracao_media_minutos' => round($duracao_media, 2),
        ];
    }

    /**
     * Calcula duração total de consultas em um período.
     * 
     * @param string $dataInicio
     * @param string $dataFim
     * @param int|null $profissionalId
     * @return int - Minutos totais
     */
    public function duracaoTotalPeriodo(
        string $dataInicio,
        string $dataFim,
        ?int $profissionalId = null
    ): int {
        $query = Agendamento::consultasFinalizadas()
            ->whereBetween('data_fim_consulta', [
                $dataInicio . ' 00:00:00',
                $dataFim . ' 23:59:59'
            ]);

        if ($profissionalId) {
            $query->where('profissional_id', $profissionalId);
        }

        return (int) $query->sum('duracao_real_minutos');
    }

    /**
     * Retorna relatório completo de consultas do profissional em um período.
     * 
     * @param int $profissionalId
     * @param string $dataInicio
     * @param string $dataFim
     * @return array
     */
    public function relatorioProfissionalPeriodo(
        int $profissionalId,
        string $dataInicio,
        string $dataFim
    ): array {
        $consultas = Agendamento::with(['paciente', 'prontuario'])
            ->where('profissional_id', $profissionalId)
            ->whereBetween('data_hora_agendamento', [
                $dataInicio . ' 00:00:00',
                $dataFim . ' 23:59:59'
            ])
            ->orderByDesc('data_hora_agendamento')
            ->get();

        $finalizadas = $consultas->filter(fn($c) => $c->status === 'concluido')->count();
        $canceladas = $consultas->filter(fn($c) => $c->status === 'cancelado')->count();
        $duracao_total = $consultas
            ->filter(fn($c) => $c->status === 'concluido')
            ->sum('duracao_real_minutos');

        return [
            'periodo' => "{$dataInicio} a {$dataFim}",
            'total_agendamentos' => $consultas->count(),
            'finalizadas' => $finalizadas,
            'canceladas' => $canceladas,
            'duracao_total_minutos' => $duracao_total,
            'duracao_media_minutos' => $finalizadas > 0 ? round($duracao_total / $finalizadas, 2) : 0,
            'consultas' => $consultas,
        ];
    }
}