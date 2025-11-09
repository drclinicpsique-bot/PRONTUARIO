@extends('layouts.app')

@section('title', 'Perfil do Profissional')

@section('content')
@if(!isset($profissional))
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Erro de Carregamento!</strong>
        <span class="block sm:inline">Não foi possível carregar os dados deste profissional. Verifique o ID na URL.</span>
    </div>
</div>
@else
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    {{-- CABEÇALHO E AÇÕES --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 pb-4 border-b border-blue-300">
        <div>
            <h2 class="text-3xl font-extrabold text-blue-800">
                Dr(a). {{ $profissional->usuario->nome_completo }}
            </h2>
            <p class="text-gray-600 mt-1">
                <i class="fas fa-stethoscope mr-2 text-blue-600"></i>
                <span class="font-semibold">{{ $profissional->especialidade }}</span>
                <span class="text-gray-400 mx-2">|</span>
                <i class="fas fa-id-badge mr-1 text-blue-600"></i>
                <span class="font-mono">{{ $profissional->crm_formatado }}</span>
            </p>
        </div>

        {{-- Bloco de Ações --}}
        <div class="flex flex-wrap mt-4 md:mt-0 space-x-2">

            {{-- BOTÃO EDIÇÃO --}}
            <a href="{{ route('profissionais.edit', $profissional->id) }}"
                class="inline-flex items-center px-4 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 transition duration-150">
                <i class="fas fa-edit mr-2"></i> Editar Cadastro
            </a>

            {{-- BOTÃO VER AGENDA --}}
            <a href="{{ route('agendamentos.index', ['profissional_id' => $profissional->id]) }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition duration-150">
                <i class="fas fa-calendar-alt mr-2"></i> Ver Agenda
            </a>

            {{-- Formulário de Exclusão --}}
            <form action="{{ route('profissionais.destroy', $profissional->id) }}"
                method="POST"
                class="inline"
                onsubmit="return confirm('Tem certeza que deseja excluir este profissional? Esta ação é irreversível.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 transition duration-150">
                    <i class="fas fa-trash-alt mr-2"></i> Excluir Profissional
                </button>
            </form>
        </div>
    </div>

    {{-- MENSAGENS DE FEEDBACK --}}
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-md">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-600 text-xl mr-3"></i>
            <p class="text-green-800 font-semibold">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-md">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle text-red-600 text-xl mr-3"></i>
            <p class="text-red-800 font-semibold">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        {{-- SIDEBAR ESQUERDA --}}
        <div class="lg:col-span-1 space-y-6">

            {{-- Card: Dados de Contato --}}
            <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200">
                <h4 class="text-lg font-semibold text-blue-700 mb-4 border-b border-blue-100 pb-2">
                    <i class="fas fa-address-book mr-2"></i> Dados de Contato
                </h4>
                <div class="space-y-3 text-gray-700 text-sm">
                    <p>
                        <i class="fas fa-envelope w-5 mr-3 text-blue-600"></i>
                        {{ $profissional->usuario->email }}
                    </p>

                    @if($profissional->usuario->telefone)
                    <p>
                        <i class="fas fa-phone w-5 mr-3 text-blue-600"></i>
                        {{ $profissional->usuario->telefone }}
                    </p>
                    @endif

                    @if($profissional->telefone_consultorio)
                    <p>
                        <i class="fas fa-clinic-medical w-5 mr-3 text-green-600"></i>
                        {{ $profissional->telefone_consultorio }}
                        <span class="text-xs text-green-600 block ml-8">(Consultório)</span>
                    </p>
                    @endif
                </div>
            </div>

            {{-- Card: Dados Profissionais --}}
            <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200">
                <h4 class="text-lg font-semibold text-blue-700 mb-4 border-b border-blue-100 pb-2">
                    <i class="fas fa-user-graduate mr-2"></i> Dados Profissionais
                </h4>
                <div class="space-y-3 text-gray-700 text-sm">
                    <div>
                        <span class="font-semibold text-gray-600">CRM:</span>
                        <span class="ml-2 font-mono">{{ $profissional->crm }}</span>
                    </div>

                    <div>
                        <span class="font-semibold text-gray-600">Especialidade:</span>
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                            <i class="fas fa-stethoscope mr-1"></i>
                            {{ $profissional->especialidade }}
                        </span>
                    </div>

                    <div>
                        <span class="font-semibold text-gray-600">Cadastro:</span>
                        <span class="ml-2">{{ $profissional->created_at->format('d/m/Y') }}</span>
                    </div>

                    <div>
                        <span class="font-semibold text-gray-600">Status:</span>
                        @if($profissional->ativo)
                        <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                            <i class="fas fa-check-circle mr-1"></i> Ativo
                        </span>
                        @else
                        <span class="ml-2 px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-full">
                            <i class="fas fa-ban mr-1"></i> Inativo
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Card: Formação Acadêmica --}}
            @if($profissional->formacao_academica)
            <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200">
                <h4 class="text-lg font-semibold text-blue-700 mb-4 border-b border-blue-100 pb-2">
                    <i class="fas fa-graduation-cap mr-2"></i> Formação Acadêmica
                </h4>
                <div class="text-sm text-gray-700 leading-relaxed">
                    {{ $profissional->formacao_academica }}
                </div>
            </div>
            @endif

            {{-- Alerta de Próximo Agendamento --}}
            @if($profissional->agendamentos && $profissional->agendamentos->count() > 0)
            @php
            $proximoAgendamento = $profissional->agendamentos->first();
            @endphp
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg shadow-md">
                <div class="flex items-start">
                    <i class="fas fa-calendar-check text-blue-600 text-xl mr-3 mt-1"></i>
                    <div>
                        <p class="font-semibold text-blue-800">Próxima Consulta</p>
                        <p class="text-sm text-blue-700 mt-1">
                            {{ $proximoAgendamento->data_hora_formatada }}
                        </p>
                        <p class="text-xs text-blue-600 mt-1">
                            Paciente: {{ $proximoAgendamento->paciente->nome_completo }}
                        </p>
                        <a href="{{ route('agendamentos.show', $proximoAgendamento->id) }}"
                            class="text-xs text-blue-800 font-medium underline mt-2 inline-block">
                            Ver detalhes →
                        </a>
                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- CONTEÚDO PRINCIPAL (ABAS) --}}
        <div class="lg:col-span-3 bg-white p-6 rounded-lg shadow-lg border border-gray-200">

            {{-- SISTEMA DE ABAS --}}
            <div class="flex border-b border-gray-300 mb-6" x-data="{ abaAtiva: 'agenda' }">

                {{-- Aba Agenda --}}
                <button
                    @click="abaAtiva = 'agenda'"
                    :class="abaAtiva === 'agenda' ? 'border-blue-700 text-blue-700' : 'border-transparent text-stone-500 hover:text-blue-700'"
                    class="py-3 px-4 text-sm font-semibold border-b-2 transition-colors duration-150">
                    <i class="fas fa-calendar-alt mr-1"></i> Agenda
                </button>

                {{-- Aba Prontuários --}}
                <button
                    @click="abaAtiva = 'prontuarios'"
                    :class="abaAtiva === 'prontuarios' ? 'border-blue-700 text-blue-700' : 'border-transparent text-stone-500 hover:text-blue-700'"
                    class="py-3 px-4 text-sm font-medium border-b-2 transition-colors duration-150">
                    <i class="fas fa-notes-medical mr-1"></i> Prontuários
                </button>

                {{-- Aba Estatísticas --}}
                <button
                    @click="abaAtiva = 'estatisticas'"
                    :class="abaAtiva === 'estatisticas' ? 'border-blue-700 text-blue-700' : 'border-transparent text-stone-500 hover:text-blue-700'"
                    class="py-3 px-4 text-sm font-medium border-b-2 transition-colors duration-150">
                    <i class="fas fa-chart-line mr-1"></i> Estatísticas
                </button>

                {{-- Aba Observações --}}
                <button
                    @click="abaAtiva = 'observacoes'"
                    :class="abaAtiva === 'observacoes' ? 'border-blue-700 text-blue-700' : 'border-transparent text-stone-500 hover:text-blue-700'"
                    class="py-3 px-4 text-sm font-medium border-b-2 transition-colors duration-150">
                    <i class="fas fa-sticky-note mr-1"></i> Observações
                </button>

            </div>

            {{-- CONTEÚDO DAS ABAS --}}
            <div x-data="{ abaAtiva: 'agenda' }">

                {{-- CONTEÚDO: Agenda --}}
                <div x-show="abaAtiva === 'agenda'" class="space-y-4">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-xl font-semibold text-blue-700">Próximos Agendamentos</h4>
                        <a href="{{ route('agendamentos.index', ['profissional_id' => $profissional->id]) }}"
                            class="text-sm text-blue-700 hover:text-blue-800 font-medium">
                            Ver agenda completa →
                        </a>
                    </div>

                    @if($profissional->agendamentos && $profissional->agendamentos->count() > 0)
                    <div class="space-y-3">
                        @foreach($profissional->agendamentos->take(10) as $agendamento)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:shadow-md transition-all duration-150">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <span class="text-sm font-semibold text-gray-700">
                                            <i class="far fa-calendar mr-1 text-blue-600"></i>
                                            {{ $agendamento->data_hora_formatada }}
                                        </span>

                                        @php
                                        $statusColors = [
                                        'agendado' => 'bg-blue-100 text-blue-800',
                                        'confirmado' => 'bg-green-100 text-green-800',
                                        'em_atendimento' => 'bg-purple-100 text-purple-800',
                                        'concluido' => 'bg-gray-100 text-gray-800',
                                        'cancelado' => 'bg-red-100 text-red-800',
                                        'falta_paciente' => 'bg-orange-100 text-orange-800',
                                        ];
                                        $colorClass = $statusColors[$agendamento->status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp

                                        <span class="px-2 py-1 {{ $colorClass }} text-xs rounded-full">
                                            {{ $agendamento->status_label }}
                                        </span>
                                    </div>

                                    <p class="text-sm font-medium text-gray-900 mb-1">
                                        <i class="fas fa-user mr-1 text-gray-500"></i>
                                        {{ $agendamento->paciente->nome_completo }}
                                    </p>

                                    @if($agendamento->motivo_consulta)
                                    <p class="text-sm text-gray-700 mt-2">
                                        <strong>Motivo:</strong> {{ Str::limit($agendamento->motivo_consulta, 100) }}
                                    </p>
                                    @endif
                                </div>

                                <a href="{{ route('agendamentos.show', $agendamento->id) }}"
                                    class="ml-4 text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Ver detalhes →
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('agendamentos.create', ['profissional_id' => $profissional->id]) }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition duration-150">
                            <i class="fas fa-calendar-plus mr-2"></i> Novo Agendamento
                        </a>
                    </div>
                    @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <i class="fas fa-calendar-times text-gray-400 text-5xl mb-4"></i>
                        <p class="text-gray-600 mb-4">Nenhum agendamento futuro.</p>
                        <a href="{{ route('agendamentos.create', ['profissional_id' => $profissional->id]) }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition duration-150">
                            <i class="fas fa-calendar-plus mr-2"></i> Criar Agendamento
                        </a>
                    </div>
                    @endif
                </div>

                {{-- CONTEÚDO: Prontuários --}}
                <div x-show="abaAtiva === 'prontuarios'" class="space-y-4">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-xl font-semibold text-blue-700">Prontuários Criados</h4>
                    </div>

                    @if($profissional->prontuarios && $profissional->prontuarios->count() > 0)
                    <div class="space-y-3">
                        @foreach($profissional->prontuarios->take(10) as $prontuario)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:shadow-md transition-all duration-150">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <span class="text-sm font-semibold text-gray-700">
                                            {{ $prontuario->data_atendimento_formatada }}
                                        </span>
                                        @if($prontuario->finalizado)
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                            <i class="fas fa-check-circle mr-1"></i> Finalizado
                                        </span>
                                        @else
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">
                                            <i class="fas fa-clock mr-1"></i> Em aberto
                                        </span>
                                        @endif
                                    </div>

                                    <p class="text-sm font-medium text-gray-900 mb-1">
                                        <i class="fas fa-user mr-1 text-gray-500"></i>
                                        {{ $prontuario->paciente->nome_completo }}
                                    </p>

                                    @if($prontuario->queixa_principal)
                                    <p class="text-sm text-gray-700 mt-2">
                                        <strong>Queixa:</strong> {{ Str::limit($prontuario->queixa_principal, 100) }}
                                    </p>
                                    @endif
                                </div>

                                <a href="{{ route('pacientes.prontuarios.show', [$prontuario->paciente_id, $prontuario->id]) }}"
                                    class="ml-4 text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Ver prontuário →
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <i class="fas fa-notes-medical text-gray-400 text-5xl mb-4"></i>
                        <p class="text-gray-600 mb-2">Nenhum prontuário criado ainda.</p>
                        <p class="text-sm text-gray-500">Os prontuários criados por este profissional aparecerão aqui.</p>
                    </div>
                    @endif
                </div>

                {{-- CONTEÚDO: Estatísticas --}}
                <div x-show="abaAtiva === 'estatisticas'" class="space-y-4">
                    <h4 class="text-xl font-semibold text-blue-700 mb-4">Estatísticas do Profissional</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Card: Total de Consultas --}}
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6 border border-blue-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-blue-700 uppercase tracking-wide">
                                        Total de Consultas
                                    </p>
                                    <p class="text-3xl font-bold text-blue-900 mt-2">
                                        {{ $profissional->agendamentos->count() }}
                                    </p>
                                </div>
                                <div class="bg-blue-200 rounded-full p-4">
                                    <i class="fas fa-calendar-check text-blue-700 text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        {{-- Card: Prontuários Criados --}}
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6 border border-green-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-green-700 uppercase tracking-wide">
                                        Prontuários Criados
                                    </p>
                                    <p class="text-3xl font-bold text-green-900 mt-2">
                                        {{ $profissional->prontuarios->count() }}
                                    </p>
                                </div>
                                <div class="bg-green-200 rounded-full p-4">
                                    <i class="fas fa-notes-medical text-green-700 text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        {{-- Card: Pacientes Atendidos --}}
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-6 border border-purple-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-purple-700 uppercase tracking-wide">
                                        Pacientes Atendidos
                                    </p>
                                    <p class="text-3xl font-bold text-purple-900 mt-2">
                                        {{ $profissional->prontuarios->pluck('paciente_id')->unique()->count() }}
                                    </p>
                                </div>
                                <div class="bg-purple-200 rounded-full p-4">
                                    <i class="fas fa-users text-purple-700 text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        {{-- Card: Consultas Este Mês --}}
                        <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg p-6 border border-amber-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-amber-700 uppercase tracking-wide">
                                        Consultas Este Mês
                                    </p>
                                    <p class="text-3xl font-bold text-amber-900 mt-2">
                                        {{ $profissional->agendamentos->where('data_hora_agendamento', '>=', now()->startOfMonth())->count() }}
                                    </p>
                                </div>
                                <div class="bg-amber-200 rounded-full p-4">
                                    <i class="fas fa-chart-line text-amber-700 text-2xl"></i>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- CONTEÚDO: Observações --}}
                <div x-show="abaAtiva === 'observacoes'" class="space-y-4">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-xl font-semibold text-blue-700">Observações Gerais</h4>
                    </div>

                    @if($profissional->observacoes)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $profissional->observacoes }}</p>
                    </div>
                    @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <i class="fas fa-sticky-note text-gray-400 text-5xl mb-4"></i>
                        <p class="text-gray-600 mb-4">Nenhuma observação cadastrada.</p>
                        <a href="{{ route('profissionais.edit', $profissional->id) }}"
                            class="inline-flex items-center px-4 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 transition duration-150">
                            <i class="fas fa-edit mr-2"></i> Adicionar Observações
                        </a>
                    </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
</div>
@endif

@endsection

{{-- Alpine.js --}}
@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush