@extends('layouts.app')

@section('title', 'Cadastrar Resultado do Exame')

@section('content')
<div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">

    {{-- CABEÇALHO --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-amber-900">
                <i class="fas fa-flask mr-2"></i> Cadastrar Resultado do Exame
            </h2>
            <p class="text-amber-700 mt-2">
                Registre os resultados e valores obtidos
            </p>
        </div>
        <a href="{{ route('exames.show', $exame->id) }}"
           class="inline-flex items-center px-4 py-2 bg-yellow-700 text-yellow-50 text-sm rounded-md hover:bg-yellow-800 transition">
            <i class="fas fa-arrow-left mr-2"></i> Voltar
        </a>
    </div>

    {{-- ERROS DE VALIDAÇÃO --}}
    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-md">
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl mr-3 mt-1"></i>
                <div class="flex-1">
                    <h3 class="text-red-800 font-semibold mb-2">Erros de validação:</h3>
                    <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- CARD: INFORMAÇÕES DO EXAME --}}
    <div class="mb-6 bg-amber-50 border-l-4 border-amber-800 p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-bold text-amber-900 mb-4">
            <i class="fas fa-info-circle mr-2"></i> Informações do Exame Solicitado
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-amber-700 mb-1">Tipo de Exame</label>
                <p class="text-sm font-bold text-amber-900">{{ $exame->tipoExame->nome }}</p>
                <p class="text-xs text-amber-700">{{ $exame->tipoExame->categoria }}</p>
            </div>
            <div>
                <label class="block text-xs font-medium text-amber-700 mb-1">Paciente</label>
                <p class="text-sm font-bold text-amber-900">{{ $exame->prontuario->paciente->nome_completo }}</p>
                <p class="text-xs text-amber-700">{{ $exame->prontuario->paciente->cpf_formatado }}</p>
            </div>
            <div>
                <label class="block text-xs font-medium text-amber-700 mb-1">Data da Solicitação</label>
                <p class="text-sm font-bold text-amber-900">{{ $exame->data_solicitacao_formatada }}</p>
            </div>
            <div>
                <label class="block text-xs font-medium text-amber-700 mb-1">Solicitado por</label>
                <p class="text-sm font-bold text-amber-900">Dr(a). {{ $exame->profissionalSolicitante->usuario->nome_completo }}</p>
            </div>
        </div>
    </div>

    {{-- FORMULÁRIO --}}
    <div class="bg-yellow-50 shadow-lg rounded-lg overflow-hidden border-2 border-amber-900">
        
        <form action="{{ route('exames.resultado.store') }}" method="POST" enctype="multipart/form-data" id="form-resultado">
            @csrf

            <input type="hidden" name="exame_solicitado_id" value="{{ $exame->id }}">

            {{-- SEÇÃO: DADOS DA REALIZAÇÃO --}}
            <div class="bg-gradient-to-r from-amber-100 to-yellow-100 px-6 py-4 border-b-2 border-amber-900">
                <h3 class="text-lg font-bold text-amber-900">
                    <i class="fas fa-calendar-check mr-2"></i> Dados da Realização
                </h3>
            </div>

            <div class="p-6 space-y-6">

                {{-- Data de Realização --}}
                <div>
                    <label for="data_realizacao" class="block text-sm font-medium text-amber-900 mb-1">
                        Data de Realização <span class="text-red-600">*</span>
                    </label>
                    <input type="date" 
                           name="data_realizacao" 
                           id="data_realizacao" 
                           value="{{ old('data_realizacao', date('Y-m-d')) }}"
                           max="{{ date('Y-m-d') }}"
                           required
                           class="w-full px-3 py-2 border-2 border-amber-200 rounded-md focus:ring-amber-500 focus:border-amber-500 bg-white @error('data_realizacao') border-red-500 @enderror">
                    @error('data_realizacao')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-amber-700 text-xs mt-1">
                        <i class="fas fa-info-circle mr-1"></i>
                        Data em que o exame foi realizado
                    </p>
                </div>

                {{-- Laboratório Responsável --}}
                <div>
                    <label for="laboratorio_responsavel" class="block text-sm font-medium text-amber-900 mb-1">
                        Laboratório Responsável
                    </label>
                    <input type="text" 
                           name="laboratorio_responsavel" 
                           id="laboratorio_responsavel" 
                           value="{{ old('laboratorio_responsavel') }}"
                           maxlength="150"
                           placeholder="Nome do laboratório que realizou o exame"
                           class="w-full px-3 py-2 border-2 border-amber-200 rounded-md focus:ring-amber-500 focus:border-amber-500 bg-white @error('laboratorio_responsavel') border-red-500 @enderror">
                    @error('laboratorio_responsavel')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- SEÇÃO: RESULTADOS --}}
            <div class="bg-gradient-to-r from-amber-100 to-yellow-100 px-6 py-4 border-y-2 border-amber-900">
                <h3 class="text-lg font-bold text-amber-900">
                    <i class="fas fa-clipboard-check mr-2"></i> Resultados
                </h3>
            </div>

            <div class="p-6 space-y-6">

                {{-- Status dos Valores --}}
                <div>
                    <label class="block text-sm font-medium text-amber-900 mb-2">
                        Status dos Valores
                    </label>
                    <div class="flex gap-4">
                        <label class="inline-flex items-center px-4 py-3 border-2 border-green-300 rounded-lg cursor-pointer hover:bg-green-50 transition">
                            <input type="radio" 
                                   name="valores_normais" 
                                   value="1" 
                                   {{ old('valores_normais') == '1' ? 'checked' : '' }}
                                   class="mr-2 text-green-600 focus:ring-green-500">
                            <span class="text-sm font-medium text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Valores Normais
                            </span>
                        </label>

                        <label class="inline-flex items-center px-4 py-3 border-2 border-red-300 rounded-lg cursor-pointer hover:bg-red-50 transition">
                            <input type="radio" 
                                   name="valores_normais" 
                                   value="0" 
                                   {{ old('valores_normais') == '0' ? 'checked' : '' }}
                                   class="mr-2 text-red-600 focus:ring-red-500">
                            <span class="text-sm font-medium text-red-800">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Valores Alterados
                            </span>
                        </label>

                        <label class="inline-flex items-center px-4 py-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                            <input type="radio" 
                                   name="valores_normais" 
                                   value="" 
                                   {{ old('valores_normais') === null ? 'checked' : '' }}
                                   class="mr-2 text-gray-600 focus:ring-gray-500">
                            <span class="text-sm font-medium text-gray-800">
                                <i class="fas fa-question-circle mr-1"></i> Não Especificado
                            </span>
                        </label>
                    </div>
                </div>

                {{-- Resultado (Texto) --}}
                <div>
                    <label for="resultado_texto" class="block text-sm font-medium text-amber-900 mb-1">
                        Resultado / Laudo Descritivo
                    </label>
                    <textarea name="resultado_texto" 
                              id="resultado_texto" 
                              rows="8"
                              maxlength="10000"
                              placeholder="Descreva o resultado do exame, achados, interpretações e conclusões..."
                              class="w-full px-3 py-2 border-2 border-amber-200 rounded-md focus:ring-amber-500 focus:border-amber-500 bg-white @error('resultado_texto') border-red-500 @enderror">{{ old('resultado_texto') }}</textarea>
                    @error('resultado_texto')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-amber-700 text-xs mt-1">
                        <span id="contador-resultado">0</span> / 10000 caracteres
                    </p>
                </div>

                {{-- Valores Medidos (JSON Dinâmico) --}}
                <div>
                    <label class="block text-sm font-medium text-amber-900 mb-2">
                        Valores Medidos / Parâmetros
                    </label>
                    <div id="valores-container" class="space-y-3">
                        {{-- Valores serão adicionados aqui dinamicamente --}}
                    </div>
                    <button type="button" 
                            id="btn-adicionar-valor"
                            class="mt-3 inline-flex items-center px-4 py-2 bg-amber-700 text-yellow-50 text-sm rounded-md hover:bg-amber-800 transition">
                        <i class="fas fa-plus-circle mr-2"></i> Adicionar Parâmetro
                    </button>
                    <p class="text-amber-700 text-xs mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Adicione os parâmetros medidos (ex: Hemoglobina: 14.5 g/dL)
                    </p>
                </div>

                {{-- Observações do Resultado --}}
                <div>
                    <label for="observacoes_resultado" class="block text-sm font-medium text-amber-900 mb-1">
                        Observações Adicionais
                    </label>
                    <textarea name="observacoes_resultado" 
                              id="observacoes_resultado" 
                              rows="4"
                              maxlength="2000"
                              placeholder="Observações complementares, recomendações, orientações..."
                              class="w-full px-3 py-2 border-2 border-amber-200 rounded-md focus:ring-amber-500 focus:border-amber-500 bg-white @error('observacoes_resultado') border-red-500 @enderror">{{ old('observacoes_resultado') }}</textarea>
                    @error('observacoes_resultado')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-amber-700 text-xs mt-1">
                        <span id="contador-observacoes">0</span> / 2000 caracteres
                    </p>
                </div>

                {{-- Upload de Arquivo (Laudo PDF) --}}
                <div>
                    <label for="arquivo_laudo" class="block text-sm font-medium text-amber-900 mb-1">
                        Arquivo do Laudo (PDF)
                    </label>
                    <input type="file" 
                           name="arquivo_laudo" 
                           id="arquivo_laudo" 
                           accept=".pdf"
                           class="w-full px-3 py-2 border-2 border-amber-200 rounded-md focus:ring-amber-500 focus:border-amber-500 bg-white file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-amber-100 file:text-amber-800 hover:file:bg-amber-200 @error('arquivo_laudo') border-red-500 @enderror">
                    @error('arquivo_laudo')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-amber-700 text-xs mt-1">
                        <i class="fas fa-file-pdf mr-1"></i>
                        Anexe o laudo em PDF do laboratório (opcional)
                    </p>
                </div>

            </div>

            {{-- BOTÕES --}}
            <div class="bg-amber-50 px-6 py-4 border-t-2 border-amber-900 flex flex-col-reverse sm:flex-row justify-end gap-3">
                <a href="{{ route('exames.show', $exame->id) }}"
                   class="inline-flex justify-center items-center px-6 py-3 border-2 border-amber-800 text-sm font-medium rounded-md text-amber-900 bg-white hover:bg-amber-50 transition">
                    <i class="fas fa-times mr-2"></i> Cancelar
                </a>
                <button type="submit"
                        class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-sm font-medium rounded-md text-yellow-50 bg-green-700 hover:bg-green-800 transition shadow-sm">
                    <i class="fas fa-check-circle mr-2"></i> Salvar Resultado
                </button>
            </div>

        </form>

    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // ============================================
        // CONTADORES DE CARACTERES
        // ============================================
        
        const textareaResultado = document.getElementById('resultado_texto');
        const contadorResultado = document.getElementById('contador-resultado');
        
        if (textareaResultado) {
            textareaResultado.addEventListener('input', function() {
                contadorResultado.textContent = this.value.length;
            });
            contadorResultado.textContent = textareaResultado.value.length;
        }

        const textareaObs = document.getElementById('observacoes_resultado');
        const contadorObs = document.getElementById('contador-observacoes');
        
        if (textareaObs) {
            textareaObs.addEventListener('input', function() {
                contadorObs.textContent = this.value.length;
            });
            contadorObs.textContent = textareaObs.value.length;
        }

        // ============================================
        // VALORES MEDIDOS DINÂMICOS
        // ============================================
        
        const valoresContainer = document.getElementById('valores-container');
        const btnAdicionarValor = document.getElementById('btn-adicionar-valor');
        let valorIndex = 0;

        // Função para adicionar novo campo de valor
        function adicionarCampoValor(parametro = '', valor = '') {
            const div = document.createElement('div');
            div.className = 'flex gap-3 items-start';
            div.innerHTML = `
                <div class="flex-1">
                    <input type="text" 
                           name="valores_medidos_parametro[]" 
                           placeholder="Ex: Hemoglobina"
                           value="${parametro}"
                           class="w-full px-3 py-2 border-2 border-amber-200 rounded-md focus:ring-amber-500 focus:border-amber-500 bg-white text-sm">
                </div>
                <div class="flex-1">
                    <input type="text" 
                           name="valores_medidos_valor[]" 
                           placeholder="Ex: 14.5 g/dL"
                           value="${valor}"
                           class="w-full px-3 py-2 border-2 border-amber-200 rounded-md focus:ring-amber-500 focus:border-amber-500 bg-white text-sm">
                </div>
                <button type="button" 
                        class="btn-remover-valor px-3 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 transition"
                        title="Remover">
                    <i class="fas fa-trash-alt"></i>
                </button>
            `;
            
            valoresContainer.appendChild(div);
            valorIndex++;

            // Event listener para remover
            div.querySelector('.btn-remover-valor').addEventListener('click', function() {
                div.remove();
            });
        }

        // Botão adicionar valor
        if (btnAdicionarValor) {
            btnAdicionarValor.addEventListener('click', function() {
                adicionarCampoValor();
            });
        }

        // Adicionar pelo menos um campo inicial
        adicionarCampoValor();

        // ============================================
        // VALIDAÇÃO ANTES DE ENVIAR
        // ============================================
        
        const form = document.getElementById('form-resultado');
        if (form) {
            form.addEventListener('submit', function(e) {
                const dataRealizacao = document.getElementById('data_realizacao').value;
                
                if (!dataRealizacao) {
                    e.preventDefault();
                    alert('⚠️ Por favor, informe a data de realização do exame!');
                    return false;
                }

                // Confirmar envio
                if (!confirm('✅ Confirma o cadastro deste resultado?\n\nEsta ação irá marcar o exame como concluído.')) {
                    e.preventDefault();
                    return false;
                }
            });
        }

    });
</script>
@endpush

@push('styles')
<style>
    /* Estilo para inputs de radio personalizados */
    input[type="radio"]:checked + span {
        font-weight: bold;
    }

    /* Animação suave */
    #valores-container > div {
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-10px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
</style>
@endpush