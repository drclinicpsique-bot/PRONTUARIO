@extends('layouts.app')

@section('title', 'Cadastrar Novo Paciente')

@section('content')
<div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">

    {{-- CABEÇALHO --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-extrabold text-amber-800">
                    <i class="fas fa-user-plus mr-2"></i> Cadastrar Novo Paciente
                </h2>
                <p class="text-gray-600 mt-2">Preencha os dados do paciente abaixo</p>
            </div>
            <a href="{{ route('pacientes.index') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
                <i class="fas fa-arrow-left mr-2"></i> Voltar à Lista
            </a>
        </div>
    </div>

    {{-- MENSAGENS DE ERRO GERAIS --}}
    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-md animate-shake">
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl mr-3 mt-1"></i>
                <div class="flex-1">
                    <p class="font-semibold text-red-800 mb-2">
                        <i class="fas fa-times-circle mr-1"></i> Erro ao cadastrar paciente
                    </p>
                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- FORMULÁRIO DE CADASTRO --}}
    <form action="{{ route('pacientes.store') }}" 
          method="POST" 
          class="bg-white shadow-lg rounded-lg overflow-hidden"
          x-data="formPaciente()"
          @submit="validarFormulario">

        @csrf

        {{-- SEÇÃO: Dados Pessoais --}}
        <div class="bg-amber-50 px-6 py-4 border-b border-amber-200">
            <h3 class="text-lg font-semibold text-amber-800">
                <i class="fas fa-user mr-2"></i> Dados Pessoais
            </h3>
            <p class="text-xs text-gray-600 mt-1">Campos marcados com <span class="text-red-500">*</span> são obrigatórios</p>
        </div>

        <div class="p-6 space-y-6">
            
            {{-- Nome Completo --}}
            <div>
                <label for="nome_completo" class="block text-sm font-medium text-gray-700 mb-2">
                    Nome Completo <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="nome_completo" 
                       id="nome_completo" 
                       value="{{ old('nome_completo') }}"
                       required
                       autofocus
                       x-model="form.nome_completo"
                       @blur="validarCampo('nome_completo')"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 transition duration-150 @error('nome_completo') border-red-500 ring-2 ring-red-200 @enderror"
                       placeholder="Ex: João da Silva Santos">
                
                {{-- Erro de validação --}}
                @error('nome_completo')
                    <p class="mt-2 text-sm text-red-600 flex items-center animate-fade-in">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                @enderror

                {{-- Validação inline (Alpine.js) --}}
                <template x-if="erros.nome_completo">
                    <p class="mt-2 text-sm text-red-600 flex items-center animate-fade-in">
                        <i class="fas fa-exclamation-circle mr-1"></i> 
                        <span x-text="erros.nome_completo"></span>
                    </p>
                </template>

                {{-- Contador de caracteres --}}
                <p class="mt-1 text-xs text-gray-500 flex justify-between">
                    <span>Mínimo de 3 caracteres</span>
                    <span x-text="`${form.nome_completo.length}/150`"></span>
                </p>
            </div>

            {{-- CPF e Data de Nascimento (Grid 2 colunas) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- CPF --}}
                <div>
                    <label for="cpf" class="block text-sm font-medium text-gray-700 mb-2">
                        CPF <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="cpf" 
                           id="cpf" 
                           value="{{ old('cpf') }}"
                           required
                           maxlength="14"
                           x-model="form.cpf"
                           @blur="validarCampo('cpf')"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 transition duration-150 @error('cpf') border-red-500 ring-2 ring-red-200 @enderror"
                           placeholder="000.000.000-00"
                           x-mask="999.999.999-99">
                    
                    @error('cpf')
                        <p class="mt-2 text-sm text-red-600 flex items-center animate-fade-in">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror

                    <template x-if="erros.cpf">
                        <p class="mt-2 text-sm text-red-600 flex items-center animate-fade-in">
                            <i class="fas fa-exclamation-circle mr-1"></i> 
                            <span x-text="erros.cpf"></span>
                        </p>
                    </template>

                    {{-- Validação visual de CPF --}}
                    <template x-if="form.cpf.length === 14 && !erros.cpf">
                        <p class="mt-2 text-sm text-green-600 flex items-center animate-fade-in">
                            <i class="fas fa-check-circle mr-1"></i> CPF válido
                        </p>
                    </template>
                </div>

                {{-- Data de Nascimento --}}
                <div>
                    <label for="data_nascimento" class="block text-sm font-medium text-gray-700 mb-2">
                        Data de Nascimento <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="data_nascimento" 
                           id="data_nascimento" 
                           value="{{ old('data_nascimento') }}"
                           required
                           max="{{ date('Y-m-d') }}"
                           min="1900-01-01"
                           x-model="form.data_nascimento"
                           @change="calcularIdade()"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 transition duration-150 @error('data_nascimento') border-red-500 ring-2 ring-red-200 @enderror">
                    
                    @error('data_nascimento')
                        <p class="mt-2 text-sm text-red-600 flex items-center animate-fade-in">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror

                    {{-- Exibição da idade calculada --}}
                    <template x-if="idade > 0">
                        <p class="mt-2 text-sm text-gray-600 flex items-center">
                            <i class="fas fa-birthday-cake mr-1 text-amber-600"></i> 
                            <span x-text="`${idade} anos`"></span>
                        </p>
                    </template>
                </div>

            </div>

            {{-- Sexo --}}
            <div>
                <label for="sexo" class="block text-sm font-medium text-gray-700 mb-2">
                    Sexo <span class="text-red-500">*</span>
                </label>
                <select name="sexo" 
                        id="sexo" 
                        required
                        x-model="form.sexo"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 transition duration-150 @error('sexo') border-red-500 ring-2 ring-red-200 @enderror">
                    <option value="">Selecione...</option>
                    <option value="masculino" {{ old('sexo') === 'masculino' ? 'selected' : '' }}>Masculino</option>
                    <option value="feminino" {{ old('sexo') === 'feminino' ? 'selected' : '' }}>Feminino</option>
                    <option value="outro" {{ old('sexo') === 'outro' ? 'selected' : '' }}>Outro</option>
                    <option value="nao_informado" {{ old('sexo') === 'nao_informado' ? 'selected' : '' }}>Prefiro não informar</option>
                </select>
                
                @error('sexo')
                    <p class="mt-2 text-sm text-red-600 flex items-center animate-fade-in">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                @enderror
            </div>

        </div>

        {{-- SEÇÃO: Dados de Contato --}}
        <div class="bg-amber-50 px-6 py-4 border-b border-amber-200 border-t">
            <h3 class="text-lg font-semibold text-amber-800">
                <i class="fas fa-phone mr-2"></i> Dados de Contato
            </h3>
        </div>

        <div class="p-6 space-y-6">
            
            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    E-mail
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email') }}"
                           x-model="form.email"
                           @blur="validarEmail()"
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 transition duration-150 @error('email') border-red-500 ring-2 ring-red-200 @enderror"
                           placeholder="exemplo@email.com">
                </div>
                
                @error('email')
                    <p class="mt-2 text-sm text-red-600 flex items-center animate-fade-in">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                @enderror

                <template x-if="erros.email">
                    <p class="mt-2 text-sm text-red-600 flex items-center animate-fade-in">
                        <i class="fas fa-exclamation-circle mr-1"></i> 
                        <span x-text="erros.email"></span>
                    </p>
                </template>
            </div>

            {{-- Telefone e Telefone de Emergência (Grid 2 colunas) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Telefone --}}
                <div>
                    <label for="telefone" class="block text-sm font-medium text-gray-700 mb-2">
                        Telefone <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-phone text-gray-400"></i>
                        </div>
                        <input type="text" 
                               name="telefone" 
                               id="telefone" 
                               value="{{ old('telefone') }}"
                               required
                               x-model="form.telefone"
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 transition duration-150 @error('telefone') border-red-500 ring-2 ring-red-200 @enderror"
                               placeholder="(00) 00000-0000"
                               x-mask="(99) 99999-9999">
                    </div>
                    
                    @error('telefone')
                        <p class="mt-2 text-sm text-red-600 flex items-center animate-fade-in">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Telefone de Emergência --}}
                <div>
                    <label for="telefone_emergencia" class="block text-sm font-medium text-gray-700 mb-2">
                        Telefone de Emergência
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-phone-square-alt text-gray-400"></i>
                        </div>
                        <input type="text" 
                               name="telefone_emergencia" 
                               id="telefone_emergencia" 
                               value="{{ old('telefone_emergencia') }}"
                               x-model="form.telefone_emergencia"
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 transition duration-150 @error('telefone_emergencia') border-red-500 ring-2 ring-red-200 @enderror"
                               placeholder="(00) 00000-0000"
                               x-mask="(99) 99999-9999">
                    </div>
                    
                    @error('telefone_emergencia')
                        <p class="mt-2 text-sm text-red-600 flex items-center animate-fade-in">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

        </div>

        {{-- SEÇÃO: Endereço --}}
        <div class="bg-amber-50 px-6 py-4 border-b border-amber-200 border-t">
            <h3 class="text-lg font-semibold text-amber-800">
                <i class="fas fa-map-marker-alt mr-2"></i> Endereço
            </h3>
            <p class="text-xs text-gray-600 mt-1">Preencha o CEP para buscar automaticamente</p>
        </div>

        <div class="p-6 space-y-6">
            
            {{-- CEP com Busca Automática --}}
            <div>
                <label for="cep" class="block text-sm font-medium text-gray-700 mb-2">
                    CEP
                </label>
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-map-pin text-gray-400"></i>
                        </div>
                        <input type="text" 
                               name="cep" 
                               id="cep" 
                               value="{{ old('cep') }}"
                               x-model="form.cep"
                               @blur="buscarCep()"
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 transition duration-150 @error('cep') border-red-500 ring-2 ring-red-200 @enderror"
                               placeholder="00000-000"
                               x-mask="99999-999">
                    </div>
                    <button type="button" 
                            @click="buscarCep()"
                            :disabled="buscandoCep"
                            class="px-4 py-2 bg-amber-600 text-white text-sm font-medium rounded-md hover:bg-amber-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition duration-150">
                        <i :class="buscandoCep ? 'fas fa-spinner fa-spin' : 'fas fa-search'" class="mr-1"></i>
                        <span x-text="buscandoCep ? 'Buscando...' : 'Buscar'"></span>
                    </button>
                </div>
                
                @error('cep')
                    <p class="mt-2 text-sm text-red-600 flex items-center animate-fade-in">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                @enderror

                {{-- Mensagem de sucesso da busca --}}
                <template x-if="cepEncontrado">
                    <p class="mt-2 text-sm text-green-600 flex items-center animate-fade-in">
                        <i class="fas fa-check-circle mr-1"></i> Endereço encontrado!
                    </p>
                </template>
            </div>

            {{-- Endereço --}}
            <div>
                <label for="endereco" class="block text-sm font-medium text-gray-700 mb-2">
                    Endereço Completo
                </label>
                <input type="text" 
                       name="endereco" 
                       id="endereco" 
                       value="{{ old('endereco') }}"
                       x-model="form.endereco"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 transition duration-150 @error('endereco') border-red-500 ring-2 ring-red-200 @enderror"
                       placeholder="Rua, número, complemento">
                
                @error('endereco')
                    <p class="mt-2 text-sm text-red-600 flex items-center animate-fade-in">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Cidade e Estado (Grid 2 colunas) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- Cidade --}}
                <div class="md:col-span-2">
                    <label for="cidade" class="block text-sm font-medium text-gray-700 mb-2">
                        Cidade
                    </label>
                    <input type="text" 
                           name="cidade" 
                           id="cidade" 
                           value="{{ old('cidade') }}"
                           x-model="form.cidade"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 transition duration-150 @error('cidade') border-red-500 ring-2 ring-red-200 @enderror"
                           placeholder="Ex: São Paulo">
                    
                    @error('cidade')
                        <p class="mt-2 text-sm text-red-600 flex items-center animate-fade-in">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Estado --}}
                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                        Estado
                    </label>
                    <input type="text" 
                           name="estado" 
                           id="estado" 
                           value="{{ old('estado') }}"
                           maxlength="2"
                           x-model="form.estado"
                           @input="form.estado = form.estado.toUpperCase()"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 transition duration-150 @error('estado') border-red-500 ring-2 ring-red-200 @enderror"
                           placeholder="SP"
                           style="text-transform: uppercase;">
                    
                    @error('estado')
                        <p class="mt-2 text-sm text-red-600 flex items-center animate-fade-in">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

        </div>

        {{-- SEÇÃO: Observações --}}
        <div class="bg-amber-50 px-6 py-4 border-b border-amber-200 border-t">
            <h3 class="text-lg font-semibold text-amber-800">
                <i class="fas fa-sticky-note mr-2"></i> Observações Gerais
            </h3>
        </div>

        <div class="p-6">
            <textarea name="observacoes_gerais" 
                      id="observacoes_gerais" 
                      rows="5"
                      x-model="form.observacoes_gerais"
                      maxlength="2000"
                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 transition duration-150 @error('observacoes_gerais') border-red-500 ring-2 ring-red-200 @enderror"
                      placeholder="Informações adicionais sobre o paciente (alergias, condições especiais, etc.)">{{ old('observacoes_gerais') }}</textarea>
            
            @error('observacoes_gerais')
                <p class="mt-2 text-sm text-red-600 flex items-center animate-fade-in">
                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                </p>
            @enderror

            {{-- Contador de caracteres --}}
            <div class="mt-2 flex justify-between items-center">
                <p class="text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i> 
                    Estas informações são confidenciais (LGPD).
                </p>
                <p class="text-xs text-gray-500">
                    <span x-text="`${form.observacoes_gerais.length}/2000`"></span> caracteres
                </p>
            </div>
        </div>

        {{-- BOTÕES DE AÇÃO --}}
        <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row justify-between items-center gap-3 border-t">
            
            {{-- Informações de Campos Obrigatórios --}}
            <p class="text-sm text-gray-600">
                <i class="fas fa-asterisk text-red-500 text-xs mr-1"></i>
                <span class="font-medium" x-text="`${camposObrigatoriosPreenchidos}/5`"></span> campos obrigatórios preenchidos
            </p>

            {{-- Botões --}}
            <div class="flex gap-3 w-full sm:w-auto">
                <a href="{{ route('pacientes.index') }}"
                   class="flex-1 sm:flex-none inline-flex justify-center items-center px-6 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
                    <i class="fas fa-times mr-2"></i> Cancelar
                </a>
                <button type="submit"
                        :disabled="!formularioValido"
                        class="flex-1 sm:flex-none inline-flex justify-center items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-amber-600 hover:bg-amber-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition duration-150">
                    <i class="fas fa-save mr-2"></i> Cadastrar Paciente
                </button>
            </div>
        </div>

    </form>

    {{-- DICAS RÁPIDAS (SIDEBAR) --}}
    <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
        <div class="flex items-start">
            <i class="fas fa-lightbulb text-blue-600 text-xl mr-3 mt-1"></i>
            <div>
                <h4 class="text-sm font-semibold text-blue-800 mb-2">Dicas para Cadastro</h4>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li><i class="fas fa-check mr-1"></i> Use <kbd class="px-1 py-0.5 bg-blue-100 rounded text-xs">Tab</kbd> para navegar entre os campos</li>
                    <li><i class="fas fa-check mr-1"></i> O CEP busca automaticamente o endereço</li>
                    <li><i class="fas fa-check mr-1"></i> A idade é calculada automaticamente</li>
                    <li><i class="fas fa-check mr-1"></i> Todos os dados são criptografados (LGPD)</li>
                </ul>
            </div>
        </div>
    </div>

