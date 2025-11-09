<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * FORMREQUEST: Validação para atualização de Prontuário
 * 
 * ⚠️ Campos sensíveis (LGPD): Todos os campos de texto contêm informações médicas.
 * Implementar auditoria e logs de acesso conforme LGPD.
 * 
 * TODO: Implementar Policy - Apenas o profissional criador pode editar
 */
class ProntuarioUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data_atendimento' => ['required', 'date', 'before_or_equal:now'],
            
            // Campos LGPD - Validação básica
            'queixa_principal' => ['nullable', 'string', 'max:2000', 'min:3'],
            'historia_doenca_atual' => ['nullable', 'string', 'max:5000'],
            'historia_patologica_pregressa' => ['nullable', 'string', 'max:5000'],
            'historia_familiar' => ['nullable', 'string', 'max:3000'],
            'historia_social' => ['nullable', 'string', 'max:3000'],
            'exame_fisico' => ['nullable', 'string', 'max:3000'],
            'hipotese_diagnostica' => ['nullable', 'string', 'max:1000'],
            'conduta_tratamento' => ['nullable', 'string', 'max:3000'],
            'prescricao_medicamentos' => ['nullable', 'string', 'max:3000'],
            'exames_solicitados' => ['nullable', 'string', 'max:2000'],
            'observacoes_gerais' => ['nullable', 'string', 'max:2000'],
            'data_retorno' => ['nullable', 'date', 'after_or_equal:data_atendimento'],
            'finalizado' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'data_atendimento.required' => '📅 A data do atendimento é obrigatória.',
            'data_atendimento.date' => '⚠️ O formato da data do atendimento é inválido.',
            'data_atendimento.before_or_equal' => '⚠️ A data do atendimento não pode ser futura.',
            'data_retorno.date' => '⚠️ O formato da data de retorno é inválido.',
            'data_retorno.after_or_equal' => '⚠️ A data de retorno deve ser posterior ou igual à data do atendimento.',
            'queixa_principal.min' => '❌ A queixa principal deve ter pelo menos 3 caracteres.',
            'queixa_principal.max' => '❌ A queixa principal não pode exceder 2000 caracteres.',
            'historia_doenca_atual.max' => '❌ A história da doença atual não pode exceder 5000 caracteres.',
            'historia_patologica_pregressa.max' => '❌ A história patológica pregressa não pode exceder 5000 caracteres.',
            'historia_familiar.max' => '❌ A história familiar não pode exceder 3000 caracteres.',
            'historia_social.max' => '❌ A história social não pode exceder 3000 caracteres.',
            'exame_fisico.max' => '❌ O exame físico não pode exceder 3000 caracteres.',
            'hipotese_diagnostica.max' => '❌ A hipótese diagnóstica não pode exceder 1000 caracteres.',
            'conduta_tratamento.max' => '❌ A conduta/tratamento não pode exceder 3000 caracteres.',
            'prescricao_medicamentos.max' => '❌ A prescrição de medicamentos não pode exceder 3000 caracteres.',
            'exames_solicitados.max' => '❌ Os exames solicitados não podem exceder 2000 caracteres.',
            'observacoes_gerais.max' => '❌ As observações gerais não podem exceder 2000 caracteres.',
        ];
    }

    /**
     * Prepare inputs for validation.
     * Sanitizar e limpar dados antes de validar.
     */
    protected function prepareForValidation(): void
    {
        // Converter finalizado para boolean
        $this->merge([
            'finalizado' => $this->has('finalizado') ? (bool) $this->input('finalizado') : false,
        ]);

        // Trim em campos de texto para remover espaços em branco
        $camposTexto = [
            'queixa_principal',
            'historia_doenca_atual',
            'historia_patologica_pregressa',
            'historia_familiar',
            'historia_social',
            'exame_fisico',
            'hipotese_diagnostica',
            'conduta_tratamento',
            'prescricao_medicamentos',
            'exames_solicitados',
            'observacoes_gerais'
        ];

        foreach ($camposTexto as $field) {
            if ($this->has($field) && $this->input($field)) {
                // Trim e remove múltiplos espaços
                $valor = trim($this->input($field));
                $valor = preg_replace('/\s+/', ' ', $valor);
                
                $this->merge([
                    $field => $valor ?: null
                ]);
            }
        }
    }
}