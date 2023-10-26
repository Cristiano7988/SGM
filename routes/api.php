<?php

use App\Http\Controllers\AlunoController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DiaController;
use App\Http\Controllers\NucleoController;
use App\Http\Controllers\SituacaoController;
use App\Http\Controllers\TipoController;
use App\Http\Controllers\TurmaController;
use App\Http\Controllers\StatusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/user')->group(function () {
    Route::get('/', [RegisterController::class, 'all']);

    Route::post('/', [RegisterController::class, 'create']);
    Route::get('/{user}', [RegisterController::class, 'show']);
    Route::patch('/{user}', [RegisterController::class, 'update']);
    Route::delete('/{user}', [RegisterController::class, 'delete']);
});

Route::prefix('/tipo')->group(function () {
    Route::get('/', [TipoController::class, 'index']);

    Route::post('/', [TipoController::class, 'store']);
    Route::get('/{tipo}', [TipoController::class, 'show']);
    Route::patch('/{tipo}', [TipoController::class, 'update']);
    Route::delete('/{tipo}', [TipoController::class, 'destroy']);
});

Route::prefix('/aluno')->group(function () {
    Route::get('/', [AlunoController::class, 'index']);

    Route::post('/', [AlunoController::class, 'store']);
    Route::get('/{aluno}', [AlunoController::class, 'show']);
    Route::patch('/{aluno}', [AlunoController::class, 'update']);
    Route::delete('/{aluno}', [AlunoController::class, 'destroy']);
});

Route::prefix('/nucleo')->group(function () {
    Route::get('/', [NucleoController::class, 'index']);

    Route::post('/', [NucleoController::class, 'store']);
    Route::get('/{nucleo}', [NucleoController::class, 'show']);
    Route::patch('/{nucleo}', [NucleoController::class, 'update']);
    Route::delete('/{nucleo}', [NucleoController::class, 'destroy']);
});

Route::prefix('/turma')->group(function () {
    Route::get('/', [TurmaController::class, 'index']);

    Route::post('/', [TurmaController::class, 'store']);
    Route::get('/{turma}', [TurmaController::class, 'show']);
    Route::patch('/{turma}', [TurmaController::class, 'update']);
    Route::delete('/{turma}', [TurmaController::class, 'destroy']);
});

Route::prefix('/status')->group(function () {
    Route::get('/', [StatusController::class, 'index']);

    Route::post('/', [StatusController::class, 'store']);
    Route::get('/{status}', [StatusController::class, 'show']);
    Route::patch('/{status}', [StatusController::class, 'update']);
    Route::delete('/{status}', [StatusController::class, 'destroy']);
});

Route::prefix('/dia')->group(function () {
    Route::get('/', [DiaController::class, 'index']);

    Route::get('/{dia}', [DiaController::class, 'show']);
});

Route::prefix('/situacao')->group(function () {
    Route::get('/', [SituacaoController::class, 'index']);

    Route::post('/', [SituacaoController::class, 'store']);
    Route::get('/{situacao}', [SituacaoController::class, 'show']);
    Route::patch('/{situacao}', [SituacaoController::class, 'update']);
    Route::delete('/{situacao}', [SituacaoController::class, 'destroy']);
});


