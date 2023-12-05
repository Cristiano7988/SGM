<?php

namespace App\Http\Controllers;

use App\Helpers\Trata;
use App\Models\Medida;

class   MedidaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $medidas = Medida::all('tipo');
            return $medidas;
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Medida  $medida
     * @return \Illuminate\Http\Response
     */
    public function show(Medida $medida)
    {
        try {
            return $medida;
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

}