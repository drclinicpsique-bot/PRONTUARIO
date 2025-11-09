@extends('layouts.app')

@section('title', 'Editar Prontuário - ' . $prontuario->paciente->nome_completo)

@section('content')
    {{-- ALERTAS DE SUCESSO --}}
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
            @if (str_contains(session('success'), 'Exame solicitado'))
                {{-- ALERTA ESPECÍFICO PARA EXAME SOLICITADO --}}
                <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-lg shadow-md animate-fade-in">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-microscope text-amber-600 text-xl mr-3"></i>
                            <div>
                                <p class="text-amber-900 font-semibold">{{ session('success') }}</p>
                                <p class="text-amber-700 text-sm mt-1">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    O exame foi registrado e está disponível na barra lateral.
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('exames.index', ['prontuario_id' => $prontuario->id]) }}"
                            class="ml-4 inline-flex items-center px-3 py-2 bg-amber-600 text-white text-sm rounded hover:bg-amber-700 transition whitespace-nowrap">
                            <i class="fas fa-flask mr-2"></i> Ver Exames
                        </a>
                    </div>
                </div>
            @else
                {{-- ALERTA PADRÃO --}}
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-md animate-fade-in">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 text-xl mr-3"></i>
                        <div>
                            <p class="text-green-800 font-semibold">{{ session('success') }}</p>
                            <p class="text-green-700 text-sm mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Preencha os dados da consulta e salve as alterações.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

        {{-- CABEÇALHO --}}
        <div class="mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-green-800">
                        <i class="fas fa-file-medical-alt mr-2"></i> Editar Prontuário
                    </h2>
                    <p class="text-gray-600 mt-2">
                        Prontuário #{{ $prontuario->id }} - {{ $prontuario->paciente->nome_completo }}
                    </p>
                </div>

                <a href="{{ route('prontuarios.show', $prontuario->id) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Voltar
                </a>
            </div>
        </div>

        {{-- ALERTA SE FINALIZADO --}}
        @if ($prontuario->finalizado)
            <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg shadow-md">
                <div class="flex items-center">
                    <i class="fas fa-lock text-yellow-600 text-xl mr-3"></i>
                    <p class="text-yellow-800 font-semibold">⚠️ Este prontuário está finalizado e não pode ser editado.</p>
                </div>
            </div>
        @endif

        {{-- MENSAGENS DE ERRO --}}
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-md">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl mr-3"></i>
                    <h3 class="text-red-800 font-semibold">Erros na validação</h3>
                </div>
                <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

            {{-- COLUNA LATERAL: AÇÕES RÁPIDAS --}}
            <div class="lg:col-span-1">
                <div class="bg-amber-50 rounded-lg shadow-lg p-6 border-2 border-amber-200 sticky top-4">
                    <h3 class="text-lg font-bold text-amber-900 mb-4">
                        <i class="fas fa-bolt mr-2"></i> Ações Rápidas
                    </h3>

                    <div class="space-y-3">

                        {{-- SOLICITAR EXAME --}}
                        @if (!$prontuario->finalizado)
                            <a href="{{ route('exames.create', ['prontuario_id' => $prontuario->id]) }}"
                                class="block w-full text-center px-4 py-2 bg-amber-700 text-yellow-50 text-sm rounded hover:bg-amber-800 transition font-medium">
                                <i class="fas fa-microscope mr-2"></i> Solicitar Exame
                            </a>
                        @endif

                        {{-- VER EXAMES SOLICITADOS --}}
                        @if ($prontuario->examesSolicitados && $prontuario->examesSolicitados->count() > 0)
                            <a href="{{ route('exames.index', ['prontuario_id' => $prontuario->id]) }}"
                                class="block w-full text-center px-4 py-2 bg-yellow-700 text-yellow-50 text-sm rounded hover:bg-yellow-800 transition relative">
                                <i class="fas fa-flask mr-2"></i> Ver Exames
                                <span
                                    class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center">
                                    {{ $prontuario->examesSolicitados->count() }}
                                </span>
                            </a>
                        @endif

                        {{-- VER HISTÓRICO DO PACIENTE --}}
                        <a href="{{ route('pacientes.show', $prontuario->paciente->id) }}"
                            class="block w-full text-center px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                            <i class="fas fa-user mr-2"></i> Ficha do Paciente
                        </a>

                        {{-- FINALIZAR CONSULTA --}}
                        @if ($prontuario->agendamento && $prontuario->agendamento->status == 'em_atendimento')
                            <div class="pt-4 mt-4 border-t-2 border-amber-300">
                                <form action="{{ route('agendamentos.finalizar-consulta', $prontuario->agendamento->id) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit"
                                        onclick="return confirm('✅ Finalizar a consulta?\n\nIsso irá:\n• Salvar o prontuário\n• Encerrar o agendamento\n• Gravar a duração')"
                                        class="block w-full px-4 py-2 bg-green-700 text-white text-sm rounded hover:bg-green-800 transition font-medium">
                                        <i class="fas fa-stop-circle mr-2"></i> Finalizar Consulta
                                    </button>
                                </form>
                            </div>
                        @endif

                    </div>

                    {{-- INFO: RESUMO DOS EXAMES --}}
                    @if ($prontuario->examesSolicitados && $prontuario->examesSolicitados->count() > 0)
                        <div class="mt-6 pt-6 border-t-2 border-amber-300">
                            <h4 class="text-sm font-bold text-amber-900 mb-3">
                                <i class="fas fa-chart-pie mr-1"></i> Resumo de Exames:
                            </h4>
                            <div class="space-y-2 text-xs">
                                @php
                                    $examesPendentes = $prontuario->examesSolicitados
                                        ->whereIn('status', ['solicitado', 'em_analise'])
                                        ->count();
                                    $examesConcluidos = $prontuario->examesSolicitados
                                        ->where('status', 'concluido')
                                        ->count();
                                @endphp
                                <div class="flex justify-between items-center">
                                    <span class="text-amber-800">Pendentes:</span>
                                    <span class="font-bold text-blue-700">{{ $examesPendentes }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-amber-800">Concluídos:</span>
                                    <span class="font-bold text-green-700">{{ $examesConcluidos }}</span>
                                </div>
                                <div class="flex justify-between items-center pt-2 border-t border-amber-300">
                                    <span class="text-amber-900 font-bold">Total:</span>
                                    <span
                                        class="font-bold text-amber-900">{{ $prontuario->examesSolicitados->count() }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            {{-- COLUNA PRINCIPAL: FORMULÁRIO --}}
            <div class="lg:col-span-3">
                <form action="{{ route('prontuarios.update', $prontuario->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- SEÇÃO 1: IDENTIFICAÇÃO --}}
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-xl font-bold text-green-700 mb-4 pb-2 border-b border-green-200">
                            <i class="fas fa-user-check mr-2"></i> Identificação
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Paciente (Somente Leitura) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Paciente</label>
                                <div
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 font-medium">
                                    <i
                                        class="fas fa-lock text-gray-400 mr-2"></i>{{ $prontuario->paciente->nome_completo }}
                                </div>
                            </div>

                            {{-- Profissional (Somente Leitura) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Profissional</label>
                                <div
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 font-medium">
                                    <i class="fas fa-lock text-gray-400 mr-2"></i>Dr(a).
                                    {{ $prontuario->profissional->usuario->nome_completo }}
                                </div>
                            </div>

                            {{-- Data do Atendimento --}}
                            <div>
                                <label for="data_atendimento" class="block text-sm font-medium text-gray-700 mb-2">
                                    Data do Atendimento <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" name="data_atendimento" id="data_atendimento"
                                    value="{{ old('data_atendimento', $prontuario->data_atendimento->format('Y-m-d\TH:i')) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('data_atendimento') border-red-500 @enderror"
                                    required>
                                @error('data_atendimento')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Data de Retorno --}}
                            <div>
                                <label for="data_retorno" class="block text-sm font-medium text-gray-700 mb-2">
                                    Data de Retorno
                                </label>
                                <input type="date" name="data_retorno" id="data_retorno"
                                    value="{{ old('data_retorno', $prontuario->data_retorno?->format('Y-m-d')) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('data_retorno') border-red-500 @enderror">
                                @error('data_retorno')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- SEÇÃO 2: HISTÓRIA CLÍNICA --}}
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-xl font-bold text-green-700 mb-4 pb-2 border-b border-green-200">
                            <i class="fas fa-stethoscope mr-2"></i> História Clínica
                        </h3>

                        <div class="space-y-4">
                            {{-- Queixa Principal --}}
                            <div>
                                <label for="queixa_principal" class="block text-sm font-medium text-gray-700 mb-2">
                                    Queixa Principal
                                </label>
                                <textarea name="queixa_principal" id="queixa_principal" rows="2"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('queixa_principal') border-red-500 @enderror"
                                    placeholder="Ex: Dor de cabeça, ansiedade, insônia...">{{ old('queixa_principal', $prontuario->queixa_principal) }}</textarea>
                                @error('queixa_principal')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- História da Doença Atual --}}
                            <div>
                                <label for="historia_doenca_atual" class="block text-sm font-medium text-gray-700 mb-2">
                                    História da Doença Atual (HDA)
                                </label>
                                <textarea name="historia_doenca_atual" id="historia_doenca_atual" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('historia_doenca_atual') border-red-500 @enderror"
                                    placeholder="Descrição dos sintomas e duração...">{{ old('historia_doenca_atual', $prontuario->historia_doenca_atual) }}</textarea>
                                @error('historia_doenca_atual')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- História Patológica Pregressa --}}
                            <div>
                                <label for="historia_patologica_pregressa"
                                    class="block text-sm font-medium text-gray-700 mb-2">
                                    História Patológica Pregressa (HPP)
                                </label>
                                <textarea name="historia_patologica_pregressa" id="historia_patologica_pregressa" rows="2"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('historia_patologica_pregressa') border-red-500 @enderror"
                                    placeholder="Antecedentes médicos relevantes...">{{ old('historia_patologica_pregressa', $prontuario->historia_patologica_pregressa) }}</textarea>
                                @error('historia_patologica_pregressa')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- História Familiar --}}
                            <div>
                                <label for="historia_familiar" class="block text-sm font-medium text-gray-700 mb-2">
                                    História Familiar
                                </label>
                                <textarea name="historia_familiar" id="historia_familiar" rows="2"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('historia_familiar') border-red-500 @enderror"
                                    placeholder="Doenças familiares...">{{ old('historia_familiar', $prontuario->historia_familiar) }}</textarea>
                                @error('historia_familiar')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- História Social --}}
                            <div>
                                <label for="historia_social" class="block text-sm font-medium text-gray-700 mb-2">
                                    História Social
                                </label>
                                <textarea name="historia_social" id="historia_social" rows="2"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('historia_social') border-red-500 @enderror"
                                    placeholder="Profissão, hábitos, tabagismo, etc...">{{ old('historia_social', $prontuario->historia_social) }}</textarea>
                                @error('historia_social')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- SEÇÃO 3: EXAME E DIAGNÓSTICO --}}
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-xl font-bold text-green-700 mb-4 pb-2 border-b border-green-200">
                            <i class="fas fa-heartbeat mr-2"></i> Exame e Diagnóstico
                        </h3>

                        <div class="space-y-4">
                            {{-- Exame Físico --}}
                            <div>
                                <label for="exame_fisico" class="block text-sm font-medium text-gray-700 mb-2">
                                    Exame Físico
                                </label>
                                <textarea name="exame_fisico" id="exame_fisico" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('exame_fisico') border-red-500 @enderror"
                                    placeholder="Achados do exame físico...">{{ old('exame_fisico', $prontuario->exame_fisico) }}</textarea>
                                @error('exame_fisico')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Hipótese Diagnóstica --}}
                            <div>
                                <label for="hipotese_diagnostica" class="block text-sm font-medium text-gray-700 mb-2">
                                    Hipótese Diagnóstica
                                </label>
                                <textarea name="hipotese_diagnostica" id="hipotese_diagnostica" rows="2"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('hipotese_diagnostica') border-red-500 @enderror"
                                    placeholder="Diagnóstico preliminar...">{{ old('hipotese_diagnostica', $prontuario->hipotese_diagnostica) }}</textarea>
                                @error('hipotese_diagnostica')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- SEÇÃO 4: CONDUTA E TRATAMENTO --}}
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-xl font-bold text-green-700 mb-4 pb-2 border-b border-green-200">
                            <i class="fas fa-prescription-bottle mr-2"></i> Conduta e Tratamento
                        </h3>

                        <div class="space-y-4">
                            {{-- Conduta/Tratamento --}}
                            <div>
                                <label for="conduta_tratamento" class="block text-sm font-medium text-gray-700 mb-2">
                                    Conduta/Tratamento
                                </label>
                                <textarea name="conduta_tratamento" id="conduta_tratamento" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('conduta_tratamento') border-red-500 @enderror"
                                    placeholder="Orientações e tratamento recomendado...">{{ old('conduta_tratamento', $prontuario->conduta_tratamento) }}</textarea>
                                @error('conduta_tratamento')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Prescrição de Medicamentos --}}
                            <div>
                                <label for="prescricao_medicamentos" class="block text-sm font-medium text-gray-700 mb-2">
                                    Prescrição de Medicamentos
                                </label>
                                <textarea name="prescricao_medicamentos" id="prescricao_medicamentos" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('prescricao_medicamentos') border-red-500 @enderror font-mono text-sm"
                                    placeholder="Ex: Medicamento X - 500mg, 2x ao dia durante 7 dias">{{ old('prescricao_medicamentos', $prontuario->prescricao_medicamentos) }}</textarea>
                                @error('prescricao_medicamentos')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- EXAMES SOLICITADOS (LISTAGEM DINÂMICA) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-microscope mr-1"></i> Exames Solicitados
                                </label>

                                @if ($prontuario->examesSolicitados && $prontuario->examesSolicitados->count() > 0)
                                    <div class="space-y-2">
                                        @foreach ($prontuario->examesSolicitados as $exame)
                                            <div
                                                class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border-l-4 {{ $exame->status == 'concluido' ? 'border-green-500' : 'border-amber-500' }}">
                                                <div class="flex items-center space-x-3">
                                                    <div class="flex-shrink-0">
                                                        @if ($exame->status == 'concluido')
                                                            <i class="fas fa-check-circle text-green-600 text-lg"></i>
                                                        @elseif($exame->status == 'em_analise')
                                                            <i class="fas fa-hourglass-half text-amber-600 text-lg"></i>
                                                        @else
                                                            <i class="fas fa-clock text-blue-600 text-lg"></i>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-bold text-gray-900">
                                                            {{ $exame->tipoExame->nome }}</p>
                                                        <p class="text-xs text-gray-600">
                                                            Solicitado em: {{ $exame->data_solicitacao_formatada }}
                                                            @if ($exame->data_prevista_resultado)
                                                                | Previsão:
                                                                {{ $exame->data_prevista_resultado->format('d/m/Y') }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    @php
                                                        $statusColors = [
                                                            'solicitado' => 'bg-blue-100 text-blue-800',
                                                            'em_analise' => 'bg-amber-100 text-amber-900',
                                                            'concluido' => 'bg-green-100 text-green-800',
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
                                                        class="text-green-600 hover:text-green-900" title="Ver detalhes">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div
                                        class="text-center py-6 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                                        <i class="fas fa-flask text-gray-400 text-3xl mb-2"></i>
                                        <p class="text-sm text-gray-600 mb-3">Nenhum exame solicitado ainda</p>
                                        <a href="{{ route('exames.create', ['prontuario_id' => $prontuario->id]) }}"
                                            class="inline-flex items-center px-4 py-2 bg-amber-600 text-white text-xs rounded hover:bg-amber-700 transition">
                                            <i class="fas fa-plus mr-2"></i> Solicitar Primeiro Exame
                                        </a>
                                    </div>
                                @endif
                            </div>

                            {{-- Observações Gerais --}}
                            <div>
                                <label for="observacoes_gerais" class="block text-sm font-medium text-gray-700 mb-2">
                                    Observações Gerais
                                </label>
                                <textarea name="observacoes_gerais" id="observacoes_gerais" rows="2"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('observacoes_gerais') border-red-500 @enderror"
                                    placeholder="Observações adicionais...">{{ old('observacoes_gerais', $prontuario->observacoes_gerais) }}</textarea>
                                @error('observacoes_gerais')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- BOTÕES DE AÇÃO --}}
                    <div class="flex items-center justify-end gap-4 pt-6 bg-white rounded-lg shadow-lg p-6">
                        <a href="{{ route('prontuarios.show', $prontuario->id) }}"
                            class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition font-medium">
                            <i class="fas fa-times mr-2"></i> Cancelar
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                            <i class="fas fa-save mr-2"></i> Salvar Alterações
                        </button>
                    </div>

                </form>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const dataAtendimento = document.getElementById('data_atendimento');

            form.addEventListener('submit', function(e) {
                if (!dataAtendimento.value) {
                    e.preventDefault();
                    alert('⚠️ Por favor, informe a data do atendimento!');
                    dataAtendimento.focus();
                    return false;
                }
            });

            // Focar no primeiro campo com erro
            const firstError = document.querySelector('textarea.border-red-500, input.border-red-500');
            if (firstError) {
                firstError.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                firstError.focus();
            }

            // SE VOLTOU DE SOLICITAR EXAME, FAZ SCROLL SUAVE PARA O TOPO
            @if (session('success') && str_contains(session('success'), 'Exame solicitado'))
                setTimeout(function() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }, 100);
            @endif
        });
    </script>
@endpush
