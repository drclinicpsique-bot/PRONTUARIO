<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/**
 * MODEL: ResultadoExame
 * 
 * ⚠️ ALTA SENSIBILIDADE (LGPD): Contém resultados médicos confidenciais.
 *
 * @property int $id
 * @property int $exame_solicitado_id
 * @property \Carbon\Carbon $data_realizacao
 * @property string|null $resultado_texto
 * @property array|null $valores_medidos
 * @property string|null $laboratorio_responsavel
 * @property string|null $arquivo_laudo_path
 * @property bool|null $valores_normais
 * @property string|null $observacoes_resultado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\ExameSolicitado $exameSolicitado
 * @property-read string $data_realizacao_formatada
 * @property-read string $status_valores
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResultadoExame newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResultadoExame newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResultadoExame onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResultadoExame query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResultadoExame whereArquivoLaudoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResultadoExame whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResultadoExame whereDataRealizacao($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResultadoExame whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResultadoExame whereExameSolicitadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResultadoExame whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResultadoExame whereLaboratorioResponsavel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResultadoExame whereObservacoesResultado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResultadoExame whereResultadoTexto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResultadoExame whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResultadoExame whereValoresMedidos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResultadoExame whereValoresNormais($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResultadoExame withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResultadoExame withoutTrashed()
 * @mixin \Eloquent
 */
class ResultadoExame extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'resultados_exame';

    protected $fillable = [
        'exame_solicitado_id',
        'data_realizacao',
        'resultado_texto',
        'valores_medidos',
        'laboratorio_responsavel',
        'arquivo_laudo_path',
        'observacoes_resultado',
        'valores_normais',
    ];

    protected $casts = [
        'data_realizacao' => 'date',
        'valores_medidos' => 'array',
        'valores_normais' => 'boolean',
    ];

    // ============================================
    // RELACIONAMENTOS
    // ============================================

    /**
     * Relacionamento N:1 com ExameSolicitado
     */
    public function exameSolicitado()
    {
        return $this->belongsTo(ExameSolicitado::class, 'exame_solicitado_id');
    }

    // ============================================
    // ACCESSORS
    // ============================================

    /**
     * Accessor: Data de realização formatada
     */
    public function getDataRealizacaoFormatadaAttribute(): string
    {
        return Carbon::parse($this->data_realizacao)->format('d/m/Y');
    }

    /**
     * Accessor: Status dos valores (normal/anormal/sem info)
     */
    public function getStatusValoresAttribute(): string
    {
        if (is_null($this->valores_normais)) {
            return 'Sem informação';
        }
        
        return $this->valores_normais ? 'Valores Normais' : 'Valores Alterados';
    }
}