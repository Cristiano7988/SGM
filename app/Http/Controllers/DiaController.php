<?php

namespace App\Http\Controllers;

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
            $dia = Dia::paginate(10);
            return $dia;
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
