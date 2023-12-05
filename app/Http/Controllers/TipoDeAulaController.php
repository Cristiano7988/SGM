<?php

namespace App\Http\Controllers;

use App\Helpers\Trata;
use App\Models\TipoDeAula;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TipoDeAulaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index():Response
    {
        try {
            $tipos_de_aula = TipoDeAula::all('tipo');
            return response($tipos_de_aula);
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
    public function store(Request $request):Response
    {
        try {
            $tipo_de_aula = TipoDeAula::create($request->all());
            return response($tipo_de_aula);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TipoDeAula $tipo_de_aula
     * @return \Illuminate\Http\Response
     */
    public function show(TipoDeAula $tipo_de_aula):Response
    {
        try {
            return response($tipo_de_aula);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TipoDeAula $tipo_de_aula
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoDeAula $tipo_de_aula)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TipoDeAula $tipo_de_aula
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TipoDeAula $tipo_de_aula):Response
    {
        try {
            $tipo_de_aula->update($request->all());
            return response($tipo_de_aula);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TipoDeAula $tipo_de_aula
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoDeAula $tipo_de_aula):Response
    {
        try {
            $tipo_de_aula->delete();
            return response("O tipo de aula de nº {$tipo_de_aula->id}, {$tipo_de_aula->nome},  foi deletado.");;
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
