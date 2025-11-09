@extends('layouts.app')
@include('agendamentos.cronometro')

@section('title', 'Agendamento - ' . $agendamento->paciente->nome_completo)

@section('content')
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

        {{-- CABEÇALHO --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h2 class="text-3xl font-extrabold text-amber-900">
                    <i class="fas fa-calendar-check mr-2"></i> Agendamento
                </h2>
                <p class="text-amber-700 mt-2">
                    ID: #{{ $agendamento->id }} - {{ $agendamento->data_hora_formatada }}
                </p>
            </div>

            <div class="mt-4 md:mt-0 flex flex-wrap gap-2">
                <a href="{{ route('agendamentos.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-yellow-700 text-yellow-50 text-sm rounded-md hover:bg-yellow-800 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Voltar
                </a>
            </div>
        </div>

        {{-- MENSAGENS --}}
        @if (session('success'))
            <div class="mb-6 bg-yellow-50 border-l-4 border-amber-600 p-4 rounded-lg shadow-md animate-fade-in">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-amber-700 text-xl mr-3"></i>
                    <p class="text-amber-900 font-semibold">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-md animate-fade-in">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl mr-3"></i>
                    <p class="text-red-800 font-semibold">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- STATUS --}}
        <div class="mb-6 bg-yellow-50 rounded-lg shadow-lg p-6 border-2 border-amber-900">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-amber-900">Status do Agendamento</h3>
                    <p class="text-amber-700 text-sm mt-1">Última atualização:
                        {{ $agendamento->updated_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    {!! $agendamento->status_badge !!}
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- COLUNA PRINCIPAL --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- CARD: IDENTIFICAÇÃO --}}
                <div class="bg-yellow-50 rounded-lg shadow-lg p-6 border-l-4 border-amber-900">
                    <h3 class="text-xl font-bold text-amber-900 mb-4 pb-2 border-b-2 border-amber-200">
                        <i class="fas fa-user-check mr-2"></i> Identificação
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-amber-700 mb-1">Paciente</label>
                            <p class="text-lg font-semibold text-amber-900">{{ $agendamento->paciente->nome_completo }}</p>
                            <p class="text-sm text-amber-800">{{ $agendamento->paciente->cpf_formatado }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-amber-700 mb-1">Profissional</label>
                            <p class="text-lg font-semibold text-amber-900">Dr(a).
                                {{ $agendamento->profissional->usuario->nome_completo }}</p>
                            <p class="text-sm text-amber-800">{{ $agendamento->profissional->especialidade }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-amber-700 mb-1">Data do Agendamento</label>
                            <p class="text-lg font-semibold text-amber-900">{{ $agendamento->data_hora_formatada }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-amber-700 mb-1">Duração Estimada</label>
                            <p class="text-lg font-semibold text-amber-900">{{ $agendamento->duracao_minutos }} minutos</p>
                        </div>
                    </div>
                </div>

                {{-- CARD: DETALHES DA CONSULTA --}}
                <div class="bg-yellow-50 rounded-lg shadow-lg p-6 border-l-4 border-yellow-700">
                    <h3 class="text-xl font-bold text-amber-900 mb-4 pb-2 border-b-2 border-amber-200">
                        <i class="fas fa-stethoscope mr-2"></i> Detalhes da Consulta
                    </h3>

                    <div class="space-y-4">
                        @if ($agendamento->motivo_consulta)
                            <div>
                                <label class="block text-sm font-medium text-amber-700 mb-1">Motivo da Consulta</label>
                                <p class="text-amber-900 bg-white p-3 rounded border-l-4 border-amber-800">
                                    {{ $agendamento->motivo_consulta }}</p>
                            </div>
                        @endif

                        @if ($agendamento->observacoes)
                            <div>
                                <label class="block text-sm font-medium text-amber-700 mb-1">Observações</label>
                                <p
                                    class="text-amber-900 bg-white p-3 rounded border-l-4 border-amber-800 whitespace-pre-wrap">
                                    {{ $agendamento->observacoes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- CARD: TEMPO DA CONSULTA (Se em andamento) --}}
                @if ($agendamento->emAtendimento())
                    <div
                        class="bg-gradient-to-br from-amber-50 to-yellow-50 rounded-lg shadow-lg p-6 border-2 border-amber-400">
                        <h3 class="text-xl font-bold text-amber-900 mb-4 pb-2 border-b-2 border-amber-300">
                            <i class="fas fa-hourglass-half mr-2"></i> Consulta em Andamento
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-white p-4 rounded border-l-4 border-amber-800">
                                <label class="block text-sm font-medium text-amber-700 mb-1">Iniciada em</label>
                                <p class="text-lg font-semibold text-amber-900">
                                    {{ $agendamento->data_inicio_consulta->format('H:i:s') }}</p>
                            </div>

                            <div class="bg-white p-4 rounded border-l-4 border-yellow-700">
                                <label class="block text-sm font-medium text-amber-700 mb-1">Tempo Decorrido</label>
                                <p class="text-lg font-semibold text-amber-900 font-mono" id="tempo-decorrido">00:00:00</p>
                            </div>

                            <div class="bg-white p-4 rounded border-l-4 border-amber-600">
                                <label class="block text-sm font-medium text-amber-700 mb-1">Tempo Restante
                                    (estimado)</label>
                                <p class="text-lg font-semibold text-amber-900" id="tempo-restante">
                                    @if ($agendamento->minutosRestantes() > 0)
                                        {{ $agendamento->minutos_restantes_formatado }}
                                    @else
                                        <span class="text-red-600">Tempo excedido!</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-amber-100 rounded border-2 border-amber-400">
                            <p class="text-sm text-amber-900">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Hora de Término Estimada:</strong>
                                {{ $agendamento->hora_termino_estimada_formatada }}
                            </p>
                        </div>
                    </div>
                @endif

                {{-- CARD: PRONTUÁRIO VINCULADO --}}
                @if ($agendamento->prontuario)
                    <div class="bg-yellow-50 rounded-lg shadow-lg p-6 border-2 border-yellow-700">
                        <h3 class="text-xl font-bold text-amber-900 mb-4 pb-2 border-b-2 border-yellow-400">
                            <i class="fas fa-file-medical mr-2"></i> Prontuário Vinculado
                        </h3>

                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-amber-700 mb-1">ID do Prontuário</label>
                                <p class="text-lg font-semibold text-amber-900">#{{ $agendamento->prontuario->id }}</p>
                            </div>

                            <div class="flex gap-3 pt-4">
                                <a href="{{ route('prontuarios.show', $agendamento->prontuario->id) }}"
                                    class="inline-flex items-center px-4 py-2 bg-amber-800 text-yellow-50 text-sm rounded hover:bg-amber-900 transition">
                                    <i class="fas fa-eye mr-2"></i> Visualizar Prontuário
                                </a>

                                @if (!$agendamento->prontuario->finalizado)
                                    <a href="{{ route('prontuarios.edit', $agendamento->prontuario->id) }}"
                                        class="inline-flex items-center px-4 py-2 bg-yellow-700 text-yellow-50 text-sm rounded hover:bg-yellow-800 transition">
                                        <i class="fas fa-edit mr-2"></i> Editar Prontuário
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            {{-- COLUNA LATERAL --}}
            <div class="lg:col-span-1 space-y-6">

                {{-- CARD: AÇÕES RÁPIDAS --}}
                <div class="bg-amber-50 rounded-lg shadow-lg p-6 border-2 border-amber-200">
                    <h3 class="text-lg font-bold text-amber-900 mb-4">
                        <i class="fas fa-bolt mr-2"></i> Ações Rápidas
                    </h3>

                    <div class="space-y-3">

                        {{-- STATUS: CONCLUÍDO --}}
                        @if ($agendamento->status == 'concluido')
                            <div class="bg-green-100 border-2 border-green-400 rounded p-4 text-center">
                                <i class="fas fa-check-circle text-green-600 text-3xl mb-2"></i>
                                <p class="text-sm text-green-900 font-bold">✓ Consulta Finalizada</p>
                                @if ($agendamento->duracao_minutos)
                                    <p class="text-xs text-green-700 mt-1">
                                        Duração: {{ $agendamento->duracao_minutos }} minutos
                                    </p>
                                @endif
                                @if ($agendamento->data_hora_fim)
                                    <p class="text-xs text-green-700">
                                        Finalizada em: {{ $agendamento->data_hora_fim->format('d/m/Y H:i') }}
                                    </p>
                                @endif
                            </div>

                            {{-- BOTÕES APÓS FINALIZAÇÃO --}}
                            @if ($agendamento->prontuario)
                                <a href="{{ route('prontuarios.show', $agendamento->prontuario->id) }}"
                                    class="block w-full text-center px-4 py-2 bg-amber-800 text-yellow-50 text-sm rounded hover:bg-amber-900 transition">
                                    <i class="fas fa-file-medical-alt mr-2"></i> Ver Prontuário
                                </a>

                                @if ($agendamento->prontuario->prescricao_medicamentos)
                                    <a href="{{ route('prontuarios.pdf.prescricao', $agendamento->prontuario->id) }}"
                                        class="block w-full text-center px-4 py-2 bg-yellow-700 text-yellow-50 text-sm rounded hover:bg-yellow-800 transition"
                                        target="_blank" download>
                                        <i class="fas fa-prescription-bottle mr-2"></i> Baixar Prescrição
                                    </a>
                                @endif

                                <a href="{{ route('prontuarios.pdf.completo', $agendamento->prontuario->id) }}"
                                    class="block w-full text-center px-4 py-2 bg-yellow-700 text-yellow-50 text-sm rounded hover:bg-yellow-800 transition"
                                    target="_blank" download>
                                    <i class="fas fa-file-pdf mr-2"></i> Baixar PDF Completo
                                </a>
                            @endif

                            {{-- STATUS: CANCELADO --}}
                        @elseif($agendamento->status == 'cancelado')
                            <div class="bg-red-100 border-2 border-red-400 rounded p-4 text-center">
                                <i class="fas fa-ban text-red-600 text-3xl mb-2"></i>
                                <p class="text-sm text-red-900 font-bold">Consulta Cancelada</p>
                                @if ($agendamento->motivo_cancelamento)
                                    <p class="text-xs text-red-700 mt-2">
                                        Motivo: {{ $agendamento->motivo_cancelamento }}
                                    </p>
                                @endif
                            </div>

                            {{-- STATUS: EM ATENDIMENTO --}}
                        @elseif($agendamento->emAtendimento())
                            <div
                                class="bg-yellow-100 border-2 border-yellow-400 rounded p-3 text-sm text-amber-900 mb-3 font-semibold text-center">
                                <i class="fas fa-hourglass-half mr-2 animate-pulse"></i>
                                ✓ Consulta em Andamento
                            </div>

                            {{-- BOTÃO FINALIZAR CONSULTA --}}
                            <form action="{{ route('agendamentos.finalizar-consulta', $agendamento->id) }}"
                                method="POST">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('✅ Finalizar a consulta?\n\nIsso irá:\n• Salvar o prontuário\n• Encerrar o agendamento\n• Gravar a duração')"
                                    class="block w-full px-4 py-2 bg-green-700 text-yellow-50 text-sm rounded hover:bg-green-800 transition font-medium">
                                    <i class="fas fa-stop-circle mr-2"></i> Finalizar Consulta
                                </button>
                            </form>

                            @if ($agendamento->prontuario)
                                <a href="{{ route('prontuarios.edit', $agendamento->prontuario->id) }}"
                                    class="block w-full text-center px-4 py-2 bg-yellow-700 text-yellow-50 text-sm rounded hover:bg-yellow-800 transition">
                                    <i class="fas fa-edit mr-2"></i> Editar Prontuário
                                </a>
                            @endif

                            {{-- STATUS: PODE INICIAR --}}
                        @elseif($agendamento->podeIniciarConsulta())
                            <button type="button" id="btn-iniciar-consulta"
                                class="block w-full px-4 py-2 bg-amber-900 text-yellow-50 text-sm rounded hover:bg-yellow-900 transition font-medium">
                                <i class="fas fa-play-circle mr-2"></i> Iniciar Consulta
                            </button>

                            {{-- STATUS: AGENDADO/CONFIRMADO (NÃO PODE INICIAR AINDA) --}}
                        @else
                            <div class="bg-blue-100 border-2 border-blue-400 rounded p-4 text-center">
                                <i class="fas fa-calendar-check text-blue-600 text-3xl mb-2"></i>
                                <p class="text-sm text-blue-900 font-bold">{{ ucfirst($agendamento->status_label) }}</p>
                                <p class="text-xs text-blue-700 mt-2">
                                    Agendado para: {{ $agendamento->data_hora_formatada }}
                                </p>
                            </div>

                            <a href="{{ route('agendamentos.edit', $agendamento->id) }}"
                                class="block w-full text-center px-4 py-2 bg-yellow-700 text-yellow-50 text-sm rounded hover:bg-yellow-800 transition">
                                <i class="fas fa-edit mr-2"></i> Editar Agendamento
                            </a>
                        @endif

                        {{-- BOTÃO CANCELAR (se aplicável) --}}
                        @if ($agendamento->podeCancelar())
                            <button type="button" id="btn-abrir-modal-cancelar"
                                class="block w-full px-4 py-2 bg-red-700 text-yellow-50 text-sm rounded hover:bg-red-800 transition">
                                <i class="fas fa-times-circle mr-2"></i> Cancelar Agendamento
                            </button>
                        @endif
                    </div>
                </div>

                {{-- CARD: INFORMAÇÕES --}}
                <div class="bg-yellow-50 rounded-lg shadow-lg p-6 border-l-4 border-amber-900">
                    <h3 class="text-lg font-bold text-amber-900 mb-4">
                        <i class="fas fa-info-circle mr-2"></i> Informações
                    </h3>

                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="text-amber-700 font-medium">Status:</span>
                            <div class="font-bold text-amber-900">{{ $agendamento->status_label }}</div>
                        </div>

                        <div class="pt-3 border-t-2 border-amber-200">
                            <span class="text-amber-700 font-medium">Criado em:</span>
                            <div class="font-bold text-amber-900">{{ $agendamento->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>

                        <div class="pt-3 border-t-2 border-amber-200">
                            <span class="text-amber-700 font-medium">Atualizado em:</span>
                            <div class="font-bold text-amber-900">{{ $agendamento->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>

                        @if ($agendamento->estahAtrasado())
                            <div class="pt-3 border-t-2 border-amber-200">
                                <span class="text-red-600 font-bold">⚠️ ATRASADO</span>
                                <div class="text-xs text-red-700">Passou da data/hora agendada</div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>

    </div>

    {{-- MODAL: INICIAR CONSULTA --}}
    <div id="modal-iniciar-consulta"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
        <div class="bg-yellow-50 rounded-lg shadow-2xl w-full max-w-md mx-4 p-6 border-2 border-amber-900">

            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-amber-900 flex items-center">
                    <i class="fas fa-play-circle text-amber-800 text-2xl mr-3"></i>
                    Iniciar Consulta
                </h3>
                <button type="button" onclick="fecharModalConsulta()" class="text-amber-700 hover:text-amber-900">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="mb-6 space-y-4">
                <div class="bg-white p-4 rounded border-l-4 border-amber-800">
                    <p class="text-sm text-amber-700 font-medium">Paciente:</p>
                    <p class="text-amber-900 font-semibold">{{ $agendamento->paciente->nome_completo }}</p>
                </div>

                <div class="bg-white p-4 rounded border-l-4 border-amber-800">
                    <p class="text-sm text-amber-700 font-medium">Profissional:</p>
                    <p class="text-amber-900 font-semibold">Dr(a).
                        {{ $agendamento->profissional->usuario->nome_completo }}</p>
                </div>

                <div class="bg-white p-4 rounded border-l-4 border-amber-800">
                    <p class="text-sm text-amber-700 font-medium">Duração Estimada:</p>
                    <p class="text-amber-900 font-semibold">{{ $agendamento->duracao_minutos }} minutos</p>
                </div>
            </div>

            <p class="text-amber-900 text-sm mb-6 bg-amber-100 p-3 rounded border-l-4 border-amber-800">
                <i class="fas fa-info-circle mr-2"></i>
                Ao iniciar a consulta, um prontuário será criado e você poderá registrar os dados da consulta.
            </p>

            <div class="flex gap-3">
                <button type="button" onclick="fecharModalConsulta()"
                    class="flex-1 px-4 py-2 bg-yellow-700 text-yellow-50 font-medium rounded-lg hover:bg-yellow-800 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Cancelar
                </button>
                <form action="{{ route('agendamentos.iniciar-consulta', $agendamento->id) }}" method="POST"
                    class="flex-1">
                    @csrf
                    <button type="submit"
                        class="w-full px-4 py-2 bg-amber-900 text-yellow-50 font-medium rounded-lg hover:bg-yellow-900 transition">
                        <i class="fas fa-play-circle mr-2"></i> Iniciar
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL: CONSULTA EM ANDAMENTO --}}
    @if ($agendamento->status === 'em_atendimento')
        <div id="modal-consulta-andamento"
            class="fixed inset-0 bg-gradient-to-br from-amber-600 to-yellow-700 flex items-center justify-center z-50">
            <div class="bg-yellow-50 rounded-2xl shadow-2xl w-full max-w-md mx-4 p-8 border-4 border-amber-900">

                {{-- ÍCONE ANIMADO --}}
                <div class="text-center mb-6">
                    <div class="inline-block animate-pulse">
                        <i class="fas fa-stethoscope text-5xl text-amber-800"></i>
                    </div>
                </div>

                {{-- TÍTULO --}}
                <h3 class="text-2xl font-bold text-center text-amber-900 mb-2">
                    <i class="fas fa-clock text-amber-700 mr-2"></i> Consulta em Andamento
                </h3>

                {{-- PACIENTE --}}
                <p class="text-center text-amber-800 mb-6 font-semibold">
                    {{ $agendamento->paciente->nome_completo }}
                </p>

                {{-- CRONÔMETRO --}}
                <div class="bg-gradient-to-br from-white to-amber-50 rounded-xl p-6 mb-6 border-4 border-amber-400">

                    {{-- HORA INÍCIO --}}
                    <div class="text-center mb-4 pb-4 border-b-2 border-amber-300">
                        <p class="text-amber-700 text-sm mb-1 font-medium">Iniciada em</p>
                        <p class="text-lg font-bold text-amber-900">
                            <i class="fas fa-play-circle text-yellow-700 mr-2"></i>
                            <span id="hora-inicio">{{ $agendamento->data_inicio_consulta->format('H:i:s') }}</span>
                        </p>
                    </div>

                    {{-- TEMPO DECORRIDO --}}
                    <div class="text-center mb-4 pb-4 border-b-2 border-amber-300">
                        <p class="text-amber-700 text-sm mb-1 font-medium">Tempo Decorrido</p>
                        <p class="text-3xl font-bold text-amber-900 font-mono">
                            <span id="tempo-decorrido">00:00:00</span>
                        </p>
                    </div>

                    {{-- DURAÇÃO PREVISTA --}}
                    <div class="text-center">
                        <p class="text-amber-700 text-sm mb-1 font-medium">Duração Prevista</p>
                        <p class="text-lg font-bold text-amber-900">
                            <i class="fas fa-hourglass-end text-yellow-700 mr-2"></i>
                            <span id="duracao-prevista">{{ $agendamento->duracao_minutos }}m</span>
                        </p>
                    </div>

                </div>

                {{-- BARRA DE PROGRESSO --}}
                <div class="mb-6">
                    <div class="w-full bg-amber-200 rounded-full h-4 overflow-hidden border-2 border-amber-400">
                        <div id="barra-progresso"
                            class="bg-gradient-to-r from-amber-600 to-yellow-600 h-4 rounded-full transition-all duration-1000"
                            style="width: 0%"></div>
                    </div>
                    <p class="text-center text-amber-800 text-xs mt-2 font-semibold">
                        <span id="percentual-progresso">0</span>% completo
                    </p>
                </div>

                {{-- BOTÕES --}}
                <div class="flex gap-3">
                    <a href="{{ route('prontuarios.show', $agendamento->prontuario->id ?? '#') }}"
                        class="flex-1 px-4 py-3 bg-yellow-700 text-yellow-50 font-medium rounded-lg hover:bg-yellow-800 transition text-center">
                        <i class="fas fa-file-medical-alt mr-2"></i> Ver Prontuário
                    </a>

                    <form action="{{ route('agendamentos.finalizar-consulta', $agendamento->id) }}" method="POST"
                        class="flex-1">
                        @csrf
                        <button type="submit" onclick="return confirm('✅ Finalizar a consulta?')"
                            class="w-full px-4 py-3 bg-green-700 text-yellow-50 font-medium rounded-lg hover:bg-green-800 transition">
                            <i class="fas fa-stop-circle mr-2"></i> Finalizar
                        </button>
                    </form>
                </div>

            </div>
        </div>
    @endif

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal: Iniciar Consulta
            const btnIniciarConsulta = document.getElementById('btn-iniciar-consulta');
            const modalConsulta = document.getElementById('modal-iniciar-consulta');

            if (btnIniciarConsulta) {
                btnIniciarConsulta.addEventListener('click', function() {
                    modalConsulta.classList.remove('hidden');
                });
            }

            window.fecharModalConsulta = function() {
                if (modalConsulta) {
                    modalConsulta.classList.add('hidden');
                }
            };

            // Fechar modal ao clicar fora
            if (modalConsulta) {
                modalConsulta.addEventListener('click', function(event) {
                    if (event.target === this) {
                        window.fecharModalConsulta();
                    }
                });
            }

            // ESC para fechar modal
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    window.fecharModalConsulta();
                }
            });
        });
    </script>
@endpush
