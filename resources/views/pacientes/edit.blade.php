@extends('layouts.app')

@section('title', 'Editar Paciente')

@section('content')
<div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">

    {{-- CABEÇALHO --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-extrabold text-amber-800">
                    <i class="fas fa-user-edit mr-2"></i> Editar Paciente
                </h2>
                <p class="text-gray-600 mt-2">
                    Atualize os dados do paciente <strong>{{ $paciente->nome_completo }}</strong>
                </p>
            </div>
            <a href="{{ route('pacientes.show', $paciente->id) }}"
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
        <form action="{{ route('pacientes.update', $paciente->id) }}" method="POST">
            @csrf
            @method('PUT')

                        {{-- DADOS PESSOAIS --}}
            <div class="mb-8">
                <h3 class="text-xl font-bold text-amber-700 mb-4 pb-2 border-b border-amber-200">
                    <i class="fas fa-user mr-2"></i> Dados Pessoais
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    {{-- Nome Completo --}}
                    <div class="md:col-span-2">
                        <label for="nome_completo" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome Completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="nome_completo" 
                               id="nome_completo"
                               value="{{ old('nome_completo', $paciente->nome_completo) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('nome_completo') border-red-500 @enderror"
                               required>
                        @error('nome_completo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- CPF --}}
                    <div>
                        <label for="cpf" class="block text-sm font-medium text-gray-700 mb-2">
                            CPF <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="cpf" 
                               id="cpf"
                               value="{{ old('cpf', $paciente->cpf) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('cpf') border-red-500 @enderror"
                               maxlength="14"
                               required>
                        @error('cpf')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Data de Nascimento --}}
                    <div>
                        <label for="data_nascimento" class="block text-sm font-medium text-gray-700 mb-2">
                            Data de Nascimento <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="data_nascimento" 
                               id="data_nascimento"
                               value="{{ old('data_nascimento', $paciente->data_nascimento ? \Carbon\Carbon::parse($paciente->data_nascimento)->format('Y-m-d') : '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('data_nascimento') border-red-500 @enderror"
                               required>
                        @error('data_nascimento')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Sexo --}}
                    <div>
                        <label for="sexo" class="block text-sm font-medium text-gray-700 mb-2">
                            Sexo <span class="text-red-500">*</span>
                        </label>
                        <select name="sexo" 
                                id="sexo"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('sexo') border-red-500 @enderror"
                                required>
                            <option value="">Selecione</option>
                            <option value="masculino" {{ old('sexo', $paciente->sexo) == 'masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="feminino" {{ old('sexo', $paciente->sexo) == 'feminino' ? 'selected' : '' }}>Feminino</option>
                            <option value="outro" {{ old('sexo', $paciente->sexo) == 'outro' ? 'selected' : '' }}>Outro</option>
                            <option value="nao_informado" {{ old('sexo', $paciente->sexo) == 'nao_informado' ? 'selected' : '' }}>Prefiro não informar</option>
                        </select>
                        @error('sexo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email"
                               value="{{ old('email', $paciente->email) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Telefone --}}
                    <div>
                        <label for="telefone" class="block text-sm font-medium text-gray-700 mb-2">
                            Telefone
                        </label>
                        <input type="text" 
                               name="telefone" 
                               id="telefone"
                               value="{{ old('telefone', $paciente->telefone) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('telefone') border-red-500 @enderror"
                               maxlength="15">
                        @error('telefone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ENDEREÇO --}}
            <div class="mb-8">
                <h3 class="text-xl font-bold text-amber-700 mb-4 pb-2 border-b border-amber-200">
                    <i class="fas fa-map-marker-alt mr-2"></i> Endereço
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    {{-- CEP --}}
                    <div>
                        <label for="cep" class="block text-sm font-medium text-gray-700 mb-2">
                            CEP
                        </label>
                        <input type="text" 
                               name="cep" 
                               id="cep"
                               value="{{ old('cep', $paciente->cep) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                               maxlength="9">
                    </div>

                    {{-- Endereço --}}
                    <div class="md:col-span-2">
                        <label for="endereco" class="block text-sm font-medium text-gray-700 mb-2">
                            Endereço
                        </label>
                        <input type="text" 
                               name="endereco" 
                               id="endereco"
                               value="{{ old('endereco', $paciente->endereco) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    </div>

                    {{-- Bairro --}}
                    <div>
                        <label for="bairro" class="block text-sm font-medium text-gray-700 mb-2">
                            Bairro
                        </label>
                        <input type="text" 
                               name="bairro" 
                               id="bairro"
                               value="{{ old('bairro', $paciente->bairro) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    </div>

                    {{-- Cidade --}}
                    <div>
                        <label for="cidade" class="block text-sm font-medium text-gray-700 mb-2">
                            Cidade
                        </label>
                        <input type="text" 
                               name="cidade" 
                               id="cidade"
                               value="{{ old('cidade', $paciente->cidade) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    </div>

                    {{-- Estado --}}
                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                            Estado
                        </label>
                        <select name="estado" 
                                id="estado"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                            <option value="">Selecione</option>
                            <option value="AC" {{ old('estado', $paciente->estado) == 'AC' ? 'selected' : '' }}>Acre</option>
                            <option value="AL" {{ old('estado', $paciente->estado) == 'AL' ? 'selected' : '' }}>Alagoas</option>
                            <option value="AP" {{ old('estado', $paciente->estado) == 'AP' ? 'selected' : '' }}>Amapá</option>
                            <option value="AM" {{ old('estado', $paciente->estado) == 'AM' ? 'selected' : '' }}>Amazonas</option>
                            <option value="BA" {{ old('estado', $paciente->estado) == 'BA' ? 'selected' : '' }}>Bahia</option>
                            <option value="CE" {{ old('estado', $paciente->estado) == 'CE' ? 'selected' : '' }}>Ceará</option>
                            <option value="DF" {{ old('estado', $paciente->estado) == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                            <option value="ES" {{ old('estado', $paciente->estado) == 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                            <option value="GO" {{ old('estado', $paciente->estado) == 'GO' ? 'selected' : '' }}>Goiás</option>
                            <option value="MA" {{ old('estado', $paciente->estado) == 'MA' ? 'selected' : '' }}>Maranhão</option>
                            <option value="MT" {{ old('estado', $paciente->estado) == 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                            <option value="MS" {{ old('estado', $paciente->estado) == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                            <option value="MG" {{ old('estado', $paciente->estado) == 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                            <option value="PA" {{ old('estado', $paciente->estado) == 'PA' ? 'selected' : '' }}>Pará</option>
                            <option value="PB" {{ old('estado', $paciente->estado) == 'PB' ? 'selected' : '' }}>Paraíba</option>
                            <option value="PR" {{ old('estado', $paciente->estado) == 'PR' ? 'selected' : '' }}>Paraná</option>
                            <option value="PE" {{ old('estado', $paciente->estado) == 'PE' ? 'selected' : '' }}>Pernambuco</option>
                            <option value="PI" {{ old('estado', $paciente->estado) == 'PI' ? 'selected' : '' }}>Piauí</option>
                            <option value="RJ" {{ old('estado', $paciente->estado) == 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                            <option value="RN" {{ old('estado', $paciente->estado) == 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                            <option value="RS" {{ old('estado', $paciente->estado) == 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                            <option value="RO" {{ old('estado', $paciente->estado) == 'RO' ? 'selected' : '' }}>Rondônia</option>
                            <option value="RR" {{ old('estado', $paciente->estado) == 'RR' ? 'selected' : '' }}>Roraima</option>
                            <option value="SC" {{ old('estado', $paciente->estado) == 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                            <option value="SP" {{ old('estado', $paciente->estado) == 'SP' ? 'selected' : '' }}>São Paulo</option>
                            <option value="SE" {{ old('estado', $paciente->estado) == 'SE' ? 'selected' : '' }}>Sergipe</option>
                            <option value="TO" {{ old('estado', $paciente->estado) == 'TO' ? 'selected' : '' }}>Tocantins</option>
                        </select>
                    </div>

                </div>
            </div>

            {{-- BOTÕES --}}
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('pacientes.show', $paciente->id) }}"
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
                    <i class="fas fa-save mr-2"></i> Salvar Alterações
                </button>
            </div>

        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Máscara de CPF
    document.getElementById('cpf').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 11) {
            value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
        }
        e.target.value = value;
    });

    // Máscara de Telefone
    document.getElementById('telefone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 11) {
            value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        }
        e.target.value = value;
    });

    // Máscara de CEP
    document.getElementById('cep').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 8) {
            value = value.replace(/(\d{5})(\d{3})/, '$1-$2');
        }
        e.target.value = value;
    });
</script>
@endpush