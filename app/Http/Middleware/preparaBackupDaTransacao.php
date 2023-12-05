<?php

namespace App\Http\Middleware;

use App\Helpers\Formata;
use App\Helpers\Trata;
use App\Models\FormaDePagamento;
use App\Models\Matricula;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class preparaBackupDaTransacao
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
            $user = User::find($request->user_id);
            $matricula = Matricula::find($request->matricula_id);
            $forma_de_pagamento = FormaDePagamento::find($request->forma_de_pagamento_id);
    
            if (!$user) return response("Usuário não encontrado", 403);
            if (!$matricula) return response("Matrícula não encontrada", 403);
            if (!$matricula->pacote) return response("Pacote não encontrado", 403);
            if (!count($matricula->pacote->periodos)) return response("Periodos não encontrados", 403);
            if (!$matricula->aluno) return response("Aluno não encontrado", 403);
            if (!$forma_de_pagamento) return response("Forma de pagamento não encontrada", 403);
            if (!count($user->tipos->where('nome', '=', 'pagante'))) return response("O usuário deve ser do tipo pagante", 403);
    
            $vigencia = "";
            foreach($matricula->pacote->periodos as $key => $periodo) {
                $separador = $key ? " - " : "";
                $vigencia .= $separador . "De " . Formata::data($periodo->inicio) . " até " . Formata::data($periodo->fim);
            }
    
            $request['forma_de_pagamento'] = $forma_de_pagamento->tipo;
            $request['valor_do_pacote'] = Formata::moeda($matricula->pacote->valor);
            $request['nome_do_aluno'] = $matricula->aluno->nome;
            $request['nome_do_usuario'] = $user->nome;
            $request['nome_do_pacote'] = $matricula->pacote->nome;
            $request['vigencia_do_pacote'] = $vigencia;
    
            if ($request->valor_pago < 0) return response("Não é possível aplicar este desconto", 403);
    
            return $next($request);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
