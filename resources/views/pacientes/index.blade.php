@extends('layouts.app')

@section('title', 'Pacientes Cadastrados')

@section('content')
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

    {{-- CABEÇALHO --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-amber-800">
                <i class="fas fa-users mr-2"></i> Pacientes Cadastrados
            </h2>
            <p class="text-gray-600 mt-2">
                Gerencie os pacientes da clínica
            </p>
        </div>

        {{-- BOTÃO ADICIONAR NOVO PACIENTE --}}
        <a href="{{ route('pacientes.create') }}"
            class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-amber-600 hover:bg-amber-700 transition duration-150">
            <i class="fas fa-user-plus mr-2"></i> Novo Paciente
        </a>
    </div>

    {{-- MENSAGENS DE FEEDBACK --}}
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-md animate-fade-in">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-600 text-xl mr-3"></i>
            <p class="text-green-800 font-semibold">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-md animate-fade-in">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle text-red-600 text-xl mr-3"></i>
            <p class="text-red-800 font-semibold">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    {{-- CARD PRINCIPAL --}}
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">

        {{-- BARRA DE FILTROS E BUSCA --}}
        <div class="bg-amber-50 px-6 py-4 border-b border-amber-200">
            <form action="{{ route('pacientes.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">

                {{-- Campo de Busca --}}
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text"
                            name="busca"
                            id="busca"
                            value="{{ $busca ?? '' }}"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 sm:text-sm"
                            placeholder="Buscar por nome ou CPF...">
                    </div>
                </div>

                {{-- Botões de Ação --}}
                <div class="flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-amber-600 hover:bg-amber-700 transition duration-150">
                        <i class="fas fa-search mr-2"></i> Buscar
                    </button>

                    @if($busca)
                    <a href="{{ route('pacientes.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
                        <i class="fas fa-times mr-2"></i> Limpar
                    </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- INFORMAÇÕES DE RESULTADOS --}}
        <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-2">
                <p class="text-sm text-gray-700">
                    @if($busca)
                    <i class="fas fa-filter mr-1 text-amber-600"></i>
                    Resultados para: <strong class="text-amber-800">"{{ $busca }}"</strong>
                    <span class="text-gray-500 ml-2">|</span>
                    @endif
                    <span class="ml-2">
                        <strong>{{ $pacientes->total() }}</strong>
                        {{ $pacientes->total() === 1 ? 'paciente encontrado' : 'pacientes encontrados' }}
                    </span>
                </p>

                {{-- Exibição de Registros --}}
                @if($pacientes->total() > 0)
                <p class="text-sm text-gray-600">
                    Exibindo
                    <strong>{{ $pacientes->firstItem() }}</strong> a
                    <strong>{{ $pacientes->lastItem() }}</strong> de
                    <strong>{{ $pacientes->total() }}</strong>
                </p>
                @endif
            </div>
        </div>

        {{-- TABELA DE PACIENTES --}}
        @if($pacientes->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-amber-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">
                            <i class="fas fa-user mr-1"></i> Paciente
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider hidden md:table-cell">
                            <i class="fas fa-id-card mr-1"></i> CPF
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider hidden lg:table-cell">
                            <i class="fas fa-birthday-cake mr-1"></i> Idade
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider hidden md:table-cell">
                            <i class="fas fa-phone mr-1"></i> Telefone
                        </th>
                        <th scope="col" class="w-20 px-2 py-3 text-center text-xs font-medium text-amber-800 uppercase tracking-wider hidden lg:table-cell">
                            <i class="fas fa-notes-medical" title="Prontuários"></i>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider hidden lg:table-cell">
                            <i class="fas fa-calendar mr-1"></i> Próxima Consulta
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-amber-800 uppercase tracking-wider">
                            <i class="fas fa-cog mr-1"></i> Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pacientes as $paciente)
                    <tr class="hover:bg-amber-50 transition-colors duration-150">

                        {{-- COLUNA: Nome --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-amber-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-amber-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $paciente->nome_completo }}
                                    </div>
                                    <div class="text-xs text-gray-500 md:hidden">
                                        {{ $paciente->cpf_formatado }}
                                    </div>
                                    @if(!$paciente->ativo)
                                    <span class="inline-flex text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 mt-1">
                                        Inativo
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- COLUNA: CPF --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-mono hidden md:table-cell">
                            {{ $paciente->cpf_formatado }}
                        </td>

                        {{-- COLUNA: Idade --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 hidden lg:table-cell">
                            {{ $paciente->idade }} anos
                            <div class="text-xs text-gray-500">{{ $paciente->data_nascimento_formatada }}</div>
                        </td>

                        {{-- COLUNA: Telefone --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 hidden md:table-cell">
                            {{ $paciente->telefone }}
                        </td>

                        {{-- COLUNA: Prontuários --}}
                        <td class="w-20 px-2 py-4 whitespace-nowrap text-center text-sm hidden lg:table-cell">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                {{ $paciente->prontuarios_count ?? 0 }}
                            </span>
                        </td>

                        {{-- COLUNA: Próxima Consulta --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 hidden lg:table-cell">
                            @php
                            $proximoAgendamento = $paciente->agendamentos->first();
                            @endphp

                            @if($proximoAgendamento)
                            <div class="flex items-center">
                                <i class="fas fa-calendar-check text-green-600 mr-2"></i>
                                <div>
                                    <div class="text-xs font-semibold text-gray-900">
                                        {{ \Carbon\Carbon::parse($proximoAgendamento->data_hora_agendamento)->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($proximoAgendamento->data_hora_agendamento)->format('H:i') }}
                                    </div>
                                </div>
                            </div>
                            @else
                            <span class="text-xs text-gray-400">Sem agendamento</span>
                            @endif
                        </td>

                        {{-- COLUNA: Ações --}}
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">

                                {{-- Botão Ver --}}
                                <a href="{{ route('pacientes.show', $paciente->id) }}"
                                    class="text-amber-600 hover:text-amber-900 transition-colors duration-150"
                                    title="Ver detalhes">
                                    <i class="fas fa-eye"></i>
                                </a>

                                {{-- Botão Editar --}}
                                <a href="{{ route('pacientes.edit', $paciente->id) }}"
                                    class="text-blue-600 hover:text-blue-900 transition-colors duration-150"
                                    title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>

                                {{-- Botão Novo Agendamento --}}
                                <a href="{{ route('agendamentos.create', ['paciente_id' => $paciente->id]) }}"
                                    class="text-green-600 hover:text-green-900 transition-colors duration-150"
                                    title="Novo agendamento">
                                    <i class="fas fa-calendar-plus"></i>
                                </a>

                                {{-- Botão Excluir --}}
                                <button type="button"
                                    data-paciente-id="{{ $paciente->id }}"
                                    data-paciente-nome="{{ $paciente->nome_completo }}"
                                    class="btn-excluir text-red-600 hover:text-red-900 transition-colors duration-150"
                                    title="Excluir">
                                    <i class="fas fa-trash-alt"></i>
                                </button>

                                {{-- Formulário de Exclusão (Hidden) --}}
                                <form id="form-excluir-{{ $paciente->id }}"
                                    action="{{ route('pacientes.destroy', $paciente->id) }}"
                                    method="POST"
                                    class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>

                            </div>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- PAGINAÇÃO --}}
        <div class="bg-white px-6 py-4 border-t border-gray-200">
            {{ $pacientes->links('vendor.pagination.tailwind') }}
        </div>

        @else
        {{-- EMPTY STATE (Nenhum resultado) --}}
        <div class="text-center py-16 px-6">
            @if($busca)
            {{-- Nenhum resultado para a busca --}}
            <i class="fas fa-search text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">
                Nenhum paciente encontrado
            </h3>
            <p class="text-gray-500 mb-6">
                Não encontramos pacientes que correspondam à busca "<strong>{{ $busca }}</strong>".
            </p>
            <a href="{{ route('pacientes.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
                <i class="fas fa-arrow-left mr-2"></i> Ver todos os pacientes
            </a>
            @else
            {{-- Nenhum paciente cadastrado --}}
            <i class="fas fa-user-friends text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">
                Nenhum paciente cadastrado
            </h3>
            <p class="text-gray-500 mb-6">
                Comece adicionando o primeiro paciente ao sistema.
            </p>
            <a href="{{ route('pacientes.create') }}"
                class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-amber-600 hover:bg-amber-700 transition duration-150">
                <i class="fas fa-user-plus mr-2"></i> Cadastrar Primeiro Paciente
            </a>
            @endif
        </div>
        @endif

    </div>

    {{-- ESTATÍSTICAS RÁPIDAS (CARDS ABAIXO DA TABELA) --}}
    @if($pacientes->total() > 0)
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Card: Total de Pacientes --}}
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-amber-500">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-amber-100 rounded-md p-3">
                    <i class="fas fa-users text-amber-600 text-2xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            Total de Pacientes
                        </dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-gray-900">
                                {{ $pacientes->total() }}
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Card: Pacientes Ativos --}}
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                    <i class="fas fa-user-check text-green-600 text-2xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            Pacientes Ativos
                        </dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-gray-900">
                                {{ $pacientes->where('ativo', true)->count() }}
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Card: Com Consultas Agendadas --}}
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                    <i class="fas fa-calendar-check text-blue-600 text-2xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            Com Consultas Agendadas
                        </dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-gray-900">
                                {{ $pacientes->filter(fn($p) => $p->agendamentos->count() > 0)->count() }}
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

    </div>
    @endif

