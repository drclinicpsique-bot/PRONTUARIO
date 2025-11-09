@extends('layouts.app')

@section('title', 'Agendamentos')

@section('content')
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

        {{-- CABEÇALHO --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h2 class="text-3xl font-extrabold text-amber-900">
                    <i class="fas fa-calendar-alt mr-2"></i> Agendamentos
                </h2>
                <p class="text-amber-700 mt-2">
                    Gerencie os agendamentos e consultas da clínica
                </p>
            </div>

            {{-- BOTÃO NOVO AGENDAMENTO --}}
            <a href="{{ route('agendamentos.create') }}"
                class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-yellow-50 bg-amber-800 hover:bg-amber-900 transition duration-150">
                <i class="fas fa-calendar-plus mr-2"></i> Novo Agendamento
            </a>
        </div>

        {{-- MENSAGENS DE FEEDBACK --}}
        @if (session('success'))
            <div class="mb-6 bg-yellow-50 border-l-4 border-amber-600 p-4 rounded-lg shadow-md animate-fade-in">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-amber-700 text-xl mr-3"></i>
                    <p class="text-amber-900 font-semibold">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-md animate-fade-in">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl mr-3"></i>
                    <p class="text-red-800 font-semibold">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- CARD PRINCIPAL --}}
        <div class="bg-yellow-50 shadow-lg rounded-lg overflow-hidden border-2 border-amber-900">

            {{-- BARRA DE FILTROS --}}
            <div class="bg-gradient-to-r from-amber-100 to-yellow-100 px-6 py-4 border-b-2 border-amber-900">
                <form action="{{ route('agendamentos.index') }}" method="GET"
                    class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    {{-- Data Início --}}
                    <div>
                        <label for="data_inicio" class="block text-xs font-medium text-amber-900 mb-1">Data Início</label>
                        <input type="date" name="data_inicio" id="data_inicio"
                            value="{{ $filtros['data_inicio'] ?? '' }}"
                            class="w-full px-3 py-2 text-sm border-2 border-amber-200 rounded-md focus:ring-amber-500 focus:border-amber-500 bg-white">
                    </div>

                    {{-- Data Fim --}}
                    <div>
                        <label for="data_fim" class="block text-xs font-medium text-amber-900 mb-1">Data Fim</label>
                        <input type="date" name="data_fim" id="data_fim" value="{{ $filtros['data_fim'] ?? '' }}"
                            class="w-full px-3 py-2 text-sm border-2 border-amber-200 rounded-md focus:ring-amber-500 focus:border-amber-500 bg-white">
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="block text-xs font-medium text-amber-900 mb-1">Status</label>
                        <select name="status" id="status"
                            class="w-full px-3 py-2 text-sm border-2 border-amber-200 rounded-md focus:ring-amber-500 focus:border-amber-500 bg-white">
                            <option value="">Todos</option>
                            <option value="agendado" {{ ($filtros['status'] ?? '') == 'agendado' ? 'selected' : '' }}>
                                Agendado</option>
                            <option value="confirmado" {{ ($filtros['status'] ?? '') == 'confirmado' ? 'selected' : '' }}>
                                Confirmado</option>
                            <option value="em_atendimento"
                                {{ ($filtros['status'] ?? '') == 'em_atendimento' ? 'selected' : '' }}>Em Atendimento
                            </option>
                            <option value="concluido" {{ ($filtros['status'] ?? '') == 'concluido' ? 'selected' : '' }}>
                                Concluído</option>
                            <option value="cancelado" {{ ($filtros['status'] ?? '') == 'cancelado' ? 'selected' : '' }}>
                                Cancelado</option>
                            <option value="falta_paciente"
                                {{ ($filtros['status'] ?? '') == 'falta_paciente' ? 'selected' : '' }}>Falta</option>
                        </select>
                    </div>

                    {{-- Botões --}}
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-amber-800 text-yellow-50 text-sm rounded-md hover:bg-amber-900 transition font-medium">
                            <i class="fas fa-filter mr-1"></i> Filtrar
                        </button>
                        @if (!empty(array_filter($filtros)))
                            <a href="{{ route('agendamentos.index') }}"
                                class="px-4 py-2 bg-yellow-700 text-yellow-50 text-sm rounded-md hover:bg-yellow-800 transition">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- INFORMAÇÕES DE RESULTADOS --}}
            <div class="px-6 py-3 bg-yellow-50 border-b-2 border-amber-200">
                <p class="text-sm text-amber-900">
                    @if (!empty(array_filter($filtros)))
                        <i class="fas fa-filter mr-1 text-amber-800 font-bold"></i>
                        <span class="text-amber-900 font-semibold">Filtros ativos</span>
                        <span class="text-amber-700 mx-2">|</span>
                    @endif
                    <strong>{{ $agendamentos->total() }}</strong>
                    {{ $agendamentos->total() === 1 ? 'agendamento encontrado' : 'agendamentos encontrados' }}
                </p>
            </div>

            {{-- TABELA DE AGENDAMENTOS --}}
            @if ($agendamentos->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-amber-200">
                        <thead class="bg-gradient-to-r from-amber-200 to-yellow-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-amber-900 uppercase">
                                    <i class="far fa-calendar mr-1"></i> Data/Hora
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-amber-900 uppercase">
                                    <i class="fas fa-user mr-1"></i> Paciente
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-bold text-amber-900 uppercase hidden md:table-cell">
                                    <i class="fas fa-user-md mr-1"></i> Profissional
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-bold text-amber-900 uppercase hidden lg:table-cell">
                                    <i class="fas fa-stethoscope mr-1"></i> Motivo
                                </th>
                                <th class="w-24 px-2 py-3 text-center text-xs font-bold text-amber-900 uppercase">
                                    <i class="fas fa-info-circle" title="Status"></i>
                                </th>
                                <th class="px-4 py-3 text-right text-xs font-bold text-amber-900 uppercase">
                                    <i class="fas fa-cog mr-1"></i> Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-amber-100">
                            @foreach ($agendamentos as $agendamento)
                                <tr class="hover:bg-yellow-50 transition-colors duration-150">

                                    {{-- Data/Hora --}}
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-amber-900">
                                            {{ \Carbon\Carbon::parse($agendamento->data_hora_agendamento)->format('d/m/Y') }}
                                        </div>
                                        <div class="text-xs text-amber-700">
                                            {{ \Carbon\Carbon::parse($agendamento->data_hora_agendamento)->format('H:i') }}
                                        </div>
                                    </td>

                                    {{-- Paciente --}}
                                    <td class="px-4 py-4">
                                        <div class="text-sm font-medium text-amber-900">
                                            {{ $agendamento->paciente->nome_completo }}
                                        </div>
                                        <div class="text-xs text-amber-700">
                                            {{ $agendamento->paciente->telefone }}
                                        </div>
                                    </td>

                                    {{-- Profissional --}}
                                    <td class="px-4 py-4 hidden md:table-cell">
                                        <div class="text-sm text-amber-900">
                                            Dr(a). {{ $agendamento->profissional->usuario->nome_completo }}
                                        </div>
                                        <div class="text-xs text-amber-700">
                                            {{ $agendamento->profissional->especialidade }}
                                        </div>
                                    </td>

                                    {{-- Motivo --}}
                                    <td class="px-4 py-4 hidden lg:table-cell">
                                        <div class="text-sm text-amber-900">
                                            {{ Str::limit($agendamento->motivo_consulta ?? 'Não informado', 40) }}
                                        </div>
                                    </td>

                                    {{-- Status --}}
                                    <td class="w-24 px-2 py-4 text-center">
                                        @php
                                            $statusColors = [
                                                'agendado' => 'bg-blue-100 text-blue-800',
                                                'confirmado' => 'bg-yellow-100 text-amber-900',
                                                'em_atendimento' => 'bg-amber-100 text-amber-800',
                                                'concluido' => 'bg-green-100 text-green-800',
                                                'cancelado' => 'bg-red-100 text-red-800',
                                                'falta_paciente' => 'bg-orange-100 text-orange-800',
                                            ];
                                            $colorClass =
                                                $statusColors[$agendamento->status] ?? 'bg-amber-100 text-amber-800';

                                            $statusLabels = [
                                                'agendado' => 'Agendado',
                                                'confirmado' => 'Confirmado',
                                                'em_atendimento' => 'Em Atendimento',
                                                'concluido' => 'Concluído',
                                                'cancelado' => 'Cancelado',
                                                'falta_paciente' => 'Falta',
                                            ];
                                            $label = $statusLabels[$agendamento->status] ?? $agendamento->status;
                                        @endphp

                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $colorClass }}">
                                            {{ $label }}
                                        </span>
                                    </td>

                                    {{-- Ações --}}
                                    <td class="px-4 py-4 whitespace-nowrap text-right text-sm">
                                        <div class="flex items-center justify-end space-x-2">

                                            {{-- Ver --}}
                                            <a href="{{ route('agendamentos.show', $agendamento->id) }}"
                                                class="text-amber-800 hover:text-amber-900 font-bold" title="Ver detalhes">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            {{-- INICIAR CONSULTA / STATUS --}}
                                            @if ($agendamento->status == 'concluido')
                                                {{-- CONSULTA FINALIZADA --}}
                                                <span class="text-green-600 font-bold" title="Consulta Finalizada">
                                                    <i class="fas fa-check-circle"></i>
                                                </span>
                                            @elseif($agendamento->emAtendimento())
                                                {{-- EM ATENDIMENTO --}}
                                                <span class="text-amber-700 font-bold animate-pulse"
                                                    title="Em Atendimento Agora">
                                                    <i class="fas fa-hourglass-half"></i>
                                                </span>
                                            @elseif($agendamento->podeIniciarConsulta())
                                                {{-- PODE INICIAR --}}
                                                <button type="button"
                                                    class="btn-iniciar-consulta text-amber-800 hover:text-amber-900 font-bold"
                                                    data-agendamento-id="{{ $agendamento->id }}"
                                                    data-paciente-nome="{{ $agendamento->paciente->nome_completo }}"
                                                    data-profissional-nome="Dr(a). {{ $agendamento->profissional->usuario->nome_completo }}"
                                                    data-duracao="{{ $agendamento->duracao_minutos }}"
                                                    title="Iniciar Consulta">
                                                    <i class="fas fa-play-circle"></i>
                                                </button>
                                            @elseif($agendamento->status == 'cancelado')
                                                {{-- CANCELADO --}}
                                                <span class="text-red-600 font-bold" title="Consulta Cancelada">
                                                    <i class="fas fa-ban"></i>
                                                </span>
                                            @elseif($agendamento->status == 'falta_paciente')
                                                {{-- FALTA DO PACIENTE --}}
                                                <span class="text-orange-600 font-bold" title="Paciente Faltou">
                                                    <i class="fas fa-user-slash"></i>
                                                </span>
                                            @endif

                                            {{-- Editar --}}
                                            @if ($agendamento->status != 'cancelado' && $agendamento->status != 'concluido' && !$agendamento->emAtendimento())
                                                <a href="{{ route('agendamentos.edit', $agendamento->id) }}"
                                                    class="text-yellow-700 hover:text-yellow-900 font-bold"
                                                    title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif

                                            {{-- Cancelar --}}
                                            @if ($agendamento->podeCancelar())
                                                <button type="button" data-agendamento-id="{{ $agendamento->id }}"
                                                    data-paciente-nome="{{ $agendamento->paciente->nome_completo }}"
                                                    data-data-hora="{{ \Carbon\Carbon::parse($agendamento->data_hora_agendamento)->format('d/m/Y H:i') }}"
                                                    class="btn-cancelar text-red-600 hover:text-red-900 font-bold"
                                                    title="Cancelar">
                                                    <i class="fas fa-times-circle"></i>
                                                </button>
                                            @endif

                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- PAGINAÇÃO --}}
                <div class="bg-yellow-50 px-6 py-4 border-t-2 border-amber-200">
                    {{ $agendamentos->appends($filtros)->links('vendor.pagination.tailwind') }}
                </div>
            @else
                {{-- EMPTY STATE --}}
                <div class="text-center py-16 px-6">
                    <i class="fas fa-calendar-times text-amber-300 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-amber-900 mb-2">
                        Nenhum agendamento encontrado
                    </h3>
                    <p class="text-amber-800 mb-6">
                        @if (!empty(array_filter($filtros)))
                            Não encontramos agendamentos com os filtros selecionados.
                        @else
                            Comece criando o primeiro agendamento.
                        @endif
                    </p>
                    <a href="{{ route('agendamentos.create') }}"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-yellow-50 bg-amber-800 hover:bg-amber-900">
                        <i class="fas fa-calendar-plus mr-2"></i> Novo Agendamento
                    </a>
                </div>
            @endif

        </div>

    </div>

    {{-- MODAL DE CONFIRMAÇÃO DE CANCELAMENTO --}}
    <div id="modal-cancelar" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border-2 w-96 shadow-lg rounded-md bg-yellow-50 border-amber-900">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-lg leading-6 font-bold text-amber-900 mt-4">
                    Cancelar Agendamento
                </h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-amber-800">
                        Confirma o cancelamento do agendamento de <strong id="nome-paciente-cancelar"></strong>?
                    </p>
                    <p class="text-xs text-amber-700 mt-2">
                        <strong id="data-hora-cancelar"></strong>
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <form id="form-cancelar" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 mb-2">
                            <i class="fas fa-times-circle mr-2"></i> Sim, cancelar agendamento
                        </button>
                    </form>
                    <button onclick="fecharModal()"
                        class="px-4 py-2 bg-yellow-700 text-yellow-50 text-base font-medium rounded-md w-full shadow-sm hover:bg-yellow-800">
                        Voltar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DE INICIAR CONSULTA --}}
    <div id="modal-iniciar-consulta"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border-2 w-96 shadow-lg rounded-md bg-yellow-50 border-amber-900">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-amber-100">
                    <i class="fas fa-play-circle text-amber-800 text-2xl"></i>
                </div>
                <h3 class="text-lg leading-6 font-bold text-amber-900 mt-4">
                    Iniciar Consulta
                </h3>
                <div class="mt-2 px-7 py-3 text-left">
                    <div class="bg-white p-3 rounded mb-3 border-l-4 border-amber-800">
                        <p class="text-xs text-amber-700 font-medium"><strong>Paciente:</strong></p>
                        <p class="text-sm text-amber-900 font-semibold" id="modal-paciente-nome">-</p>
                    </div>

                    <div class="bg-white p-3 rounded mb-3 border-l-4 border-amber-800">
                        <p class="text-xs text-amber-700 font-medium"><strong>Profissional:</strong></p>
                        <p class="text-sm text-amber-900 font-semibold" id="modal-profissional-nome">-</p>
                    </div>

                    <div class="bg-white p-3 rounded mb-3 border-l-4 border-amber-800">
                        <p class="text-xs text-amber-700 font-medium"><strong>Duração Estimada:</strong></p>
                        <p class="text-sm text-amber-900 font-semibold" id="modal-duracao">-</p>
                    </div>

                    <div class="bg-amber-100 border-l-4 border-amber-800 p-3 rounded">
                        <p class="text-xs text-amber-900">
                            <i class="fas fa-info-circle mr-1"></i>
                            <strong>Ao iniciar:</strong> Um prontuário será criado vinculado a este agendamento.
                        </p>
                    </div>
                </div>
                <div class="items-center px-4 py-3">
                    <form id="form-iniciar-consulta" method="POST">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 bg-amber-800 text-yellow-50 text-base font-medium rounded-md w-full shadow-sm hover:bg-amber-900 mb-2">
                            <i class="fas fa-play-circle mr-2"></i> Iniciar Consulta
                        </button>
                    </form>
                    <button onclick="fecharModalIniciar()"
                        class="px-4 py-2 bg-yellow-700 text-yellow-50 text-base font-medium rounded-md w-full shadow-sm hover:bg-yellow-800">
                        Voltar
                    </button>
                </div>
            </div>
        </div>

    </div>
    {{-- CARDS DE ESTATÍSTICAS --}}
    @if (isset($estatisticas))
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            {{-- Card: Consultas Hoje --}}
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-amber-500 hover:shadow-lg transition">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-gradient-to-br from-amber-100 to-yellow-100 rounded-md p-3">
                        <i class="fas fa-calendar-day text-amber-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-amber-700 truncate">
                                Consultas Hoje
                            </dt>
                            <dd class="flex items-baseline">
                                <div class="text-3xl font-bold text-amber-900">
                                    {{ $estatisticas['hoje'] }}
                                </div>
                            </dd>
                            @if ($estatisticas['aguardando_hoje'] > 0)
                                <dd class="text-xs text-amber-600 mt-1">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $estatisticas['aguardando_hoje'] }} aguardando
                                </dd>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>

            {{-- Card: Consultas na Semana --}}
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500 hover:shadow-lg transition">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-gradient-to-br from-yellow-100 to-amber-100 rounded-md p-3">
                        <i class="fas fa-calendar-week text-yellow-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-yellow-700 truncate">
                                Consultas esta Semana
                            </dt>
                            <dd class="flex items-baseline">
                                <div class="text-3xl font-bold text-yellow-900">
                                    {{ $estatisticas['semana'] }}
                                </div>
                            </dd>
                            <dd class="text-xs text-yellow-600 mt-1">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                {{ now()->locale('pt_BR')->startOfWeek()->format('d/m') }} -
                                {{ now()->locale('pt_BR')->endOfWeek()->format('d/m') }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            {{-- Card: Consultas no Mês --}}
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-amber-600 hover:shadow-lg transition">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-gradient-to-br from-amber-100 to-yellow-100 rounded-md p-3">
                        <i class="fas fa-calendar-alt text-amber-700 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-amber-700 truncate">
                                Consultas este Mês
                            </dt>
                            <dd class="flex items-baseline">
                                <div class="text-3xl font-bold text-amber-900">
                                    {{ $estatisticas['mes'] }}
                                </div>
                            </dd>
                            <dd class="text-xs text-amber-600 mt-1">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ now()->locale('pt_BR')->translatedFormat('F Y') }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            {{-- Card: Em Atendimento Agora --}}
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-gradient-to-br from-green-100 to-emerald-100 rounded-md p-3">
                        <i class="fas fa-user-md text-green-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-green-700 truncate">
                                Em Atendimento
                            </dt>
                            <dd class="flex items-baseline">
                                <div class="text-3xl font-bold text-green-900">
                                    {{ $estatisticas['em_atendimento'] }}
                                </div>
                            </dd>
                            @if ($estatisticas['em_atendimento'] > 0)
                                <dd class="text-xs text-green-600 mt-1 animate-pulse">
                                    <i class="fas fa-circle mr-1"></i>
                                    Consultas ativas agora
                                </dd>
                            @else
                                <dd class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Nenhuma consulta ativa
                                </dd>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>

        </div>
    @endif

    </div>
