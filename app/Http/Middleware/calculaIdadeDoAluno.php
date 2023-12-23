<?php

namespace App\Http\Middleware;

use App\Helpers\Trata;
use App\Models\Aluno;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class calculaIdadeDoAluno
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
            $id = $request->aluno_id;
            $mensagem = "Você está buscando núcleos disponíveis para qual aluno? Informe antes de acessar esta página.";

            if (!$id && !Auth::user()->is_admin) return web()
                ? redirect()->back()->with('failure', $mensagem)
                : response($mensagem, 403);

            if ($id) {
                $aluno = Aluno::find($id);
                $now = Carbon::now();
                
                $mensagem = "Aluno não encontrado";
    
                if (!$aluno) return web()
                    ? redirect()->back()->with('failure', $mensagem)
                    : response($mensagem, 403);
    
                $data_de_nascimento = Carbon::create($aluno->data_de_nascimento);
                $request['meses'] = $data_de_nascimento->floatDiffInMonths($now);
                $request['anos'] = $data_de_nascimento->floatDiffInYears($now);
            }
            return $next($request);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return web()
                ? redirect()->back()->with('failure', $mensagem)
                : response($mensagem, 500);
        }
    }
}
