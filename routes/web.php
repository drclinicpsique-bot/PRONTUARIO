<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\ProfissionalController;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\ProntuarioController;
use App\Http\Controllers\ExameController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProntuarioPdfController;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Support\Facades\Auth;

// ============================================
// ROTA TEMPORÁRIA - CRIAR ADMIN
// ============================================
Route::get('/criar-admin-agora', function () {
    try {
        $exists = DB::table('usuarios')->where('email', 'admin@drclinic.com')->exists();
        
        if ($exists) {
            return '<h1>✅ Admin já existe!</h1><p>Email: admin@drclinic.com</p><p>Senha: Admin@123</p><p><a href="/login">Ir para o login</a></p>';
        }
        
        DB::table('usuarios')->insert([
            'nome_completo' => 'Administrador',
            'email' => 'admin@drclinic.com',
            'password' => Hash::make('Admin@123'),
            'tipo_usuario' => 'admin',
            'ativo' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return '<h1>✅ Admin criado com sucesso!</h1><p>Email: admin@drclinic.com</p><p>Senha: Admin@123</p><p><a href="/login">Ir para o login</a></p>';
        
    } catch (\Exception $e) {
        return '<h1>❌ Erro:</h1><pre>' . $e->getMessage() . '</pre>';
    }
});

// ============================================
// ROTA PÚBLICA (REDIRECT PARA LOGIN)
// ============================================
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// ============================================
// ROTAS PROTEGIDAS (AUTENTICAÇÃO OBRIGATÓRIA)
// ============================================
Route::middleware(['auth'])->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // TROCAR SENHA
    Route::get('/perfil/senha', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('/perfil/senha', [PasswordController::class, 'update'])->name('password.update');

    // PACIENTES
    Route::resource('pacientes', PacienteController::class);

    // PROFISSIONAIS
    Route::resource('profissionais', ProfissionalController::class);

    // AGENDAMENTOS
    Route::resource('agendamentos', AgendamentoController::class);
    Route::post('agendamentos/{id}/iniciar-consulta', [AgendamentoController::class, 'iniciarConsulta'])
        ->name('agendamentos.iniciar-consulta');
    Route::post('agendamentos/{id}/finalizar-consulta', [AgendamentoController::class, 'finalizarConsulta'])
        ->name('agendamentos.finalizar-consulta');
    Route::post('agendamentos/{id}/cancelar', [AgendamentoController::class, 'cancelar'])
        ->name('agendamentos.cancelar');
    Route::post('agendamentos/{id}/marcar-falta', [AgendamentoController::class, 'marcarFalta'])
        ->name('agendamentos.marcar-falta');
    Route::post('agendamentos/{id}/confirmar', [AgendamentoController::class, 'confirmar'])
        ->name('agendamentos.confirmar');

    // PRONTUÁRIOS
    Route::resource('prontuarios', ProntuarioController::class);
    Route::post('prontuarios/{id}/finalizar', [ProntuarioController::class, 'finalizar'])
        ->name('prontuarios.finalizar');
    Route::get('pacientes/{paciente_id}/prontuarios', [ProntuarioController::class, 'porPaciente'])
        ->name('prontuarios.por-paciente');

    // PDFs
    Route::get('prontuarios/{id}/pdf/completo', [ProntuarioPdfController::class, 'gerarPdfCompleto'])
        ->name('prontuarios.pdf.completo');
    Route::get('prontuarios/{id}/pdf/prescricao', [ProntuarioPdfController::class, 'gerarPdfPrescricao'])
        ->name('prontuarios.pdf.prescricao');

    // EXAMES
    Route::resource('exames', ExameController::class);
    Route::get('exames/{exameSolicitado}/resultado/criar', [ExameController::class, 'createResultado'])
        ->name('exames.resultado.create');
    Route::post('exames/resultado', [ExameController::class, 'storeResultado'])
        ->name('exames.resultado.store');
});

// ============================================
// ROTAS DE AUTENTICAÇÃO
// ============================================
require __DIR__.'/auth.php';
