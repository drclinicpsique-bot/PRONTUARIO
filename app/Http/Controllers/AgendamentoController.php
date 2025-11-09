<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgendamentoStoreRequest;
use App\Http\Requests\AgendamentoUpdateRequest;
use App\Models\Agendamento;
use App\Models\Prontuario;
use App\Services\AgendamentoService;
use App\Services\PacienteService;
use App\Services\ProfissionalService;
use App\Services\ConsultaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * CONTROLLER: AgendamentoController
 * 
 * CRUD completo de Agendamentos.
 * Todas as operações delegadas aos Services (zero acesso direto ao banco).
 */
class AgendamentoController extends Controller
{
    public function __construct(
        private AgendamentoService $agendamentoService,
        private PacienteService $pacienteService,
        private ProfissionalService $profissionalService,
        private ConsultaService $consultaService
    ) {}

    /**
     * Lista todos os agendamentos com filtros.
     */
    public function index(Request $request)
    {
        $filtros = [
            'data_inicio' => $request->input('data_inicio'),
            'data_fim' => $request->input('data_fim'),
            'status' => $request->input('status'),
        ];

        $query = Agendamento::with(['paciente', 'profissional.usuario'])
            ->orderBy('data_hora_agendamento', 'desc');

        // Aplicar filtros
        if ($filtros['data_inicio']) {
            $query->whereDate('data_hora_agendamento', '>=', $filtros['data_inicio']);
        }

        if ($filtros['data_fim']) {
            $query->whereDate('data_hora_agendamento', '<=', $filtros['data_fim']);
        }

        if ($filtros['status']) {
            $query->where('status', $filtros['status']);
        }

        $agendamentos = $query->paginate(15);

        // ESTATÍSTICAS
        $hoje = now()->startOfDay();
        $fimHoje = now()->endOfDay();
        $inicioSemana = now()->startOfWeek();
        $fimSemana = now()->endOfWeek();
        $inicioMes = now()->startOfMonth();
        $fimMes = now()->endOfMonth();

        $estatisticas = [
            'hoje' => Agendamento::whereBetween('data_hora_agendamento', [$hoje, $fimHoje])
                ->whereNotIn('status', ['cancelado'])
                ->count(),

            'semana' => Agendamento::whereBetween('data_hora_agendamento', [$inicioSemana, $fimSemana])
                ->whereNotIn('status', ['cancelado'])
                ->count(),

            'mes' => Agendamento::whereBetween('data_hora_agendamento', [$inicioMes, $fimMes])
                ->whereNotIn('status', ['cancelado'])
                ->count(),

            'em_atendimento' => Agendamento::where('status', 'em_atendimento')->count(),

            'aguardando_hoje' => Agendamento::whereBetween('data_hora_agendamento', [$hoje, $fimHoje])
                ->whereIn('status', ['agendado', 'confirmado'])
                ->count(),
        ];

        return view('agendamentos.index', compact('agendamentos', 'filtros', 'estatisticas'));
    }

    /**
     * Exibe formulário de criação.
     */
    public function create(Request $request)
    {
        $pacienteId = $request->get('paciente_id');
        $paciente = $pacienteId ? $this->pacienteService->buscarPacientePorId($pacienteId) : null;
        $profissionais = $this->profissionalService->listarProfissionais(100)->items();

        return view('agendamentos.create', compact('paciente', 'profissionais'));
    }

    /**
     * Armazena um novo agendamento.
     */
    public function store(AgendamentoStoreRequest $request)
    {
        try {
            $agendamento = $this->agendamentoService->criarAgendamento($request->validated());

            return redirect()
                ->route('agendamentos.show', $agendamento->id)
                ->with('success', '✅ Agendamento criado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ ' . $e->getMessage());
        }
    }

    /**
     * Exibe detalhes de um agendamento.
     */
    public function show(int $id)
    {
        $agendamento = $this->agendamentoService->buscarAgendamentoPorId($id);

        return view('agendamentos.show', compact('agendamento'));
    }

    /**
     * Exibe formulário de edição.
     */
    public function edit(int $id)
    {
        $agendamento = $this->agendamentoService->buscarAgendamentoPorId($id);
        $profissionais = $this->profissionalService->listarProfissionais(100)->items();

        return view('agendamentos.edit', compact('agendamento', 'profissionais'));
    }

    /**
     * Atualiza um agendamento existente.
     */
    public function update(AgendamentoUpdateRequest $request, int $id)
    {
        try {
            $agendamento = $this->agendamentoService->atualizarAgendamento($id, $request->validated());

            return redirect()
                ->route('agendamentos.show', $agendamento->id)
                ->with('success', '✅ Agendamento atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ Erro ao atualizar agendamento: ' . $e->getMessage());
        }
    }

    /**
     * Cancela um agendamento (soft delete via status).
     */
    public function destroy(Request $request, int $id)
    {
        $request->validate([
            'motivo_cancelamento' => ['required', 'string', 'min:10', 'max:500']
        ], [
            'motivo_cancelamento.required' => 'O motivo do cancelamento é obrigatório.',
            'motivo_cancelamento.min' => 'O motivo deve ter no mínimo 10 caracteres.',
            'motivo_cancelamento.max' => 'O motivo não pode ter mais de 500 caracteres.',
        ]);

        try {
            $this->agendamentoService->cancelarAgendamento($id, $request->motivo_cancelamento);

            return redirect()
                ->route('agendamentos.index')
                ->with('success', '✅ Agendamento cancelado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', '❌ Erro ao cancelar agendamento: ' . $e->getMessage());
        }
    }

    // ============================================
    // MÉTODOS - CONSULTA
    // ============================================

