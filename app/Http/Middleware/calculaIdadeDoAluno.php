<?php

namespace App\Http\Middleware;

use App\Models\Aluno;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

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
        $id = $request->aluno_id;

        if ($id) {
            $aluno = Aluno::find($id);
            $now = Carbon::now();

            if (!$aluno) return response("Aluno não encontrado", 403);

            $data_de_nascimento = Carbon::create($aluno->data_de_nascimento);
            $request['meses'] = $data_de_nascimento->diffInMonths($now);
            $request['anos'] = $data_de_nascimento->diffInYears($now);
        }
        return $next($request);
    }
}
