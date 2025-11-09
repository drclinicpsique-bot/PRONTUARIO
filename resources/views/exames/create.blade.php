@extends('layouts.app')

@section('title', 'Solicitar Exame')

@section('content')
    <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">

        {{-- CABEÇALHO --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-extrabold text-amber-900">
                    <i class="fas fa-microscope mr-2"></i> Solicitar Exame
                </h2>
                <p class="text-amber-700 mt-2">
                    Preencha os dados da solicitação de exame
                </p>
            </div>
            <a href="{{ route('exames.index') }}"
                class="inline-flex items-center px-4 py-2 bg-yellow-700 text-yellow-50 text-sm rounded-md hover:bg-yellow-800 transition">
                <i class="fas fa-arrow-left mr-2"></i> Voltar
            </a>
        </div>

        {{-- ERROS DE VALIDAÇÃO --}}
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-md">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl mr-3 mt-1"></i>
                    <div class="flex-1">
                        <h3 class="text-red-800 font-semibold mb-2">Erros de validação:</h3>
                        <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- FORMULÁRIO --}}
        <div class="bg-yellow-50 shadow-lg rounded-lg overflow-hidden border-2 border-amber-900">

            <form action="{{ route('exames.store') }}" method="POST" id="form-solicitar-exame">
                @csrf

                {{-- SEÇÃO: IDENTIFICAÇÃO --}}
                <div class="bg-gradient-to-r from-amber-100 to-yellow-100 px-6 py-4 border-b-2 border-amber-900">
                    <h3 class="text-lg font-bold text-amber-900">
                        <i class="fas fa-id-card mr-2"></i> Identificação
                    </h3>
                </div>

                <div class="p-6 space-y-6">

                    {{-- Prontuário --}}
                    <div>
                        <label for="prontuario_id" class="block text-sm font-medium text-amber-900 mb-1">
                            Prontuário / Paciente <span class="text-red-600">*</span>
                        </label>

                        @if ($prontuario)
                            {{-- SE VIER DE UM PRONTUÁRIO ESPECÍFICO --}}
                            <input type="hidden" name="prontuario_id" value="{{ $prontuario->id }}">
                            <div class="w-full px-4 py-3 border-2 border-amber-300 rounded-md bg-amber-50">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 bg-amber-200 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-amber-800"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-bold text-amber-900">
                                            {{ $prontuario->paciente->nome_completo }}</p>
                                        <p class="text-xs text-amber-700">
                                            Prontuário #{{ $prontuario->id }} |
                                            CPF: {{ $prontuario->paciente->cpf_formatado }} |
                                            Idade: {{ $prontuario->paciente->idade }} anos
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <p class="text-amber-700 text-xs mt-1">
                                <i class="fas fa-lock mr-1"></i>
                                Prontuário vinculado à consulta em andamento
                            </p>
                        @else
                            {{-- SE FOR SOLICITAÇÃO AVULSA (ADMIN/RECEPÇÃO) --}}
                            <select name="prontuario_id" id="prontuario_id" required
                                class="w-full px-3 py-2 border-2 border-amber-200 rounded-md focus:ring-amber-500 focus:border-amber-500 bg-white @error('prontuario_id') border-red-500 @enderror">
                                <option value="">Selecione um paciente</option>
                                {{-- Aqui você pode carregar lista de pacientes se necessário --}}
                            </select>
                            <p class="text-amber-700 text-xs mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Selecione o paciente para criar um prontuário e vincular o exame
                            </p>
                        @endif

                        @error('prontuario_id')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Profissional Solicitante --}}
                    <div>
                        <label for="profissional_solicitante_id" class="block text-sm font-medium text-amber-900 mb-1">
                            Profissional Solicitante <span class="text-red-600">*</span>
                        </label>
                        <select name="profissional_solicitante_id" id="profissional_solicitante_id" required
                            class="w-full px-3 py-2 border-2 border-amber-200 rounded-md focus:ring-amber-500 focus:border-amber-500 bg-white @error('profissional_solicitante_id') border-red-500 @enderror">
                            <option value="">Selecione o profissional</option>
                            @foreach ($profissionais as $profissional)
                                <option value="{{ $profissional->id }}"
                                    {{ old('profissional_solicitante_id') == $profissional->id ? 'selected' : '' }}>
                                    Dr(a). {{ $profissional->usuario->nome_completo }} -
                                    {{ $profissional->especialidade }}
                                </option>
                            @endforeach
                        </select>
                        @error('profissional_solicitante_id')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- SEÇÃO: DADOS DO EXAME --}}
                <div class="bg-gradient-to-r from-amber-100 to-yellow-100 px-6 py-4 border-y-2 border-amber-900">
                    <h3 class="text-lg font-bold text-amber-900">
                        <i class="fas fa-flask mr-2"></i> Dados do Exame
                    </h3>
                </div>

                <div class="p-6 space-y-6">

                    {{-- Tipo de Exame --}}
                    <div>
                        <label for="tipo_exame_id" class="block text-sm font-medium text-amber-900 mb-1">
                            Tipo de Exame <span class="text-red-600">*</span>
                        </label>
                        <select name="tipo_exame_id" id="tipo_exame_id" required
                            class="w-full px-3 py-2 border-2 border-amber-200 rounded-md focus:ring-amber-500 focus:border-amber-500 bg-white @error('tipo_exame_id') border-red-500 @enderror">
                            <option value="">Selecione o tipo de exame</option>
                            @foreach ($tiposExame as $tipo)
                                <option value="{{ $tipo->id }}" data-categoria="{{ $tipo->categoria }}"
                                    data-prazo="{{ $tipo->prazo_entrega_dias }}"
                                    data-preparacao="{{ $tipo->preparacao_necessaria }}"
                                    {{ old('tipo_exame_id') == $tipo->id ? 'selected' : '' }}>
                                    {{ $tipo->nome }}
                                    @if ($tipo->codigo_tuss)
                                        ({{ $tipo->codigo_tuss }})
                                    @endif
                                    - {{ $tipo->categoria }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipo_exame_id')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Card de Informações do Exame --}}
                    <div id="info-exame" class="hidden bg-amber-50 border-l-4 border-amber-800 p-4 rounded">
                        <h4 class="text-sm font-bold text-amber-900 mb-2">Informações do Exame:</h4>
                        <div class="space-y-1 text-sm text-amber-800">
                            <p><strong>Categoria:</strong> <span id="exame-categoria">-</span></p>
                            <p><strong>Prazo de Entrega:</strong> <span id="exame-prazo">-</span></p>
                            <div id="exame-preparacao-container" class="hidden">
                                <p class="text-amber-700 mt-2"><strong>⚠️ Preparação Necessária:</strong></p>
                                <p id="exame-preparacao" class="text-xs bg-yellow-100 p-2 rounded mt-1"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Datas --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Data de Solicitação --}}
                        <div>
                            <label for="data_solicitacao" class="block text-sm font-medium text-amber-900 mb-1">
                                Data de Solicitação <span class="text-red-600">*</span>
                            </label>
                            <input type="date" name="data_solicitacao" id="data_solicitacao"
                                value="{{ old('data_solicitacao', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required
                                class="w-full px-3 py-2 border-2 border-amber-200 rounded-md focus:ring-amber-500 focus:border-amber-500 bg-white @error('data_solicitacao') border-red-500 @enderror">
                            @error('data_solicitacao')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Data Prevista de Resultado --}}
                        <div>
                            <label for="data_prevista_resultado" class="block text-sm font-medium text-amber-900 mb-1">
                                Data Prevista de Resultado
                            </label>
                            <input type="date" name="data_prevista_resultado" id="data_prevista_resultado"
                                value="{{ old('data_prevista_resultado') }}" min="{{ date('Y-m-d') }}"
                                class="w-full px-3 py-2 border-2 border-amber-200 rounded-md focus:ring-amber-500 focus:border-amber-500 bg-white @error('data_prevista_resultado') border-red-500 @enderror">
                            @error('data_prevista_resultado')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-amber-700 text-xs mt-1">
                                <i class="fas fa-calendar mr-1"></i>
                                Data estimada para o resultado ficar disponível
                            </p>
                        </div>

                    </div>

                    {{-- Observações --}}
                    <div>
                        <label for="observacoes_solicitacao" class="block text-sm font-medium text-amber-900 mb-1">
                            Observações da Solicitação
                        </label>
                        <textarea name="observacoes_solicitacao" id="observacoes_solicitacao" rows="4" maxlength="1000"
                            placeholder="Informações adicionais sobre a solicitação, indicação clínica, suspeita diagnóstica, etc."
                            class="w-full px-3 py-2 border-2 border-amber-200 rounded-md focus:ring-amber-500 focus:border-amber-500 bg-white @error('observacoes_solicitacao') border-red-500 @enderror">{{ old('observacoes_solicitacao') }}</textarea>
                        @error('observacoes_solicitacao')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-amber-700 text-xs mt-1">
                            <span id="contador-caracteres">0</span> / 1000 caracteres
                        </p>
                    </div>

                </div>

                {{-- BOTÕES --}}
                <div
                    class="bg-amber-50 px-6 py-4 border-t-2 border-amber-900 flex flex-col-reverse sm:flex-row justify-end gap-3">
                    <a href="{{ route('exames.index') }}"
                        class="inline-flex justify-center items-center px-6 py-3 border-2 border-amber-800 text-sm font-medium rounded-md text-amber-900 bg-white hover:bg-amber-50 transition">
                        <i class="fas fa-times mr-2"></i> Cancelar
                    </a>
                    <button type="submit"
                        class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-sm font-medium rounded-md text-yellow-50 bg-amber-800 hover:bg-amber-900 transition shadow-sm">
                        <i class="fas fa-paper-plane mr-2"></i> Solicitar Exame
                    </button>
                </div>

            </form>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Contador de caracteres
            const textareaObs = document.getElementById('observacoes_solicitacao');
            const contadorCaracteres = document.getElementById('contador-caracteres');

            if (textareaObs) {
                textareaObs.addEventListener('input', function() {
                    contadorCaracteres.textContent = this.value.length;
                });

                // Contar caracteres iniciais (caso tenha old())
                contadorCaracteres.textContent = textareaObs.value.length;
            }

            // Mostrar informações do exame selecionado
            const selectTipoExame = document.getElementById('tipo_exame_id');
            const infoExame = document.getElementById('info-exame');
            const exameCategoria = document.getElementById('exame-categoria');
            const examePrazo = document.getElementById('exame-prazo');
            const examePreparacaoContainer = document.getElementById('exame-preparacao-container');
            const examePreparacao = document.getElementById('exame-preparacao');
            const dataPrevista = document.getElementById('data_prevista_resultado');
            const dataSolicitacao = document.getElementById('data_solicitacao');

            if (selectTipoExame) {
                selectTipoExame.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];

                    if (this.value) {
                        const categoria = selectedOption.dataset.categoria;
                        const prazo = selectedOption.dataset.prazo;
                        const preparacao = selectedOption.dataset.preparacao;

                        // Mostrar informações
                        examePrazo.textContent = prazo ? `${prazo} dias` : 'Não especificado';

                        // Preparação
                        if (preparacao && preparacao !== 'null') {
                            examePreparacao.textContent = preparacao;
                            examePreparacaoContainer.classList.remove('hidden');
                        } else {
                            examePreparacaoContainer.classList.add('hidden');
                        }

                        // Calcular data prevista automaticamente
                        if (prazo && dataSolicitacao.value) {
                            const dataInicio = new Date(dataSolicitacao.value);
                            dataInicio.setDate(dataInicio.getDate() + parseInt(prazo));
                            dataPrevista.value = dataInicio.toISOString().split('T')[0];
                        }

                        infoExame.classList.remove('hidden');
                    } else {
                        infoExame.classList.add('hidden');
                    }
                });
            }

            // Atualizar data prevista ao mudar data de solicitação
            if (dataSolicitacao && selectTipoExame) {
                dataSolicitacao.addEventListener('change', function() {
                    const selectedOption = selectTipoExame.options[selectTipoExame.selectedIndex];
                    const prazo = selectedOption.dataset.prazo;

                    if (prazo && this.value) {
                        const dataInicio = new Date(this.value);
                        dataInicio.setDate(dataInicio.getDate() + parseInt(prazo));
                        dataPrevista.value = dataInicio.toISOString().split('T')[0];
                    }
                });
            }

            // Validação antes de enviar
            const form = document.getElementById('form-solicitar-exame');
            if (form) {
                form.addEventListener('submit', function(e) {
                        const prontuarioId = document.getElementById('prontuario_id').value;
                        const tipoExameId = document.getElementById('tipo_exame_id').value;
                        const profissionalId = document.getElementById('profissional_solicitante_id').value;

                        e.preventDefault();
                        alert('⚠️ Por favor, preencha todos os campos obrigatórios!');
                        return false;
                    }
                });
        }

        });
    </script>
@endpush

@push('styles')
    <style>
        /* Animação suave para mostrar card de informações */
        #info-exame {
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush
