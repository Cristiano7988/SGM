<?php

use App\Http\Controllers\AlunoController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\NucleoController;
use App\Http\Controllers\PacoteController;
use App\Http\Controllers\PeriodoController;
use App\Http\Controllers\TurmaController;
use App\Http\Controllers\MatriculaController;
use App\Http\Middleware\calculaIdadeDoAluno;
use App\Http\Middleware\ChecaSeEAdmin;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::middleware(['auth', 'verified'])->group(function () {
    /**
     * ROTAS ADMINISTRATIVAS
    */
    Route::middleware(ChecaSeEAdmin::class)->group(function () {
        Route::resource('nucleos', NucleoController::class)->except(['index', 'show']);
        Route::resource('turmas', TurmaController::class)->except(['index', 'show']);
        Route::resource('pacotes', PacoteController::class)->except(['index', 'show']);
        Route::resource('periodos', PeriodoController::class)->except(['index', 'show']);
        Route::resource('matriculas', MatriculaController::class)->except(['index', 'create', 'store', 'show']);
    });

    /**
     * ROTAS DE USUÁRIO COMUM
     */
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::middleware(calculaIdadeDoAluno::class)
        ->resource('nucleos', NucleoController::class)->only(['index', 'show']);
        
    Route::resource('turmas', TurmaController::class)->only(['index', 'show']);
    Route::resource('pacotes', PacoteController::class)->only(['index', 'show']);
    Route::resource('periodos', PeriodoController::class)->only(['index', 'show']);
    Route::resource('matriculas', MatriculaController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('alunos', AlunoController::class);
    Route::resource('users', UserController::class);
});

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
