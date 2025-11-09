<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/**
 * MODEL: Paciente
 * 
 * ⚠️ CAMPOS SENSÍVEIS (LGPD): cpf, data_nascimento, email, telefone, endereco
 *
 * @property int $id
 * @property string $nome_completo
 * @property string $cpf
 * @property \Carbon\Carbon $data_nascimento
 * @property string $sexo
 * @property string|null $email
 * @property string $telefone
 * @property string|null $telefone_emergencia
 * @property string|null $endereco
 * @property bool $ativo
 * @property string|null $cidade
 * @property string|null $estado
 * @property string|null $cep
 * @property string|null $observacoes_gerais
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Agendamento> $agendamentos
 * @property-read int|null $agendamentos_count
 * @property-read string $cpf_formatado
 * @property-read string $data_nascimento_formatada
 * @property-read int $idade
 * @property-read string $nome_completo_maiusculo
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Prontuario> $prontuarios
 * @property-read int|null $prontuarios_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paciente ativos()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paciente buscarPorCpf(string $cpf)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paciente buscarPorNome(string $nome)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paciente newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paciente newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paciente onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paciente query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paciente whereAtivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paciente whereCep($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paciente whereCidade($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paciente whereCpf($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paciente whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paciente whereDataNascimento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paciente whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paciente whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paciente whereEndereco($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paciente whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paciente whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paciente whereNomeCompleto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paciente whereObservacoesGerais($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paciente whereSexo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paciente whereTelefone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paciente whereTelefoneEmergencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paciente whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paciente withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Paciente withoutTrashed()
 * @mixin \Eloquent
 */
class Paciente extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pacientes';

    protected $fillable = [
        'nome_completo',
        'cpf',
        'data_nascimento',
        'sexo',
        'email',
        'telefone',
        'telefone_emergencia',
        'endereco',
        'cidade',
        'estado',
        'cep',
        'observacoes_gerais',
        'ativo',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'ativo' => 'boolean',
    ];

    // ============================================
    // RELACIONAMENTOS
    // ============================================

    /**
     * Relacionamento 1:N com Agendamento
     */
    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class, 'paciente_id');
    }

    /**
     * Relacionamento 1:N com Prontuario
     */
    public function prontuarios()
    {
        return $this->hasMany(Prontuario::class, 'paciente_id');
    }

    // ============================================
    // SCOPES
    // ============================================

    /**
     * Scope: Retorna apenas pacientes ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope: Busca por nome
     */
    public function scopeBuscarPorNome($query, string $nome)
    {
        return $query->where('nome_completo', 'like', "%{$nome}%");
    }

    /**
     * Scope: Busca por CPF
     */
    public function scopeBuscarPorCpf($query, string $cpf)
    {
        $cpfLimpo = preg_replace('/[^0-9]/', '', $cpf);
        return $query->where('cpf', 'like', "%{$cpfLimpo}%");
    }

    // ============================================
    // ACCESSORS
    // ============================================

    /**
     * Accessor: Idade calculada a partir da data de nascimento
     */
    public function getIdadeAttribute(): int
    {
        return Carbon::parse($this->data_nascimento)->age;
    }

    /**
     * Accessor: Nome completo em maiúsculas
     */
    public function getNomeCompletoMaiusculoAttribute(): string
    {
        return strtoupper($this->nome_completo);
    }

    /**
     * Accessor: CPF formatado (000.000.000-00)
     */
    public function getCpfFormatadoAttribute(): string
    {
        $cpf = preg_replace('/[^0-9]/', '', $this->cpf);
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
    }

    /**
     * Accessor: Data de nascimento formatada (dd/mm/yyyy)
     */
    public function getDataNascimentoFormatadaAttribute(): string
    {
        return Carbon::parse($this->data_nascimento)->format('d/m/Y');
    }

    // ============================================
    // MÉTODOS AUXILIARES
    // ============================================

    /**
     * Verifica se o paciente tem agendamentos futuros
     */
    public function temAgendamentosFuturos(): bool
    {
        return $this->agendamentos()
            ->where('data_hora_agendamento', '>', now())
            ->where('status', '!=', 'cancelado')
            ->exists();
    }

    /**
     * Retorna o próximo agendamento do paciente
     */
    public function proximoAgendamento()
    {
        return $this->agendamentos()
            ->where('data_hora_agendamento', '>', now())
            ->where('status', '!=', 'cancelado')
            ->orderBy('data_hora_agendamento', 'asc')
            ->first();
    }
}