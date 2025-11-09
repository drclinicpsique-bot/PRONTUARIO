<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * FORMREQUEST: Validação para solicitação de Exame
 */
class ExameSolicitadoStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'prontuario_id' => ['required', 'exists:prontuarios,id'],
            'tipo_exame_id' => ['required', 'exists:tipos_exame,id'],
            'profissional_solicitante_id' => ['required', 'exists:profissionais,id'],
            'data_solicitacao' => ['required', 'date', 'before_or_equal:today'],
            'observacoes_solicitacao' => ['nullable', 'string', 'max:1000'],
            'data_prevista_resultado' => ['nullable', 'date', 'after:data_solicitacao'],
        ];
    }

    public function messages(): array
    {
        return [
            'prontuario_id.required' => 'O prontuário é obrigatório.',
            'tipo_exame_id.required' => 'Selecione um tipo de exame.',
            'profissional_solicitante_id.required' => 'O profissional solicitante é obrigatório.',
            'data_solicitacao.required' => 'A data de solicitação é obrigatória.',
            'data_prevista_resultado.after' => 'A data prevista deve ser posterior à data de solicitação.',
        ];
    }
}