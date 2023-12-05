<?php

namespace App\Http\Controllers;

use App\Helpers\Trata;
use App\Models\MedidaDeTempo;

class MedidaDeTempoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $medidas = MedidaDeTempo::all('tipo');
            return $medidas;
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MedidaDeTempo  $medidaDeTempo
     * @return \Illuminate\Http\Response
     */
    public function show(MedidaDeTempo $medidaDeTempo)
    {
        try {
            return $medidaDeTempo;
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
