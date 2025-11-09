<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * MODEL: Profissional
 * 
 * Representa profissionais de saúde (médicos, psicólogos, etc.)
 *
 * @property int $id
 * @property int $usuario_id
 * @property string $crm
 * @property string $especialidade
 * @property bool $ativo
 * @property string|null $telefone_consultorio
 * @property string|null $formacao_academica
 * @property string|null $observacoes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Agendamento> $agendamentos
 * @property-read int|null $agendamentos_count
 * @property-read string $crm_formatado
 * @property-read string $nome_completo
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Prontuario> $prontuarios
 * @property-read int|null $prontuarios_count
 * @property-read \App\Models\Usuario $usuario
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profissional ativos()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profissional newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profissional newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profissional onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profissional porEspecialidade(string $especialidade)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profissional query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profissional whereAtivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profissional whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profissional whereCrm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profissional whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profissional whereEspecialidade($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profissional whereFormacaoAcademica($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profissional whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profissional whereObservacoes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profissional whereTelefoneConsultorio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profissional whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profissional whereUsuarioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profissional withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profissional withoutTrashed()
 * @mixin \Eloquent
 */
class Profissional extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'profissionais';

    protected $fillable = [
        'usuario_id',
        'crm',
        'especialidade',
        'telefone_consultorio',
        'formacao_academica',
        'observacoes',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    // ============================================
    // RELACIONAMENTOS
    // ============================================

    /**
     * Relacionamento N:1 com Usuario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Relacionamento 1:N com Agendamento
     */
    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class, 'profissional_id');
    }

    /**
     * Relacionamento 1:N com Prontuario
     */
    public function prontuarios()
    {
        return $this->hasMany(Prontuario::class, 'profissional_id');
    }

    // ============================================
    // SCOPES
    // ============================================

    /**
     * Scope: Retorna apenas profissionais ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope: Filtra por especialidade
     */
    public function scopePorEspecialidade($query, string $especialidade)
    {
        return $query->where('especialidade', 'like', "%{$especialidade}%");
    }

    // ============================================
    // ACCESSORS
    // ============================================

    /**
     * Accessor: Nome completo do profissional (via usuário)
     */
    public function getNomeCompletoAttribute(): string
    {
        return $this->usuario->nome_completo ?? 'Profissional Desconhecido';
    }

    /**
     * Accessor: CRM formatado
     */
    public function getCrmFormatadoAttribute(): string
    {
        return "CRM {$this->crm}";
    }
}