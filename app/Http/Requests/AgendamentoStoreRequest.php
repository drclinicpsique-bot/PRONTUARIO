<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * FORMREQUEST: Validação para criação de Agendamento
 * 
 * Valida disponibilidade de horário (evita double booking).
 */
class AgendamentoStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'paciente_id' => ['required', 'exists:pacientes,id'],
            'profissional_id' => ['required', 'exists:profissionais,id'],
            'data_hora_agendamento' => [
                'required',
                'date',
                'after:now', // Não permite agendar no passado
            ],
            'duracao_minutos' => ['required', 'integer', 'min:15', 'max:240'],
            'motivo_consulta' => ['nullable', 'string', 'max:500'],
            'observacoes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'paciente_id.required' => 'Selecione um paciente.',
            'paciente_id.exists' => 'O paciente selecionado não existe.',
            'profissional_id.required' => 'Selecione um profissional.',
            'profissional_id.exists' => 'O profissional selecionado não existe.',
            'data_hora_agendamento.required' => 'A data e hora do agendamento são obrigatórias.',
            'data_hora_agendamento.after' => 'Não é possível agendar consultas no passado.',
            'duracao_minutos.min' => 'A duração mínima é de 15 minutos.',
            'duracao_minutos.max' => 'A duração máxima é de 240 minutos (4 horas).',
        ];
    }
}