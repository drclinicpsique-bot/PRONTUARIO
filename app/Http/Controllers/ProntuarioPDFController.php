<?php

namespace App\Http\Controllers;

use App\Models\Prontuario;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ProntuarioPDFController extends Controller
{
    /**
     * Gerar PDF com prontuário completo
     */
    public function gerarPdfCompleto(int $id)
    {
        try {
            $prontuario = Prontuario::findOrFail($id);

            $pdf = Pdf::loadView('pdf.prontuario-completo', compact('prontuario'))
                ->setPaper('a4')
                ->setOption('margin-top', 15)
                ->setOption('margin-bottom', 15)
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);

            $nomeArquivo = 'prontuario_' . $prontuario->id . '_' . now()->format('d_m_Y') . '.pdf';

            Log::info('PDF prontuário gerado', [
                'prontuario_id' => $prontuario->id,
                'paciente' => $prontuario->paciente->nome_completo,
            ]);

            return $pdf->download($nomeArquivo);
        } catch (\Exception $e) {
            Log::error('Erro ao gerar PDF prontuário', [
                'id' => $id,
                'erro' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', '❌ Erro ao gerar PDF: ' . $e->getMessage());
        }
    }

    /**
     * Gerar PDF com apenas a prescrição
     */
    public function gerarPdfPrescricao(int $id)
    {
        try {
            $prontuario = Prontuario::findOrFail($id);

            if (!$prontuario->prescricao_medicamentos) {
                return redirect()->back()->with('error', '❌ Nenhuma prescrição para gerar PDF.');
            }

            $pdf = Pdf::loadView('pdf.prescricao', compact('prontuario'))
                ->setPaper('a4')
                ->setOption('margin-top', 15)
                ->setOption('margin-bottom', 15)
                ->setOption('margin-left', 15)
                ->setOption('margin-right', 15);

            $nomeArquivo = 'prescricao_' . $prontuario->id . '_' . now()->format('d_m_Y') . '.pdf';

            Log::info('PDF prescrição gerado', [
                'prontuario_id' => $prontuario->id,
                'paciente' => $prontuario->paciente->nome_completo,
            ]);

            return $pdf->download($nomeArquivo);
        } catch (\Exception $e) {
            Log::error('Erro ao gerar PDF prescrição', [
                'id' => $id,
                'erro' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', '❌ Erro ao gerar PDF: ' . $e->getMessage());
        }
    }
}