<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * FORMREQUEST: Validação para cadastro de Resultado de Exame
 * 
 * ⚠️ LGPD: Campos sensíveis (resultado_texto, valores_medidos).
 */
class ResultadoExameStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'exame_solicitado_id' => ['required', 'exists:exames_solicitados,id'],
            'data_realizacao' => ['required', 'date', 'before_or_equal:today'],
            'resultado_texto' => ['nullable', 'string', 'max:10000'],
            'valores_medidos_parametro' => ['nullable', 'array'],
            'valores_medidos_valor' => ['nullable', 'array'],
            'laboratorio_responsavel' => ['nullable', 'string', 'max:150'],
            'arquivo_laudo' => ['nullable', 'file', 'mimes:pdf', 'max:10240'], // 10MB
            'observacoes_resultado' => ['nullable', 'string', 'max:2000'],
            'valores_normais' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'exame_solicitado_id.required' => 'O exame solicitado é obrigatório.',
            'data_realizacao.required' => 'A data de realização é obrigatória.',
            'data_realizacao.before_or_equal' => 'A data de realização não pode ser futura.',
        ];
    }
}
