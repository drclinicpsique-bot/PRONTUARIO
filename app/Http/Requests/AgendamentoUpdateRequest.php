<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgendamentoUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'paciente_id' => ['required', 'integer', 'exists:pacientes,id'],
            'profissional_id' => ['required', 'integer', 'exists:profissionais,id'],
            'data_hora_agendamento' => ['required', 'date', 'after_or_equal:today'],
            'duracao_minutos' => ['required', 'integer', 'min:15', 'max:240'],
            'status' => ['required', 'string', 'in:agendado,confirmado,em_atendimento,concluido,cancelado,falta_paciente'],
            'motivo_consulta' => ['nullable', 'string', 'max:1000'],
            'observacoes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'paciente_id.required' => 'O paciente é obrigatório.',
            'paciente_id.exists' => 'O paciente selecionado não existe.',
            'profissional_id.required' => 'O profissional é obrigatório.',
            'profissional_id.exists' => 'O profissional selecionado não existe.',
            'data_hora_agendamento.required' => 'A data e hora do agendamento são obrigatórias.',
            'data_hora_agendamento.date' => 'A data e hora devem estar em um formato válido.',
            'data_hora_agendamento.after_or_equal' => 'A data do agendamento não pode ser no passado.',
            'duracao_minutos.required' => 'A duração é obrigatória.',
            'duracao_minutos.min' => 'A duração mínima é de 15 minutos.',
            'duracao_minutos.max' => 'A duração máxima é de 240 minutos.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status selecionado é inválido.',
            'motivo_consulta.max' => 'O motivo da consulta não pode ter mais de 1000 caracteres.',
            'observacoes.max' => 'As observações não podem ter mais de 1000 caracteres.',
        ];
    }
}