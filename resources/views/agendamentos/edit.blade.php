@extends('layouts.app')

@section('title', 'Editar Agendamento')

@section('content')
<div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">

    {{-- CABEÇALHO --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-extrabold text-green-800">
                    <i class="fas fa-calendar-edit mr-2"></i> Editar Agendamento
                </h2>
                <p class="text-gray-600 mt-2">
                    Agendamento #{{ $agendamento->id }}
                </p>
            </div>
            <a href="{{ route('agendamentos.show', $agendamento->id) }}"
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
        <form action="{{ route('agendamentos.update', $agendamento->id) }}" method="POST" id="form-agendamento">
            @csrf
            @method('PUT')

            {{-- DADOS DO AGENDAMENTO --}}
            <div class="mb-8">
                <h3 class="text-xl font-bold text-green-700 mb-4 pb-2 border-b border-green-200">
                    <i class="fas fa-calendar-check mr-2"></i> Dados do Agendamento
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    {{-- Paciente (Somente Leitura) --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Paciente
                        </label>
                        <input type="hidden" name="paciente_id" value="{{ $agendamento->paciente_id }}">
                        <div class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50">
                            <div class="flex items-center">
                                <i class="fas fa-user text-green-600 mr-3"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $agendamento->paciente->nome_completo }}</div>
                                    <div class="text-sm text-gray-500">{{ $agendamento->paciente->cpf_formatado }} | {{ $agendamento->paciente->telefone }}</div>
                                </div>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            <i class="fas fa-lock mr-1"></i> O paciente não pode ser alterado após criar o agendamento
                        </p>
                    </div>

                    {{-- Profissional --}}
                    <div class="md:col-span-2">
                        <label for="profissional_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Profissional <span class="text-red-500">*</span>
                        </label>
                        <select name="profissional_id" 
                                id="profissional_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('profissional_id') border-red-500 @enderror"
                                required>
                            <option value="">Selecione um profissional</option>
                            @foreach($profissionais as $prof)
                                <option value="{{ $prof->id }}" {{ old('profissional_id', $agendamento->profissional_id) == $prof->id ? 'selected' : '' }}>
                                    Dr(a). {{ $prof->usuario->nome_completo }} - {{ $prof->especialidade }}
                                </option>
                            @endforeach
                        </select>
                        @error('profissional_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Data e Hora do Agendamento --}}
                    <div class="md:col-span-2">
                        <label for="data_hora_agendamento" class="block text-sm font-medium text-gray-700 mb-2">
                            Data e Hora do Agendamento <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" 
                               name="data_hora_agendamento" 
                               id="data_hora_agendamento"
                               value="{{ old('data_hora_agendamento', \Carbon\Carbon::parse($agendamento->data_hora_agendamento)->format('Y-m-d\TH:i')) }}"
                               min="{{ date('Y-m-d\TH:i') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('data_hora_agendamento') border-red-500 @enderror"
                               required>
                        @error('data_hora_agendamento')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            <i class="fas fa-info-circle text-green-600 mr-1"></i>
                            Selecione a nova data e horário da consulta
                        </p>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" 
                                id="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('status') border-red-500 @enderror"
                                required>
                            <option value="agendado" {{ old('status', $agendamento->status) == 'agendado' ? 'selected' : '' }}>Agendado</option>
                            <option value="confirmado" {{ old('status', $agendamento->status) == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                            <option value="em_atendimento" {{ old('status', $agendamento->status) == 'em_atendimento' ? 'selected' : '' }}>Em Atendimento</option>
                            <option value="concluido" {{ old('status', $agendamento->status) == 'concluido' ? 'selected' : '' }}>Concluído</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Duração em Minutos --}}
                    <div>
                        <label for="duracao_minutos" class="block text-sm font-medium text-gray-700 mb-2">
                            Duração (minutos) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="duracao_minutos" 
                               id="duracao_minutos"
                               value="{{ old('duracao_minutos', $agendamento->duracao_minutos) }}"
                               min="15"
                               max="240"
                               step="15"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('duracao_minutos') border-red-500 @enderror"
                               required>
                        @error('duracao_minutos')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Duração prevista da consulta</p>
                    </div>

                    {{-- Motivo da Consulta --}}
                    <div class="md:col-span-2">
                        <label for="motivo_consulta" class="block text-sm font-medium text-gray-700 mb-2">
                            Motivo da Consulta
                        </label>
                        <textarea name="motivo_consulta" 
                                  id="motivo_consulta"
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('motivo_consulta') border-red-500 @enderror"
                                  placeholder="Descreva brevemente o motivo da consulta...">{{ old('motivo_consulta', $agendamento->motivo_consulta) }}</textarea>
                        @error('motivo_consulta')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Observações --}}
                    <div class="md:col-span-2">
                        <label for="observacoes" class="block text-sm font-medium text-gray-700 mb-2">
                            Observações
                        </label>
                        <textarea name="observacoes" 
                                  id="observacoes"
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('observacoes') border-red-500 @enderror"
                                  placeholder="Observações adicionais (ex: primeira consulta, retorno, etc.)">{{ old('observacoes', $agendamento->observacoes) }}</textarea>
                        @error('observacoes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- BOTÕES --}}
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('agendamentos.show', $agendamento->id) }}"
                   class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm">
                    <i class="fas fa-times mr-2"></i> Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition shadow-md">
                    <i class="fas fa-save mr-2"></i> Salvar Alterações
                </button>
            </div>

        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        const dataHoraInput = document.getElementById('data_hora_agendamento');
        const formAgendamento = document.getElementById('form-agendamento');
        const duracaoInput = document.getElementById('duracao_minutos');

        // Validar data/hora não é no passado
        dataHoraInput.addEventListener('change', function() {
            if (this.value) {
                const dataHoraSelecionada = new Date(this.value);
                const agora = new Date();

                if (dataHoraSelecionada < agora) {
                    if (!confirm('A data/horário selecionado é no passado. Deseja continuar?')) {
                        this.value = '';
                        return;
                    }
                }

                // Validar horário comercial
                const hora = dataHoraSelecionada.getHours();
                    if (!confirm('Horário fora do expediente comercial (7h às 19h). Deseja continuar?')) {
                        this.value = '';
                    }
                }
            }
        });

        // Validar duração
        duracaoInput.addEventListener('change', function() {
            const duracao = parseInt(this.value);
            if (duracao < 15) {
                alert('A duração mínima é de 15 minutos!');
                this.value = 15;
            } else if (duracao > 240) {
                alert('A duração máxima é de 240 minutos (4 horas)!');
                this.value = 240;
            }
        });

        // Validar antes de enviar
        formAgendamento.addEventListener('submit', function(e) {
            const profissional = document.getElementById('profissional_id');
            const dataHora = dataHoraInput.value;
            const duracao = duracaoInput.value;
            const status = document.getElementById('status').value;

            // Validar campos obrigatórios
            if (!profissional.value) {
                e.preventDefault();
                alert('Por favor, selecione um profissional!');
                profissional.focus();
                return false;
            }

            if (!dataHora) {
                e.preventDefault();
                alert('Por favor, selecione a data e hora do agendamento!');
                dataHoraInput.focus();
                return false;
            }

            if (!duracao) {
                e.preventDefault();
                alert('Por favor, informe a duração da consulta!');
                duracaoInput.focus();
                return false;
            }

            if (!status) {
                e.preventDefault();
                alert('Por favor, selecione o status!');
                return false;
            }

            // Tudo OK, permite o envio
            return true;
        });

    });
</script>
@endpush