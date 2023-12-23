<?php

use App\Http\Controllers\IdadeMaximaController;
use App\Http\Controllers\IdadeMinimaController;
use App\Http\Controllers\NucleoController;
use App\Http\Middleware\calculaIdadeDoAluno;
use App\Http\Middleware\checaDisponibilidadeDoNucleo;
use App\Http\Middleware\ChecaSeEAdmin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    Route::prefix('nucleos')->group(function () {
        Route::get('/', [NucleoController::class, 'index'])->middleware(calculaIdadeDoAluno::class)->name('nucleos.index');
        Route::get('/{nucleo}', [NucleoController::class, 'show'])->middleware(calculaIdadeDoAluno::class)->middleware(checaDisponibilidadeDoNucleo::class)->name('nucleos.show');
    });

    // Área Administrativa
    Route::middleware(ChecaSeEAdmin::class)->prefix('admin')->group(function () {
        Route::resource('nucleos', NucleoController::class, [
            'except' => ['index', 'show']
        ]);

        Route::resource('idades_minimas', IdadeMinimaController::class, [
            'only' => ['store']
        ]);
        Route::resource('idades_maximas', IdadeMaximaController::class, [
            'only' => ['store']
        ]);
    });
});
