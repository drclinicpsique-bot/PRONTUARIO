@extends('layouts.app')

@section('title', 'Dashboard - Visão Geral')

@section('content')
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

    {{-- CABEÇALHO --}}
    <div class="mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h2 class="text-3xl font-extrabold text-amber-800">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </h2>
                <p class="text-gray-600 mt-2">
                    Bem-vindo(a), <strong>{{ auth()->user()->nome_completo }}</strong>! Aqui está a visão geral da clínica.
                </p>
                <p class="text-sm text-gray-500 mt-1">
                    <i class="far fa-clock mr-1"></i> Atualizado em {{ now()->format('d/m/Y H:i') }}
                </p>
            </div>
            <div class="hidden md:block mt-4 md:mt-0">
                <div class="flex items-center space-x-2 bg-amber-100 rounded-lg px-4 py-3 border-2 border-amber-300">
                    <i class="fas fa-shield-alt text-amber-700 text-2xl"></i>
                    <div>
                        <p class="text-xs text-amber-700 font-medium">Perfil Ativo</p>
                        <p class="text-sm font-bold text-amber-900">{{ auth()->user()->tipo_usuario_label }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CARDS DE ESTATÍSTICAS PRINCIPAIS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        {{-- Card: Total de Pacientes --}}
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-amber-500 hover:shadow-lg transition-all duration-150 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">
                        Total de Pacientes
                    </p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">
                        {{ number_format($estatisticas['total_pacientes'], 0, ',', '.') }}
                    </p>
                    <a href="{{ route('pacientes.index') }}" class="text-xs text-amber-600 hover:text-amber-800 mt-2 inline-block font-medium">
                        Ver todos <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="flex-shrink-0 bg-amber-100 rounded-full p-4">
                    <i class="fas fa-users text-amber-600 text-2xl"></i>
                </div>
            </div>
        </div>

        {{-- Card: Profissionais Ativos --}}
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition-all duration-150 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">
                        Profissionais Ativos
                    </p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">
                        {{ number_format($estatisticas['total_profissionais'], 0, ',', '.') }}
                    </p>
                    <a href="{{ route('profissionais.index') }}" class="text-xs text-blue-600 hover:text-blue-800 mt-2 inline-block font-medium">
                        Gerenciar <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="flex-shrink-0 bg-blue-100 rounded-full p-4">
                    <i class="fas fa-user-md text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        {{-- Card: Consultas Hoje --}}
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition-all duration-150 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">
                        Consultas Hoje
                    </p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">
                        {{ $estatisticas['agendamentos_hoje'] }}
                    </p>
                    <a href="{{ route('agendamentos.index') }}" class="text-xs text-green-600 hover:text-green-800 mt-2 inline-block font-medium">
                        Ver agenda <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="flex-shrink-0 bg-green-100 rounded-full p-4">
                    <i class="fas fa-calendar-check text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        {{-- Card: Taxa de Comparecimento --}}
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500 hover:shadow-lg transition-all duration-150 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">
                        Taxa de Comparecimento
                    </p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">
                        {{ $estatisticas['taxa_comparecimento'] }}%
                    </p>
                    <p class="text-xs text-gray-500 mt-2">
                        <i class="fas fa-chart-line mr-1"></i> Mês atual
                    </p>
                </div>
                <div class="flex-shrink-0 bg-purple-100 rounded-full p-4">
                    <i class="fas fa-percentage text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- ESTATÍSTICAS SECUNDÁRIAS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        
        {{-- Card: Agendamentos do Mês --}}
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-150">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-calendar-alt text-amber-600 mr-2"></i>
                    Agendamentos do Mês
                </h3>
                <span class="text-2xl font-bold text-amber-600">
                    {{ $estatisticas['agendamentos_mes'] }}
                </span>
            </div>
            <div class="flex items-center text-sm text-gray-600">
                <i class="fas fa-info-circle mr-2"></i>
                Total de consultas agendadas em {{ now()->locale('pt_BR')->translatedFormat('F/Y') }}
            </div>
        </div>

        {{-- Card: Prontuários Criados --}}
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-150">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-notes-medical text-amber-600 mr-2"></i>
                    Prontuários Criados
                </h3>
                <span class="text-2xl font-bold text-amber-600">
                    {{ $estatisticas['prontuarios_mes'] }}
                </span>
            </div>
            <div class="flex items-center text-sm text-gray-600">
                <i class="fas fa-info-circle mr-2"></i>
                Prontuários registrados em {{ now()->locale('pt_BR')->translatedFormat('F/Y') }}
            </div>
        </div>

    </div>

    {{-- GRID: GRÁFICOS E LISTAS --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        {{-- GRÁFICO: Agendamentos dos Últimos 7 Dias --}}
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-150">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-chart-line text-amber-600 mr-2"></i>
                Agendamentos dos Últimos 7 Dias
            </h3>
            <div class="relative h-64">
                <canvas id="graficoAgendamentos"></canvas>
            </div>
        </div>

        {{-- LISTA: Próximos Agendamentos --}}
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-150">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-clock text-amber-600 mr-2"></i>
                    Próximos Agendamentos
                </h3>
                <a href="{{ route('agendamentos.index') }}" class="text-xs text-amber-600 hover:text-amber-800 font-medium">
                    Ver todos
                </a>
            </div>

            @if($proximosAgendamentos->count() > 0)
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @foreach($proximosAgendamentos as $agendamento)
                        <div class="border-l-4 border-amber-300 pl-3 py-2 hover:bg-amber-50 transition-colors duration-150 rounded-r">
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $agendamento->paciente->nome_completo }}
                            </p>
                            <p class="text-xs text-gray-600 mt-1">
                                <i class="far fa-clock mr-1"></i>
                                {{ \Carbon\Carbon::parse($agendamento->data_hora_agendamento)->format('d/m/Y H:i') }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-user-md mr-1"></i>
                                Dr(a). {{ $agendamento->profissional->usuario->nome_completo }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-calendar-times text-4xl mb-2"></i>
                    <p class="text-sm">Nenhum agendamento futuro</p>
                </div>
            @endif
        </div>

    </div>

    {{-- GRID: GRÁFICO DE PACIENTES E LISTA DE RECENTES --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        {{-- GRÁFICO: Pacientes Cadastrados (6 Meses) --}}
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-150">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-chart-bar text-amber-600 mr-2"></i>
                Pacientes Cadastrados (Últimos 6 Meses)
            </h3>
            <div class="relative h-64">
                <canvas id="graficoPacientes"></canvas>
            </div>
        </div>

        {{-- LISTA: Pacientes Recentes --}}
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-150">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-user-plus text-amber-600 mr-2"></i>
                    Pacientes Recentes
                </h3>
                <a href="{{ route('pacientes.index') }}" class="text-xs text-amber-600 hover:text-amber-800 font-medium">
                    Ver todos
                </a>
            </div>

            @if($pacientesRecentes->count() > 0)
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @foreach($pacientesRecentes as $paciente)
                        <div class="flex items-center border-l-4 border-green-300 pl-3 py-2 hover:bg-green-50 transition-colors duration-150 rounded-r">
                            <div class="flex-shrink-0 h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-green-600 text-sm"></i>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $paciente->nome_completo }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    <i class="far fa-clock mr-1"></i>
                                    Cadastrado {{ $paciente->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <a href="{{ route('pacientes.show', $paciente->id) }}" class="text-green-600 hover:text-green-800">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-user-slash text-4xl mb-2"></i>
                    <p class="text-sm">Nenhum paciente recente</p>
                </div>
            @endif
        </div>

    </div>

    {{-- ATALHOS RÁPIDOS --}}
    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-150">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-rocket text-amber-600 mr-2"></i>
            Atalhos Rápidos
        </h3>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            
            {{-- Novo Paciente --}}
            <a href="{{ route('pacientes.create') }}"
               class="flex flex-col items-center justify-center p-6 bg-amber-50 rounded-lg border-2 border-amber-200 hover:bg-amber-100 hover:border-amber-400 transition-all duration-150 group">
                <i class="fas fa-user-plus text-3xl text-amber-600 mb-2 group-hover:scale-110 transition-transform duration-150"></i>
                <span class="text-sm font-medium text-gray-800">Novo Paciente</span>
            </a>

            {{-- Novo Agendamento --}}
            <a href="{{ route('agendamentos.create') }}"
               class="flex flex-col items-center justify-center p-6 bg-green-50 rounded-lg border-2 border-green-200 hover:bg-green-100 hover:border-green-400 transition-all duration-150 group">
                <i class="fas fa-calendar-plus text-3xl text-green-600 mb-2 group-hover:scale-110 transition-transform duration-150"></i>
                <span class="text-sm font-medium text-gray-800">Novo Agendamento</span>
            </a>

            {{-- Ver Agenda --}}
            <a href="{{ route('agendamentos.index') }}"
               class="flex flex-col items-center justify-center p-6 bg-blue-50 rounded-lg border-2 border-blue-200 hover:bg-blue-100 hover:border-blue-400 transition-all duration-150 group">
                <i class="fas fa-calendar-alt text-3xl text-blue-600 mb-2 group-hover:scale-110 transition-transform duration-150"></i>
                <span class="text-sm font-medium text-gray-800">Ver Agenda</span>
            </a>

            {{-- Exames --}}
            <a href="{{ route('exames.index') }}"
               class="flex flex-col items-center justify-center p-6 bg-purple-50 rounded-lg border-2 border-purple-200 hover:bg-purple-100 hover:border-purple-400 transition-all duration-150 group">
                <i class="fas fa-microscope text-3xl text-purple-600 mb-2 group-hover:scale-110 transition-transform duration-150"></i>
                <span class="text-sm font-medium text-gray-800">Exames</span>
            </a>

        </div>
    </div>

</div>
@endsection

{{-- SCRIPTS PARA GRÁFICOS --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    // Configuração global do Chart.js
    Chart.defaults.font.family = "'Inter', 'system-ui', 'sans-serif'";
    Chart.defaults.color = '#6b7280';

    /**
     * GRÁFICO: Agendamentos dos Últimos 7 Dias (Linha)
     */
    const ctxAgendamentos = document.getElementById('graficoAgendamentos').getContext('2d');
    const graficoAgendamentos = new Chart(ctxAgendamentos, {
        type: 'line',
        data: {
            labels: @json($graficoAgendamentos['labels']),
            datasets: [{
                label: 'Agendamentos',
                data: @json($graficoAgendamentos['dados']),
                borderColor: '#f59e0b',
                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: '#f59e0b',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    /**
     * GRÁFICO: Pacientes Cadastrados (6 Meses) - Barra
     */
    const ctxPacientes = document.getElementById('graficoPacientes').getContext('2d');
    const graficoPacientes = new Chart(ctxPacientes, {
        type: 'bar',
        data: {
            labels: @json($graficoPacientes['labels']),
            datasets: [{
                label: 'Pacientes Cadastrados',
                data: @json($graficoPacientes['dados']),
                backgroundColor: [
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(251, 191, 36, 0.8)',
                    'rgba(217, 119, 6, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(251, 191, 36, 0.8)',
                    'rgba(217, 119, 6, 0.8)',
                ],
                borderColor: [
                    '#f59e0b',
                    '#fbbf24',
                    '#d97706',
                    '#f59e0b',
                    '#fbbf24',
                    '#d97706',
                ],
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endpush

{{-- ESTILOS CUSTOMIZADOS --}}
@push('styles')
<style>
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }

    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #d97706;
        border-radius: 10px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #b45309;
    }

    .group:hover i {
        animation: bounce 0.5s ease-in-out;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0) scale(1.1); }
        50% { transform: translateY(-5px) scale(1.1); }
    }
</style>
@endpush