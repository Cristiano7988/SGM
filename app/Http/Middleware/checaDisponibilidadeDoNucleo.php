<?php

namespace App\Http\Middleware;

use App\Models\Nucleo;
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
        $nucleo = $request->route('nucleo') ?? Turma::find($request->turma_id)->nucleo; // Pega o núcleo definido na rota ou pega o núcleo definido no corpo da requisição
        $matricular = !!$request->matricular; // Para checar se é possível se matricular no Núcleo (quando requisitado)
        $matricula = $request->server('REQUEST_URI')  == "/api/matricula";
        $checaDisponibilidade = $matricula || $request->meses; // Considera a disponibilidade do núcleo no ato da matrícula ou caso seja requisitado
        $now = Carbon::now();

        $escopoDaIdade = $nucleo->idade_minima <= $request->meses && $nucleo->idade_maxima >= $request->meses;
        $noPeriodoDeRematricula =  $nucleo->fim_rematricula >= $now && $nucleo->inicio_rematricula <= $now;
        
        if ($checaDisponibilidade && !$escopoDaIdade) return response("Faixa etária incompatível", 403);
        if ($matricular && !$noPeriodoDeRematricula) return response ("Núcleo fechado para matrículas ou rematrículas", 403);
        
        return $next($request);
    }
}
