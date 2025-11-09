// database/migrations/2024_01_01_000008_criar_tabela_resultados_exame.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * MIGRATION: Tabela de Resultados de Exames
     * 
     * ⚠️ ALTA SENSIBILIDADE (LGPD): Contém resultados médicos confidenciais.
     */
    public function up(): void
    {
        Schema::create('resultados_exame', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exame_solicitado_id')->constrained('exames_solicitados')->onDelete('cascade');
            $table->date('data_realizacao');
            $table->text('resultado_texto')->nullable(); // LGPD - campo sensível
            $table->json('valores_medidos')->nullable(); // LGPD - campo sensível (JSON com medidas)
            $table->string('laboratorio_responsavel', 150)->nullable();
            $table->string('arquivo_laudo_path')->nullable(); // Caminho do PDF do laudo
            $table->text('observacoes_resultado')->nullable();
            $table->boolean('valores_normais')->nullable(); // true/false/null
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('exame_solicitado_id');
            $table->index('data_realizacao');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resultados_exame');
    }
};