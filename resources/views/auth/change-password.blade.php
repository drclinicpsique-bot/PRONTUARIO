@extends('layouts.app')

@section('title', 'Trocar Senha')

@section('content')
<div class="max-w-2xl mx-auto py-10 sm:px-6 lg:px-8">

    {{-- CABEÇALHO --}}
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-amber-800">
            <i class="fas fa-key mr-2"></i> Trocar Senha
        </h2>
        <p class="text-gray-600 mt-2">
            Altere sua senha de acesso ao sistema
        </p>
    </div>

    {{-- MENSAGENS DE ERRO --}}
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

    {{-- FORMULÁRIO --}}
    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-amber-600">
        
        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            @method('PUT')

            {{-- INFO DO USUÁRIO --}}
            <div class="mb-6 p-4 bg-amber-50 rounded-lg border border-amber-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-12 h-12 bg-amber-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-bold text-amber-900">{{ Auth::user()->nome_completo }}</p>
                        <p class="text-xs text-amber-700">{{ Auth::user()->email }}</p>
                        <p class="text-xs text-amber-600">{{ Auth::user()->tipo_usuario_label }}</p>
                    </div>
                </div>
            </div>

            {{-- SENHA ATUAL --}}
            <div class="mb-5">
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-1"></i> Senha Atual <span class="text-red-600">*</span>
                </label>
                <input type="password" 
                       name="current_password" 
                       id="current_password" 
                       required
                       autofocus
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('current_password') border-red-500 @enderror"
                       placeholder="Digite sua senha atual">
                @error('current_password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- NOVA SENHA --}}
            <div class="mb-5">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-key mr-1"></i> Nova Senha <span class="text-red-600">*</span>
                </label>
                <input type="password" 
                       name="password" 
                       id="password" 
                       required
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('password') border-red-500 @enderror"
                       placeholder="Digite a nova senha (mínimo 8 caracteres)">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    A senha deve ter no mínimo 8 caracteres
                </p>
            </div>

            {{-- CONFIRMAR NOVA SENHA --}}
            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-check-circle mr-1"></i> Confirmar Nova Senha <span class="text-red-600">*</span>
                </label>
                <input type="password" 
                       name="password_confirmation" 
                       id="password_confirmation" 
                       required
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                       placeholder="Digite a nova senha novamente">
                <p class="mt-1 text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    As senhas devem ser idênticas
                </p>
            </div>

            {{-- ALERTA DE SEGURANÇA --}}
            <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                <div class="flex">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                    <div>
                        <p class="text-sm font-medium text-yellow-800">Dicas de segurança:</p>
                        <ul class="mt-2 text-xs text-yellow-700 space-y-1">
                            <li>• Use uma senha forte com letras, números e símbolos</li>
                            <li>• Não compartilhe sua senha com ninguém</li>
                            <li>• Troque sua senha periodicamente</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- BOTÕES --}}
            <div class="flex flex-col sm:flex-row gap-3">
                <button type="submit"
                        class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-amber-800 text-yellow-50 rounded-lg hover:bg-amber-900 transition font-medium shadow-sm">
                    <i class="fas fa-save mr-2"></i> Alterar Senha
                </button>
                <a href="{{ route('dashboard') }}"
                   class="flex-1 inline-flex justify-center items-center px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                    <i class="fas fa-times mr-2"></i> Cancelar
                </a>
            </div>

        </form>

    </div>

    {{-- INFO ADICIONAL --}}
    <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
        <div class="flex">
            <i class="fas fa-info-circle text-blue-600 mr-3"></i>
            <div>
                <p class="text-sm font-medium text-blue-800">Esqueceu sua senha?</p>
                <p class="text-xs text-blue-700 mt-1">
                    Entre em contato com o administrador do sistema para redefinir sua senha.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection