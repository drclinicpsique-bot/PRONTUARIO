// database/migrations/2024_01_01_000001_criar_tabela_usuarios.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * MIGRATION: Tabela de Usuários do Sistema
     * 
     * Armazena médicos, recepcionistas, administradores.
     * Segurança: Senhas hasheadas, timestamps de verificação de email.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome_completo', 150);
            $table->string('email')->unique();
            $table->timestamp('email_verificado_em')->nullable();
            $table->string('password');
            $table->enum('tipo_usuario', ['medico', 'recepcionista', 'admin'])->default('recepcionista');
            $table->string('crm', 20)->nullable()->unique(); // Apenas para médicos
            $table->string('telefone', 20)->nullable();
            $table->boolean('ativo')->default(true);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes(); // Soft delete obrigatório (auditoria LGPD)

            // Índices para performance
            $table->index('email');
            $table->index('tipo_usuario');
            $table->index('ativo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};