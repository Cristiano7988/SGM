<?php

namespace App\Http\Controllers;

use App\Models\MedidaDeTempo;
use Illuminate\Http\Request;

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
            return response()->json($th->getMessage());
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
        return $medidaDeTempo;
    }
}