    /**
     * Inicia uma consulta e cria prontuário vinculado.
     * 
     * POST /agendamentos/{id}/iniciar-consulta
     */
    public function iniciarConsulta(int $id)
    {
        try {
            $agendamento = Agendamento::findOrFail($id);

            // Validar se pode iniciar
            if (!$agendamento->podeIniciarConsulta()) {
                return redirect()
                    ->back()
                    ->with('error', '❌ Este agendamento não pode ser iniciado.');
            }

            // Criar prontuário se não existir
            if (!$agendamento->prontuario) {
                $prontuario = Prontuario::create([
                    'paciente_id' => $agendamento->paciente_id,
                    'profissional_id' => $agendamento->profissional_id,
                    'agendamento_id' => $agendamento->id,
                    'data_atendimento' => now(),
                    'finalizado' => false,
                ]);
            } else {
                $prontuario = $agendamento->prontuario;
            }

            // Atualizar status do agendamento
            $agendamento->update([
                'status' => 'em_atendimento',
                'data_hora_inicio' => now(),
            ]);

            Log::info('Consulta iniciada com sucesso', [
                'agendamento_id' => $agendamento->id,
                'prontuario_id' => $prontuario->id,
                'paciente' => $agendamento->paciente->nome_completo,
            ]);

            // REDIRECIONAR DIRETO PARA EDITAR PRONTUÁRIO
            return redirect()
                ->route('prontuarios.edit', $prontuario->id)
                ->with('success', '✅ Consulta iniciada! Preencha o prontuário.');
        } catch (\Exception $e) {
            Log::error('Erro ao iniciar consulta', [
                'agendamento_id' => $id,
                'erro' => $e->getMessage(),
                'linha' => $e->getLine(),
                'arquivo' => $e->getFile(),
            ]);

            return redirect()
                ->back()
                ->with('error', '❌ Erro ao iniciar consulta: ' . $e->getMessage());
        }
    }

    /**
     * Finaliza uma consulta.
     * 
     * POST /agendamentos/{id}/finalizar-consulta
     */
    public function finalizarConsulta(Request $request, int $id)
    {
        try {
            $agendamento = Agendamento::findOrFail($id);

            if (!$agendamento->prontuario) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => '❌ Prontuário não encontrado.'
                    ], 404);
                }
                return redirect()->back()->with('error', '❌ Prontuário não encontrado.');
            }

            // Calcular duração
            $duracao = $agendamento->data_hora_inicio
                ? now()->diffInMinutes($agendamento->data_hora_inicio)
                : 0;

            // Finalizar o agendamento
            $agendamento->update([
                'status' => 'concluido',
                'data_hora_fim' => now(),
                'duracao_minutos' => $duracao,
            ]);

            // Finalizar o prontuário também
            $agendamento->prontuario->update([
                'finalizado' => true,
            ]);

            Log::info('Consulta finalizada com sucesso', [
                'agendamento_id' => $agendamento->id,
                'paciente' => $agendamento->paciente->nome_completo,
                'duracao_minutos' => $duracao,
            ]);

            // ✅ RETORNAR REDIRECT OU JSON CONFORME O TIPO DE REQUISIÇÃO
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => '✅ Consulta finalizada com sucesso!',
                    'redirect' => route('pacientes.show', $agendamento->paciente->id)
                ]);
            }

            return redirect()
                ->route('pacientes.show', $agendamento->paciente->id)
                ->with('success', '✅ Consulta finalizada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao finalizar consulta', [
                'agendamento_id' => $id,
                'erro' => $e->getMessage(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Erro: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', '❌ Erro: ' . $e->getMessage());
        }
    }
    /**
     * Cancela um agendamento.
     * 
     * POST /agendamentos/{id}/cancelar
     */
    public function cancelar(Request $request, int $id)
    {
        $request->validate([
            'motivo_cancelamento' => ['nullable', 'string', 'max:500']
        ]);

        try {
            $agendamento = Agendamento::findOrFail($id);

            // Usar método do Model para cancelar
            $agendamento->cancelar($request->input('motivo_cancelamento'));

            Log::info('Agendamento cancelado', [
                'agendamento_id' => $id,
                'motivo' => $request->input('motivo_cancelamento'),
            ]);

            return redirect()
                ->route('agendamentos.index')
                ->with('success', '✅ Agendamento cancelado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao cancelar agendamento', [
                'agendamento_id' => $id,
                'erro' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('error', '❌ Erro: ' . $e->getMessage());
        }
    }

    /**
     * Marca como falta do paciente.
     * 
     * POST /agendamentos/{id}/marcar-falta
     */
    public function marcarFalta(int $id)
    {
        try {
            $agendamento = Agendamento::findOrFail($id);
            $agendamento->marcarFaltaPaciente();

            Log::info('Falta do paciente registrada', [
                'agendamento_id' => $id,
                'paciente' => $agendamento->paciente->nome_completo,
            ]);

            return redirect()
                ->back()
                ->with('success', '✅ Falta do paciente registrada.');
        } catch (\Exception $e) {
            Log::error('Erro ao marcar falta', [
                'agendamento_id' => $id,
                'erro' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('error', '❌ Erro: ' . $e->getMessage());
        }
    }

    /**
     * Confirma um agendamento.
     * 
     * POST /agendamentos/{id}/confirmar
     */
    public function confirmar(int $id)
    {
        try {
            $agendamento = Agendamento::findOrFail($id);
            $agendamento->confirmar();

            Log::info('Agendamento confirmado', [
                'agendamento_id' => $id,
            ]);

            return redirect()
                ->back()
                ->with('success', '✅ Agendamento confirmado!');
        } catch (\Exception $e) {
            Log::error('Erro ao confirmar agendamento', [
                'agendamento_id' => $id,
                'erro' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('error', '❌ Erro: ' . $e->getMessage());
        }
    }
}
