<?php

use App\Http\Controllers\AlunoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\CupomController;
use App\Http\Controllers\DiaController;
use App\Http\Controllers\FormaDePagamentoController;
use App\Http\Controllers\MarcacaoController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\MedidaController;
use App\Http\Controllers\NucleoController;
use App\Http\Controllers\PacoteController;
use App\Http\Controllers\PeriodoController;
use App\Http\Controllers\SituacaoController;
use App\Http\Controllers\TipoController;
use App\Http\Controllers\TurmaController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TransacaoController;
use App\Http\Middleware\CalculaDescontoAntesDaController;
use App\Http\Middleware\CalculaDescontoDepoisDaController;
use App\Http\Middleware\calculaIdadeDoAluno;
use App\Http\Middleware\checaDisponibilidadeDaTurma;
use App\Http\Middleware\checaDisponibilidadeDoNucleo;
use App\Http\Middleware\checaDisponibilidadeDoPacote;
use App\Http\Middleware\preparaBackupDaTransacao;
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

Route::post('/register', [RegisterController::class, 'store']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout', [LoginController::class, 'logout']);

    Route::prefix('/user')->group(function () {
        Route::get('/', [UserController::class, 'index']);
    
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{user}', [UserController::class, 'show']);
        Route::patch('/{user}', [UserController::class, 'update']);
        Route::delete('/{user}', [UserController::class, 'delete']);
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
        Route::get('/', [NucleoController::class, 'index'])->middleware(calculaIdadeDoAluno::class);

        Route::post('/', [NucleoController::class, 'store']);
        Route::get('/{nucleo}', [NucleoController::class, 'show'])->middleware(calculaIdadeDoAluno::class)->middleware(checaDisponibilidadeDoNucleo::class);
        Route::patch('/{nucleo}', [NucleoController::class, 'update']);
        Route::delete('/{nucleo}', [NucleoController::class, 'destroy']);
    });

    Route::prefix('/turma')->group(function () {
        Route::get('/', [TurmaController::class, 'index']);

        Route::post('/', [TurmaController::class, 'store']);
        Route::get('/{turma}', [TurmaController::class, 'show'])->middleware(checaDisponibilidadeDaTurma::class);
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

    Route::prefix('/marcacao')->group(function () {
        Route::get('/', [MarcacaoController::class, 'index']);

        Route::post('/', [MarcacaoController::class, 'store']);
        Route::get('/{marcacao}', [MarcacaoController::class, 'show']);
        Route::patch('/{marcacao}', [MarcacaoController::class, 'update']);
        Route::delete('/{marcacao}', [MarcacaoController::class, 'destroy']);
    });

    Route::prefix('/matricula')->group(function () {
        Route::get('/', [MatriculaController::class, 'index']);

        Route::post('/', [MatriculaController::class, 'store'])->middleware(checaDisponibilidadeDaTurma::class)->middleware(calculaIdadeDoAluno::class)->middleware(checaDisponibilidadeDoNucleo::class)->middleware(checaDisponibilidadeDoPacote::class);
        Route::get('/{matricula}', [MatriculaController::class, 'show']);
        Route::patch('/{matricula}', [MatriculaController::class, 'update']);
        Route::delete('/{matricula}', [MatriculaController::class, 'destroy']);
    });

    Route::prefix('/periodo')->group(function () {
        Route::get('/', [PeriodoController::class, 'index']);

        Route::post('/', [PeriodoController::class, 'store']);
        Route::get('/{periodo}', [PeriodoController::class, 'show']);
        Route::patch('/{periodo}', [PeriodoController::class, 'update']);
        Route::delete('/{periodo}', [PeriodoController::class, 'destroy']);
    });

    Route::prefix('/pacote')->group(function () {
        Route::get('/', [PacoteController::class, 'index'])->middleware(CalculaDescontoDepoisDaController::class);

        Route::post('/', [PacoteController::class, 'store']);
        Route::get('/{pacote}', [PacoteController::class, 'show'])->middleware(checaDisponibilidadeDoPacote::class)->middleware(CalculaDescontoDepoisDaController::class);
        Route::patch('/{pacote}', [PacoteController::class, 'update']);
        Route::delete('/{pacote}', [PacoteController::class, 'destroy']);
    });

    Route::prefix('/transacao')->group(function () {
        Route::get('/', [TransacaoController::class, 'index']);

        Route::middleware(CalculaDescontoAntesDaController::class)->post('/', [TransacaoController::class, 'store'])->middleware(preparaBackupDaTransacao::class);
        Route::get('/{transacao}', [TransacaoController::class, 'show']);
        Route::patch('/{transacao}', [TransacaoController::class, 'update'])->middleware(preparaBackupDaTransacao::class);
        Route::delete('/{transacao}', [TransacaoController::class, 'destroy']);
    });

    Route::prefix('/forma-de-pagamento')->group(function () {
        Route::get('/', [FormaDePagamentoController::class, 'index']);

        Route::post('/', [FormaDePagamentoController::class, 'store']);
        Route::get('/{forma-de-pagamento}', [FormaDePagamentoController::class, 'show']);
        Route::patch('/{forma-de-pagamento}', [FormaDePagamentoController::class, 'update']);
        Route::delete('/{forma-de-pagamento}', [FormaDePagamentoController::class, 'destroy']);
    });

    Route::prefix('/medida')->group(function () {
        Route::get('/', [MedidaController::class, 'index']);
        Route::get('/{{medida}}', [MedidaController::class, 'show']);
    });

    Route::prefix('/cupom')->group(function () {
        Route::get('/', [CupomController::class, 'index']);

        Route::post('/', [CupomController::class, 'store']);
        Route::get('/codigo', [CupomController::class, 'show']);
        Route::get('/{cupom}', [CupomController::class, 'show']);
        Route::patch('/{cupom}', [CupomController::class, 'update']);
        Route::delete('/{cupom}', [CupomController::class, 'destroy']);
    });
});
