<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Models\Situacao;
use Illuminate\Http\Request;

class SituacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            extract(request()->all());
            $situacoes = Situacao::query();

            $situacoes
                ->leftJoin('matriculas', 'situacoes.id', 'matriculas.situacao_id')
                ->select(['situacoes.*'])->groupBy('situacoes.id');

            if (isset($matriculas)) $situacoes = Filtra::resultado($situacoes, $matriculas, 'matriculas.id')->with('matriculas');

            $situacoes = Trata::resultado($situacoes, 'situacoes.esta'); // Ordenação por situação ou matrícula.

            return $situacoes;
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $situacoes = Situacao::create($request->all());
            return $situacoes;
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Situacao  $situacao
     * @return \Illuminate\Http\Response
     */
    public function show(Situacao $situacao)
    {
        try {
            return $situacao;
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Situacao  $situacao
     * @return \Illuminate\Http\Response
     */
    public function edit(Situacao $situacao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Situacao  $situacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Situacao $situacao)
    {
        try {
            $situacao->update($request->all());
            return $situacao;
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Situacao  $situacao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Situacao $situacao)
    {
        try {
            $deleted = $situacao->delete();
            return !!$deleted;
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
