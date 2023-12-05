<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Models\Dia;
use Illuminate\Http\Response;

class DiaController extends Controller
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
            $dias = Dia::query();
            
            if (isset($turmas)) $dias = Filtra::resultado($dias, $turmas, 'turma_id')->with('turmas');

            $dias = $dias->get('nome');

            return response($dias);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Dia $dia
     * @return \Illuminate\Http\Response
     */
    public function show(Dia $dia):Response
    {
        try {
            return response($dia);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
