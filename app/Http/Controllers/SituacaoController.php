<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
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

            $order_by = $order_by ?? 'situacoes.esta'; // Ordenação por situações e matrículas
            $sort =  $sort ?? 'asc';
            $per_page = $per_page ?? 10;

            $situacoes = $situacoes->orderBy($order_by, $sort)->paginate($per_page);

            return $situacoes;
        } catch (\Throwable $th) {
            return $th->getMessage();
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
            return $th->getMessage();
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
            return $th->getMessage();
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
            return $th->getMessage();
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
            return $th->getMessage();
        }
    }
}
