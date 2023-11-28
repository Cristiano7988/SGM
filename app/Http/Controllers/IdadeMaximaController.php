<?php

namespace App\Http\Controllers;

use App\Models\IdadeMaxima;
use Illuminate\Http\Request;

class IdadeMaximaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $idades = IdadeMaxima::paginate(10);
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
            $idadeMaxima = IdadeMaxima::create($request->all());
            return response()->json($idadeMaxima);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\IdadeMaxima  $idadeMaxima
     * @return \Illuminate\Http\Response
     */
    public function show(IdadeMaxima $idadeMaxima)
    {
        return response()->json($idadeMaxima);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\IdadeMaxima  $idadeMaxima
     * @return \Illuminate\Http\Response
     */
    public function edit(IdadeMaxima $idadeMaxima)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\IdadeMaxima  $idadeMaxima
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IdadeMaxima $idadeMaxima)
    {
        try {
            $idadeMaxima->update($request->all());
            return response()->json($idadeMaxima);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\IdadeMaxima  $idadeMaxima
     * @return \Illuminate\Http\Response
     */
    public function destroy(IdadeMaxima $idadeMaxima)
    {
        try {
            $deleted = $idadeMaxima->delete();
            return response()->json(!!$deleted);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }
}
