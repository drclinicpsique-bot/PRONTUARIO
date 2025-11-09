@extends('layouts.app')

@section('title', 'Exames Solicitados')

@section('content')
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

        {{-- CABEÇALHO --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h2 class="text-3xl font-extrabold text-amber-900">
                    <i class="fas fa-microscope mr-2"></i> Exames Solicitados
                </h2>
                <p class="text-amber-700 mt-2">
                    Gerencie solicitações e resultados de exames
                </p>
            </div>

            <div class="mt-4 md:mt-0 flex gap-2">
                {{-- SE VIER DE UM PRONTUÁRIO, MOSTRA BOTÃO VOLTAR --}}
                @if (request()->has('prontuario_id') && request()->get('prontuario_id'))
                    <a href="{{ route('prontuarios.edit', request()->get('prontuario_id')) }}"
                        class="inline-flex items-center px-4 py-3 border-2 border-green-600 text-sm font-medium rounded-md text-green-700 bg-white hover:bg-green-50 transition shadow-sm">
                        <i class="fas fa-arrow-left mr-2"></i> Voltar para Consulta
                    </a>
                @endif

                {{-- BOTÃO SOLICITAR EXAME --}}
                <a href="{{ route('exames.create', request()->has('prontuario_id') ? ['prontuario_id' => request()->get('prontuario_id')] : []) }}"
                    class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-yellow-50 bg-amber-800 hover:bg-amber-900 transition duration-150">
                    <i class="fas fa-plus-circle mr-2"></i> Solicitar Exame
                </a>
            </div>
        </div>

        {{-- MENSAGENS --}}
        @if (session('success'))
            <div class="mb-6 bg-yellow-50 border-l-4 border-amber-600 p-4 rounded-lg shadow-md animate-fade-in">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-amber-700 text-xl mr-3"></i>
                    <p class="text-amber-900 font-semibold">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{-- CARD PRINCIPAL --}}
        <div class="bg-yellow-50 shadow-lg rounded-lg overflow-hidden border-2 border-amber-900">

            {{-- FILTROS --}}
            <div class="bg-gradient-to-r from-amber-100 to-yellow-100 px-6 py-4 border-b-2 border-amber-900">
                <form action="{{ route('exames.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    {{-- Filtro Status --}}
                    <div>
                        <label for="status" class="block text-xs font-medium text-amber-900 mb-1">Status</label>
                        <select name="status" id="status"
                            class="w-full px-3 py-2 text-sm border-2 border-amber-200 rounded-md focus:ring-amber-500 focus:border-amber-500 bg-white">
                            <option value="">Todos</option>
                            <option value="solicitado" {{ ($filtros['status'] ?? '') == 'solicitado' ? 'selected' : '' }}>
                                Solicitado</option>
                            <option value="em_analise" {{ ($filtros['status'] ?? '') == 'em_analise' ? 'selected' : '' }}>Em
                                Análise</option>
                            <option value="concluido" {{ ($filtros['status'] ?? '') == 'concluido' ? 'selected' : '' }}>
                                Concluído</option>
                            <option value="cancelado" {{ ($filtros['status'] ?? '') == 'cancelado' ? 'selected' : '' }}>
                                Cancelado</option>
                        </select>
                    </div>

                    {{-- Botões --}}
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-amber-800 text-yellow-50 text-sm rounded-md hover:bg-amber-900 transition font-medium">
                            <i class="fas fa-filter mr-1"></i> Filtrar
                        </button>
                        @if (!empty(array_filter($filtros)))
                            <a href="{{ route('exames.index') }}"
                                class="px-4 py-2 bg-yellow-700 text-yellow-50 text-sm rounded-md hover:bg-yellow-800 transition">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- INFORMAÇÕES --}}
            <div class="px-6 py-3 bg-yellow-50 border-b-2 border-amber-200">
                <p class="text-sm text-amber-900">
                    @if (!empty(array_filter($filtros)))
                        <i class="fas fa-filter mr-1 text-amber-800 font-bold"></i>
                        <span class="text-amber-900 font-semibold">Filtros ativos</span>
                        <span class="text-amber-700 mx-2">|</span>
                    @endif
                    <strong>{{ $exames->total() }}</strong>
                    {{ $exames->total() === 1 ? 'exame encontrado' : 'exames encontrados' }}
                </p>
            </div>

            {{-- TABELA --}}
            @if ($exames->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-amber-200">
                        <thead class="bg-gradient-to-r from-amber-200 to-yellow-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-amber-900 uppercase">
                                    <i class="fas fa-calendar mr-1"></i> Data
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-amber-900 uppercase">
                                    <i class="fas fa-microscope mr-1"></i> Exame
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-bold text-amber-900 uppercase hidden md:table-cell">
                                    <i class="fas fa-user mr-1"></i> Paciente
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-bold text-amber-900 uppercase hidden lg:table-cell">
                                    <i class="fas fa-user-md mr-1"></i> Solicitante
                                </th>
                                <th class="w-24 px-2 py-3 text-center text-xs font-bold text-amber-900 uppercase">
                                    <i class="fas fa-info-circle"></i>
                                </th>
                                <th class="px-4 py-3 text-right text-xs font-bold text-amber-900 uppercase">
                                    <i class="fas fa-cog mr-1"></i> Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-amber-100">
                            @foreach ($exames as $exame)
                                <tr class="hover:bg-yellow-50 transition-colors duration-150">

                                    {{-- Data --}}
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-amber-900">
                                            {{ $exame->data_solicitacao_formatada }}
                                        </div>
                                        @if ($exame->data_prevista_resultado)
                                            <div class="text-xs text-amber-700">
                                                Prev: {{ $exame->data_prevista_resultado->format('d/m/Y') }}
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Exame --}}
                                    <td class="px-4 py-4">
                                        <div class="text-sm font-medium text-amber-900">
                                            {{ $exame->tipoExame->nome }}
                                        </div>
                                        <div class="text-xs text-amber-700">
                                            {{ $exame->tipoExame->categoria }}
                                        </div>
                                    </td>

                                    {{-- Paciente --}}
                                    <td class="px-4 py-4 hidden md:table-cell">
                                        <div class="text-sm text-amber-900">
                                            {{ $exame->prontuario->paciente->nome_completo }}
                                        </div>
                                    </td>

                                    {{-- Solicitante --}}
                                    <td class="px-4 py-4 hidden lg:table-cell">
                                        <div class="text-sm text-amber-900">
                                            Dr(a). {{ $exame->profissionalSolicitante->usuario->nome_completo }}
                                        </div>
                                    </td>

                                    {{-- Status --}}
                                    <td class="w-24 px-2 py-4 text-center">
                                        @php
                                            $statusColors = [
                                                'solicitado' => 'bg-blue-100 text-blue-800',
                                                'em_analise' => 'bg-yellow-100 text-amber-900',
                                                'concluido' => 'bg-green-100 text-green-800',
                                                'cancelado' => 'bg-red-100 text-red-800',
                                            ];
                                            $colorClass =
                                                $statusColors[$exame->status] ?? 'bg-amber-100 text-amber-800';
                                        @endphp

                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $colorClass }}">
                                            {{ ucfirst($exame->status) }}
                                        </span>
                                    </td>

                                    {{-- Ações --}}
                                    <td class="px-4 py-4 whitespace-nowrap text-right text-sm">
                                        <div class="flex items-center justify-end space-x-2">

                                            {{-- Ver Detalhes --}}
                                            <a href="{{ route('exames.show', $exame->id) }}"
                                                class="text-amber-800 hover:text-amber-900 font-bold" title="Ver detalhes">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            {{-- Cadastrar Resultado --}}
                                            @if ($exame->status != 'concluido' && $exame->status != 'cancelado')
                                                <a href="{{ route('exames.resultado.create', $exame->id) }}"
                                                    class="text-green-600 hover:text-green-900 font-bold"
                                                    title="Cadastrar Resultado">
                                                    <i class="fas fa-flask"></i>
                                                </a>
                                            @endif

                                            {{-- Ver Resultado --}}
                                            @if ($exame->resultado)
                                                <span class="text-green-600 font-bold" title="Resultado Disponível">
                                                    <i class="fas fa-check-circle"></i>
                                                </span>
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
                    {{ $exames->appends($filtros)->links('vendor.pagination.tailwind') }}
                </div>
            @else
                {{-- EMPTY STATE --}}
                <div class="text-center py-16 px-6">
                    <i class="fas fa-microscope text-amber-300 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-amber-900 mb-2">
                        Nenhum exame encontrado
                    </h3>
                    <p class="text-amber-800 mb-6">
                        Comece solicitando o primeiro exame.
                    </p>
                    <a href="{{ route('exames.create') }}"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-yellow-50 bg-amber-800 hover:bg-amber-900">
                        <i class="fas fa-plus-circle mr-2"></i> Solicitar Exame
                    </a>
                </div>
            @endif

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        // Auto-hide mensagens
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.querySelectorAll('.animate-fade-in').forEach(function(el) {
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 300);
                });
            }, 5000);
        });
    </script>
@endpush

@push('styles')
    <style>
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
    </style>
@endpush
