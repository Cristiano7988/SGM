<?php

namespace App\Http\Controllers;

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
            $marcacoes = Marcacao::paginate(10);
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
            $nucleo = Marcacao::create($request->all());
            return $nucleo;
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
