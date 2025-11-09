@extends('layouts.app')

@section('title', 'Novo Prontuário')

@section('content')
<div class="max-w-6xl mx-auto py-10 sm:px-6 lg:px-8">

    {{-- CABEÇALHO --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-extrabold text-green-800">
                    <i class="fas fa-file-medical-alt mr-2"></i> Novo Prontuário
                </h2>
                <p class="text-gray-600 mt-2">
                    Registre uma nova consulta ou atendimento
                </p>
            </div>
            <a href="{{ route('prontuarios.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Voltar
            </a>
        </div>
    </div>

    {{-- MENSAGENS DE ERRO --}}
    @if($errors->any())
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-md">
        <div class="flex items-center mb-2">
            <i class="fas fa-exclamation-triangle text-red-600 text-xl mr-3"></i>
            <h3 class="text-red-800 font-semibold">Erro na validação!</h3>
        </div>
        <ul class="list-disc list-inside text-red-700 text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- FORMULÁRIO --}}
    <div class="bg-white rounded-lg shadow-lg p-8">
        <form action="{{ route('prontuarios.store') }}" method="POST" id="form-prontuario">
            @csrf

            {{-- SEÇÃO 1: IDENTIFICAÇÃO --}}
            <div class="mb-8">
                <h3 class="text-xl font-bold text-green-700 mb-4 pb-2 border-b border-green-200">
                    <i class="fas fa-user-check mr-2"></i> Identificação
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Paciente --}}
                    <div class="md:col-span-1">
                        <label for="paciente_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Paciente <span class="text-red-500">*</span>
                        </label>
                        <select name="paciente_id"
                                id="paciente_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('paciente_id') border-red-500 @enderror"
                                required>
                            <option value="">Selecione um paciente</option>
                            @foreach(\App\Models\Paciente::where('ativo', true)->orderBy('nome_completo')->get() as $p)
                                <option value="{{ $p->id }}" 
                                        {{ old('paciente_id', $paciente?->id) == $p->id ? 'selected' : '' }}>
                                    {{ $p->nome_completo }} - {{ $p->cpf_formatado }}
                                </option>
                            @endforeach
                        </select>
                        @error('paciente_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Profissional --}}
                    <div class="md:col-span-1">
                        <label for="profissional_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Profissional <span class="text-red-500">*</span>
                        </label>
                        <select name="profissional_id"
                                id="profissional_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('profissional_id') border-red-500 @enderror"
                                required>
                            <option value="">Selecione um profissional</option>
                            @foreach($profissionais as $prof)
                                <option value="{{ $prof->id }}" 
                                        {{ old('profissional_id') == $prof->id ? 'selected' : '' }}>
                                    Dr(a). {{ $prof->usuario->nome_completo }} - {{ $prof->especialidade }}
                                </option>
                            @endforeach
                        </select>
                        @error('profissional_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Data do Atendimento --}}
                    <div class="md:col-span-1">
                        <label for="data_atendimento" class="block text-sm font-medium text-gray-700 mb-2">
                            Data do Atendimento <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local"
                               name="data_atendimento"
                               id="data_atendimento"
                               value="{{ old('data_atendimento', $agendamento ? $agendamento->data_hora_agendamento->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('data_atendimento') border-red-500 @enderror"
                               required>
                        @error('data_atendimento')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Data de Retorno --}}
                    <div class="md:col-span-1">
                        <label for="data_retorno" class="block text-sm font-medium text-gray-700 mb-2">
                            Data de Retorno
                        </label>
                        <input type="date"
                               name="data_retorno"
                               id="data_retorno"
                               value="{{ old('data_retorno', $agendamento ? $agendamento->data_hora_agendamento->addDays(7)->format('Y-m-d') : '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('data_retorno') border-red-500 @enderror">
                        @error('data_retorno')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Agendamento (Hidden) --}}
                    @if($agendamento)
                    <input type="hidden" name="agendamento_id" value="{{ $agendamento->id }}">
                    @endif

                </div>
            </div>

            {{-- SEÇÃO 2: HISTÓRIA CLÍNICA --}}
            <div class="mb-8">
                <h3 class="text-xl font-bold text-green-700 mb-4 pb-2 border-b border-green-200">
                    <i class="fas fa-stethoscope mr-2"></i> História Clínica
                </h3>

                <div class="grid grid-cols-1 gap-6">

                    {{-- Queixa Principal --}}
                    <div>
                        <label for="queixa_principal" class="block text-sm font-medium text-gray-700 mb-2">
                            Queixa Principal
                        </label>
                        <textarea name="queixa_principal"
                                  id="queixa_principal"
                                  rows="2"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('queixa_principal') border-red-500 @enderror"
                                  placeholder="Motivo principal da consulta...">{{ old('queixa_principal') }}</textarea>
                        @error('queixa_principal')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- História da Doença Atual --}}
                    <div>
                        <label for="historia_doenca_atual" class="block text-sm font-medium text-gray-700 mb-2">
                            História da Doença Atual (HDA)
                        </label>
                        <textarea name="historia_doenca_atual"
                                  id="historia_doenca_atual"
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('historia_doenca_atual') border-red-500 @enderror"
                                  placeholder="Descrição detalhada dos sintomas e evolução...">{{ old('historia_doenca_atual') }}</textarea>
                        @error('historia_doenca_atual')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- História Patológica Pregressa --}}
                    <div>
                        <label for="historia_patologica_pregressa" class="block text-sm font-medium text-gray-700 mb-2">
                            História Patológica Pregressa (HPP)
                        </label>
                        <textarea name="historia_patologica_pregressa"
                                  id="historia_patologica_pregressa"
                                  rows="2"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('historia_patologica_pregressa') border-red-500 @enderror"
                                  placeholder="Doenças anteriores, cirurgias, alergias...">{{ old('historia_patologica_pregressa') }}</textarea>
                        @error('historia_patologica_pregressa')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- História Familiar --}}
                    <div>
                        <label for="historia_familiar" class="block text-sm font-medium text-gray-700 mb-2">
                            História Familiar
                        </label>
                        <textarea name="historia_familiar"
                                  id="historia_familiar"
                                  rows="2"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('historia_familiar') border-red-500 @enderror"
                                  placeholder="Histórico de doenças na família...">{{ old('historia_familiar') }}</textarea>
                        @error('historia_familiar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- História Social --}}
                    <div>
                        <label for="historia_social" class="block text-sm font-medium text-gray-700 mb-2">
                            História Social
                        </label>
                        <textarea name="historia_social"
                                  id="historia_social"
                                  rows="2"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('historia_social') border-red-500 @enderror"
                                  placeholder="Hábitos, profissão, uso de substâncias...">{{ old('historia_social') }}</textarea>
                        @error('historia_social')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- SEÇÃO 3: EXAME E DIAGNÓSTICO --}}
            <div class="mb-8">
                <h3 class="text-xl font-bold text-green-700 mb-4 pb-2 border-b border-green-200">
                    <i class="fas fa-heartbeat mr-2"></i> Exame e Diagnóstico
                </h3>

                <div class="grid grid-cols-1 gap-6">

                    {{-- Exame Físico --}}
                    <div>
                        <label for="exame_fisico" class="block text-sm font-medium text-gray-700 mb-2">
                            Exame Físico
                        </label>
                        <textarea name="exame_fisico"
                                  id="exame_fisico"
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('exame_fisico') border-red-500 @enderror"
                                  placeholder="Findings do exame físico...">{{ old('exame_fisico') }}</textarea>
                        @error('exame_fisico')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Hipótese Diagnóstica --}}
                    <div>
                        <label for="hipotese_diagnostica" class="block text-sm font-medium text-gray-700 mb-2">
                            Hipótese Diagnóstica
                        </label>
                        <textarea name="hipotese_diagnostica"
                                  id="hipotese_diagnostica"
                                  rows="2"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('hipotese_diagnostica') border-red-500 @enderror"
                                  placeholder="Diagnóstico presuntivo (com CID-10)...">{{ old('hipotese_diagnostica') }}</textarea>
                        @error('hipotese_diagnostica')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- SEÇÃO 4: CONDUTA E TRATAMENTO --}}
            <div class="mb-8">
                <h3 class="text-xl font-bold text-green-700 mb-4 pb-2 border-b border-green-200">
                    <i class="fas fa-prescription-bottle mr-2"></i> Conduta e Tratamento
                </h3>

                <div class="grid grid-cols-1 gap-6">

                    {{-- Conduta de Tratamento --}}
                    <div>
                        <label for="conduta_tratamento" class="block text-sm font-medium text-gray-700 mb-2">
                            Conduta/Tratamento
                        </label>
                        <textarea name="conduta_tratamento"
                                  id="conduta_tratamento"
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('conduta_tratamento') border-red-500 @enderror"
                                  placeholder="Plano de tratamento e orientações...">{{ old('conduta_tratamento') }}</textarea>
                        @error('conduta_tratamento')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Prescrição de Medicamentos --}}
                    <div>
                        <label for="prescricao_medicamentos" class="block text-sm font-medium text-gray-700 mb-2">
                            Prescrição de Medicamentos
                        </label>
                        <textarea name="prescricao_medicamentos"
                                  id="prescricao_medicamentos"
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('prescricao_medicamentos') border-red-500 @enderror"
                                  placeholder="Medicamentos prescritos com dosagem...">{{ old('prescricao_medicamentos') }}</textarea>
                        @error('prescricao_medicamentos')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Exames Solicitados --}}
                    <div>
                        <label for="exames_solicitados" class="block text-sm font-medium text-gray-700 mb-2">
                            Exames Solicitados
                        </label>
                        <textarea name="exames_solicitados"
                                  id="exames_solicitados"
                                  rows="2"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('exames_solicitados') border-red-500 @enderror"
                                  placeholder="Exames de laboratório ou imagem...">{{ old('exames_solicitados') }}</textarea>
                        @error('exames_solicitados')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Observações Gerais --}}
                    <div>
                        <label for="observacoes_gerais" class="block text-sm font-medium text-gray-700 mb-2">
                            Observações Gerais
                        </label>
                        <textarea name="observacoes_gerais"
                                  id="observacoes_gerais"
                                  rows="2"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('observacoes_gerais') border-red-500 @enderror"
                                  placeholder="Outras observações relevantes...">{{ old('observacoes_gerais') }}</textarea>
                        @error('observacoes_gerais')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- BOTÕES --}}
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('prontuarios.index') }}"
                   class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm">
                    <i class="fas fa-times mr-2"></i> Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition shadow-md">
                    <i class="fas fa-save mr-2"></i> Salvar Prontuário
                </button>
            </div>

        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('form-prontuario');
        const pacienteId = document.getElementById('paciente_id');
        const profissionalId = document.getElementById('profissional_id');

        form.addEventListener('submit', function(e) {
            if (!pacienteId.value) {
                e.preventDefault();
                alert('Por favor, selecione um paciente!');
                pacienteId.focus();
                return false;
            }

            if (!profissionalId.value) {
                e.preventDefault();
                alert('Por favor, selecione um profissional!');
                profissionalId.focus();
                return false;
            }

            return true;
        });
    });
</script>
@endpush