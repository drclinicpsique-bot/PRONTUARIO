<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->timestamp('data_inicio_consulta')->nullable()->after('status');
            $table->timestamp('data_fim_consulta')->nullable()->after('data_inicio_consulta');
            $table->integer('duracao_real_minutos')->nullable()->after('duracao_minutos');
        });
    }

    public function down(): void
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->dropColumn(['data_inicio_consulta', 'data_fim_consulta', 'duracao_real_minutos']);
        });
    }
};