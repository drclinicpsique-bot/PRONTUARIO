<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * MODEL: Agendamento
 * 
 * Representa consultas agendadas entre pacientes e profissionais.
 *
 * @property int $id
 * @property int $paciente_id
 * @property int $profissional_id
 * @property \Carbon\Carbon $data_hora_agendamento
 * @property int $duracao_minutos
 * @property string $status (agendado, confirmado, em_atendimento, concluido, cancelado, falta_paciente)
 * @property \Carbon\Carbon|null $data_inicio_consulta
 * @property \Carbon\Carbon|null $data_fim_consulta
 * @property int|null $duracao_real_minutos
 * @property \Carbon\Carbon|null $cancelado_em
 * @property string|null $motivo_consulta
 * @property string|null $observacoes
 * @property string|null $motivo_cancelamento
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read string $data_hora_formatada
 * @property-read string $status_label
 * @property-read string $tempo_decorrido_formatado
 * @property-read \App\Models\Paciente $paciente
 * @property-read \App\Models\Profissional $profissional
 * @property-read \App\Models\Prontuario|null $prontuario
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Agendamento futuros()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Agendamento hoje()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Agendamento porStatus(string $status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Agendamento porProfissional(int $profissionalId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Agendamento porPaciente(int $pacienteId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Agendamento emAndamento()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Agendamento consultasFinalizadas()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Agendamento confirmados()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Agendamento atrasados()
 * @mixin \Eloquent
 */
class Agendamento extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'agendamentos';

    protected $fillable = [
        'paciente_id',
        'profissional_id',
        'data_hora_agendamento',
        'duracao_minutos',
        'status',
        'motivo_consulta',
        'observacoes',
        'cancelado_em',
        'motivo_cancelamento',
        'data_inicio_consulta',
        'data_fim_consulta',
        'duracao_real_minutos',
    ];

    protected $casts = [
        'data_hora_agendamento' => 'datetime',
        'data_inicio_consulta' => 'datetime',
        'data_fim_consulta' => 'datetime',
        'cancelado_em' => 'datetime',
        'duracao_minutos' => 'integer',
        'duracao_real_minutos' => 'integer',
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
     * Relacionamento 1:1 com Prontuario
     */
    public function prontuario()
    {
        return $this->hasOne(Prontuario::class, 'agendamento_id');
    }

    // ============================================
    // SCOPES - FILTROS
    // ============================================

    /**
     * Scope: Retorna agendamentos futuros
     */
    public function scopeFuturos($query)
    {
        return $query->where('data_hora_agendamento', '>', now());
    }

    /**
     * Scope: Retorna agendamentos de hoje
     */
    public function scopeHoje($query)
    {
        return $query->whereDate('data_hora_agendamento', today());
    }

    /**
     * Scope: Filtra por status
     */
    public function scopePorStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Filtra por profissional
     */
    public function scopePorProfissional($query, int $profissionalId)
    {
        return $query->where('profissional_id', $profissionalId);
    }

    /**
     * Scope: Filtra por paciente
     */
    public function scopePorPaciente($query, int $pacienteId)
    {
        return $query->where('paciente_id', $pacienteId);
    }

    /**
     * Scope: Agendamentos confirmados
     */
    public function scopeConfirmados($query)
    {
        return $query->where('status', 'confirmado');
    }

    /**
     * Scope: Consultas em andamento
     */
    public function scopeEmAndamento($query)
    {
        return $query->whereNotNull('data_inicio_consulta')
            ->whereNull('data_fim_consulta');
    }

    /**
     * Scope: Consultas finalizadas
     */
    public function scopeConsultasFinalizadas($query)
    {
        return $query->whereNotNull('data_inicio_consulta')
            ->whereNotNull('data_fim_consulta');
    }

    /**
     * Scope: Agendamentos atrasados (não iniciados)
     */
    public function scopeAtrasados($query)
    {
        return $query->where('data_hora_agendamento', '<', now())
            ->where('status', '!=', 'concluido')
            ->where('status', '!=', 'cancelado')
            ->whereNull('data_inicio_consulta');
    }

    /**
     * Scope: Agendamentos próximos
     */
    public function scopeProximos($query, int $dias = 7)
    {
        return $query->whereBetween('data_hora_agendamento', [
            now(),
            now()->addDays($dias)
        ])->orderBy('data_hora_agendamento');
    }

    // ============================================
    // MÉTODOS DE VALIDAÇÃO
    // ============================================

    /**
     * Verifica se pode iniciar consulta
     */
    public function podeIniciarConsulta(): bool
    {
        return in_array($this->status, ['confirmado', 'agendado'])
            && is_null($this->data_inicio_consulta);
    }

    /**
     * Verifica se a consulta está em andamento
     */
    public function emAtendimento(): bool
    {
        return !is_null($this->data_inicio_consulta)
            && is_null($this->data_fim_consulta);
    }

    /**
     * Verifica se pode finalizar consulta
     */
    public function podeFinalizarConsulta(): bool
    {
        return $this->emAtendimento();
    }

    /**
     * Verifica se o agendamento pode ser cancelado
     */
    public function podeCancelar(): bool
    {
        return in_array($this->status, ['agendado', 'confirmado'])
            && $this->data_hora_agendamento > now()
            && is_null($this->data_inicio_consulta);
    }

    /**
     * Verifica se o agendamento está no passado
     */
    public function estaNoPassado(): bool
    {
        return $this->data_hora_agendamento < now();
    }

    /**
     * Verifica se passou da data/hora agendada
     */
    public function estahAtrasado(): bool
    {
        return $this->data_hora_agendamento->isPast()
            && !in_array($this->status, ['concluido', 'cancelado'])
            && is_null($this->data_inicio_consulta);
    }

    /**
     * Verifica se o agendamento é hoje
     */
    public function ehHoje(): bool
    {
        return $this->data_hora_agendamento->isToday();
    }

    /**
     * Verifica se o agendamento é amanhã
     */
    public function ehAmanha(): bool
    {
        return $this->data_hora_agendamento->isTomorrow();
    }

    // ============================================
    // MÉTODOS DE CÁLCULO
    // ============================================

    /**
     * Calcula duração real da consulta em minutos
     */
    public function calcularDuracaoReal(): ?int
    {
        if ($this->data_inicio_consulta && $this->data_fim_consulta) {
            return (int) $this->data_fim_consulta->diffInMinutes($this->data_inicio_consulta);
        }
        return null;
    }

    /**
     * Retorna tempo decorrido desde o início da consulta (em minutos)
     */
    public function tempoDecorrido(): ?int
    {
        if ($this->emAtendimento()) {
            return (int) $this->data_inicio_consulta->diffInMinutes(now());
        }
        return null;
    }

    /**
     * Retorna hora de término estimada
     */
    public function horaTerminoEstimada(): ?Carbon
    {
        if ($this->data_inicio_consulta) {
            return $this->data_inicio_consulta->addMinutes($this->duracao_minutos);
        }
        return null;
    }

    /**
     * Verifica se consultoria está ultrapassando tempo estimado
     */
    public function ultrapassouTempoEstimado(): bool
    {
        if ($this->emAtendimento()) {
            $horaEstimada = $this->horaTerminoEstimada();
            return now()->isAfter($horaEstimada);
        }
        return false;
    }

    /**
     * Retorna minutos restantes até o término estimado
     */
    public function minutosRestantes(): ?int
    {
        if ($this->emAtendimento()) {
            $horaEstimada = $this->horaTerminoEstimada();
            if (now()->isBefore($horaEstimada)) {
                return (int) now()->diffInMinutes($horaEstimada);
            }
            return 0;
        }
        return null;
    }

    // ============================================
    // MÉTODOS DE AÇÃO
    // ============================================

    /**
     * Inicia a consulta
     */
    public function iniciarConsulta(): void
    {
        if (!$this->podeIniciarConsulta()) {
            throw new \Exception('Este agendamento não pode ser iniciado.');
        }

        $this->update([
            'status' => 'em_atendimento',
            'data_inicio_consulta' => now(),
        ]);

        // Log de auditoria
        Log::info('Consulta iniciada', [
            'agendamento_id' => $this->id,
            'paciente' => $this->paciente->nome_completo,
            'profissional' => $this->profissional->usuario->nome_completo,
            'horario' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Finaliza a consulta
     */
    public function finalizarConsulta(): void
    {
        if (!$this->podeFinalizarConsulta()) {
            throw new \Exception('Esta consulta não pode ser finalizada.');
        }

        $dataFim = now();
        $duracaoRealMinutos = (int) $this->data_inicio_consulta->diffInMinutes($dataFim);

        $this->update([
            'status' => 'concluido',
            'data_fim_consulta' => $dataFim,
            'duracao_real_minutos' => $duracaoRealMinutos,
        ]);

        // Log de auditoria
        Log::info('Consulta finalizada', [
            'agendamento_id' => $this->id,
            'paciente' => $this->paciente->nome_completo,
            'profissional' => $this->profissional->usuario->nome_completo,
            'duracao_minutos' => $duracaoRealMinutos,
            'horario' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Cancela o agendamento
     */
    public function cancelar(?string $motivo = null): void
    {
        if (!$this->podeCancelar()) {
            throw new \Exception('Este agendamento não pode ser cancelado.');
        }

        $this->update([
            'status' => 'cancelado',
            'cancelado_em' => now(),
            'motivo_cancelamento' => $motivo,
        ]);

        // Log de auditoria
        Log::info('Agendamento cancelado', [
            'agendamento_id' => $this->id,
            'paciente' => $this->paciente->nome_completo,
            'profissional' => $this->profissional->usuario->nome_completo,
            'motivo' => $motivo,
            'horario' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Marca como falta do paciente
     */
    public function marcarFaltaPaciente(): void
    {
        if ($this->status !== 'agendado' && $this->status !== 'confirmado') {
            throw new \Exception('Não é possível marcar falta neste status.');
        }

        $this->update([
            'status' => 'falta_paciente',
        ]);

        // Log de auditoria
        Log::info('Falta do paciente registrada', [
            'agendamento_id' => $this->id,
            'paciente' => $this->paciente->nome_completo,
            'horario' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Confirma o agendamento
     */
    public function confirmar(): void
    {
        if ($this->status !== 'agendado') {
            throw new \Exception('Apenas agendamentos podem ser confirmados.');
        }

        $this->update([
            'status' => 'confirmado',
        ]);

        Log::info('Agendamento confirmado', [
            'agendamento_id' => $this->id,
        ]);
    }

    // ============================================
    // ACCESSORS - FORMATAÇÃO
    // ============================================

    /**
     * Accessor: Data/Hora formatada (dd/mm/yyyy HH:mm)
     */
    public function getDataHoraFormatadaAttribute(): string
    {
        return $this->data_hora_agendamento->format('d/m/Y H:i');
    }

    /**
     * Accessor: Data formatada
     */
    public function getDataFormatadaAttribute(): string
    {
        return $this->data_hora_agendamento->format('d/m/Y');
    }

    /**
     * Accessor: Hora formatada
     */
    public function getHoraFormatadaAttribute(): string
    {
        return $this->data_hora_agendamento->format('H:i');
    }

    /**
     * Accessor: Status com label legível
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'agendado' => 'Agendado',
            'confirmado' => 'Confirmado',
            'em_atendimento' => 'Em Atendimento',
            'concluido' => 'Concluído',
            'cancelado' => 'Cancelado',
            'falta_paciente' => 'Falta do Paciente',
        ];

        return $labels[$this->status] ?? 'Desconhecido';
    }

    /**
     * Accessor: Status com badge HTML
     */
    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'agendado' => '<span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-gray-100 text-gray-800"><i class="fas fa-calendar mr-1"></i> Agendado</span>',
            'confirmado' => '<span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-blue-100 text-blue-800"><i class="fas fa-check mr-1"></i> Confirmado</span>',
            'em_atendimento' => '<span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-yellow-100 text-yellow-800"><i class="fas fa-hourglass-half mr-1"></i> Em Atendimento</span>',
            'concluido' => '<span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i> Concluído</span>',
            'cancelado' => '<span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-red-100 text-red-800"><i class="fas fa-times-circle mr-1"></i> Cancelado</span>',
            'falta_paciente' => '<span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-purple-100 text-purple-800"><i class="fas fa-user-slash mr-1"></i> Falta</span>',
        ];

        return $badges[$this->status] ?? $badges['agendado'];
    }

    /**
     * Accessor: Tempo decorrido formatado
     */
    public function getTempoDecorridoFormatadoAttribute(): ?string
    {
        if ($this->emAtendimento()) {
            $minutos = $this->tempoDecorrido();
            $horas = intdiv($minutos, 60);
            $mins = $minutos % 60;

            if ($horas > 0) {
                return "{$horas}h {$mins}m";
            }
            return "{$mins}m";
        }
        return null;
    }

    /**
     * Accessor: Verificar atraso
     */
    public function getStatusAtrasadoAttribute(): bool
    {
        return $this->estahAtrasado();
    }

    /**
     * Accessor: Hora término estimada formatada
     */
    public function getHoraTerminoEstimadaFormatadaAttribute(): ?string
    {
        $hora = $this->horaTerminoEstimada();
        return $hora ? $hora->format('H:i') : null;
    }

    /**
     * Accessor: Minutos restantes formatados
     */
    public function getMinutosRestantesFormatadoAttribute(): ?string
    {
        if ($this->emAtendimento()) {
            $minutos = $this->minutosRestantes();
            
            if ($minutos === 0) {
                return '<span class="text-red-600 font-bold">⏰ Tempo excedido!</span>';
            }

            $horas = intdiv($minutos, 60);
            $mins = $minutos % 60;

            if ($horas > 0) {
                return "⏱️ {$horas}h {$mins}m restantes";
            }
            return "⏱️ {$mins}m restantes";
        }
        return null;
    }

    /**
     * Accessor: Duração real formatada
     */
    public function getDuracaoRealFormatadaAttribute(): ?string
    {
        if ($this->duracao_real_minutos !== null) {
            $horas = intdiv($this->duracao_real_minutos, 60);
            $minutos = $this->duracao_real_minutos % 60;

            if ($horas > 0) {
                return "{$horas}h {$minutos}m";
            }
            return "{$minutos}m";
        }
        return null;
    }
}