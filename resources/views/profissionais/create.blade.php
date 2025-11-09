// resources/views/profissionais/create.blade.php
@extends('layouts.app')

@section('title', 'Cadastrar Novo Profissional')

@section('content')
<div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">

    {{-- CABEÇALHO --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-extrabold text-blue-800">
                    <i class="fas fa-user-plus mr-2"></i> Cadastrar Novo Profissional
                </h2>
                <p class="text-gray-600 mt-2">Preencha os dados do profissional abaixo</p>
            </div>
            <a href="{{ route('profissionais.index') }}"
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
                        <i class="fas fa-times-circle mr-1"></i> Erro ao cadastrar profissional
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
    <form action="{{ route('profissionais.store') }}" 
          method="POST" 
          class="bg-white shadow-lg rounded-lg overflow-hidden"
          x-data="formProfissional()"
          @submit="validarFormulario">

        @csrf

        {{-- SEÇÃO: Dados Pessoais --}}
        <div class="bg-blue-50 px-6 py-4 border-b border-blue-200">
            <h3 class="text-lg font-semibold text-blue-800">
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
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('nome_completo') border-red-500 ring-2 ring-red-200 @enderror"
                       placeholder="Ex: Dr. João da Silva Santos">
                
                @error('nome_completo')
                    <p class="mt-2 text-sm text-red-600 flex items-center animate-fade-in">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                @enderror

                <template x-if="erros.nome_completo">
                    <p class="mt-2 text-sm text-red-600 flex items-center animate-fade-in">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <span x-text="erros.nome_completo"></span>
                    </p>
                </template>

                <p class="mt-1 text-xs text-gray-500 flex justify-between">
                    <span>Mínimo de 3 caracteres</span>
                    <span x-text="`${form.nome_completo.length}/150`"></span>
                </p>
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    E-mail <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email') }}"
                           required
                           x-model="form.email"
                           @blur="validarEmail()"
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('email') border-red-500 ring-2 ring-red-200 @enderror"
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

            {{-- Senha e Confirmação (Grid 2 colunas) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Senha --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Senha <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               required
                               minlength="8"
                               x-model="form.password"
                               @blur="validarSenha()"
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('password') border-red-500 ring-2 ring-red-200 @enderror"
                               placeholder="Mínimo 8 caracteres">
                    </div>
                    
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 flex items-center animate-fade-in">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror

                    <template x-if="erros.password">
                        <p class="mt-2 text-sm text-red-600 flex items-center animate-fade-in">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            <span x-text="erros.password"></span>
                        </p>
                    </template>
                </div>

                {{-- Confirmar Senha --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirmar Senha <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation" 
                               required
                               minlength="8"
                               x-model="form.password_confirmation"
                               @blur="validarConfirmacaoSenha()"
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('password_confirmation') border-red-500 ring-2 ring-red-200 @enderror"
                               placeholder="Repita a senha">
                    </div>
                    
                    <template x-if="erros.password_confirmation">
                        <p class="mt-2 text-sm text-red-600 flex items-center animate-fade-in">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            <span x-text="erros.password_confirmation"></span>
                        </p>
                    </template>

                    <template x-if="form.password.length >= 8 && form.password === form.password_confirmation && form.password_confirmation.length > 0">
                        <p class="mt-2 text-sm text-green-600 flex items-center animate-fade-in">
                            <i class="fas fa-check-circle mr-1"></i> Senhas correspondem
                        </p>
                    </template>
                </div>

            </div>

            {{-- Telefone --}}
            <div>
                <label for="telefone" class="block text-sm font-medium text-gray-700 mb-2">
                    Telefone Pessoal
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-phone text-gray-400"></i>
                    </div>
                    <input type="text" 
                           name="telefone" 
                           id="telefone" 
                           value="{{ old('telefone') }}"
                           x-model="form.telefone"
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('telefone') border-red-500 ring-2 ring-red-200 @enderror"
                           placeholder="(00) 00000-0000"
                           x-mask="(99) 99999-9999">
                </div>
                
                @error('telefone')
                    <p class="mt-2 text-sm text-red-600 flex items-center animate-fade-in">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                @enderror
            </div>

        </div>

        {{-- SEÇÃO: Dados Profissionais --}}
        <div class="bg-blue-50 px-6 py-4 border-b border-blue-200 border-t">
            <h3 class="text-lg font-semibold text-blue-800">
                <i class="fas fa-user-md mr-2"></i> Dados Profissionais
            </h3>
        </div>

        <div class="p-6 space-y-6">
            
            {{-- CRM e Especialidade (Grid 2 colunas) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- CRM --}}
                <div>
                    <label for="crm" class="block text-sm font-medium text-gray-700 mb-2">
                        CRM <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-id-badge text-gray-400"></i>
                        </div>
                        <input type="text" 
                               name="crm" 
                               id="crm" 
                               value="{{ old('crm') }}"
                               required
                               maxlength="20"
                               x-model="form.crm"
                               @input="formatarCrm()"
                               @blur="validarCrm()"
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('crm') border-red-500 ring-2 ring-red-200 @enderror"
                               placeholder="Ex: 12345/SP">
                    </div>
                    
                    @error('crm')
                        <p class="mt-2 text-sm text-red-600 flex items-center animate-fade-in">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror

                    <template x-if="erros.crm">
                        <p class="mt-2 text-sm text-red-600 flex items-center animate-fade-in">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            <span x-text="erros.crm"></span>
                        </p>
                    </template>

                    <template x-if="form.crm.length > 0 && !erros.crm && /^[0-9]{4,10}\/[A-Z]{2}$/.test(form.crm)">
                        <p class="mt-2 text-sm text-green-600 flex items-center animate-fade-in">
                            <i class="fas fa-check-circle mr-1"></i> CRM válido
                        </p>
                    </template>
                </div>

                {{-- Especialidade --}}
                <div>
                    <label for="especialidade" class="block text-sm font-medium text-gray-700 mb-2">
                        Especialidade <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-stethoscope text-gray-400"></i>
                        </div>
                        <input type="text" 
                               name="especialidade" 
                               id="especialidade" 
                               value="{{ old('especialidade') }}"
                               required
                               x-model="form.especialidade"
                               @blur="validarCampo('especialidade')"
                               list="especialidades-list"
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('especialidade') border-red-500 ring-2 ring-red-200 @enderror"
                               placeholder="Ex: Psiquiatria">
                    </div>

                    <datalist id="especialidades-list">
                        <option value="Psiquiatria">
                        <option value="Clínica Geral">
                        <option value="Cardiologia">
                        <option value="Pediatria">
                        <option value="Ginecologia">
                        <option value="Ortopedia">
                        <option value="Dermatologia">
                        <option value="Neurologia">
                        <option value="Oftalmologia">
                        <option value="Otorrinolaringologia">
                        <option value="Psicologia">
                        <option value="Nutrição">
                        <option value="Fisioterapia">
                    </datalist>
                    
                    @error('especialidade')
                        <p class="mt-2 text-sm text-red-600 flex items-center animate-fade-in">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror

                    <template x-if="erros.especialidade">
                        <p class="mt-2 text-sm text-red-600 flex items-center animate-fade-in">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            <span x-text="erros.especialidade"></span>
                        </p>
                    </template>

                    <p class="mt-1 text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i> Digite para ver sugestões
                    </p>
                </div>

            </div>

            {{-- Telefone do Consultório --}}
            <div>
                <label for="telefone_consultorio" class="block text-sm font-medium text-gray-700 mb-2">
                    Telefone do Consultório
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-clinic-medical text-gray-400"></i>
                    </div>
                    <input type="text" 
                           name="telefone_consultorio" 
                           id="telefone_consultorio" 
                           value="{{ old('telefone_consultorio') }}"
                           x-model="form.telefone_consultorio"
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('telefone_consultorio') border-red-500 ring-2 ring-red-200 @enderror"
                           placeholder="(00) 00000-0000"
                           x-mask="(99) 99999-9999">
                </div>
                
                @error('telefone_consultorio')
                    <p class="mt-2 text-sm text-red-600 flex items-center animate-fade-in">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                @enderror
            </div>

        </div>

        {{-- SEÇÃO: Formação Acadêmica --}}
        <div class="bg-blue-50 px-6 py-4 border-b border-blue-200 border-t">
            <h3 class="text-lg font-semibold text-blue-800">
                <i class="fas fa-graduation-cap mr-2"></i> Formação Acadêmica
            </h3>
        </div>

        <div class="p-6">
            <textarea name="formacao_academica" 
                      id="formacao_academica" 
                      rows="5"
                      x-model="form.formacao_academica"
                      maxlength="2000"
                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('formacao_academica') border-red-500 ring-2 ring-red-200 @enderror"
                      placeholder="Exemplo:&#10;- Graduação em Medicina pela USP (2015)&#10;- Residência em Psiquiatria - HC/FMUSP (2018)&#10;- Especialização em Psiquiatria Forense (2020)">{{ old('formacao_academica') }}</textarea>
            
            @error('formacao_academica')
                <p class="mt-2 text-sm text-red-600 flex items-center animate-fade-in">
                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                </p>
            @enderror

            <div class="mt-2 flex justify-between items-center">
                <p class="text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i> 
                    Graduação, residências, especializações, cursos relevantes
                </p>
                <p class="text-xs text-gray-500">
                    <span x-text="`${form.formacao_academica.length}/2000`"></span> caracteres
                </p>
            </div>
        </div>

        {{-- SEÇÃO: Observações --}}
        <div class="bg-blue-50 px-6 py-4 border-b border-blue-200 border-t">
            <h3 class="text-lg font-semibold text-blue-800">
                <i class="fas fa-sticky-note mr-2"></i> Observações Gerais
            </h3>
        </div>

        <div class="p-6">
            <textarea name="observacoes" 
                      id="observacoes" 
                      rows="5"
                      x-model="form.observacoes"
                      maxlength="2000"
                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('observacoes') border-red-500 ring-2 ring-red-200 @enderror"
                      placeholder="Informações adicionais sobre o profissional (horários de atendimento, convênios aceitos, etc.)">{{ old('observacoes') }}</textarea>
            
            @error('observacoes')
                <p class="mt-2 text-sm text-red-600 flex items-center animate-fade-in">
                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                </p>
            @enderror

            <div class="mt-2 flex justify-between items-center">
                <p class="text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i> 
                    Informações visíveis internamente na clínica
                </p>
                <p class="text-xs text-gray-500">
                    <span x-text="`${form.observacoes.length}/2000`"></span> caracteres
                </p>
            </div>
        </div>

        {{-- BOTÕES DE AÇÃO --}}
        <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row justify-between items-center gap-3 border-t">
            
            {{-- Informações de Campos Obrigatórios --}}
            <p class="text-sm text-gray-600">
                <i class="fas fa-asterisk text-red-500 text-xs mr-1"></i>
                <span class="font-medium" x-text="`${camposObrigatoriosPreenchidos}/6`"></span> campos obrigatórios preenchidos
            </p>

            {{-- Botões --}}
            <div class="flex gap-3 w-full sm:w-auto">
                <a href="{{ route('profissionais.index') }}"
                   class="flex-1 sm:flex-none inline-flex justify-center items-center px-6 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
                    <i class="fas fa-times mr-2"></i> Cancelar
                </a>
                <button type="submit"
                        :disabled="!formularioValido"
                        class="flex-1 sm:flex-none inline-flex justify-center items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition duration-150">
                    <i class="fas fa-save mr-2"></i> Cadastrar Profissional
                </button>
            </div>
        </div>

    </form>

    {{-- DICAS RÁPIDAS --}}
    <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
        <div class="flex items-start">
            <i class="fas fa-lightbulb text-blue-600 text-xl mr-3 mt-1"></i>
            <div>
                <h4 class="text-sm font-semibold text-blue-800 mb-2">Dicas para Cadastro</h4>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li><i class="fas fa-check mr-1"></i> O CRM deve estar no formato: 00000/UF (ex: 12345/SP)</li>
                    <li><i class="fas fa-check mr-1"></i> A senha deve ter no mínimo 8 caracteres</li>
                    <li><i class="fas fa-check mr-1"></i> Use <kbd class="px-1 py-0.5 bg-blue-100 rounded text-xs">Tab</kbd> para navegar entre os campos</li>
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
    function formProfissional() {
        return {
            form: {
                nome_completo: '',
                email: '',
                password: '',
                password_confirmation: '',
                telefone: '',
                crm: '',
                especialidade: '',
                telefone_consultorio: '',
                formacao_academica: '',
                observacoes: ''
            },

            erros: {},

            validarCampo(campo) {
                switch(campo) {
                    case 'nome_completo':
                        if (this.form.nome_completo.length < 3) {
                            this.erros.nome_completo = 'O nome deve ter no mínimo 3 caracteres';
                        } else {
                            delete this.erros.nome_completo;
                        }
                        break;

                    case 'especialidade':
                        if (this.form.especialidade.length < 3) {
                            this.erros.especialidade = 'A especialidade deve ter no mínimo 3 caracteres';
                        } else {
                            delete this.erros.especialidade;
                        }
                        break;
                }
            },

            validarEmail() {
                if (this.form.email.length === 0) {
                    this.erros.email = 'O e-mail é obrigatório';
                    return;
                }

                const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!regexEmail.test(this.form.email)) {
                    this.erros.email = 'E-mail inválido';
                } else {
                    delete this.erros.email;
                }
            },

            validarSenha() {
                if (this.form.password.length < 8) {
                    this.erros.password = 'A senha deve ter no mínimo 8 caracteres';
                } else {
                    delete this.erros.password;
                }
            },

            validarConfirmacaoSenha() {
                if (this.form.password !== this.form.password_confirmation) {
                    this.erros.password_confirmation = 'As senhas não correspondem';
                } else {
                    delete this.erros.password_confirmation;
                }
            },

            formatarCrm() {
                let crm = this.form.crm.replace(/[^0-9A-Z]/gi, '').toUpperCase();
                
                if (crm.length > 4) {
                    let numero = crm.substring(0, crm.length - 2);
                    let uf = crm.substring(crm.length - 2);
                    numero = numero.substring(0, 10);
                    this.form.crm = numero + '/' + uf;
                } else {
                    this.form.crm = crm;
                }
            },

            validarCrm() {
                if (this.form.crm.length === 0) {
                    this.erros.crm = 'O CRM é obrigatório';
                    return;
                }

                const regex = /^[0-9]{4,10}\/[A-Z]{2}$/;
                
                if (!regex.test(this.form.crm)) {
                    this.erros.crm = 'CRM inválido. Use o formato 00000/UF (ex: 12345/SP)';
                    return;
                }

                const ufsValidas = [
                    'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA',
                    'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN',
                    'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'
                ];

                const partes = this.form.crm.split('/');
                const uf = partes[1];

                if (!ufsValidas.includes(uf)) {
                    this.erros.crm = 'UF inválida. Use uma sigla válida (ex: SP, RJ, MG)';
                    return;
                }

                delete this.erros.crm;
            },

            get camposObrigatoriosPreenchidos() {
                let count = 0;
                if (this.form.nome_completo.length >= 3) count++;
                if (this.form.email.length > 0 && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.form.email)) count++;
                if (this.form.password.length >= 8) count++;
                if (this.form.password === this.form.password_confirmation && this.form.password_confirmation.length >= 8) count++;
                if (this.form.crm.length >= 3 && /^[0-9]{4,10}\/[A-Z]{2}$/.test(this.form.crm)) count++;
                if (this.form.especialidade.length >= 3) count++;
                return count;
            },

            get formularioValido() {
                return this.camposObrigatoriosPreenchidos === 6 && 
                       Object.keys(this.erros).length === 0;
            },

            validarFormulario(event) {
                this.validarCampo('nome_completo');
                this.validarEmail();
                this.validarSenha();
                this.validarConfirmacaoSenha();
                this.validarCrm();
                this.validarCampo('especialidade');

                if (!this.formularioValido) {
                    event.preventDefault();
                    alert('Por favor, corrija os erros no formulário antes de continuar.');
                    
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

{{-- ESTILOS --}}
@push('styles')
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }

    .animate-shake {
        animation: shake 0.5s ease-out;
    }

    kbd {
        font-family: monospace;
        border: 1px solid #cbd5e0;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    input:focus, 
    select:focus, 
    textarea:focus {
        transition: all 0.15s ease-in-out;
    }

    .border-red-500 {
        animation: pulse-error 1s ease-in-out;
    }

    @keyframes pulse-error {
        0%, 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
        50% { box-shadow: 0 0 0 5px rgba(239, 68, 68, 0); }
    }
</style>
@endpush