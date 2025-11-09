<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DrClinic</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-amber-50 to-yellow-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full">
        
        {{-- LOGO E TÍTULO --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-amber-800 rounded-full mb-4">
                <i class="fas fa-user-md text-yellow-50 text-3xl"></i>
            </div>
            <h1 class="text-4xl font-extrabold text-amber-900 mb-2">DrClinic</h1>
            <p class="text-amber-700">Sistema de Gestão de Prontuários</p>
        </div>

        {{-- CARD DE LOGIN --}}
        <div class="bg-white rounded-lg shadow-2xl p-8 border-2 border-amber-900">
            
            <h2 class="text-2xl font-bold text-amber-900 mb-6 text-center">
                <i class="fas fa-lock mr-2"></i> Área Restrita
            </h2>

            {{-- MENSAGENS DE ERRO --}}
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
                        <p class="text-red-800 font-semibold">{{ $errors->first() }}</p>
                    </div>
                </div>
            @endif

            {{-- FORMULÁRIO --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- EMAIL --}}
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-amber-900 mb-2">
                        <i class="fas fa-envelope mr-1"></i> E-mail
                    </label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email') }}"
                           required 
                           autofocus
                           class="w-full px-4 py-3 border-2 border-amber-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition @error('email') border-red-500 @enderror"
                           placeholder="seu@email.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- SENHA --}}
                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-amber-900 mb-2">
                        <i class="fas fa-key mr-1"></i> Senha
                    </label>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           required
                           class="w-full px-4 py-3 border-2 border-amber-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition @error('password') border-red-500 @enderror"
                           placeholder="••••••••">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- LEMBRAR-ME --}}
                <div class="mb-6 flex items-center">
                    <input type="checkbox" 
                           name="remember" 
                           id="remember"
                           class="w-4 h-4 text-amber-800 bg-amber-100 border-amber-300 rounded focus:ring-amber-500">
                    <label for="remember" class="ml-2 text-sm text-amber-800">
                        Manter-me conectado
                    </label>
                </div>

                {{-- BOTÃO ENTRAR --}}
                <button type="submit"
                        class="w-full bg-amber-800 text-yellow-50 py-3 px-4 rounded-lg hover:bg-amber-900 transition font-bold text-lg shadow-lg">
                    <i class="fas fa-sign-in-alt mr-2"></i> Entrar
                </button>

            </form>

            {{-- FOOTER --}}
            <div class="mt-6 text-center">
                <p class="text-xs text-amber-700">
                    <i class="fas fa-shield-alt mr-1"></i>
                    Acesso restrito a profissionais autorizados
                </p>
            </div>

        </div>

        {{-- INFO DE TESTE (APENAS LOCAL) --}}
        @if(app()->environment('local'))
        <div class="mt-6 bg-yellow-50 border-2 border-yellow-400 rounded-lg p-4 text-center">
            <p class="text-xs font-bold text-yellow-900 mb-2">🔧 AMBIENTE DE DESENVOLVIMENTO</p>
            <p class="text-xs text-yellow-800"><strong>Email:</strong> admin@drclinic.com</p>
            <p class="text-xs text-yellow-800"><strong>Senha:</strong> Admin@123</p>
        </div>
        @endif

    </div>

</body>
</html>