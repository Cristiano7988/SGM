<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Models\Dia;

class DiaController extends Controller
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
            $dias = Dia::query();
            
            if (isset($turmas)) $dias = Filtra::resultado($dias, $turmas, 'turma_id')->with('turmas');

            $dias = $dias->get('nome');

            return $dias;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Dia $dia
     * @return \Illuminate\Http\Response
     */
    public function show(Dia $dia)
    {
        try {
            return $dia;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
