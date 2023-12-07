<?php

namespace App\Http\Middleware;

use App\Helpers\Calcula;
use App\Helpers\Trata;
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
        try {
            $response = $next($request);
            $pacote = $request->route('pacote');

            if (!$pacote) {
                $paginator = json_decode($response->getContent());
                $pacotes = $paginator->data;
                $pacotes = Calcula::desconto($pacotes);
                $paginator->data = $pacotes;
                $response->setContent(json_encode($paginator));
            } else {
                [$pacote] = Calcula::desconto([$pacote]);   
                $response->setContent($pacote);
            }

            return $response;
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
