// database/migrations/2024_01_01_000007_criar_tabela_exames_solicitados.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * MIGRATION: Tabela de Exames Solicitados
     * 
     * Solicitações de exames feitas pelos profissionais durante consultas.
     */
    public function up(): void
    {
        Schema::create('exames_solicitados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prontuario_id')->constrained('prontuarios')->onDelete('cascade');
            $table->foreignId('tipo_exame_id')->constrained('tipos_exame')->onDelete('restrict');
            $table->foreignId('profissional_solicitante_id')->constrained('profissionais')->onDelete('restrict');
            $table->date('data_solicitacao');
            $table->enum('status', [
                'solicitado',
                'em_analise',
                'concluido',
                'cancelado'
            ])->default('solicitado');
            $table->text('observacoes_solicitacao')->nullable();
            $table->date('data_prevista_resultado')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('prontuario_id');
            $table->index('tipo_exame_id');
            $table->index('status');
            $table->index('data_solicitacao');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exames_solicitados');
    }
};