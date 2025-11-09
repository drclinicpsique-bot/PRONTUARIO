// database/migrations/2024_01_01_000005_criar_tabela_prontuarios.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * MIGRATION: Tabela de Prontuários Médicos
     * 
     * ⚠️ ALTA SENSIBILIDADE (LGPD): Todos os campos contêm informações médicas confidenciais.
     * Segurança: Logs de acesso obrigatórios, criptografia recomendada.
     */
    public function up(): void
    {
        Schema::create('prontuarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');
            $table->foreignId('profissional_id')->constrained('profissionais')->onDelete('restrict'); // Não deletar profissional com prontuários
            $table->foreignId('agendamento_id')->nullable()->constrained('agendamentos')->onDelete('set null');
            $table->dateTime('data_atendimento');
            
            // Campos LGPD - Alta Sensibilidade
            $table->text('queixa_principal')->nullable(); // Motivo da consulta
            $table->text('historia_doenca_atual')->nullable(); // HDA
            $table->text('historia_patologica_pregressa')->nullable(); // HPP
            $table->text('historia_familiar')->nullable();
            $table->text('historia_social')->nullable(); // Hábitos, uso de substâncias
            $table->text('exame_fisico')->nullable();
            $table->text('hipotese_diagnostica')->nullable(); // CID-10
            $table->text('conduta_tratamento')->nullable();
            $table->text('prescricao_medicamentos')->nullable();
            $table->text('exames_solicitados')->nullable();
            $table->text('observacoes_gerais')->nullable();
            $table->date('data_retorno')->nullable();
            
            $table->boolean('finalizado')->default(false);
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('paciente_id');
            $table->index('profissional_id');
            $table->index('data_atendimento');
            $table->index('finalizado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prontuarios');
    }
};