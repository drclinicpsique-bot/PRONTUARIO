<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * FORMREQUEST: Validação para atualização de Paciente
 * 
 * Similar ao StoreRequest, mas ignora o CPF do próprio paciente na validação unique.
 */
class PacienteUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pacienteId = $this->route('paciente'); // Obtém ID da rota

        return [
            'nome_completo' => ['required', 'string', 'max:150', 'min:3'],
            'cpf' => [
                'required',
                'string',
                'size:14',
                Rule::unique('pacientes', 'cpf')->ignore($pacienteId), // Ignora o próprio CPF
                'regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/',
            ],
            'data_nascimento' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'sexo' => ['required', Rule::in(['masculino', 'feminino', 'outro', 'nao_informado'])],
            'email' => ['nullable', 'email', 'max:100'],
            'telefone' => ['required', 'string', 'max:20'],
            'telefone_emergencia' => ['nullable', 'string', 'max:20'],
            'endereco' => ['nullable', 'string', 'max:500'],
            'cidade' => ['nullable', 'string', 'max:100'],
            'estado' => ['nullable', 'string', 'size:2', 'regex:/^[A-Z]{2}$/'],
            'cep' => ['nullable', 'string', 'regex:/^\d{5}-\d{3}$/'],
            'observacoes_gerais' => ['nullable', 'string', 'max:2000'],
            'ativo' => ['sometimes', 'boolean'], // Permite ativar/desativar paciente
        ];
    }

    public function messages(): array
    {
        return [
            'nome_completo.required' => 'O nome completo é obrigatório.',
            'cpf.unique' => 'Este CPF já está cadastrado para outro paciente.',
            'cpf.regex' => 'O CPF deve estar no formato 000.000.000-00.',
            'data_nascimento.before' => 'A data de nascimento deve ser anterior a hoje.',
            'telefone.required' => 'O telefone é obrigatório.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->cpf) {
            $cpfLimpo = preg_replace('/[^0-9]/', '', $this->cpf);
            $this->merge([
                'cpf' => preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpfLimpo),
            ]);
        }

        if ($this->cep) {
            $cepLimpo = preg_replace('/[^0-9]/', '', $this->cep);
            $this->merge([
                'cep' => preg_replace('/(\d{5})(\d{3})/', '$1-$2', $cepLimpo),
            ]);
        }
    }
}