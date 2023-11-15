<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnviaConfirmacaoDeMatricula
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
        $response = $next($request);
        $matricula = $request->route('matricula');
        $cursando = $matricula->situacao_id == 2;

        if ($cursando && $request->enviar) // Envia email

        $response->setContent($matricula);

        return response()->json($response);
    }
}
