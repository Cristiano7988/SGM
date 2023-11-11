<?php

namespace App\Http\Middleware;

use App\Helpers\Calcula;
use Closure;
use Illuminate\Http\Request;

class CalculaDescontoDepoisDaController
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
        $response = $next($request)->getData();
        $temVariosPacotes = isset($response->data);
        $pacotes = $temVariosPacotes ? $response->data : [$response];

        $pacotes = Calcula::desconto($pacotes);
        
        if ($temVariosPacotes) $response->data = $pacotes;
        else [$response] = $pacotes;

        return response()->json($response);
    }
}
