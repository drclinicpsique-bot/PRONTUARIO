@extends('layouts.app')

@section('title', 'Perfil do Paciente')

@section('content')

    @if (!isset($paciente))
        <div class="max-w-7xl mx-auto py-10">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <strong>Erro!</strong> Paciente não encontrado.
            </div>
        </div>
    @else
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

            {{-- CABEÇALHO --}}
            <div class="mb-6 pb-4 border-b-4 border-amber-900">
                <h2 class="text-3xl font-bold text-amber-900">{{ $paciente->nome_completo }}</h2>
                <p class="text-amber-700 mt-1">
                    CPF: {{ $paciente->cpf_formatado }} |
                    Nasc.: {{ $paciente->data_nascimento_formatada }} ({{ $paciente->idade }} anos)
                </p>
            </div>

            {{-- BOTÕES DE AÇÃO --}}
            <div class="mb-6 flex gap-2">
                <a href="{{ route('pacientes.edit', $paciente->id) }}"
                    class="inline-flex items-center px-4 py-2 bg-amber-800 text-yellow-50 rounded hover:bg-amber-900 transition">
                    <i class="fas fa-edit mr-2"></i> Editar
                </a>
                <a href="{{ route('pacientes.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-yellow-700 text-yellow-50 rounded hover:bg-yellow-800 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Voltar
                </a>
            </div>

            {{-- DADOS BÁSICOS --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-yellow-50 p-6 rounded shadow-lg border-l-4 border-amber-900">
                    <h3 class="text-xl font-bold text-amber-900 mb-4">
                        <i class="fas fa-user-circle mr-2"></i> Dados do Paciente
                    </h3>

                    <div class="space-y-3 text-sm">
                        @if ($paciente->email)
                            <div>
                                <span class="font-semibold text-amber-900">Email:</span>
                                <p class="text-amber-800">{{ $paciente->email }}</p>
                            </div>
                        @endif

                        @if ($paciente->telefone)
                            <div>
                                <span class="font-semibold text-amber-900">Telefone:</span>
                                <p class="text-amber-800">{{ $paciente->telefone }}</p>
                            </div>
                        @endif

                        @if ($paciente->telefone_emergencia)
                            <div>
                                <span class="font-semibold text-amber-900">Telefone Emergência:</span>
                                <p class="text-amber-800">{{ $paciente->telefone_emergencia }}</p>
                            </div>
                        @endif

                        @if ($paciente->endereco)
                            <div>
                                <span class="font-semibold text-amber-900">Endereço:</span>
                                <p class="text-amber-800">{{ $paciente->endereco }}</p>
                                @if ($paciente->cidade)
                                    <p class="text-amber-800">{{ $paciente->cidade }} - {{ $paciente->estado }} -
                                        {{ $paciente->cep }}</p>
                                @endif
                            </div>
                        @endif

                        @if ($paciente->observacoes_gerais)
                            <div>
                                <span class="font-semibold text-amber-900">Observações:</span>
                                <p class="text-amber-800">{{ $paciente->observacoes_gerais }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- RESUMO DE PRONTUÁRIOS --}}
                <div class="lg:col-span-2 bg-yellow-50 p-6 rounded shadow-lg border-l-4 border-yellow-700">
                    <h3 class="text-xl font-bold text-amber-900 mb-4">
                        <i class="fas fa-chart-bar mr-2"></i> Resumo Clínico
                    </h3>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="bg-gradient-to-br from-amber-100 to-yellow-100 p-4 rounded border-l-4 border-amber-800">
                            <p class="text-amber-900 font-bold text-2xl">{{ $paciente->prontuarios_count ?? 0 }}</p>
                            <p class="text-amber-800 text-sm">Total de Consultas</p>
                        </div>

                        <div
                            class="bg-gradient-to-br from-yellow-100 to-amber-100 p-4 rounded border-l-4 border-yellow-700">
                            <p class="text-yellow-900 font-bold text-2xl">
                                {{ $paciente->prontuarios->where('finalizado', true)->count() }}
                            </p>
                            <p class="text-yellow-800 text-sm">Consultas Finalizadas</p>
                        </div>
                    </div>

                    @if ($paciente->prontuarios && $paciente->prontuarios->count() > 0)
                        <div class="mt-4">
                            <p class="text-sm text-amber-900 font-semibold mb-2">Últimas Consultas:</p>
                            <div class="space-y-2">
                                @foreach ($paciente->prontuarios->take(3) as $prontuario)
                                    <div class="border-l-4 border-yellow-700 pl-3 py-1 bg-yellow-100 p-2 rounded">
                                        <p class="text-sm font-semibold text-amber-900">
                                            {{ $prontuario->data_atendimento->format('d/m/Y') }}</p>
                                        <p class="text-xs text-amber-800">Dr(a).
                                            {{ $prontuario->profissional->usuario->nome_completo }}</p>
                                        @if ($prontuario->queixa_principal)
                                            <p class="text-xs text-amber-700">
                                                {{ Str::limit($prontuario->queixa_principal, 60) }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="text-amber-700 text-sm">Nenhuma consulta registrada.</p>
                    @endif
                </div>
            </div>

            {{-- SEÇÃO: PRONTUÁRIOS COMPLETA --}}
            <div class="bg-yellow-50 rounded-lg shadow-lg p-6 border-2 border-amber-900">
                <h3 class="text-2xl font-bold text-amber-900 mb-6 pb-3 border-b-4 border-yellow-700">
                    <i class="fas fa-file-medical-alt mr-2"></i> Histórico Completo de Prontuários
                </h3>

                @if ($paciente->prontuarios && $paciente->prontuarios->count() > 0)
                    <div class="space-y-4">
                        @foreach ($paciente->prontuarios->sortByDesc('data_atendimento') as $prontuario)
                            <div
                                class="border-2 border-amber-200 rounded-lg p-6 hover:shadow-md transition bg-gradient-to-r from-yellow-50 to-amber-50">
                                <div
                                    class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-3">
                                    <div>
                                        <h4 class="text-lg font-semibold text-amber-900">
                                            <i class="fas fa-file-alt mr-2"></i> Prontuário #{{ $prontuario->id }}
                                        </h4>
                                        <p class="text-sm text-amber-800 mt-1">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $prontuario->data_atendimento->format('d/m/Y H:i') }}
                                        </p>
                                        <p class="text-sm text-amber-800">
                                            <i class="fas fa-stethoscope mr-1"></i>
                                            Dr(a). {{ $prontuario->profissional->usuario->nome_completo }}
                                            ({{ $prontuario->profissional->especialidade }})
                                        </p>
                                    </div>

                                    {{-- STATUS --}}
                                    <div>
                                        @if ($prontuario->finalizado)
                                            <span
                                                class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold bg-yellow-200 text-amber-900 border border-yellow-400">
                                                <i class="fas fa-check-circle mr-2"></i> Finalizado
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold bg-amber-100 text-amber-900 border border-amber-300">
                                                <i class="fas fa-hourglass-half mr-2"></i> Em Aberto
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- QUEIXA PRINCIPAL --}}
                                @if ($prontuario->queixa_principal)
                                    <div class="mb-3 pb-3 border-b-2 border-amber-200">
                                        <p class="text-sm text-amber-900"><strong>Queixa Principal:</strong>
                                            {{ $prontuario->queixa_principal }}</p>
                                    </div>
                                @endif

                                {{-- RESUMO DO PRONTUÁRIO --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    @if ($prontuario->hipotese_diagnostica)
                                        <div class="bg-amber-100 p-3 rounded text-sm border-l-4 border-amber-800">
                                            <strong class="text-amber-900">Diagnóstico:</strong>
                                            <p class="text-amber-800">{{ $prontuario->hipotese_diagnostica }}</p>
                                        </div>
                                    @endif

                                    @if ($prontuario->data_retorno)
                                        <div class="bg-yellow-100 p-3 rounded text-sm border-l-4 border-yellow-700">
                                            <strong class="text-yellow-900">Retorno:</strong>
                                            <p class="text-yellow-800">{{ $prontuario->data_retorno->format('d/m/Y') }}</p>
                                        </div>
                                    @endif
                                </div>
                                {{-- SEÇÃO: HISTÓRICO DE EXAMES --}}
                                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-amber-600">
                                    <div class="flex justify-between items-center mb-4 pb-2 border-b-2 border-amber-200">
                                        <h3 class="text-xl font-bold text-amber-900">
                                            <i class="fas fa-microscope mr-2"></i> Histórico de Exames
                                        </h3>
                                        @if ($exames->count() > 0)
                                            <span
                                                class="bg-amber-800 text-yellow-50 px-3 py-1 rounded-full text-sm font-bold">
                                                {{ $exames->count() }} {{ $exames->count() === 1 ? 'exame' : 'exames' }}
                                            </span>
                                        @endif
                                    </div>

                                    @if ($exames->count() > 0)
                                        {{-- ESTATÍSTICAS DE EXAMES --}}
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                            <div class="bg-blue-50 rounded-lg p-3 border-l-4 border-blue-500">
                                                <p class="text-xs text-blue-700 font-medium">Total</p>
                                                <p class="text-2xl font-bold text-blue-900">
                                                    {{ $estatisticasExames['total'] }}</p>
                                            </div>
                                            <div class="bg-amber-50 rounded-lg p-3 border-l-4 border-amber-500">
                                                <p class="text-xs text-amber-700 font-medium">Pendentes</p>
                                                <p class="text-2xl font-bold text-amber-900">
                                                    {{ $estatisticasExames['pendentes'] }}</p>
                                            </div>
                                            <div class="bg-green-50 rounded-lg p-3 border-l-4 border-green-500">
                                                <p class="text-xs text-green-700 font-medium">Concluídos</p>
                                                <p class="text-2xl font-bold text-green-900">
                                                    {{ $estatisticasExames['concluidos'] }}</p>
                                            </div>
                                            <div class="bg-purple-50 rounded-lg p-3 border-l-4 border-purple-500">
                                                <p class="text-xs text-purple-700 font-medium">Com Resultado</p>
                                                <p class="text-2xl font-bold text-purple-900">
                                                    {{ $estatisticasExames['com_resultado'] }}</p>
                                            </div>
                                        </div>

                                        {{-- FILTROS RÁPIDOS --}}
                                        <div class="mb-4 flex flex-wrap gap-2">
                                            <button onclick="filtrarExames('todos')"
                                                class="filtro-btn active px-3 py-1 text-xs rounded-full bg-gray-200 text-gray-800 hover:bg-gray-300 transition">
                                                Todos ({{ $exames->count() }})
                                            </button>
                                            <button onclick="filtrarExames('pendentes')"
                                                class="filtro-btn px-3 py-1 text-xs rounded-full bg-amber-100 text-amber-800 hover:bg-amber-200 transition">
                                                Pendentes ({{ $estatisticasExames['pendentes'] }})
                                            </button>
                                            <button onclick="filtrarExames('concluidos')"
                                                class="filtro-btn px-3 py-1 text-xs rounded-full bg-green-100 text-green-800 hover:bg-green-200 transition">
                                                Concluídos ({{ $estatisticasExames['concluidos'] }})
                                            </button>
                                        </div>

                                        {{-- TIMELINE DE EXAMES --}}
                                        <div class="space-y-3 max-h-96 overflow-y-auto">
                                            @foreach ($exames as $exame)
                                                <div class="exame-item {{ $exame->status }}"
                                                    data-status="{{ $exame->status == 'concluido' ? 'concluidos' : 'pendentes' }}">
                                                    <div
                                                        class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg border-l-4 {{ $exame->status == 'concluido' ? 'border-green-500' : ($exame->status == 'cancelado' ? 'border-red-500' : 'border-amber-500') }} hover:shadow-md transition">
                                                        <div class="flex-shrink-0 mt-1">
                                                            @if ($exame->status == 'concluido')
                                                                <div
                                                                    class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                                                    <i class="fas fa-check-circle text-green-600"></i>
                                                                </div>
                                                            @elseif($exame->status == 'em_analise')
                                                                <div
                                                                    class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
                                                                    <i class="fas fa-hourglass-half text-amber-600"></i>
                                                                </div>
                                                            @elseif($exame->status == 'cancelado')
                                                                <div
                                                                    class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                                                    <i class="fas fa-ban text-red-600"></i>
                                                                </div>
                                                            @else
                                                                <div
                                                                    class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                                    <i class="fas fa-clock text-blue-600"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="flex items-start justify-between">
                                                                <div>
                                                                    <p class="text-sm font-bold text-gray-900">
                                                                        {{ $exame->tipoExame->nome }}</p>
                                                                    <p class="text-xs text-gray-600 mt-1">
                                                                        <i class="fas fa-calendar mr-1"></i>
                                                                        Solicitado em:
                                                                        {{ $exame->data_solicitacao_formatada }}
                                                                    </p>
                                                                    <p class="text-xs text-gray-600">
                                                                        <i class="fas fa-user-md mr-1"></i>
                                                                        Dr(a).
                                                                        {{ $exame->profissionalSolicitante->usuario->nome_completo }}
                                                                    </p>
                                                                    @if ($exame->prontuario)
                                                                        <p class="text-xs text-gray-500">
                                                                            <i class="fas fa-file-medical mr-1"></i>
                                                                            Prontuário #{{ $exame->prontuario->id }}
                                                                        </p>
                                                                    @endif
                                                                </div>
                                                                <div class="flex flex-col items-end space-y-2">
                                                                    @php
                                                                        $statusColors = [
                                                                            'solicitado' => 'bg-blue-100 text-blue-800',
                                                                            'em_analise' =>
                                                                                'bg-amber-100 text-amber-900',
                                                                            'concluido' =>
                                                                                'bg-green-100 text-green-800',
                                                                            'cancelado' => 'bg-red-100 text-red-800',
                                                                        ];
                                                                        $colorClass =
                                                                            $statusColors[$exame->status] ??
                                                                            'bg-gray-100 text-gray-800';
                                                                    @endphp
                                                                    <span
                                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $colorClass }}">
                                                                        {{ ucfirst($exame->status) }}
                                                                    </span>
                                                                    <a href="{{ route('exames.show', $exame->id) }}"
                                                                        class="text-xs text-amber-700 hover:text-amber-900 font-bold">
                                                                        Ver detalhes <i
                                                                            class="fas fa-arrow-right ml-1"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            @if ($exame->resultado)
                                                                <div
                                                                    class="mt-2 p-2 bg-green-50 rounded border-l-2 border-green-400">
                                                                    <p class="text-xs font-bold text-green-900">
                                                                        <i class="fas fa-flask mr-1"></i> Resultado
                                                                        disponível
                                                                    </p>
                                                                    <p class="text-xs text-green-700">
                                                                        Realizado em:
                                                                        {{ $exame->resultado->data_realizacao_formatada }}
                                                                    </p>
                                                                    @if (!is_null($exame->resultado->valores_normais))
                                                                        <p class="text-xs mt-1">
                                                                            @if ($exame->resultado->valores_normais)
                                                                                <span class="text-green-700"><i
                                                                                        class="fas fa-check-circle mr-1"></i>Valores
                                                                                    normais</span>
                                                                            @else
                                                                                <span class="text-red-700"><i
                                                                                        class="fas fa-exclamation-triangle mr-1"></i>Valores
                                                                                    alterados</span>
                                                                            @endif
                                                                        </p>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-12">
                                            <i class="fas fa-flask text-gray-300 text-5xl mb-3"></i>
                                            <p class="text-gray-600 font-medium">Nenhum exame solicitado</p>
                                            <p class="text-gray-500 text-sm mt-1">Os exames solicitados aparecerão aqui</p>
                                        </div>
                                    @endif
                                </div>

                                {{-- BOTÕES DE AÇÃO --}}
                                <div class="flex flex-wrap gap-3 pt-3 border-t-2 border-amber-200">
                                    {{-- VISUALIZAR --}}
                                    <a href="{{ route('prontuarios.show', $prontuario->id) }}"
                                        class="inline-flex items-center px-4 py-2 bg-amber-800 text-yellow-50 text-sm rounded-md hover:bg-amber-900 transition">
                                        <i class="fas fa-eye mr-2"></i> Visualizar
                                    </a>

                                    {{-- PDF COMPLETO --}}
                                    <a href="{{ route('prontuarios.pdf.completo', $prontuario->id) }}"
                                        class="inline-flex items-center px-4 py-2 bg-yellow-700 text-yellow-50 text-sm rounded-md hover:bg-yellow-800 transition"
                                        target="_blank" download>
                                        <i class="fas fa-file-pdf mr-2"></i> PDF Completo
                                    </a>

                                    {{-- PRESCRIÇÃO --}}
                                    @if ($prontuario->prescricao_medicamentos)
                                        <a href="{{ route('prontuarios.pdf.prescricao', $prontuario->id) }}"
                                            class="inline-flex items-center px-4 py-2 bg-amber-700 text-yellow-50 text-sm rounded-md hover:bg-amber-800 transition"
                                            target="_blank" download>
                                            <i class="fas fa-prescription-bottle mr-2"></i> Prescrição
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-amber-50 border-2 border-amber-300 rounded-lg p-6 text-center">
                        <i class="fas fa-inbox text-amber-400 text-4xl mb-3"></i>
                        <p class="text-amber-900">📋 Nenhum prontuário registrado para este paciente</p>
                    </div>
                @endif
            </div>

        </div>
    @endif

@endsection

@push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
