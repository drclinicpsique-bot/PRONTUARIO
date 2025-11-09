<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * FORMREQUEST: Validação para criação de Prontuário
 * 
 * ⚠️ Campos sensíveis (LGPD): Todos os campos de texto contêm informações médicas.
 * Implementar auditoria e logs de acesso conforme LGPD.
 */
class ProntuarioStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'paciente_id' => ['required', 'integer', 'exists:pacientes,id'],
            'profissional_id' => ['required', 'integer', 'exists:profissionais,id'],
            'agendamento_id' => ['nullable', 'integer', 'exists:agendamentos,id'],
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
            'paciente_id.required' => '📋 Selecione um paciente.',
            'paciente_id.exists' => '❌ Paciente não encontrado.',
            'profissional_id.required' => '👨‍⚕️ Selecione um profissional.',
            'profissional_id.exists' => '❌ Profissional não encontrado.',
            'data_atendimento.required' => '📅 A data do atendimento é obrigatória.',
            'data_atendimento.before_or_equal' => '⚠️ A data do atendimento não pode ser futura.',
            'data_retorno.after_or_equal' => '⚠️ A data de retorno deve ser posterior ou igual à data do atendimento.',
            'queixa_principal.min' => '❌ A queixa principal deve ter pelo menos 3 caracteres.',
        ];
    }

    /**
     * Prepare inputs for validation.
     * Sanitizar e limpar dados antes de validar.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'finalizado' => $this->has('finalizado') ? (bool) $this->input('finalizado') : false,
        ]);

        // Trim em campos de texto para remover espaços
        foreach (['queixa_principal', 'historia_doenca_atual', 'historia_patologica_pregressa', 
                  'historia_familiar', 'historia_social', 'exame_fisico', 'hipotese_diagnostica',
                  'conduta_tratamento', 'prescricao_medicamentos', 'exames_solicitados', 'observacoes_gerais'] as $field) {
            if ($this->has($field) && $this->input($field)) {
                $this->merge([
                    $field => trim($this->input($field))
                ]);
            }
        }
    }
}