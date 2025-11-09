<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $estatisticas = $this->dashboardService->obterEstatisticasGerais();
        $proximosAgendamentos = $this->dashboardService->obterProximosAgendamentos(5);
        $pacientesRecentes = $this->dashboardService->obterPacientesRecentes(5);
        $graficoAgendamentos = $this->dashboardService->obterDadosGraficoAgendamentos();
        $graficoPacientes = $this->dashboardService->obterDadosGraficoPacientes();

        return view('dashboard.index', compact(
            'estatisticas',
            'proximosAgendamentos',
            'pacientesRecentes',
            'graficoAgendamentos',
            'graficoPacientes'
        ));
    }
}