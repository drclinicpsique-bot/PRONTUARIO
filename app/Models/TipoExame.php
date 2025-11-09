<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * MODEL: TipoExame
 * 
 * Catálogo de tipos de exames disponíveis.
 *
 * @property int $id
 * @property string $nome
 * @property string|null $codigo_tuss
 * @property string $categoria
 * @property bool $ativo
 * @property string|null $descricao
 * @property numeric|null $preco_referencia
 * @property int|null $prazo_entrega_dias
 * @property string|null $preparacao_necessaria
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExameSolicitado> $examesSolicitados
 * @property-read int|null $exames_solicitados_count
 * @property-read string|null $preco_formatado
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoExame ativos()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoExame newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoExame newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoExame onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoExame porCategoria(string $categoria)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoExame query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoExame whereAtivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoExame whereCategoria($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoExame whereCodigoTuss($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoExame whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoExame whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoExame whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoExame whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoExame whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoExame wherePrazoEntregaDias($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoExame wherePrecoReferencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoExame wherePreparacaoNecessaria($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoExame whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoExame withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoExame withoutTrashed()
 * @mixin \Eloquent
 */
class TipoExame extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tipos_exame';

    protected $fillable = [
        'nome',
        'codigo_tuss',
        'descricao',
        'categoria',
        'preco_referencia',
        'prazo_entrega_dias',
        'preparacao_necessaria',
        'ativo',
    ];

    protected $casts = [
        'preco_referencia' => 'decimal:2',
        'prazo_entrega_dias' => 'integer',
        'ativo' => 'boolean',
    ];

    // ============================================
    // RELACIONAMENTOS
    // ============================================

    /**
     * Relacionamento 1:N com ExameSolicitado
     */
    public function examesSolicitados()
    {
        return $this->hasMany(ExameSolicitado::class, 'tipo_exame_id');
    }

    // ============================================
    // SCOPES
    // ============================================

    /**
     * Scope: Retorna apenas tipos de exame ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope: Filtra por categoria
     */
    public function scopePorCategoria($query, string $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    // ============================================
    // ACCESSORS
    // ============================================

    /**
     * Accessor: Preço formatado (R$ 0,00)
     */
    public function getPrecoFormatadoAttribute(): ?string
    {
        return $this->preco_referencia 
            ? 'R$ ' . number_format($this->preco_referencia, 2, ',', '.') 
            : null;
    }
}