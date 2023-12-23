<?php

namespace App\Http\Middleware;

use App\Helpers\Trata;
use App\Models\Turma;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class checaDisponibilidadeDoNucleo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $turma = Turma::find($request->turma_id);
            $nucleo = $request->route('nucleo');
            
            if (!$nucleo) {
                if (!$turma) {
                    $mensagem = 'turma não encontrada';
                    return web()
                        ? redirect()->back()->with($mensagem)
                        : response($mensagem, 404);
                }
                $nucleo = $turma->nucleo; // Pega o núcleo definido na rota ou pega o núcleo definido no corpo da requisição
            }
            $matricular = !!$request->matricular; // Para checar se é possível se matricular no Núcleo (quando requisitado)
            $matricula = $request->server('REQUEST_URI')  == "/api/matricula";
            $checaDisponibilidade = $matricula || ($request->meses && $request->anos); // Considera a disponibilidade do núcleo no ato da matrícula ou caso seja requisitado
            $now = Carbon::now();
    
            $noPeriodoDeRematricula =  $nucleo->fim_rematricula >= $now && $nucleo->inicio_rematricula <= $now;
            $escopoDaIdade =
                $nucleo->idade_minima->medida_de_tempo_id == 1 && $nucleo->idade_minima->idade <= $request->meses ||
                $nucleo->idade_minima->medida_de_tempo_id == 2 && $nucleo->idade_minima->idade <= $request->anos
                &&
                $nucleo->idade_maxima->medida_de_tempo_id == 1 && $nucleo->idade_maxima->idade <= $request->meses ||
                $nucleo->idade_maxima->medida_de_tempo_id == 2 && $nucleo->idade_maxima->idade <= $request->anos;
            
            if ($checaDisponibilidade && !$escopoDaIdade) {
                $mensagem = 'Faixa etária incompatível';
                return web()
                    ? redirect()->back()->with('failure', $mensagem)
                    : response($mensagem, 403);
            }
            if ($matricular && !$noPeriodoDeRematricula) {
                $mensagem = 'Núcleo fechado para matrículas ou rematrículas';
                return web()
                    ? redirect()->back()->with('failure', $mensagem)
                    : response ($mensagem, 403);
            }
            return $next($request);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return web()
                ? redirect()->back()->with('failure', $mensagem)
                : response($mensagem, 500);
        }
    }
}