@endsection


@push('scripts')
    <script>
        function fecharModal() {
            document.getElementById('modal-cancelar').classList.add('hidden');
        }

        function fecharModalIniciar() {
            document.getElementById('modal-iniciar-consulta').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {

            // Event listener para botões de cancelar
            document.querySelectorAll('.btn-cancelar').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const agendamentoId = this.getAttribute('data-agendamento-id');
                    const pacienteNome = this.getAttribute('data-paciente-nome');
                    const dataHora = this.getAttribute('data-data-hora');

                    document.getElementById('nome-paciente-cancelar').textContent = pacienteNome;
                    document.getElementById('data-hora-cancelar').textContent = dataHora;
                    document.getElementById('form-cancelar').action =
                        `/agendamentos/${agendamentoId}`;
                    document.getElementById('modal-cancelar').classList.remove('hidden');
                });
            });

            // Event listener para botões de iniciar consulta
            document.querySelectorAll('.btn-iniciar-consulta').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const agendamentoId = this.getAttribute('data-agendamento-id');
                    const pacienteNome = this.getAttribute('data-paciente-nome');
                    const profissionalNome = this.getAttribute('data-profissional-nome');
                    const duracao = this.getAttribute('data-duracao');

                    document.getElementById('modal-paciente-nome').textContent = pacienteNome;
                    document.getElementById('modal-profissional-nome').textContent =
                        profissionalNome;
                    document.getElementById('modal-duracao').textContent = duracao + ' minutos';
                    document.getElementById('form-iniciar-consulta').action =
                        `/agendamentos/${agendamentoId}/iniciar-consulta`;
                    document.getElementById('modal-iniciar-consulta').classList.remove('hidden');
                });
            });

            // Fecha modais ao clicar fora
            document.getElementById('modal-cancelar').addEventListener('click', function(e) {
                if (e.target === this) {
                    fecharModal();
                }
            });

            document.getElementById('modal-iniciar-consulta').addEventListener('click', function(e) {
                if (e.target === this) {
                    fecharModalIniciar();
                }
            });

            // Auto-hide mensagens
            setTimeout(function() {
                document.querySelectorAll('.animate-fade-in').forEach(function(el) {
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 300);
                });
            }, 5000);

        });
    </script>
@endpush
