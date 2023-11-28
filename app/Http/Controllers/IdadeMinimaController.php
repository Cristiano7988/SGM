<?php

namespace App\Http\Controllers;

use App\Models\IdadeMinima;
use Illuminate\Http\Request;

class IdadeMinimaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $idades = IdadeMinima::paginate(10);
            return response()->json($idades);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
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
            $idadeMinima = IdadeMinima::create($request->all());
            return response()->json($idadeMinima);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\IdadeMinima  $idadeMinima
     * @return \Illuminate\Http\Response
     */
    public function show(IdadeMinima $idadeMinima)
    {
        return response()->json($idadeMinima);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\IdadeMinima  $idadeMinima
     * @return \Illuminate\Http\Response
     */
    public function edit(IdadeMinima $idadeMinima)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\IdadeMinima  $idadeMinima
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IdadeMinima $idadeMinima)
    {
        try {
            $idadeMinima->update($request->all());
            return response()->json($idadeMinima);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\IdadeMinima  $idadeMinima
     * @return \Illuminate\Http\Response
     */
    public function destroy(IdadeMinima $idadeMinima)
    {
        try {
            $deleted = $idadeMinima->delete();
            return response()->json(!!$deleted);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }
}
