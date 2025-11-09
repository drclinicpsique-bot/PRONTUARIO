<?php

namespace App\Services;

use App\Models\Paciente;
use App\Models\Agendamento;
use App\Models\Profissional;
use App\Models\Prontuario;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * SERVICE: DashboardService
 * 
 * Centraliza lógica de negócio para o Dashboard.
 * Calcula estatísticas, gera dados para gráficos.
 */
class DashboardService
{
    /**
     * Retorna estatísticas gerais do sistema.
     * 
     * @return array
     */
    public function obterEstatisticasGerais(): array
    {
        return [
            'total_pacientes' => Paciente::ativos()->count(),
            'total_profissionais' => Profissional::ativos()->count(),
            'agendamentos_hoje' => Agendamento::hoje()
                ->whereIn('status', ['agendado', 'confirmado', 'em_atendimento'])
                ->count(),
            'agendamentos_mes' => Agendamento::whereMonth('data_hora_agendamento', now()->month)
                ->whereYear('data_hora_agendamento', now()->year)
                ->count(),
            'prontuarios_mes' => Prontuario::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'taxa_comparecimento' => $this->calcularTaxaComparecimento(),
        ];
    }

    /**
     * Calcula taxa de comparecimento do mês atual.
     * 
     * @return float
     */
    private function calcularTaxaComparecimento(): float
    {
        $totalAgendamentos = Agendamento::whereMonth('data_hora_agendamento', now()->month)
            ->whereYear('data_hora_agendamento', now()->year)
            ->where('data_hora_agendamento', '<', now())
            ->count();

        if ($totalAgendamentos === 0) {
            return 0;
        }

        $comparecimentos = Agendamento::whereMonth('data_hora_agendamento', now()->month)
            ->whereYear('data_hora_agendamento', now()->year)
            ->where('data_hora_agendamento', '<', now())
            ->where('status', 'concluido')
            ->count();

        return round(($comparecimentos / $totalAgendamentos) * 100, 1);
    }

    /**
     * Retorna próximos agendamentos.
     * 
     * @param int $limite
     * @return Collection
     */
    public function obterProximosAgendamentos(int $limite = 5): Collection
    {
        return Agendamento::with(['paciente', 'profissional.usuario'])
            ->futuros()
            ->whereIn('status', ['agendado', 'confirmado'])
            ->orderBy('data_hora_agendamento', 'asc')
            ->limit($limite)
            ->get();
    }

    /**
     * Retorna pacientes cadastrados recentemente.
     * 
     * @param int $limite
     * @return Collection
     */
    public function obterPacientesRecentes(int $limite = 5): Collection
    {
        return Paciente::ativos()
            ->orderBy('created_at', 'desc')
            ->limit($limite)
            ->get();
    }

    /**
     * Retorna dados para gráfico de agendamentos (últimos 7 dias).
     * 
     * @return array
     */
    public function obterDadosGraficoAgendamentos(): array
    {
        $dados = [];
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $data = Carbon::now()->subDays($i);
            $labels[] = $data->format('d/m');

            $dados[] = Agendamento::whereDate('data_hora_agendamento', $data)
                ->count();
        }

        return [
            'labels' => $labels,
            'dados' => $dados,
        ];
    }

    /**
     * Retorna dados para gráfico de pacientes cadastrados (últimos 6 meses).
     * 
     * @return array
     */
    public function obterDadosGraficoPacientes(): array
    {
        $dados = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $data = Carbon::now()->subMonths($i);
            $labels[] = $data->translatedFormat('M/Y');

            $dados[] = Paciente::whereMonth('created_at', $data->month)
                ->whereYear('created_at', $data->year)
                ->count();
        }

        return [
            'labels' => $labels,
            'dados' => $dados,
        ];
    }

    /**
     * Retorna distribuição de agendamentos por status (mês atual).
     * 
     * @return array
     */
    public function obterDistribuicaoStatus(): array
    {
        $distribuicao = Agendamento::whereMonth('data_hora_agendamento', now()->month)
            ->whereYear('data_hora_agendamento', now()->year)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return [
            'labels' => array_keys($distribuicao),
            'dados' => array_values($distribuicao),
        ];
    }
}