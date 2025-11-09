<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * FORMREQUEST: Validação para atualização de Profissional
 * 
 * Ignora o CRM e Email do próprio profissional na validação unique.
 */
class ProfissionalUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $profissionalId = $this->route('profissional');
        $profissional = \App\Models\Profissional::find($profissionalId);
        $usuarioId = $profissional->usuario_id ?? null;

        return [
            'nome_completo' => ['required', 'string', 'max:150', 'min:3'],
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('usuarios', 'email')->ignore($usuarioId),
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],
            'telefone' => ['nullable', 'string', 'max:20'],
            'crm' => [
                'required',
                'string',
                'max:20',
                'min:3',
                Rule::unique('profissionais', 'crm')->ignore($profissionalId),
                'regex:/^[0-9]{4,10}\/[A-Z]{2}$/'
            ],
            'especialidade' => ['required', 'string', 'max:100', 'min:3'],
            'telefone_consultorio' => ['nullable', 'string', 'max:20'],
            'formacao_academica' => ['nullable', 'string', 'max:2000'],
            'observacoes' => ['nullable', 'string', 'max:2000'],
            'ativo' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome_completo.required' => 'O nome completo é obrigatório.',
            'nome_completo.min' => 'O nome completo deve ter no mínimo 3 caracteres.',
            'email.required' => 'O email é obrigatório.',
            'email.unique' => 'Este email já está cadastrado para outro usuário.',
            'email.regex' => 'O email deve estar em um formato válido.',
            'crm.required' => 'O CRM é obrigatório.',
            'crm.min' => 'O CRM deve ter no mínimo 3 caracteres.',
            'crm.unique' => 'Este CRM já está cadastrado para outro profissional.',
            'crm.regex' => 'O CRM deve estar no formato 00000/UF (exemplo: 12345/SP).',
            'especialidade.required' => 'A especialidade é obrigatória.',
            'especialidade.min' => 'A especialidade deve ter no mínimo 3 caracteres.',
            'formacao_academica.max' => 'A formação acadêmica deve ter no máximo 2000 caracteres.',
            'observacoes.max' => 'As observações devem ter no máximo 2000 caracteres.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Formata o CRM para o padrão 00000/UF
        if ($this->crm) {
            $crmLimpo = strtoupper(preg_replace('/[^0-9A-Z\/]/', '', $this->crm));
            $this->merge(['crm' => $crmLimpo]);
        }

        // Remove campos de telefone se estiverem vazios (para não acionar validação)
        if ($this->telefone && trim($this->telefone) === '') {
            $this->request->remove('telefone');
        } else if ($this->telefone) {
            $this->merge(['telefone' => trim($this->telefone)]);
        }

        if ($this->telefone_consultorio && trim($this->telefone_consultorio) === '') {
            $this->request->remove('telefone_consultorio');
        } else if ($this->telefone_consultorio) {
            $this->merge(['telefone_consultorio' => trim($this->telefone_consultorio)]);
        }
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->crm) {
                $partes = explode('/', $this->crm);

                if (count($partes) !== 2) {
                    $validator->errors()->add('crm', 'O CRM deve conter o número e a UF separados por barra (/).');
                    return;
                }

                $numero = $partes[0];
                $uf = $partes[1];

                if (!preg_match('/^\d{4,10}$/', $numero)) {
                    $validator->errors()->add('crm', 'O número do CRM deve ter entre 4 e 10 dígitos.');
                }

                $ufsValidas = [
                    'AC',
                    'AL',
                    'AP',
                    'AM',
                    'BA',
                    'CE',
                    'DF',
                    'ES',
                    'GO',
                    'MA',
                    'MT',
                    'MS',
                    'MG',
                    'PA',
                    'PB',
                    'PR',
                    'PE',
                    'PI',
                    'RJ',
                    'RN',
                    'RS',
                    'RO',
                    'RR',
                    'SC',
                    'SP',
                    'SE',
                    'TO'
                ];

                if (!in_array($uf, $ufsValidas)) {
                    $validator->errors()->add('crm', 'A UF do CRM é inválida. Use siglas válidas (ex: SP, RJ, MG).');
                }
            }

            if ($this->filled('telefone')) {
                $telefoneLimpo = preg_replace('/[^0-9]/', '', $this->telefone);
                if (strlen($telefoneLimpo) > 0) {
                    if (!preg_match('/^\(\d{2}\) \d{4,5}-\d{4}$/', $this->telefone)) {
                        $validator->errors()->add('telefone', 'O telefone deve estar no formato (00) 00000-0000 ou (00) 0000-0000.');
                    }
                }
            }

            if ($this->filled('telefone_consultorio')) {
                $telefoneLimpo = preg_replace('/[^0-9]/', '', $this->telefone_consultorio);
                if (strlen($telefoneLimpo) > 0) {
                    if (!preg_match('/^\(\d{2}\) \d{4,5}-\d{4}$/', $this->telefone_consultorio)) {
                        $validator->errors()->add('telefone_consultorio', 'O telefone do consultório deve estar no formato (00) 00000-0000 ou (00) 0000-0000.');
                    }
                }
            }
        });
    }
}
