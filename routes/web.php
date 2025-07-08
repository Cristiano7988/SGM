<?php

use App\Http\Controllers\NucleoController;
use App\Http\Controllers\PacoteController;
use App\Http\Controllers\PeriodoController;
use App\Http\Controllers\TurmaController;
use App\Http\Middleware\calculaIdadeDoAluno;
use App\Http\Middleware\ChecaSeEAdmin;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::middleware(ChecaSeEAdmin::class)
        ->prefix('/nucleos')
        ->group(function () {
            Route::get('/create', [NucleoController::class, 'create'])->name('nucleos.create');
            Route::post('/', [NucleoController::class, 'store'])->name('nucleos.store');
            Route::get('/{nucleo}/edit', [NucleoController::class, 'edit'])->name('nucleos.edit');
            Route::post('/{nucleo}', [NucleoController::class, 'update'])->name('nucleos.update');
            Route::delete('/{nucleo}', [NucleoController::class, 'destroy'])->name('nucleos.destroy');
        });

    Route::middleware(calculaIdadeDoAluno::class)
        ->prefix('/nucleos')
        ->group(function () {  
            Route::get('/{nucleo}', [NucleoController::class, 'show'])->name('nucleos.show');
            Route::get('/', [NucleoController::class, 'index'])->name('nucleos.index');
        });

    Route::middleware(ChecaSeEAdmin::class)
        ->prefix('/turmas')
        ->group(function () {
            Route::get('/create', [TurmaController::class, 'create'])->name('turmas.create');
            Route::post('/', [TurmaController::class, 'store'])->name('turmas.store');
            Route::get('/{turma}/edit', [TurmaController::class, 'edit'])->name('turmas.edit');
            Route::post('/{turma}', [TurmaController::class, 'update'])->name('turmas.update');
            Route::delete('/{turma}', [TurmaController::class, 'destroy'])->name('turmas.destroy');
        });

    Route::prefix('/turmas')->group(function () {
        Route::get('/', [TurmaController::class, 'index'])->name('turmas.index');
        Route::get('/{turma}', [TurmaController::class, 'show'])->name('turmas.show');
    });
    
    Route::middleware(ChecaSeEAdmin::class)
        ->prefix('/pacotes')
        ->group(function () {
            Route::get('/{pacote}/edit', [PacoteController::class, 'edit'])->name('pacotes.edit');
            Route::get('/create', [PacoteController::class, 'create'])->name('pacotes.create');
            Route::post('/{pacote}', [PacoteController::class, 'update'])->name('pacotes.update');
            Route::post('/', [PacoteController::class, 'store'])->name('pacotes.store');
            Route::delete('/{pacote}', [PacoteController::class, 'destroy'])->name('pacotes.destroy');
        });

    Route::prefix('/pacotes')->group(function () {
        Route::get('/', [PacoteController::class, 'index'])->name('pacotes.index');
        Route::get('/{pacote}', [PacoteController::class, 'show'])->name('pacotes.show');
    });

    Route::prefix('/periodos')->group(function () {
        Route::get('/', [PeriodoController::class, 'index'])->name('periodos.index');
        Route::get('/', [PeriodoController::class, 'show'])->name('periodos.show');
    });

    Route::middleware(ChecaSeEAdmin::class)
        ->prefix('/periodos')
        ->group(function () {
            Route::get('/create', [PeriodoController::class, 'create'])->name('periodos.create');
            Route::post('/', [PeriodoController::class, 'store'])->name('periodos.store');
            Route::get('/{periodo}/edit', [PeriodoController::class, 'edit'])->name('periodos.edit');
            Route::post('/{periodo}', [PeriodoController::class, 'update'])->name('periodos.update');
            Route::delete('/{periodo}', [PeriodoController::class, 'destroy'])->name('periodos.destroy');
        });
    });

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
