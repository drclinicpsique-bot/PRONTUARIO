// database/migrations/2024_01_01_000003_criar_tabela_pacientes.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * MIGRATION: Tabela de Pacientes
     * 
     * ⚠️ CAMPOS SENSÍVEIS (LGPD): cpf, data_nascimento, email, telefone, endereco
     * Segurança: Considerar criptografia em nível de aplicação para campos sensíveis.
     */
    public function up(): void
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome_completo', 150);
            $table->string('cpf', 14)->unique(); // Formato: 000.000.000-00 (LGPD - campo sensível)
            $table->date('data_nascimento'); // LGPD - campo sensível
            $table->enum('sexo', ['masculino', 'feminino', 'outro', 'nao_informado'])->default('nao_informado');
            $table->string('email', 100)->nullable();
            $table->string('telefone', 20); // LGPD - campo sensível
            $table->string('telefone_emergencia', 20)->nullable();
            $table->text('endereco')->nullable(); // LGPD - campo sensível
            $table->string('cidade', 100)->nullable();
            $table->string('estado', 2)->nullable();
            $table->string('cep', 10)->nullable();
            $table->text('observacoes_gerais')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Índices para busca e performance
            $table->index('cpf');
            $table->index('nome_completo');
            $table->index('ativo');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};