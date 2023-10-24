<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TipoController;
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


