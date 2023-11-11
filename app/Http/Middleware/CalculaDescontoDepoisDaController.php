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
        $pacotes = Calcula::desconto($response->data);
        $response->data = $pacotes;

        return response()->json($response);
    }
}
