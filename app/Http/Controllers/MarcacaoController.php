<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Models\Marcacao;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MarcacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index():Response
    {
        try {
            extract(request()->all());
            $marcacoes = Marcacao::query();

            $marcacoes
                ->leftJoin('matriculas', 'marcacoes.id', 'matriculas.marcacao_id')
                ->select(['marcacoes.*'])->groupBy('marcacoes.id');

            if (isset($matriculas)) $marcacoes = Filtra::resultado($marcacoes, $matriculas, 'matricula_id')->with('matriculas');
    
            $marcacoes = Trata::resultado($marcacoes, 'observacao'); // Ordenação apenas por marcação.

            return response($marcacoes);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request):Response
    {
        try {
            $marcacao = Marcacao::create($request->all());
            return response($marcacao);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Marcacao  $marcacao
     * @return \Illuminate\Http\Response
     */
    public function show(Marcacao $marcacao):Response
    {
        try {
            return response($marcacao);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Marcacao  $marcacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Marcacao $marcacao):Response
    {
        try {
            $marcacao->update($request->all());
            return response($marcacao);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Marcacao  $marcacao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Marcacao $marcacao):Response
    {
        try {
            $marcacao->delete();
            return response("A marcação de nº {$marcacao->id}, {$marcacao->observacao},  foi deletada.");
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
