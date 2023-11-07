<?php

namespace App\Http\Controllers;

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
            $medidas = Medida::paginate(10);
            return $medidas;
        } catch (\Throwable $th) {
            return $th->getMessage();
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
            return $th->getMessage();
        }
    }

}