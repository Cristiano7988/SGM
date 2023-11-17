<?php

use App\Http\Controllers\AlunoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\CupomController;
use App\Http\Controllers\DiaController;
use App\Http\Controllers\EmailController;
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
use App\Http\Middleware\ChecaSeEAdmin;
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
    Route::prefix('/aluno')->group(function () {
        Route::get('/', [AlunoController::class, 'index']);
        Route::post('/', [AlunoController::class, 'store']);
        Route::get('/{aluno}', [AlunoController::class, 'show']);
        Route::patch('/{aluno}', [AlunoController::class, 'update']);
    });

    Route::prefix('/cupom')->group(function () {
        Route::get('/', [CupomController::class, 'index']);
        Route::get('/codigo', [CupomController::class, 'show']);
    });

    Route::prefix('/dia')->group(function () {
        Route::get('/', [DiaController::class, 'index']);
        Route::get('/{dia}', [DiaController::class, 'show']);
    });

    Route::prefix('/forma-de-pagamento')->group(function () {
        Route::get('/', [FormaDePagamentoController::class, 'index']);
        Route::get('/{forma-de-pagamento}', [FormaDePagamentoController::class, 'show']);
    });

    Route::get('/logout', [LoginController::class, 'logout']);

    Route::prefix('/matricula')->group(function () {
        Route::get('/', [MatriculaController::class, 'index']);
        Route::post('/', [MatriculaController::class, 'store'])->middleware(checaDisponibilidadeDaTurma::class)->middleware(calculaIdadeDoAluno::class)->middleware(checaDisponibilidadeDoNucleo::class)->middleware(checaDisponibilidadeDoPacote::class);
        Route::get('/{matricula}', [MatriculaController::class, 'show']);
    });

    Route::prefix('/medida')->group(function () {
        Route::get('/', [MedidaController::class, 'index']);
        Route::get('/{{medida}}', [MedidaController::class, 'show']);
    });

    Route::prefix('/nucleo')->group(function () {
        Route::get('/', [NucleoController::class, 'index'])->middleware(calculaIdadeDoAluno::class);
        Route::get('/{nucleo}', [NucleoController::class, 'show'])->middleware(calculaIdadeDoAluno::class)->middleware(checaDisponibilidadeDoNucleo::class);
    });

    Route::prefix('/pacote')->group(function () {
        Route::get('/', [PacoteController::class, 'index'])->middleware(CalculaDescontoDepoisDaController::class);
        Route::get('/{pacote}', [PacoteController::class, 'show'])->middleware(checaDisponibilidadeDoPacote::class)->middleware(CalculaDescontoDepoisDaController::class);
    });

    Route::prefix('/periodo')->group(function () {
        Route::get('/', [PeriodoController::class, 'index']);
        Route::get('/{periodo}', [PeriodoController::class, 'show']);
    });
    
    Route::prefix('/situacao')->group(function () {
        Route::get('/{situacao}', [SituacaoController::class, 'show']);
    });

    Route::prefix('/status')->group(function () {
        Route::get('/', [StatusController::class, 'index']);
        Route::get('/{status}', [StatusController::class, 'show']);
    });

    Route::prefix('/tipo')->group(function () {
        Route::get('/', [TipoController::class, 'index']);
        Route::get('/{tipo}', [TipoController::class, 'show']);
    });

    Route::prefix('/transacao')->group(function () {
        Route::get('/', [TransacaoController::class, 'index']);
        Route::middleware(CalculaDescontoAntesDaController::class)->post('/', [TransacaoController::class, 'store'])->middleware(preparaBackupDaTransacao::class);
        Route::get('/{transacao}', [TransacaoController::class, 'show']);
        Route::patch('/{transacao}', [TransacaoController::class, 'update'])->middleware(preparaBackupDaTransacao::class);
    });

    Route::prefix('/turma')->group(function () {
        Route::get('/', [TurmaController::class, 'index']);
        Route::get('/{turma}', [TurmaController::class, 'show'])->middleware(checaDisponibilidadeDaTurma::class);
    });

    Route::prefix('/user')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{user}', [UserController::class, 'show']);
        Route::patch('/{user}', [UserController::class, 'update']);
    });

    /* Área Administrativa */
    Route::middleware(ChecaSeEAdmin::class)->group(function () {
        Route::prefix('/aluno')->group(function () {
            Route::delete('/{aluno}', [AlunoController::class, 'destroy']);
        });

        Route::prefix('/cupom')->group(function () {
            Route::post('/', [CupomController::class, 'store']);
            Route::get('/{cupom}', [CupomController::class, 'show']);
            Route::patch('/{cupom}', [CupomController::class, 'update']);
            Route::delete('/{cupom}', [CupomController::class, 'destroy']);
        });

        Route::prefix('/emails')->group(function () {            
            Route::get('/', [EmailController::class, 'index']);
            Route::post('/', [EmailController::class, 'store']);
            
            Route::prefix('{email}')->group(function () {
                Route::post('/', [EmailController::class, 'send']);
                Route::get('/', [EmailController::class, 'show']);
                Route::patch('/', [EmailController::class, 'update']);
                Route::delete('/', [EmailController::class, 'destroy']);
            });
        });
    

        Route::prefix('/forma-de-pagamento')->group(function () {
            Route::post('/', [FormaDePagamentoController::class, 'store']);
            Route::patch('/{forma-de-pagamento}', [FormaDePagamentoController::class, 'update']);
            Route::delete('/{forma-de-pagamento}', [FormaDePagamentoController::class, 'destroy']);
        });

        Route::prefix('/marcacao')->group(function () {
            Route::get('/', [MarcacaoController::class, 'index']);
            Route::post('/', [MarcacaoController::class, 'store']);
            Route::get('/{marcacao}', [MarcacaoController::class, 'show']);
            Route::patch('/{marcacao}', [MarcacaoController::class, 'update']);
            Route::delete('/{marcacao}', [MarcacaoController::class, 'destroy']);
        });

        Route::prefix('/matricula')->group(function () {
            Route::patch('/{matricula}', [MatriculaController::class, 'update']);
            Route::delete('/{matricula}', [MatriculaController::class, 'destroy']);
        });

        Route::prefix('/nucleo')->group(function () {    
            Route::post('/', [NucleoController::class, 'store']);
            Route::patch('/{nucleo}', [NucleoController::class, 'update']);
            Route::delete('/{nucleo}', [NucleoController::class, 'destroy']);
        });

        Route::prefix('/pacote')->group(function () {    
            Route::post('/', [PacoteController::class, 'store']);
            Route::patch('/{pacote}', [PacoteController::class, 'update']);
            Route::delete('/{pacote}', [PacoteController::class, 'destroy']);
        });

        Route::prefix('/periodo')->group(function () {
            Route::post('/', [PeriodoController::class, 'store']);
            Route::patch('/{periodo}', [PeriodoController::class, 'update']);
            Route::delete('/{periodo}', [PeriodoController::class, 'destroy']);
        });

        Route::prefix('/situacao')->group(function () {
            Route::get('/', [SituacaoController::class, 'index']);
            Route::post('/', [SituacaoController::class, 'store']);
            Route::patch('/{situacao}', [SituacaoController::class, 'update']);
            Route::delete('/{situacao}', [SituacaoController::class, 'destroy']);
        });

        Route::prefix('/status')->group(function () {
            Route::post('/', [StatusController::class, 'store']);
            Route::patch('/{status}', [StatusController::class, 'update']);
            Route::delete('/{status}', [StatusController::class, 'destroy']);
        });

        Route::prefix('/tipo')->group(function () {
            Route::post('/', [TipoController::class, 'store']);
            Route::patch('/{tipo}', [TipoController::class, 'update']);
            Route::delete('/{tipo}', [TipoController::class, 'destroy']);
        });

        Route::prefix('/transacao')->group(function () {
            Route::delete('/{transacao}', [TransacaoController::class, 'destroy']);
        });
    

        Route::prefix('/turma')->group(function () {
            Route::post('/', [TurmaController::class, 'store']);
            Route::patch('/{turma}', [TurmaController::class, 'update']);
            Route::delete('/{turma}', [TurmaController::class, 'destroy']);
        });

        Route::prefix('/user')->group(function () {
            Route::delete('/{user}', [UserController::class, 'delete']);
        });
    });
});
