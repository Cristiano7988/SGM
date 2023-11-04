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
        $turma = $request->route('turma') ?? Turma::find($request->turma_id); // Pega a turma definida na rota ou pega a turma definida no corpo da requisição
        $matricula = $request->server('REQUEST_URI')  == "/api/matricula";
        $disponivel = $matricula || $request->disponivel; // Considera a disponibilidade da turma no ato da matrícula ou caso seja requisitado

        if (!$turma) return response("Turma não encontrada", 403);
        if ($disponivel && !$turma->disponivel) return response("Turma indisponível no momento", 403);
        if ($disponivel && $turma->vagas_disponiveis <= 0) return response("Vagas indisponíveis no momento", 403);

        return $next($request);
    }
}
