<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DrClinic') - Sistema de Prontuário Médico</title>

    <!-- TailwindCSS CDN (Produção: usar Vite) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Configuração Tailwind Personalizada (Paleta Terrosa) -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'clinic-primary': '#d97706', // amber-600
                        'clinic-secondary': '#b45309', // amber-700
                        'clinic-light': '#fef3c7', // amber-50
                    }
                }
            }
        }
    </script>

    <style>
        /* Animações suaves */
        * {
            transition: all 0.2s ease-in-out;
        }

        /* Scroll customizado */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #d97706;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #b45309;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-stone-50 text-gray-800 flex flex-col min-h-screen">

    {{-- NAVBAR --}}
    <nav class="bg-white shadow-md border-b-2 border-amber-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">

                {{-- Logo e Nome --}}
                <div class="flex items-center">
                    <i class="fas fa-clinic-medical text-3xl text-amber-600 mr-3"></i>
                    <span class="text-2xl font-bold text-amber-800">DrClinic</span>
                </div>

                {{-- Menu de Navegação Desktop --}}
                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-amber-100 text-amber-800' : 'text-gray-700 hover:bg-amber-50' }}">
                        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                    </a>
                    <a href="{{ route('pacientes.index') }}"
                        class="px-4 py-2 rounded-md text-sm font-medium {{ request()->routeIs('pacientes.*') ? 'bg-amber-100 text-amber-800' : 'text-gray-700 hover:bg-amber-50' }}">
                        <i class="fas fa-users mr-2"></i> Pacientes
                    </a>
                    <a href="{{ route('profissionais.index') }}"
                        class="px-4 py-2 rounded-md text-sm font-medium {{ request()->routeIs('profissionais.*') ? 'bg-amber-100 text-amber-800' : 'text-gray-700 hover:bg-amber-50' }}">
                        <i class="fas fa-user-md mr-2"></i> Profissionais
                    </a>
                    <a href="{{ route('agendamentos.index') }}"
                        class="px-4 py-2 rounded-md text-sm font-medium {{ request()->routeIs('agendamentos.*') ? 'bg-amber-100 text-amber-800' : 'text-gray-700 hover:bg-amber-50' }}">
                        <i class="fas fa-calendar-alt mr-2"></i> Agendamentos
                    </a>
                    <a href="{{ route('exames.index') }}"
                        class="px-4 py-2 rounded-md text-sm font-medium {{ request()->routeIs('exames.*') ? 'bg-amber-100 text-amber-800' : 'text-gray-700 hover:bg-amber-50' }}">
                        <i class="fas fa-microscope mr-2"></i> Exames
                    </a>
                </div>

                {{-- INFO DO USUÁRIO E LOGOUT (Desktop) --}}
                <div class="hidden md:flex items-center space-x-3">
                    <div class="flex items-center bg-amber-50 rounded-lg px-3 py-2 border border-amber-200">
                        <div class="w-8 h-8 bg-amber-600 rounded-full flex items-center justify-center mr-2">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-xs font-bold text-amber-900 leading-tight">{{ Auth::user()->nome_completo }}
                            </p>
                            <p class="text-xs text-amber-700 leading-tight">{{ Auth::user()->tipo_usuario_label }}</p>
                        </div>
                    </div>

                    {{-- BOTÃO TROCAR SENHA --}}
                    <a href="{{ route('password.edit') }}"
                        class="flex items-center px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-sm font-medium"
                        title="Trocar senha">
                        <i class="fas fa-key mr-2"></i> Senha
                    </a>

                    {{-- BOTÃO LOGOUT --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition text-sm font-medium"
                            title="Sair do sistema">
                            <i class="fas fa-sign-out-alt mr-2"></i> Sair
                        </button>
                    </form>
                </div>

                {{-- Menu Mobile (Hamburguer) --}}
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-gray-700 hover:text-amber-600 p-2">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Menu Mobile (Oculto por padrão) --}}
        <div id="mobile-menu" class="hidden md:hidden bg-amber-50 border-t border-amber-200">

            {{-- Info do Usuário Mobile --}}
            <div class="px-4 py-3 bg-amber-100 border-b border-amber-300">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-amber-600 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-amber-900">{{ Auth::user()->nome_completo }}</p>
                        <p class="text-xs text-amber-700">{{ Auth::user()->email }}</p>
                        <p class="text-xs text-amber-600">{{ Auth::user()->tipo_usuario_label }}</p>
                    </div>
                </div>
            </div>

            {{-- ⬇️⬇️⬇️ ADICIONE AQUI (LINKS DE NAVEGAÇÃO) ⬇️⬇️⬇️ --}}

            {{-- Links de Navegação Mobile --}}
            <a href="{{ route('dashboard') }}"
                class="block px-4 py-3 text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-amber-200 text-amber-900' : 'text-gray-700' }} hover:bg-amber-100">
                <i class="fas fa-tachometer-alt mr-2 w-5"></i> Dashboard
            </a>
            <a href="{{ route('pacientes.index') }}"
                class="block px-4 py-3 text-sm font-medium {{ request()->routeIs('pacientes.*') ? 'bg-amber-200 text-amber-900' : 'text-gray-700' }} hover:bg-amber-100">
                <i class="fas fa-users mr-2 w-5"></i> Pacientes
            </a>
            <a href="{{ route('profissionais.index') }}"
                class="block px-4 py-3 text-sm font-medium {{ request()->routeIs('profissionais.*') ? 'bg-amber-200 text-amber-900' : 'text-gray-700' }} hover:bg-amber-100">
                <i class="fas fa-user-md mr-2 w-5"></i> Profissionais
            </a>
            <a href="{{ route('agendamentos.index') }}"
                class="block px-4 py-3 text-sm font-medium {{ request()->routeIs('agendamentos.*') ? 'bg-amber-200 text-amber-900' : 'text-gray-700' }} hover:bg-amber-100">
                <i class="fas fa-calendar-alt mr-2 w-5"></i> Agendamentos
            </a>
            <a href="{{ route('exames.index') }}"
                class="block px-4 py-3 text-sm font-medium {{ request()->routeIs('exames.*') ? 'bg-amber-200 text-amber-900' : 'text-gray-700' }} hover:bg-amber-100">
                <i class="fas fa-microscope mr-2 w-5"></i> Exames
            </a>

            {{-- TROCAR SENHA MOBILE --}}
            <a href="{{ route('password.edit') }}"
                class="block px-4 py-3 text-sm font-medium text-blue-700 hover:bg-blue-50 border-t border-amber-300">
                <i class="fas fa-key mr-2 w-5"></i> Trocar Senha
            </a>

            {{-- Logout Mobile --}}
            <form method="POST" action="{{ route('logout') }}" class="border-t border-amber-300">
                @csrf
                <button type="submit"
                    class="w-full text-left px-4 py-3 text-sm font-medium text-red-700 hover:bg-red-50">
                    <i class="fas fa-sign-out-alt mr-2 w-5"></i> Sair do Sistema
                </button>
            </form>

        </div>
        {{-- FIM DO MENU MOBILE --}}

        {{-- MENSAGENS DE FEEDBACK (Success/Error) --}}
        @if (session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-md animate-slide-down"
                    role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-2xl mr-3"></i>
                        <div class="flex-1">
                            <p class="font-medium">{{ session('success') }}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()"
                            class="text-green-700 hover:text-green-900">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-md animate-slide-down"
                    role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-2xl mr-3"></i>
                        <div class="flex-1">
                            <p class="font-medium">{{ session('error') }}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()"
                            class="text-red-700 hover:text-red-900">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- CONTEÚDO PRINCIPAL --}}
        <main class="flex-1">
            @yield('content')
        </main>

        {{-- FOOTER --}}
        <footer class="bg-white shadow-md border-t-2 border-amber-200 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-center md:text-left mb-4 md:mb-0">
                        <p class="text-sm text-gray-600">
                            &copy; {{ date('Y') }} <strong class="text-amber-700">DrClinic</strong> - Sistema de
                            Prontuário Médico
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            Desenvolvido com <i class="fas fa-heart text-red-500"></i> por Bruniera
                        </p>
                    </div>
                    <div class="flex items-center space-x-4 text-xs text-gray-500">
                        <span><i class="fas fa-user mr-1"></i> {{ Auth::user()->nome_completo }}</span>
                        <span class="hidden sm:inline">|</span>
                        <span class="hidden sm:inline"><i class="fas fa-clock mr-1"></i>
                            {{ now()->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </footer>

        {{-- JavaScript para Menu Mobile --}}
        <script>
            // Toggle menu mobile
            document.getElementById('mobile-menu-btn').addEventListener('click', function() {
                const menu = document.getElementById('mobile-menu');
                menu.classList.toggle('hidden');
            });

            // Auto-hide mensagens de feedback após 5 segundos
            setTimeout(function() {
                const alerts = document.querySelectorAll('[role="alert"]');
                alerts.forEach(alert => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(() => alert.remove(), 300);
                });
            }, 5000);

            // Fecha menu mobile ao clicar em um link
            document.querySelectorAll('#mobile-menu a').forEach(link => {
                link.addEventListener('click', function() {
                    document.getElementById('mobile-menu').classList.add('hidden');
                });
            });
        </script>

        @stack('scripts')

        <style>
            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-slide-down {
                animation: slideDown 0.3s ease-out;
            }
        </style>
</body>

</html>
