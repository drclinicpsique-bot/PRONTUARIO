<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * MODEL: Usuario
 * 
 * Representa usuários do sistema (médicos, recepcionistas, admins).
 *
 * @property int $id
 * @property string $nome_completo
 * @property string $email
 * @property string $password
 * @property string|null $crm
 * @property bool $ativo
 * @property \Illuminate\Support\Carbon|null $email_verificado_em
 * @property string $tipo_usuario
 * @property string|null $telefone
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read string $nome_completo_maiusculo
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Profissional|null $profissional
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario ativos()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario medicos()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereAtivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereCrm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereEmailVerificadoEm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereNomeCompleto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereTelefone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereTipoUsuario($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario withoutTrashed()
 * @mixin \Eloquent
 */
class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'usuarios';

    protected $fillable = [
        'nome_completo',
        'email',
        'password',
        'tipo_usuario',
        'crm',
        'telefone',
        'ativo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verificado_em' => 'datetime',
        'ativo' => 'boolean',
        'password' => 'hashed', // Laravel 10+ automatic hashing
    ];

    // ============================================
    // RELACIONAMENTOS
    // ============================================

    /**
     * Relacionamento 1:1 com Profissional
     * Um usuário do tipo 'medico' pode ter um registro de profissional associado.
     */
    public function profissional()
    {
        return $this->hasOne(Profissional::class, 'usuario_id');
    }

    // ============================================
    // SCOPES
    // ============================================

    /**
     * Scope: Retorna apenas usuários ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope: Retorna apenas médicos
     */
    public function scopeMedicos($query)
    {
        return $query->where('tipo_usuario', 'medico');
    }

    // ============================================
    // ACCESSORS
    // ============================================

    /**
     * Accessor: Nome completo em maiúsculas
     */
    public function getNomeCompletoMaiusculoAttribute(): string
    {
        return strtoupper($this->nome_completo);
    }
}