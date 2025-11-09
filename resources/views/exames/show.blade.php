@extends('layouts.app')

@section('title', 'Detalhes do Exame')

@section('content')
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

    {{-- CABEÇALHO --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-amber-900">
                <i class="fas fa-microscope mr-2"></i> Detalhes do Exame
            </h2>
            <p class="text-amber-700 mt-2">
                ID: #{{ $exame->id }} - {{ $exame->tipoExame->nome }}
            </p>
        </div>

        <div class="mt-4 md:mt-0 flex flex-wrap gap-2">
            <a href="{{ route('exames.index') }}"
               class="inline-flex items-center px-4 py-2 bg-yellow-700 text-yellow-50 text-sm rounded-md hover:bg-yellow-800 transition">
                <i class="fas fa-arrow-left mr-2"></i> Voltar
            </a>
        </div>
    </div>

    {{-- MENSAGENS --}}
    @if(session('success'))
    <div class="mb-6 bg-yellow-50 border-l-4 border-amber-600 p-4 rounded-lg shadow-md animate-fade-in">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-amber-700 text-xl mr-3"></i>
            <p class="text-amber-900 font-semibold">{{ session('success') }}</p>
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

    {{-- STATUS --}}
    <div class="mb-6 bg-yellow-50 rounded-lg shadow-lg p-6 border-2 border-amber-900">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-amber-900">Status do Exame</h3>
                <p class="text-amber-700 text-sm mt-1">Última atualização: {{ $exame->updated_at->format('d/m/Y H:i') }}</p>
            </div>
            <div>
                @php
                    $statusColors = [
                        'solicitado' => 'bg-blue-100 text-blue-800 border-blue-300',
                        'em_analise' => 'bg-yellow-100 text-amber-900 border-amber-300',
                        'concluido' => 'bg-green-100 text-green-800 border-green-300',
                        'cancelado' => 'bg-red-100 text-red-800 border-red-300',
                    ];
                    $colorClass = $statusColors[$exame->status] ?? 'bg-amber-100 text-amber-800 border-amber-300';
                @endphp
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold border-2 {{ $colorClass }}">
                    {{ ucfirst($exame->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- COLUNA PRINCIPAL --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- CARD: DADOS DO EXAME --}}
            <div class="bg-yellow-50 rounded-lg shadow-lg p-6 border-l-4 border-amber-900">
                <h3 class="text-xl font-bold text-amber-900 mb-4 pb-2 border-b-2 border-amber-200">
                    <i class="fas fa-flask mr-2"></i> Dados do Exame
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-amber-700 mb-1">Tipo de Exame</label>
                        <p class="text-lg font-semibold text-amber-900">{{ $exame->tipoExame->nome }}</p>
                        @if($exame->tipoExame->codigo_tuss)
                        <p class="text-sm text-amber-800">Código TUSS: {{ $exame->tipoExame->codigo_tuss }}</p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-amber-700 mb-1">Categoria</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-900">
                            <i class="fas fa-tag mr-1"></i>
                            {{ $exame->tipoExame->categoria }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-amber-700 mb-1">Data da Solicitação</label>
                        <p class="text-lg font-semibold text-amber-900">{{ $exame->data_solicitacao_formatada }}</p>
                    </div>

                    @if($exame->data_prevista_resultado)
                    <div>
                        <label class="block text-sm font-medium text-amber-700 mb-1">Previsão de Resultado</label>
                        <p class="text-lg font-semibold text-amber-900">
                            {{ $exame->data_prevista_resultado->format('d/m/Y') }}
                        </p>
                        @php
                            $diasRestantes = now()->diffInDays($exame->data_prevista_resultado, false);
                        @endphp
                        @if($diasRestantes > 0)
                        <p class="text-xs text-amber-700 mt-1">
                            <i class="fas fa-clock mr-1"></i>
                            Faltam {{ $diasRestantes }} {{ $diasRestantes == 1 ? 'dia' : 'dias' }}
                        </p>
                        @elseif($diasRestantes == 0)
                        <p class="text-xs text-yellow-700 mt-1 font-semibold">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            Prazo vence hoje
                        </p>
                        @else
                        <p class="text-xs text-red-700 mt-1 font-semibold">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Prazo vencido há {{ abs($diasRestantes) }} {{ abs($diasRestantes) == 1 ? 'dia' : 'dias' }}
                        </p>
                        @endif
                    </div>
                    @endif
                </div>

                @if($exame->tipoExame->preparacao_necessaria)
                <div class="mt-6 p-4 bg-amber-100 rounded border-l-4 border-amber-800">
                    <p class="text-sm font-bold text-amber-900 mb-2">
                        <i class="fas fa-exclamation-circle mr-1"></i> Preparação Necessária:
                    </p>
                    <p class="text-sm text-amber-900">{{ $exame->tipoExame->preparacao_necessaria }}</p>
                </div>
                @endif

                @if($exame->observacoes_solicitacao)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-amber-700 mb-2">Observações da Solicitação</label>
                    <div class="bg-white p-4 rounded border-l-4 border-amber-800">
                        <p class="text-amber-900 whitespace-pre-wrap">{{ $exame->observacoes_solicitacao }}</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- CARD: PACIENTE --}}
            <div class="bg-yellow-50 rounded-lg shadow-lg p-6 border-l-4 border-yellow-700">
                <h3 class="text-xl font-bold text-amber-900 mb-4 pb-2 border-b-2 border-amber-200">
                    <i class="fas fa-user mr-2"></i> Paciente
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-amber-700 mb-1">Nome Completo</label>
                        <p class="text-lg font-semibold text-amber-900">{{ $exame->prontuario->paciente->nome_completo }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-amber-700 mb-1">CPF</label>
                        <p class="text-lg font-semibold text-amber-900 font-mono">{{ $exame->prontuario->paciente->cpf_formatado }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-amber-700 mb-1">Data de Nascimento</label>
                        <p class="text-lg font-semibold text-amber-900">
                            {{ $exame->prontuario->paciente->data_nascimento_formatada }}
                            <span class="text-sm text-amber-700">({{ $exame->prontuario->paciente->idade }} anos)</span>
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-amber-700 mb-1">Telefone</label>
                        <p class="text-lg font-semibold text-amber-900">{{ $exame->prontuario->paciente->telefone }}</p>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t-2 border-amber-200">
                    <a href="{{ route('pacientes.show', $exame->prontuario->paciente->id) }}"
                       class="inline-flex items-center px-4 py-2 bg-amber-800 text-yellow-50 text-sm rounded hover:bg-amber-900 transition">
                        <i class="fas fa-eye mr-2"></i> Ver Ficha do Paciente
                    </a>
                </div>
            </div>

            {{-- CARD: RESULTADO (SE EXISTIR) --}}
            @if($exame->resultado)
            <div class="bg-green-50 rounded-lg shadow-lg p-6 border-2 border-green-500">
                <h3 class="text-xl font-bold text-green-900 mb-4 pb-2 border-b-2 border-green-300">
                    <i class="fas fa-check-circle mr-2"></i> Resultado do Exame
                </h3>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-green-700 mb-1">Data de Realização</label>
                            <p class="text-lg font-semibold text-green-900">{{ $exame->resultado->data_realizacao_formatada }}</p>
                        </div>

                        @if($exame->resultado->laboratorio_responsavel)
                        <div>
                            <label class="block text-sm font-medium text-green-700 mb-1">Laboratório</label>
                            <p class="text-lg font-semibold text-green-900">{{ $exame->resultado->laboratorio_responsavel }}</p>
                        </div>
                        @endif

                        @if(!is_null($exame->resultado->valores_normais))
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-green-700 mb-2">Status dos Valores</label>
                            @if($exame->resultado->valores_normais)
                            <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-bold bg-green-100 text-green-800 border-2 border-green-300">
                                <i class="fas fa-check-circle mr-2"></i> Valores Normais
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-bold bg-red-100 text-red-800 border-2 border-red-300">
                                <i class="fas fa-exclamation-triangle mr-2"></i> Valores Alterados
                            </span>
                            @endif
                        </div>
                        @endif
                    </div>

                    @if($exame->resultado->resultado_texto)
                    <div>
                        <label class="block text-sm font-medium text-green-700 mb-2">Resultado</label>
                        <div class="bg-white p-4 rounded border-l-4 border-green-600">
                            <p class="text-green-900 whitespace-pre-wrap">{{ $exame->resultado->resultado_texto }}</p>
                        </div>
                    </div>
                    @endif

                    @if($exame->resultado->valores_medidos)
                    <div>
                        <label class="block text-sm font-medium text-green-700 mb-2">Valores Medidos</label>
                        <div class="bg-white p-4 rounded border-l-4 border-green-600">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($exame->resultado->valores_medidos as $parametro => $valor)
                                <div class="flex justify-between items-center border-b border-green-200 pb-2">
                                    <span class="text-sm font-medium text-green-800">{{ $parametro }}:</span>
                                    <span class="text-sm font-bold text-green-900">{{ $valor }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($exame->resultado->observacoes_resultado)
                    <div>
                        <label class="block text-sm font-medium text-green-700 mb-2">Observações do Resultado</label>
                        <div class="bg-white p-4 rounded border-l-4 border-green-600">
                            <p class="text-green-900 whitespace-pre-wrap">{{ $exame->resultado->observacoes_resultado }}</p>
                        </div>
                    </div>
                    @endif

                    @if($exame->resultado->arquivo_laudo_path)
                    <div class="pt-4 border-t-2 border-green-300">
                        <a href="{{ Storage::url($exame->resultado->arquivo_laudo_path) }}"
                           class="inline-flex items-center px-4 py-2 bg-green-700 text-white text-sm rounded hover:bg-green-800 transition"
                           target="_blank"
                           download>
                            <i class="fas fa-file-pdf mr-2"></i> Baixar Laudo (PDF)
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif

        </div>

        {{-- COLUNA LATERAL --}}
        <div class="lg:col-span-1 space-y-6">

            {{-- CARD: AÇÕES RÁPIDAS --}}
            <div class="bg-amber-50 rounded-lg shadow-lg p-6 border-2 border-amber-200">
                <h3 class="text-lg font-bold text-amber-900 mb-4">
                    <i class="fas fa-bolt mr-2"></i> Ações Rápidas
                </h3>

                <div class="space-y-3">

                    {{-- SE JÁ TEM RESULTADO --}}
                    @if($exame->resultado)
                    <div class="bg-green-100 border-2 border-green-400 rounded p-4 text-center">
                        <i class="fas fa-check-circle text-green-600 text-3xl mb-2"></i>
                        <p class="text-sm text-green-900 font-bold">✓ Resultado Cadastrado</p>
                        <p class="text-xs text-green-700 mt-1">
                            Em: {{ $exame->resultado->data_realizacao_formatada }}
                        </p>
                    </div>

                    {{-- SE NÃO TEM RESULTADO E NÃO ESTÁ CANCELADO --}}
                    @elseif($exame->status != 'cancelado')
                    <a href="{{ route('exames.resultado.create', $exame->id) }}"
                       class="block w-full text-center px-4 py-2 bg-green-700 text-yellow-50 text-sm rounded hover:bg-green-800 transition font-medium">
                        <i class="fas fa-flask mr-2"></i> Cadastrar Resultado
                    </a>

                    {{-- SE CANCELADO --}}
                    @else
                    <div class="bg-red-100 border-2 border-red-400 rounded p-4 text-center">
                        <i class="fas fa-ban text-red-600 text-3xl mb-2"></i>
                        <p class="text-sm text-red-900 font-bold">Exame Cancelado</p>
                    </div>
                    @endif

                </div>
            </div>

            {{-- CARD: PROFISSIONAL SOLICITANTE --}}
            <div class="bg-yellow-50 rounded-lg shadow-lg p-6 border-l-4 border-amber-900">
                <h3 class="text-lg font-bold text-amber-900 mb-4">
                    <i class="fas fa-user-md mr-2"></i> Profissional Solicitante
                </h3>

                <div class="space-y-3 text-sm">
                    <div>
                        <label class="block text-xs font-medium text-amber-700 mb-1">Nome</label>
                        <p class="font-bold text-amber-900">
                            Dr(a). {{ $exame->profissionalSolicitante->usuario->nome_completo }}
                        </p>
                    </div>

                    <div class="pt-3 border-t-2 border-amber-200">
                        <label class="block text-xs font-medium text-amber-700 mb-1">Especialidade</label>
                        <p class="font-bold text-amber-900">{{ $exame->profissionalSolicitante->especialidade }}</p>
                    </div>

                    <div class="pt-3 border-t-2 border-amber-200">
                        <label class="block text-xs font-medium text-amber-700 mb-1">CRM</label>
                        <p class="font-bold text-amber-900 font-mono">{{ $exame->profissionalSolicitante->crm_formatado }}</p>
                    </div>
                </div>
            </div>

            {{-- CARD: INFORMAÇÕES --}}
            <div class="bg-yellow-50 rounded-lg shadow-lg p-6 border-l-4 border-amber-900">
                <h3 class="text-lg font-bold text-amber-900 mb-4">
                    <i class="fas fa-info-circle mr-2"></i> Informações
                </h3>

                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-amber-700 font-medium">Prontuário:</span>
                        <div class="font-bold text-amber-900">#{{ $exame->prontuario_id }}</div>
                    </div>

                    <div class="pt-3 border-t-2 border-amber-200">
                        <span class="text-amber-700 font-medium">Criado em:</span>
                        <div class="font-bold text-amber-900">{{ $exame->created_at->format('d/m/Y H:i') }}</div>
                    </div>

                    <div class="pt-3 border-t-2 border-amber-200">
                        <span class="text-amber-700 font-medium">Atualizado em:</span>
                        <div class="font-bold text-amber-900">{{ $exame->updated_at->format('d/m/Y H:i') }}</div>
                    </div>

                    @if($exame->tipoExame->preco_referencia)
                    <div class="pt-3 border-t-2 border-amber-200">
                        <span class="text-amber-700 font-medium">Preço Referência:</span>
                        <div class="font-bold text-amber-900">{{ $exame->tipoExame->preco_formatado }}</div>
                    </div>
                    @endif
                </div>
            </div>

        </div>

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
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
</style>
@endpush