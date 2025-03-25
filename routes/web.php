<?php

use App\Http\Controllers\NucleoController;
use App\Http\Middleware\ChecaSeEAdmin;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard', [
            'session' => viteSession()
        ]);
    })->name('dashboard');

    Route::middleware(ChecaSeEAdmin::class)->group(function () {
        Route::prefix('/nucleos')->group(function () {    
            Route::get('/', [NucleoController::class, 'index'])->name('nucleos.index');
            Route::get('/create', [NucleoController::class, 'create'])->name('nucleos.create');
            Route::post('/', [NucleoController::class, 'store'])->name('nucleos.store');
            Route::get('/{nucleo}/edit', [NucleoController::class, 'edit'])->name('nucleos.edit');
            Route::post('/{nucleo}', [NucleoController::class, 'update'])->name('nucleos.update');
            Route::delete('/{nucleo}', [NucleoController::class, 'destroy'])->name('nucleos.destroy');
        });
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
