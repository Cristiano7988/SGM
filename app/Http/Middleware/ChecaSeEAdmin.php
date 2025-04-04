<?php

namespace App\Http\Middleware;

use App\Helpers\Trata;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChecaSeEAdmin
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
            $isAdmin = Auth::user()->is_admin;
    
            if ($isAdmin) return $next($request);
            else {
                $mensagem = "Acesso negado";
                session(['error' => $mensagem]);

                return isWeb()
                    ? redirect()->route('dashboard')
                    : response($mensagem, 403);
            }
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);

            return isWeb()
                ? redirect()->route('dashboard')
                : response($mensagem, 500);
        }
    }
}
