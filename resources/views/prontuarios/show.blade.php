@extends('layouts.app')

@section('title', 'Prontuário - ' . $prontuario->paciente->nome_completo)

@section('content')
    <div class="max-w-6xl mx-auto py-10 sm:px-6 lg:px-8">

        {{-- CABEÇALHO --}}
        <div class="mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-amber-900">
                        <i class="fas fa-file-medical-alt mr-2"></i> Prontuário Médico
                    </h2>
                    <p class="text-amber-700 mt-2">
                        Prontuário #{{ $prontuario->id }} - {{ $prontuario->paciente->nome_completo }}
                    </p>
                </div>

                <div class="flex flex-wrap gap-2 justify-end">
                    {{-- VOLTAR PARA PACIENTE --}}
                    <a href="{{ route('pacientes.show', $prontuario->paciente->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-700 text-yellow-50 text-sm rounded-md hover:bg-yellow-800 transition">
                        <i class="fas fa-arrow-left mr-2"></i> Voltar para Paciente
                    </a>

                    @if (!$prontuario->finalizado)
                        {{-- EDITAR PRONTUÁRIO --}}
                        <a href="{{ route('prontuarios.edit', $prontuario->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-amber-700 text-yellow-50 text-sm rounded-md hover:bg-amber-800 transition">
                            <i class="fas fa-edit mr-2"></i> Editar
                        </a>

                        {{-- FINALIZAR CONSULTA (ÚNICA FINALIZAÇÃO) --}}
                        @if ($prontuario->agendamento)
                            <form action="{{ route('agendamentos.finalizar-consulta', $prontuario->agendamento->id) }}"
                                method="POST" class="inline"
                                onsubmit="return confirm('✅ Finalizar a consulta?\n\nIsso irá:\n• Salvar o prontuário\n• Encerrar o agendamento\n• Gravar a duração');">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-green-700 text-yellow-50 text-sm rounded-md hover:bg-green-800 transition">
                                    <i class="fas fa-check-circle mr-2"></i> Finalizar Consulta
                                </button>
                            </form>
                        @endif
                    @else
                        {{-- BOTÕES DE EXPORTAÇÃO QUANDO FINALIZADO --}}
                        <a href="{{ route('prontuarios.pdf.completo', $prontuario->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-yellow-700 text-yellow-50 text-sm rounded-md hover:bg-yellow-800 transition"
                            target="_blank" download>
                            <i class="fas fa-file-pdf mr-2"></i> PDF Completo
                        </a>

                        @if ($prontuario->prescricao_medicamentos)
                            <a href="{{ route('prontuarios.pdf.prescricao', $prontuario->id) }}"
                                class="inline-flex items-center px-4 py-2 bg-amber-700 text-yellow-50 text-sm rounded-md hover:bg-amber-800 transition"
                                target="_blank" download>
                                <i class="fas fa-prescription-bottle mr-2"></i> Prescrição
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        {{-- MENSAGENS --}}
        @if (session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-md">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 text-xl mr-3"></i>
                    <p class="text-green-800 font-semibold">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-md">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl mr-3"></i>
                    <p class="text-red-800 font-semibold">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- STATUS --}}
        <div class="mb-6 bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Status do Prontuário</h3>
                    <p class="text-gray-600 text-sm mt-1">Última atualização:
                        {{ $prontuario->updated_at->format('d/m/Y H:i') }}</p>
                </div>
                @if ($prontuario->finalizado)
                    <span
                        class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold bg-green-100 text-green-800">
                        <i class="fas fa-lock mr-2"></i> Finalizado
                    </span>
                @else
                    <span
                        class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold bg-yellow-100 text-yellow-800">
                        <i class="fas fa-edit mr-2"></i> Em Aberto
                    </span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- COLUNA PRINCIPAL --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- CARD: IDENTIFICAÇÃO --}}
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-bold text-green-700 mb-4 pb-2 border-b border-green-200">
                        <i class="fas fa-user-check mr-2"></i> Identificação
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Paciente</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $prontuario->paciente->nome_completo }}</p>
                            <p class="text-sm text-gray-600">{{ $prontuario->paciente->cpf_formatado ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Profissional</label>
                            <p class="text-lg font-semibold text-gray-900">Dr(a).
                                {{ $prontuario->profissional->usuario->nome_completo ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600">{{ $prontuario->profissional->especialidade ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Data do Atendimento</label>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $prontuario->data_atendimento->format('d/m/Y H:i') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Data de Retorno</label>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $prontuario->data_retorno ? $prontuario->data_retorno->format('d/m/Y') : 'Não agendado' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- CARD: HISTÓRIA CLÍNICA --}}
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-bold text-green-700 mb-4 pb-2 border-b border-green-200">
                        <i class="fas fa-stethoscope mr-2"></i> História Clínica
                    </h3>

                    <div class="space-y-4">
                        @if ($prontuario->queixa_principal)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Queixa Principal</label>
                                <p class="text-gray-800 bg-gray-50 p-3 rounded">{{ $prontuario->queixa_principal }}</p>
                            </div>
                        @endif

                        @if ($prontuario->historia_doenca_atual)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">História da Doença Atual
                                    (HDA)</label>
                                <p class="text-gray-800 bg-gray-50 p-3 rounded whitespace-pre-wrap">
                                    {{ $prontuario->historia_doenca_atual }}</p>
                            </div>
                        @endif

                        @if ($prontuario->historia_patologica_pregressa)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">História Patológica Pregressa
                                    (HPP)</label>
                                <p class="text-gray-800 bg-gray-50 p-3 rounded">
                                    {{ $prontuario->historia_patologica_pregressa }}</p>
                            </div>
                        @endif

                        @if ($prontuario->historia_familiar)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">História Familiar</label>
                                <p class="text-gray-800 bg-gray-50 p-3 rounded">{{ $prontuario->historia_familiar }}</p>
                            </div>
                        @endif

                        @if ($prontuario->historia_social)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">História Social</label>
                                <p class="text-gray-800 bg-gray-50 p-3 rounded">{{ $prontuario->historia_social }}</p>
                            </div>
                        @endif

                        @if (
                            !$prontuario->queixa_principal &&
                                !$prontuario->historia_doenca_atual &&
                                !$prontuario->historia_patologica_pregressa &&
                                !$prontuario->historia_familiar &&
                                !$prontuario->historia_social)
                            <p class="text-gray-500 text-sm italic">📝 Nenhuma informação preenchida nesta seção.</p>
                        @endif
                    </div>
                </div>

                {{-- CARD: EXAME E DIAGNÓSTICO --}}
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-bold text-green-700 mb-4 pb-2 border-b border-green-200">
                        <i class="fas fa-heartbeat mr-2"></i> Exame e Diagnóstico
                    </h3>

                    <div class="space-y-4">
                        @if ($prontuario->exame_fisico)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Exame Físico</label>
                                <p class="text-gray-800 bg-gray-50 p-3 rounded whitespace-pre-wrap">
                                    {{ $prontuario->exame_fisico }}</p>
                            </div>
                        @endif

                        @if ($prontuario->hipotese_diagnostica)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Hipótese Diagnóstica</label>
                                <p class="text-gray-800 bg-blue-50 p-3 rounded font-semibold border-l-4 border-blue-500">
                                    {{ $prontuario->hipotese_diagnostica }}</p>
                            </div>
                        @endif

                        @if (!$prontuario->exame_fisico && !$prontuario->hipotese_diagnostica)
                            <p class="text-gray-500 text-sm italic">📝 Nenhuma informação preenchida nesta seção.</p>
                        @endif
                    </div>
                </div>

                {{-- CARD: CONDUTA E TRATAMENTO --}}
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-bold text-green-700 mb-4 pb-2 border-b border-green-200">
                        <i class="fas fa-prescription-bottle mr-2"></i> Conduta e Tratamento
                    </h3>

                    <div class="space-y-4">
                        @if ($prontuario->conduta_tratamento)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Conduta/Tratamento</label>
                                <p class="text-gray-800 bg-gray-50 p-3 rounded whitespace-pre-wrap">
                                    {{ $prontuario->conduta_tratamento }}</p>
                            </div>
                        @endif

                        @if ($prontuario->prescricao_medicamentos)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Prescrição de
                                    Medicamentos</label>
                                <p class="text-gray-800 bg-gray-50 p-3 rounded whitespace-pre-wrap font-mono text-sm">
                                    {{ $prontuario->prescricao_medicamentos }}</p>
                            </div>
                        @endif

                        @if ($prontuario->exames_solicitados)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Exames Solicitados</label>
                                <p class="text-gray-800 bg-gray-50 p-3 rounded">{{ $prontuario->exames_solicitados }}</p>
                            </div>
                        @endif

                        @if ($prontuario->observacoes_gerais)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Observações Gerais</label>
                                <p class="text-gray-800 bg-gray-50 p-3 rounded">{{ $prontuario->observacoes_gerais }}</p>
                            </div>
                        @endif

                        @if (
                            !$prontuario->conduta_tratamento &&
                                !$prontuario->prescricao_medicamentos &&
                                !$prontuario->exames_solicitados &&
                                !$prontuario->observacoes_gerais)
                            <p class="text-gray-500 text-sm italic">📝 Nenhuma informação preenchida nesta seção.</p>
                        @endif
                    </div>
                </div>
                {{-- CARD: EXAMES SOLICITADOS --}}
                @if ($prontuario->examesSolicitados && $prontuario->examesSolicitados->count() > 0)
                    <div class="bg-yellow-50 rounded-lg shadow-lg p-6 border-l-4 border-amber-900">
                        <div class="flex justify-between items-center mb-4 pb-2 border-b-2 border-amber-200">
                            <h3 class="text-xl font-bold text-amber-900">
                                <i class="fas fa-microscope mr-2"></i> Exames Solicitados
                            </h3>
                            <span class="bg-amber-800 text-yellow-50 px-3 py-1 rounded-full text-sm font-bold">
                                {{ $prontuario->examesSolicitados->count() }}
                            </span>
                        </div>

                        <div class="space-y-3">
                            @foreach ($prontuario->examesSolicitados->take(5) as $exame)
                                <div
                                    class="bg-white p-4 rounded border-l-4 {{ $exame->status == 'concluido' ? 'border-green-500' : 'border-amber-500' }}">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-bold text-amber-900">{{ $exame->tipoExame->nome }}</p>
                                            <p class="text-xs text-amber-700 mt-1">
                                                Solicitado em: {{ $exame->data_solicitacao_formatada }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            @php
                                                $statusColors = [
                                                    'solicitado' => 'bg-blue-100 text-blue-800',
                                                    'em_analise' => 'bg-yellow-100 text-amber-900',
                                                    'concluido' => 'bg-green-100 text-green-800',
                                                    'cancelado' => 'bg-red-100 text-red-800',
                                                ];
                                                $colorClass =
                                                    $statusColors[$exame->status] ?? 'bg-amber-100 text-amber-800';
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $colorClass }}">
                                                {{ ucfirst($exame->status) }}
                                            </span>
                                            <a href="{{ route('exames.show', $exame->id) }}"
                                                class="block mt-2 text-xs text-amber-800 hover:text-amber-900 font-bold">
                                                Ver detalhes <i class="fas fa-arrow-right ml-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if ($prontuario->examesSolicitados->count() > 5)
                            <div class="mt-4 text-center">
                                <a href="{{ route('exames.index', ['prontuario_id' => $prontuario->id]) }}"
                                    class="text-sm text-amber-800 hover:text-amber-900 font-bold">
                                    Ver todos os exames ({{ $prontuario->examesSolicitados->count() }}) <i
                                        class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

            </div>

            {{-- COLUNA LATERAL --}}
            <div class="lg:col-span-1 space-y-6">

                {{-- CARD: INFORMAÇÕES --}}
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-bold text-green-700 mb-4">
                        <i class="fas fa-info-circle mr-2"></i> Informações
                    </h3>

                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="text-gray-500">ID do Prontuário:</span>
                            <div class="font-medium text-gray-900">#{{ $prontuario->id }}</div>
                        </div>

                        <div class="pt-3 border-t border-gray-200">
                            <span class="text-gray-500">Criado em:</span>
                            <div class="font-medium text-gray-900">{{ $prontuario->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>

                        <div class="pt-3 border-t border-gray-200">
                            <span class="text-gray-500">Atualizado em:</span>
                            <div class="font-medium text-gray-900">{{ $prontuario->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>

                        <div class="pt-3 border-t border-gray-200">
                            <span class="text-gray-500">Status:</span>
                            @if ($prontuario->finalizado)
                                <div class="text-green-700 font-bold"><i class="fas fa-lock mr-1"></i> Finalizado</div>
                            @else
                                <div class="text-yellow-700 font-bold"><i class="fas fa-edit mr-1"></i> Em Aberto</div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- CARD: AÇÕES RÁPIDAS --}}
                {{-- CARD: AÇÕES RÁPIDAS --}}
                @if (!$prontuario->finalizado)
                    <div class="bg-amber-50 rounded-lg shadow-lg p-6 border-2 border-amber-200">
                        <h3 class="text-lg font-bold text-amber-900 mb-4">
                            <i class="fas fa-stethoscope mr-2"></i> Consulta em Andamento
                        </h3>

                        <div class="space-y-3">
                            <a href="{{ route('prontuarios.edit', $prontuario->id) }}"
                                class="block w-full text-center px-4 py-2 bg-amber-700 text-yellow-50 text-sm rounded-md hover:bg-amber-800 transition">
                                <i class="fas fa-edit mr-2"></i> Editar Prontuário
                            </a>

                            @if ($prontuario->agendamento)
                                <form
                                    action="{{ route('agendamentos.finalizar-consulta', $prontuario->agendamento->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('✅ Finalizar a consulta?\n\nIsso irá:\n• Salvar o prontuário\n• Encerrar o agendamento\n• Gravar a duração');">
                                    @csrf
                                    <button type="submit"
                                        class="w-full px-4 py-2 bg-green-700 text-yellow-50 text-sm rounded-md hover:bg-green-800 transition font-bold">
                                        <i class="fas fa-check-circle mr-2"></i> Finalizar Consulta
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="bg-yellow-50 rounded-lg shadow-lg p-6 border-2 border-green-400">
                        <h3 class="text-lg font-bold text-green-800 mb-4">
                            <i class="fas fa-check-circle mr-2"></i> Consulta Finalizada
                        </h3>

                        <div class="space-y-3">
                            <a href="{{ route('pacientes.show', $prontuario->paciente->id) }}"
                                class="block w-full text-center px-4 py-2 bg-yellow-700 text-yellow-50 text-sm rounded-md hover:bg-yellow-800 transition">
                                <i class="fas fa-arrow-left mr-2"></i> Voltar Paciente
                            </a>

                            <a href="{{ route('prontuarios.pdf.completo', $prontuario->id) }}"
                                class="block w-full text-center px-4 py-2 bg-yellow-700 text-yellow-50 text-sm rounded-md hover:bg-yellow-800 transition"
                                target="_blank" download>
                                <i class="fas fa-file-pdf mr-2"></i> PDF Completo
                            </a>

                            @if ($prontuario->prescricao_medicamentos)
                                <a href="{{ route('prontuarios.pdf.prescricao', $prontuario->id) }}"
                                    class="block w-full text-center px-4 py-2 bg-amber-700 text-yellow-50 text-sm rounded-md hover:bg-amber-800 transition"
                                    target="_blank" download>
                                    <i class="fas fa-prescription-bottle mr-2"></i> Prescrição
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

            </div>

        </div>

    </div>

    {{-- MODAL DE FINALIZAÇÃO DO PRONTUÁRIO --}}
    <div id="modal-finalizar-prontuario"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-md mx-4 p-6">

            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-check-circle text-green-600 text-2xl mr-3"></i>
                    Finalizar Prontuário
                </h3>
                <button type="button" onclick="fecharModalProntuario()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <p class="text-gray-600 text-sm mb-6">
                Você está prestes a finalizar este prontuário. <strong>Após a finalização, não será possível
                    editá-lo.</strong>
            </p>

            <div class="flex gap-3">
                <button type="button" onclick="fecharModalProntuario()"
                    class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 font-medium rounded-lg hover:bg-gray-300 transition">
                    <i class="fas fa-times mr-2"></i> Cancelar
                </button>
                <form action="{{ route('prontuarios.finalizar', $prontuario->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit"
                        class="w-full px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-check mr-2"></i> Finalizar
                    </button>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Javascript simplificado - agora temos apenas uma finalização
            console.log('Prontuário carregado');
        });
    </script>
@endpush
