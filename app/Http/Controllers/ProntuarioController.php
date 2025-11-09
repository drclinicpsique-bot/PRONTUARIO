<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProntuarioStoreRequest;
use App\Http\Requests\ProntuarioUpdateRequest;
use App\Services\ProntuarioService;
use App\Services\PacienteService;
use App\Services\ProfissionalService;
use Illuminate\Http\Request;

/**
 * CONTROLLER: ProntuarioController
 * 
 * CRUD completo de Prontuários Médicos.
 * ⚠️ LGPD: Dados altamente sensíveis - implementar auditoria.
 */
class ProntuarioController extends Controller
{
    public function __construct(
        private ProntuarioService $prontuarioService,
        private PacienteService $pacienteService,
        private ProfissionalService $profissionalService
    ) {}

    /**
     * Lista todos os prontuários com filtros.
     */
    public function index(Request $request)
    {
        $filtros = $request->only(['paciente_id', 'profissional_id', 'finalizado']);
        $prontuarios = $this->prontuarioService->listarProntuarios(15, $filtros);

        return view('prontuarios.index', compact('prontuarios', 'filtros'));
    }

    /**
     * Exibe formulário de criação.
     */
    public function create(Request $request)
    {
        $pacienteId = $request->get('paciente_id');
        $agendamentoId = $request->get('agendamento_id');
        
        $paciente = $pacienteId ? $this->pacienteService->buscarPacientePorId($pacienteId) : null;
        $agendamento = $agendamentoId ? \App\Models\Agendamento::findOrFail($agendamentoId) : null;
        $profissionais = $this->profissionalService->listarProfissionais(100)->items();

        return view('prontuarios.create', compact('paciente', 'agendamento', 'profissionais'));
    }

    /**
     * Armazena um novo prontuário.
     */
    public function store(ProntuarioStoreRequest $request)
    {
        try {
            $prontuario = $this->prontuarioService->criarProntuario($request->validated());

            return redirect()
                ->route('prontuarios.show', $prontuario->id)
                ->with('success', 'Prontuário criado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao criar prontuário: ' . $e->getMessage());
        }
    }

    /**
     * Exibe detalhes de um prontuário.
     */
    public function show(int $id)
    {
        $prontuario = $this->prontuarioService->buscarProntuarioPorId($id);

        return view('prontuarios.show', compact('prontuario'));
    }

    /**
     * Exibe formulário de edição.
     */
    public function edit(int $id)
    {
        $prontuario = $this->prontuarioService->buscarProntuarioPorId($id);

        if ($prontuario->finalizado) {
            return redirect()
                ->route('prontuarios.show', $id)
                ->with('error', 'Prontuários finalizados não podem ser editados.');
        }

        $profissionais = $this->profissionalService->listarProfissionais(100)->items();

        return view('prontuarios.edit', compact('prontuario', 'profissionais'));
    }

    /**
     * Atualiza um prontuário existente.
     */
    public function update(ProntuarioUpdateRequest $request, int $id)
    {
        try {
            $prontuario = $this->prontuarioService->atualizarProntuario($id, $request->validated());

            return redirect()
                ->route('prontuarios.show', $prontuario->id)
                ->with('success', 'Prontuário atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar prontuário: ' . $e->getMessage());
        }
    }

    /**
     * Finaliza um prontuário (torna somente leitura).
     */
    public function finalizar(int $id)
    {
        try {
            $prontuario = $this->prontuarioService->finalizarProntuario($id);

            return redirect()
                ->route('prontuarios.show', $prontuario->id)
                ->with('success', 'Prontuário finalizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao finalizar prontuário: ' . $e->getMessage());
        }
    }

    /**
     * Deleta um prontuário (soft delete).
     */
    public function destroy(int $id)
    {
        try {
            $this->prontuarioService->deletarProntuario($id);

            return redirect()
                ->route('prontuarios.index')
                ->with('success', 'Prontuário deletado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao deletar prontuário: ' . $e->getMessage());
        }
    }

    /**
     * Lista prontuários de um paciente específico.
     */
    public function porPaciente(int $pacienteId)
    {
        $paciente = $this->pacienteService->buscarPacientePorId($pacienteId);
        $prontuarios = $this->prontuarioService->listarProntuariosPorPaciente($pacienteId);

        return view('prontuarios.por-paciente', compact('paciente', 'prontuarios'));
    }
}