<?php

namespace App\Http\Middleware;

use App\Helpers\Trata;
use App\Models\Pacote;
use Closure;
use Illuminate\Http\Request;

class checaDisponibilidadeDoPacote
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
            $pacote = $request->route('pacote') ?? Pacote::find($request->pacote_id);
            $matricula = $request->server('REQUEST_URI') == '/api/matricula';
            $checaDisponibilidade =  $matricula || !!$request->ativo; // Checa disponibilidade na matricula ou quando requisitado
            if (!$pacote) return response('Pacote não encontrado', 404);
            if ($checaDisponibilidade && !$pacote->ativo) return response("Pacote inativo.", 403);
    
            return $next($request);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
