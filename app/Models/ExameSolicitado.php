<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/**
 * MODEL: ExameSolicitado
 * 
 * Representa uma solicitação de exame feita por um profissional.
 *
 * @property int $id
 * @property int $prontuario_id
 * @property int $tipo_exame_id
 * @property int $profissional_solicitante_id
 * @property \Carbon\Carbon $data_solicitacao
 * @property \Carbon\Carbon|null $data_prevista_resultado
 * @property string $status
 * @property string|null $observacoes_solicitacao
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read string $data_solicitacao_formatada
 * @property-read \App\Models\Profissional $profissionalSolicitante
 * @property-read \App\Models\Prontuario $prontuario
 * @property-read \App\Models\ResultadoExame|null $resultado
 * @property-read \App\Models\TipoExame $tipoExame
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExameSolicitado newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExameSolicitado newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExameSolicitado onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExameSolicitado pendentes()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExameSolicitado porStatus(string $status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExameSolicitado query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExameSolicitado whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExameSolicitado whereDataPrevistaResultado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExameSolicitado whereDataSolicitacao($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExameSolicitado whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExameSolicitado whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExameSolicitado whereObservacoesSolicitacao($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExameSolicitado whereProfissionalSolicitanteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExameSolicitado whereProntuarioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExameSolicitado whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExameSolicitado whereTipoExameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExameSolicitado whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExameSolicitado withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExameSolicitado withoutTrashed()
 * @mixin \Eloquent
 */
class ExameSolicitado extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'exames_solicitados';

    protected $fillable = [
        'prontuario_id',
        'tipo_exame_id',
        'profissional_solicitante_id',
        'data_solicitacao',
        'status',
        'observacoes_solicitacao',
        'data_prevista_resultado',
    ];

    protected $casts = [
        'data_solicitacao' => 'date',
        'data_prevista_resultado' => 'date',
    ];

    // ============================================
    // RELACIONAMENTOS
    // ============================================

    /**
     * Relacionamento: Paciente (através do Prontuário)
     */
    public function paciente()
    {
        return $this->hasOneThrough(
            Paciente::class,
            Prontuario::class,
            'id',              // Foreign key na tabela prontuarios
            'id',              // Foreign key na tabela pacientes
            'prontuario_id',   // Local key na tabela exames_solicitados
            'paciente_id'      // Local key na tabela prontuarios
        );
    }

    /**
     * Relacionamento N:1 com Prontuario
     */
    public function prontuario()
    {
        return $this->belongsTo(Prontuario::class, 'prontuario_id');
    }

    /**
     * Relacionamento N:1 com TipoExame
     */
    public function tipoExame()
    {
        return $this->belongsTo(TipoExame::class, 'tipo_exame_id');
    }

    /**
     * Relacionamento N:1 com Profissional
     */
    public function profissionalSolicitante()
    {
        return $this->belongsTo(Profissional::class, 'profissional_solicitante_id');
    }

    /**
     * Relacionamento 1:1 com ResultadoExame
     */
    public function resultado()
    {
        return $this->hasOne(ResultadoExame::class, 'exame_solicitado_id');
    }

    // ============================================
    // SCOPES
    // ============================================

    /**
     * Scope: Filtra por status
     */
    public function scopePorStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Exames pendentes (solicitados ou em análise)
     */
    public function scopePendentes($query)
    {
        return $query->whereIn('status', ['solicitado', 'em_analise']);
    }

    // ============================================
    // ACCESSORS
    // ============================================

    /**
     * Accessor: Data de solicitação formatada
     */
    public function getDataSolicitacaoFormatadaAttribute(): string
    {
        return Carbon::parse($this->data_solicitacao)->format('d/m/Y');
    }
}