</div>

{{-- MODAL DE CONFIRMAÇÃO DE EXCLUSÃO --}}
<div id="modal-excluir" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">
                Confirmar Exclusão
            </h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Tem certeza que deseja excluir o paciente <strong id="nome-paciente-excluir"></strong>?
                </p>
                <p class="text-xs text-red-600 mt-2">
                    <i class="fas fa-exclamation-circle mr-1"></i> Esta ação é irreversível!
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="btn-confirmar-excluir"
                    class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 transition duration-150 mb-2">
                    <i class="fas fa-trash-alt mr-2"></i> Sim, excluir paciente
                </button>
                <button onclick="fecharModal()"
                    class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-300 transition duration-150">
                    <i class="fas fa-times mr-2"></i> Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

{{-- SCRIPTS --}}
@push('scripts')
<script>
    // Variável global para armazenar o ID do paciente a ser excluído
    let pacienteIdParaExcluir = null;

    /**
     * Exibe modal de confirmação de exclusão
     */
    function confirmarExclusao(pacienteId, nomePaciente) {
        pacienteIdParaExcluir = pacienteId;
        document.getElementById('nome-paciente-excluir').textContent = nomePaciente;
        document.getElementById('modal-excluir').classList.remove('hidden');
    }

    /**
     * Fecha o modal de confirmação
     */
    function fecharModal() {
        pacienteIdParaExcluir = null;
        document.getElementById('modal-excluir').classList.add('hidden');
    }

    /**
     * Inicialização quando o DOM estiver pronto
     */
    document.addEventListener('DOMContentLoaded', function() {

        // Event listener para botões de exclusão (usando delegação)
        document.querySelectorAll('.btn-excluir').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const pacienteId = this.getAttribute('data-paciente-id');
                const pacienteNome = this.getAttribute('data-paciente-nome');
                confirmarExclusao(pacienteId, pacienteNome);
            });
        });

        // Event listener para botão de confirmar exclusão
        document.getElementById('btn-confirmar-excluir').addEventListener('click', function() {
            if (pacienteIdParaExcluir) {
                document.getElementById('form-excluir-' + pacienteIdParaExcluir).submit();
            }
        });

        // Fecha modal ao clicar fora dele
        document.getElementById('modal-excluir').addEventListener('click', function(e) {
            if (e.target === this) {
                fecharModal();
            }
        });

        // Auto-hide das mensagens de feedback após 5 segundos
        const alerts = document.querySelectorAll('.animate-fade-in');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            }, 5000);
        });

        // Atalho de teclado: CTRL+F para focar no campo de busca
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'f') {
                e.preventDefault();
                const campoBusca = document.getElementById('busca');
                if (campoBusca) {
                    campoBusca.focus();
                    campoBusca.select();
                }
            }
        });

    }); // Fim DOMContentLoaded
</script>
@endpush

{{-- ESTILOS CUSTOMIZADOS --}}
@push('styles')
<style>
    /* Animação de fade-in para mensagens */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }

    /* Hover nos botões de ação */
    tbody tr:hover {
        transition: background-color 0.15s ease-in-out;
    }

    /* Destaque no campo de busca focado */
    #busca:focus {
        box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.1);
    }
</style>
@endpush