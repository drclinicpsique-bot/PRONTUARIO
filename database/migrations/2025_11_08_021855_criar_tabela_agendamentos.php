// database/migrations/2024_01_01_000004_criar_tabela_agendamentos.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * MIGRATION: Tabela de Agendamentos de Consultas
     * 
     * Relaciona pacientes com profissionais em datas/horários específicos.
     */
    public function up(): void
    {
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');
            $table->foreignId('profissional_id')->constrained('profissionais')->onDelete('cascade');
            $table->dateTime('data_hora_agendamento');
            $table->integer('duracao_minutos')->default(60);
            $table->enum('status', [
                'agendado',
                'confirmado',
                'em_atendimento',
                'concluido',
                'cancelado',
                'falta_paciente'
            ])->default('agendado');
            $table->text('motivo_consulta')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamp('cancelado_em')->nullable();
            $table->string('motivo_cancelamento')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Índices para busca eficiente
            $table->index(['paciente_id', 'data_hora_agendamento']);
            $table->index(['profissional_id', 'data_hora_agendamento']);
            $table->index('status');
            $table->index('data_hora_agendamento');

            // Índice único para prevenir double booking (mesmo profissional, mesmo horário)
            $table->unique(['profissional_id', 'data_hora_agendamento'], 'idx_profissional_horario_unico');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agendamentos');
    }
};