</div>
@endsection

{{-- SCRIPTS --}}
@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>

<script>
    /**
     * Alpine.js Component: Gerenciamento do Formulário de Paciente
     */
    function formPaciente() {
        return {
            // Estado do formulário
            form: {
                nome_completo: '{{ old("nome_completo") }}',
                cpf: '{{ old("cpf") }}',
                data_nascimento: '{{ old("data_nascimento") }}',
                sexo: '{{ old("sexo") }}',
                email: '{{ old("email") }}',
                telefone: '{{ old("telefone") }}',
                telefone_emergencia: '{{ old("telefone_emergencia") }}',
                cep: '{{ old("cep") }}',
                endereco: '{{ old("endereco") }}',
                cidade: '{{ old("cidade") }}',
                estado: '{{ old("estado") }}',
                observacoes_gerais: '{{ old("observacoes_gerais") }}'
            },

            // Erros de validação
            erros: {},

            // Estado auxiliares
            idade: 0,
            buscandoCep: false,
            cepEncontrado: false,

            /**
             * Valida campo individual
             */
            validarCampo(campo) {
                switch(campo) {
                    case 'nome_completo':
                        if (this.form.nome_completo.length < 3) {
                            this.erros.nome_completo = 'O nome deve ter no mínimo 3 caracteres';
                        } else {
                            delete this.erros.nome_completo;
                        }
                        break;

                    case 'cpf':
                        const cpfLimpo = this.form.cpf.replace(/\D/g, '');
                        if (cpfLimpo.length !== 11) {
                            this.erros.cpf = 'CPF deve ter 11 dígitos';
                        } else if (!this.validarCPF(cpfLimpo)) {
                            this.erros.cpf = 'CPF inválido';
                        } else {
                            delete this.erros.cpf;
                        }
                        break;
                }
            },

            /**
             * Valida CPF (algoritmo oficial)
             */
            validarCPF(cpf) {

                let soma = 0;
                let resto;

                for (let i = 1; i <= 9; i++) {
                    soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
                }

                resto = (soma * 10) % 11;
                if (resto !== parseInt(cpf.substring(9, 10))) return false;

                soma = 0;
                for (let i = 1; i <= 10; i++) {
                    soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
                }

                resto = (soma * 10) % 11;
                if (resto !== parseInt(cpf.substring(10, 11))) return false;

                return true;
            },

            /**
             * Valida email
             */
            validarEmail() {
                if (this.form.email.length === 0) {
                    delete this.erros.email;
                    return;
                }

                const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!regexEmail.test(this.form.email)) {
                    this.erros.email = 'E-mail inválido';
                } else {
                    delete this.erros.email;
                }
            },

            /**
             * Calcula idade a partir da data de nascimento
             */
            calcularIdade() {
                if (!this.form.data_nascimento) {
                    this.idade = 0;
                    return;
                }

                const hoje = new Date();
                const nascimento = new Date(this.form.data_nascimento);
                let idade = hoje.getFullYear() - nascimento.getFullYear();
                const mes = hoje.getMonth() - nascimento.getMonth();

                    idade--;
                }

                this.idade = idade;
            },

            /**
             * Busca CEP na API ViaCEP
             */
            async buscarCep() {
                const cepLimpo = this.form.cep.replace(/\D/g, '');

                if (cepLimpo.length !== 8) {
                    alert('CEP inválido. Digite um CEP com 8 dígitos.');
                    return;
                }

                this.buscandoCep = true;
                this.cepEncontrado = false;

                try {
                    const response = await fetch(`https://viacep.com.br/ws/${cepLimpo}/json/`);
                    const data = await response.json();

                    if (data.erro) {
                        alert('CEP não encontrado. Verifique e tente novamente.');
                        return;
                    }

                    // Preenche os campos automaticamente
                    this.form.endereco = data.logradouro;
                    this.form.cidade = data.localidade;
                    this.form.estado = data.uf;
                    this.cepEncontrado = true;

                    // Remove a mensagem de sucesso após 3 segundos
                    setTimeout(() => {
                        this.cepEncontrado = false;
                    }, 3000);

                } catch (error) {
                    console.error('Erro ao buscar CEP:', error);
                    alert('Erro ao buscar CEP. Tente novamente mais tarde.');
                } finally {
                    this.buscandoCep = false;
                }
            },

            /**
             * Conta campos obrigatórios preenchidos
             */
            get camposObrigatoriosPreenchidos() {
                let count = 0;
                if (this.form.nome_completo.length >= 3) count++;
                if (this.form.cpf.length === 14) count++;
                if (this.form.data_nascimento) count++;
                if (this.form.sexo) count++;
                if (this.form.telefone.length > 0) count++;
                return count;
            },

            /**
             * Verifica se o formulário está válido
             */
            get formularioValido() {
                return this.camposObrigatoriosPreenchidos === 5 && 
                       Object.keys(this.erros).length === 0;
            },

            /**
             * Validação antes do submit
             */
            validarFormulario(event) {
                // Validação final
                this.validarCampo('nome_completo');
                this.validarCampo('cpf');
                this.validarEmail();

                if (!this.formularioValido) {
                    event.preventDefault();
                    alert('Por favor, corrija os erros no formulário antes de continuar.');
                    
                    // Scroll para o primeiro erro
                    const primeiroErro = document.querySelector('.border-red-500');
                    if (primeiroErro) {
                        primeiroErro.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        primeiroErro.focus();
                    }
                }
            }
        };
    }
</script>
@endpush

{{-- ESTILOS CUSTOMIZADOS --}}
@push('styles')
<style>
    /* Animação de fade-in */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }

    /* Animação de shake para erros */
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }

    .animate-shake {
        animation: shake 0.5s ease-out;
    }

    /* Estilo do kbd (teclado) */
    kbd {
        font-family: monospace;
        border: 1px solid #cbd5e0;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    /* Transição suave nos inputs */
    input:focus, 
    select:focus, 
    textarea:focus {
        transition: all 0.15s ease-in-out;
    }

    /* Estado de erro com pulsação */
    .border-red-500 {
        animation: pulse-error 1s ease-in-out;
    }

    @keyframes pulse-error {
        0%, 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
        50% { box-shadow: 0 0 0 5px rgba(239, 68, 68, 0); }
    }
</style>
@endpush