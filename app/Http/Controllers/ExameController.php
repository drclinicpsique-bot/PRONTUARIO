<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExameSolicitadoStoreRequest;
use App\Http\Requests\ResultadoExameStoreRequest;
use App\Services\ExameService;
use Illuminate\Http\Request;
use App\Models\ExameSolicitado;  // ⬅️ ADICIONE ESTA LINHA


/**
 * CONTROLLER: ExameController
 * 
 * Gerencia solicitação e resultados de exames.
 */
class ExameController extends Controller
{
    public function __construct(
        private ExameService $exameService
    ) {}

    /**
     * Lista exames solicitados.
     */
    public function index(Request $request)
    {
        $filtros = $request->only(['status', 'prontuario_id']);
        $exames = $this->exameService->listarExamesSolicitados(15, $filtros);

        return view('exames.index', compact('exames', 'filtros'));
    }
    /**
     * Exibe detalhes de um exame solicitado.
     */
    public function show(int $id)
    {
        $exame = ExameSolicitado::with([
            'tipoExame',
            'prontuario.paciente',
            'profissionalSolicitante.usuario',
            'resultado'
        ])->findOrFail($id);

        return view('exames.show', compact('exame'));
    }

    /**
     * Exibe formulário para solicitar exame.
     */
    public function create(Request $request)
    {
        $prontuarioId = $request->get('prontuario_id');
        $tiposExame = $this->exameService->listarTiposExame();

        // Buscar profissionais ativos
        $profissionais = \App\Models\Profissional::with('usuario')
            ->where('ativo', true)
            ->get();

        // SE VIER prontuario_id, buscar dados do prontuário
        $prontuario = null;
        if ($prontuarioId) {
            $prontuario = \App\Models\Prontuario::with('paciente')->findOrFail($prontuarioId);
        }

        return view('exames.create', compact('prontuarioId', 'tiposExame', 'profissionais', 'prontuario'));
    }

    /**
     * Armazena uma solicitação de exame.
     */
    /**
     * Armazena uma solicitação de exame.
     */
    public function store(ExameSolicitadoStoreRequest $request)
    {
        $exame = $this->exameService->solicitarExame($request->validated());

        // SE VIER DE UM PRONTUÁRIO, VOLTA PARA ELE
        if ($request->input('prontuario_id')) {
            return redirect()
                ->route('prontuarios.edit', $request->input('prontuario_id'))
                ->with('success', '✅ Exame solicitado com sucesso! Continue preenchendo o prontuário.');
        }

        // SENÃO, VAI PARA LISTA DE EXAMES
        return redirect()
            ->route('exames.index')
            ->with('success', '✅ Exame solicitado com sucesso!');
    }

    /**
     * Exibe formulário para cadastrar resultado.
     */
    public function createResultado($exameSolicitadoId)
    {
        $exameSolicitado = ExameSolicitado::with(['tipoExame', 'paciente', 'profissionalSolicitante.usuario'])
            ->findOrFail($exameSolicitadoId);

        // Passar com o nome que a view espera
        return view('exames.create-resultado', [
            'exame' => $exameSolicitado,  // ⬅️ MUDOU AQUI
            'exameSolicitado' => $exameSolicitado,
        ]);
    }

    /**
     * Armazena resultado de um exame.
     */
    /**
     * Armazena resultado de um exame.
     */
    public function storeResultado(ResultadoExameStoreRequest $request)
    {
        $dados = $request->validated();

        // Upload do arquivo laudo
        if ($request->hasFile('arquivo_laudo')) {
            $arquivo = $request->file('arquivo_laudo');
            $nomeArquivo = 'laudo_' . time() . '_' . $arquivo->getClientOriginalName();
            $dados['arquivo_laudo_path'] = $arquivo->storeAs('laudos', $nomeArquivo, 'public');
        }

        // Processar valores medidos
        if ($request->has('valores_medidos_parametro') && $request->has('valores_medidos_valor')) {
            $parametros = $request->input('valores_medidos_parametro');
            $valores = $request->input('valores_medidos_valor');

            $valoresMedidos = [];
            foreach ($parametros as $index => $parametro) {
                if (!empty($parametro) && !empty($valores[$index])) {
                    $valoresMedidos[$parametro] = $valores[$index];
                }
            }

            $dados['valores_medidos'] = !empty($valoresMedidos) ? $valoresMedidos : null;
        }

        $resultado = $this->exameService->cadastrarResultado($dados);

        return redirect()
            ->route('exames.show', $resultado->exame_solicitado_id)
            ->with('success', '✅ Resultado cadastrado com sucesso!');
    }
}
