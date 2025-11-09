// database/migrations/2024_01_01_000002_criar_tabela_profissionais.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * MIGRATION: Tabela de Profissionais de Saúde
     * 
     * Médicos, psicólogos, nutricionistas, etc.
     * Relaciona com 'usuarios' via FK.
     */
    public function up(): void
    {
        Schema::create('profissionais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->string('crm', 20)->unique(); // Registro profissional
            $table->string('especialidade', 100);
            $table->string('telefone_consultorio', 20)->nullable();
            $table->text('formacao_academica')->nullable();
            $table->text('observacoes')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('especialidade');
            $table->index('ativo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profissionais');
    }
};