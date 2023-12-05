<?php

namespace App\Http\Middleware;

use App\Helpers\Calcula;
use App\Helpers\Formata;
use App\Helpers\Trata;
use App\Models\Matricula;
use Closure;
use Illuminate\Http\Request;

class CalculaDescontoAntesDaController
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
            $matricula = Matricula::find($request->matricula_id)->first();
            [$pacote] = Calcula::desconto([$matricula->pacote]);
        
            $request['desconto_aplicado'] = $pacote->desconto_aplicado;
            $request['valor_pago'] = $pacote->desconto_aplicado
                ? $pacote->valor_a_pagar
                :  Formata::moeda($pacote->valor);
    
            return $next($request);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
