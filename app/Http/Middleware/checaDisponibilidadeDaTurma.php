<?php

namespace App\Http\Middleware;

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
        $turma = $request->route('turma') ?? $matricula->turma; // Pega a turma ou a turma da matrícula definida na rota
        $checaDisponibilidade = $matricula || $request->disponivel; // Considera a disponibilidade da turma no ato da matrícula ou caso seja requisitado
        
        $vagas_preenchidas = ($turma->vagas_preenchidas + $turma->vagas_fora_do_site) + 1;

        if (!$checaDisponibilidade) return $next($request);
        if (!$turma) return response("Turma não encontrada", 403);
        if (!$turma->disponivel) return response("Turma indisponível no momento", 403);
        if ($vagas_preenchidas > $turma->vagas_ofertadas) return response("Não há vagas disponíveis na turma {$turma->nome}", 403);
        
        return $next($request);
    }
}
