<?php

namespace App\Http\Middleware;

use App\Models\Turma;
use Closure;
use Illuminate\Http\Request;

class checaDisponibilidadeDaTurma
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
        $matricula = $request->route('matricula');
        $turma = $request->route('turma') ?? Turma::find($request->turma_id);
        if ($matricula) $turma = $matricula->turma;
        $rotaDeCriacaoDaMatricula = $request->server('REQUEST_URI')  == "/api/matricula";
        $checaDisponibilidade = $rotaDeCriacaoDaMatricula || $matricula || $request->disponivel; // Considera a disponibilidade da turma no ato da matrícula, na atualização da matrícula ou caso seja requisitado
        
        if (!$checaDisponibilidade) return $next($request);
        if (!$turma) return response("Turma não encontrada", 403);
        if (!$turma->disponivel) return response("Turma indisponível no momento", 403);

        $vagas_preenchidas = ($turma->vagas_preenchidas + $turma->vagas_fora_do_site) + 1;
        if ($vagas_preenchidas > $turma->vagas_ofertadas) return response("Não há vagas disponíveis na turma {$turma->nome}", 403);
        
        return $next($request);
    }
}
