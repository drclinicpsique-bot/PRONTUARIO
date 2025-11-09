<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/**
 * MODEL: Prontuario
 * 
 * ⚠️ ALTA SENSIBILIDADE (LGPD): TODOS os campos contêm informações médicas confidenciais.
 *
 * @property int $id
 * @property int $paciente_id
 * @property int $profissional_id
 * @property int|null $agendamento_id
 * @property \Carbon\Carbon $data_atendimento
 * @property \Carbon\Carbon|null $data_retorno
 * @property string|null $queixa_principal
 * @property string|null $hipotese_diagnostica
 * @property bool $finalizado
 * @property string|null $historia_doenca_atual
 * @property string|null $historia_patologica_pregressa
 * @property string|null $historia_familiar
 * @property string|null $historia_social
 * @property string|null $exame_fisico
 * @property string|null $conduta_tratamento
 * @property string|null $prescricao_medicamentos
 * @property string|null $exames_solicitados
 * @property string|null $observacoes_gerais
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Agendamento|null $agendamento
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExameSolicitado> $examesSolicitados
 * @property-read int|null $exames_solicitados_count
 * @property-read string $data_atendimento_formatada
 * @property-read string|null $data_retorno_formatada
 * @property-read \App\Models\Paciente $paciente
 * @property-read \App\Models\Profissional $profissional
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario emAberto()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario finalizados()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario porPaciente(int $pacienteId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario porProfissional(int $profissionalId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario whereAgendamentoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario whereCondutaTratamento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario whereDataAtendimento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario whereDataRetorno($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario whereExameFisico($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario whereExamesSolicitados($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario whereFinalizado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario whereHipoteseDiagnostica($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario whereHistoriaDoencaAtual($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario whereHistoriaFamiliar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario whereHistoriaPatologicaPregressa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario whereHistoriaSocial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario whereObservacoesGerais($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario wherePacienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario wherePrescricaoMedicamentos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario whereProfissionalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario whereQueixaPrincipal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prontuario withoutTrashed()
 * @mixin \Eloquent
 */
class Prontuario extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'prontuarios';

    protected $fillable = [
        'paciente_id',
        'profissional_id',
        'agendamento_id',
        'data_atendimento',
        'queixa_principal',
        'historia_doenca_atual',
        'historia_patologica_pregressa',
        'historia_familiar',
        'historia_social',
        'exame_fisico',
        'hipotese_diagnostica',
        'conduta_tratamento',
        'prescricao_medicamentos',
        'exames_solicitados',
        'observacoes_gerais',
        'data_retorno',
        'finalizado',
    ];

    protected $casts = [
        'data_atendimento' => 'datetime',
        'data_retorno' => 'date',
        'finalizado' => 'boolean',
    ];

    // ============================================
    // RELACIONAMENTOS
    // ============================================

    /**
     * Relacionamento N:1 com Paciente
     */
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    /**
     * Relacionamento N:1 com Profissional
     */
    public function profissional()
    {
        return $this->belongsTo(Profissional::class, 'profissional_id');
    }

    /**
     * Relacionamento N:1 com Agendamento (opcional)
     */
    public function agendamento()
    {
        return $this->belongsTo(Agendamento::class, 'agendamento_id');
    }

    /**
     * Relacionamento 1:N com ExameSolicitado
     */
    public function examesSolicitados()
    {
        return $this->hasMany(ExameSolicitado::class, 'prontuario_id');
    }

    // ============================================
    // SCOPES
    // ============================================

    /**
     * Scope: Retorna prontuários finalizados
     */
    public function scopeFinalizados($query)
    {
        return $query->where('finalizado', true);
    }

    /**
     * Scope: Retorna prontuários em aberto
     */
    public function scopeEmAberto($query)
    {
        return $query->where('finalizado', false);
    }

    /**
     * Scope: Filtra por paciente
     */
    public function scopePorPaciente($query, int $pacienteId)
    {
        return $query->where('paciente_id', $pacienteId);
    }

    /**
     * Scope: Filtra por profissional
     */
    public function scopePorProfissional($query, int $profissionalId)
    {
        return $query->where('profissional_id', $profissionalId);
    }

    // ============================================
    // ACCESSORS
    // ============================================

    /**
     * Accessor: Data do atendimento formatada (dd/mm/yyyy HH:mm)
     */
    public function getDataAtendimentoFormatadaAttribute(): string
    {
        return Carbon::parse($this->data_atendimento)->format('d/m/Y H:i');
    }

    /**
     * Accessor: Data de retorno formatada (dd/mm/yyyy)
     */
    public function getDataRetornoFormatadaAttribute(): ?string
    {
        return $this->data_retorno 
            ? Carbon::parse($this->data_retorno)->format('d/m/Y') 
            : null;
    }
}