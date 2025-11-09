<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * FORMREQUEST: Validação para criação de Paciente
 * 
 * Regras de validação rigorosas para garantir integridade dos dados.
 * CPF único, data de nascimento válida, campos obrigatórios.
 */
class PacienteStoreRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta requisição.
     */
    public function authorize(): bool
    {
        // TODO: Implementar lógica de autorização (Policy)
        // Por enquanto, permite todos os usuários autenticados
        return true;
    }

    /**
     * Regras de validação para criação de paciente.
     */
    public function rules(): array
    {
        return [
            'nome_completo' => ['required', 'string', 'max:150', 'min:3'],
            'cpf' => [
                'required',
                'string',
                'size:14', // Formato: 000.000.000-00
                'unique:pacientes,cpf',
                'regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', // Valida formato
            ],
            'data_nascimento' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'sexo' => ['required', Rule::in(['masculino', 'feminino', 'outro', 'nao_informado'])],
            'email' => ['nullable', 'email', 'max:100'],
            'telefone' => ['required', 'string', 'max:20'],
            'telefone_emergencia' => ['nullable', 'string', 'max:20'],
            'endereco' => ['nullable', 'string', 'max:500'],
            'cidade' => ['nullable', 'string', 'max:100'],
            'estado' => ['nullable', 'string', 'size:2', 'regex:/^[A-Z]{2}$/'],
            'cep' => ['nullable', 'string', 'regex:/^\d{5}-\d{3}$/'], // Formato: 00000-000
            'observacoes_gerais' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * Mensagens de erro personalizadas em português.
     */
    public function messages(): array
    {
        return [
            'nome_completo.required' => 'O nome completo é obrigatório.',
            'nome_completo.min' => 'O nome completo deve ter no mínimo 3 caracteres.',
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.unique' => 'Este CPF já está cadastrado no sistema.',
            'cpf.regex' => 'O CPF deve estar no formato 000.000.000-00.',
            'data_nascimento.required' => 'A data de nascimento é obrigatória.',
            'data_nascimento.before' => 'A data de nascimento deve ser anterior a hoje.',
            'data_nascimento.after' => 'A data de nascimento deve ser posterior a 01/01/1900.',
            'telefone.required' => 'O telefone é obrigatório.',
            'estado.size' => 'O estado deve ter exatamente 2 letras (ex: SP, RJ).',
            'cep.regex' => 'O CEP deve estar no formato 00000-000.',
        ];
    }

    /**
     * Prepara os dados para validação (sanitização).
     * Remove caracteres não numéricos do CPF antes de validar.
     */
    protected function prepareForValidation(): void
    {
        // Remove pontos e traços do CPF se vieram sem formatação
        if ($this->cpf) {
            $cpfLimpo = preg_replace('/[^0-9]/', '', $this->cpf);
            $this->merge([
                'cpf' => preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpfLimpo),
            ]);
        }

        // Remove traço do CEP se vier sem formatação
        if ($this->cep) {
            $cepLimpo = preg_replace('/[^0-9]/', '', $this->cep);
            $this->merge([
                'cep' => preg_replace('/(\d{5})(\d{3})/', '$1-$2', $cepLimpo),
            ]);
        }
    }
}