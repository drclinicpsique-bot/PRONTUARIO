// database/migrations/2024_01_01_000006_criar_tabela_tipos_exame.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * MIGRATION: Tabela de Tipos de Exames
     * 
     * Catálogo de exames disponíveis (hemograma, glicemia, raio-x, etc.)
     */
    public function up(): void
    {
        Schema::create('tipos_exame', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 150);
            $table->string('codigo_tuss', 20)->nullable()->unique(); // Código TUSS (padrão Brasil)
            $table->text('descricao')->nullable();
            $table->enum('categoria', [
                'laboratorial',
                'imagem',
                'cardiologico',
                'endoscopico',
                'outros'
            ])->default('laboratorial');
            $table->decimal('preco_referencia', 10, 2)->nullable();
            $table->integer('prazo_entrega_dias')->nullable();
            $table->text('preparacao_necessaria')->nullable(); // Ex: "jejum de 12h"
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('nome');
            $table->index('categoria');
            $table->index('ativo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_exame');
    }
};