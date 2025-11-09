<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfissionalStoreRequest;
use App\Http\Requests\ProfissionalUpdateRequest;
use App\Models\Profissional;
use App\Services\ProfissionalService;
use Illuminate\Http\Request;

/**
 * CONTROLLER: ProfissionalController
 * 
 * CRUD completo de Profissionais de Saúde.
 */
class ProfissionalController extends Controller
{
    public function __construct(
        private ProfissionalService $profissionalService
    ) {}

    /**
     * Lista todos os profissionais.
     */
    public function index(Request $request)
    {
        $especialidade = $request->get('especialidade');
        $profissionais = $this->profissionalService->listarProfissionais(15, $especialidade);
        $especialidades = $this->profissionalService->listarEspecialidades();

        return view('profissionais.index', compact('profissionais', 'especialidades', 'especialidade'));
    }

    /**
     * Exibe formulário de criação.
     */
    public function create()
    {
        return view('profissionais.create');
    }

    /**
     * Armazena um novo profissional.
     */
    public function store(ProfissionalStoreRequest $request)
    {
        $profissional = $this->profissionalService->criarProfissional($request->validated());

        return redirect()
            ->route('profissionais.show', $profissional->id)
            ->with('success', 'Profissional cadastrado com sucesso!');
    }

    /**
     * Exibe detalhes de um profissional.
     */
    public function show(int $id)
    {
        $profissional = $this->profissionalService->buscarProfissionalPorId($id);

        return view('profissionais.show', compact('profissional'));
    }

    /**
     * Exibe formulário de edição.
     */
    public function edit(int $id)
    {
        $profissional = $this->profissionalService->buscarProfissionalPorId($id);

        return view('profissionais.edit', compact('profissional'));
    }

    /**
     * Atualiza um profissional existente.
     */
    public function update(ProfissionalUpdateRequest $request, int $id)
    {
        try {
            $profissional = Profissional::findOrFail($id);
            $this->profissionalService->atualizarProfissional($profissional, $request->validated());

            return redirect()
                ->route('profissionais.show', $profissional->id)
                ->with('success', 'Profissional atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar profissional: ' . $e->getMessage());
        }
    }

    /**
     * Exclui (soft delete) um profissional.
     */
    public function destroy(int $id)
    {
        try {
            $profissional = Profissional::findOrFail($id);
            $this->profissionalService->excluirProfissional($profissional);

            return redirect()
                ->route('profissionais.index')
                ->with('success', 'Profissional excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
