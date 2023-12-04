<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Models\Marcacao;
use Illuminate\Http\Request;

class MarcacaoController extends Controller
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
            $marcacoes = Marcacao::query();

            $marcacoes
                ->leftJoin('matriculas', 'marcacoes.id', 'matriculas.marcacao_id')
                ->select(['marcacoes.*'])->groupBy('marcacoes.id');

            if (isset($matriculas)) $marcacoes = Filtra::resultado($marcacoes, $matriculas, 'matricula_id')->with('matriculas');

            $order_by = $order_by ?? 'observacao'; // Apenas por Marcação
            $sort =  $sort ?? 'asc';
            $per_page = $per_page ?? 10;

            $marcacoes = $marcacoes->orderBy($order_by, $sort)->paginate($per_page);

            return $marcacoes;
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
            $marcacao = Marcacao::create($request->all());
            return $marcacao;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Marcacao  $marcacao
     * @return \Illuminate\Http\Response
     */
    public function show(Marcacao $marcacao)
    {
        try {
            return $marcacao;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Marcacao  $marcacao
     * @return \Illuminate\Http\Response
     */
    public function edit(Marcacao $marcacao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Marcacao  $marcacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Marcacao $marcacao)
    {
        try {
            $marcacao->update($request->all());
            return $marcacao;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Marcacao  $marcacao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Marcacao $marcacao)
    {
        try {
            $deleted = $marcacao->delete();
            return !!$deleted;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
