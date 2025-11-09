@extends('layouts.app')

@section('title', 'Prontuários Médicos')

@section('content')
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

    {{-- CABEÇALHO --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-extrabold text-green-800">
                    <i class="fas fa-file-medical mr-2"></i> Prontuários Médicos
                </h2>
                <p class="text-gray-600 mt-2">
                    Gestão de prontuários e histórico clínico dos pacientes
                </p>
            </div>
            <a href="{{ route('prontuarios.create') }}"
               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                <i class="fas fa-plus mr-2"></i> Novo Prontuário
            </a>
        </div>
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

    {{-- FILTROS --}}
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <form action="{{ route('prontuarios.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            
            {{-- Filtro: Status --}}
            <div>
                <label for="finalizado" class="block text-sm font-medium text-gray-700 mb-2">
                    Status
                </label>
                <select name="finalizado" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <option value="">Todos</option>
                    <option value="0" {{ request('finalizado') == '0' ? 'selected' : '' }}>Em Aberto</option>
                    <option value="1" {{ request('finalizado') == '1' ? 'selected' : '' }}>Finalizados</option>
                </select>
            </div>

            {{-- Botões --}}
            <div class="flex items-end gap-2 md:col-span-2">
                <button type="submit"
                        class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-search mr-2"></i> Filtrar
                </button>
                <a href="{{ route('prontuarios.index') }}"
                   class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition text-center">
                    <i class="fas fa-redo mr-2"></i> Limpar
                </a>
            </div>
        </form>
    </div>

    {{-- TABELA DE PRONTUÁRIOS --}}
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        
        @if($prontuarios->isEmpty())
        <div class="p-8 text-center">
            <i class="fas fa-file-medical text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-600 font-semibold">Nenhum prontuário encontrado</p>
            <a href="{{ route('prontuarios.create') }}"
               class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                <i class="fas fa-plus mr-2"></i> Criar Primeiro Prontuário
            </a>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-green-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Paciente</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Profissional</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Data Atendimento</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Diagnóstico</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($prontuarios as $prontuario)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <i class="fas fa-user text-green-600 mr-3"></i>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $prontuario->paciente->nome_completo }}</div>
                                    <div class="text-xs text-gray-500">{{ $prontuario->paciente->cpf_formatado }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <div class="font-medium text-gray-900">Dr(a). {{ $prontuario->profissional->usuario->nome_completo }}</div>
                                <div class="text-xs text-gray-500">{{ $prontuario->profissional->especialidade }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                {{ $prontuario->data_atendimento_formatada }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-600 truncate max-w-xs">
                                {{ Str::limit($prontuario->hipotese_diagnostica ?? 'Não informado', 40) }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($prontuario->finalizado)
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Finalizado
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-yellow-100 text-yellow-800">
                                <i class="fas fa-edit mr-1"></i> Em Aberto
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('prontuarios.show', $prontuario->id) }}"
                                   class="text-green-600 hover:text-green-800 text-sm font-medium"
                                   title="Visualizar">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if(!$prontuario->finalizado)
                                <a href="{{ route('prontuarios.edit', $prontuario->id) }}"
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif

                                <form action="{{ route('prontuarios.destroy', $prontuario->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Tem certeza que deseja deletar este prontuário?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-800 text-sm font-medium"
                                            title="Deletar">
                                        <i class="fas fa-trash"></i>
                                    </button>
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
            {{ $prontuarios->links() }}
        </div>
        @endif

    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            document.querySelectorAll('.animate-fade-in').forEach(function(el) {
                el.style.transition = 'opacity 0.3s ease-out';
                el.style.opacity = '0';
                setTimeout(() => el.style.display = 'none', 300);
            });
        }, 5000);
    });
</script>
@endpush