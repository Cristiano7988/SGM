<?php

namespace App\Http\Controllers;

use App\Models\Cupom;
use Illuminate\Http\Request;

class CupomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $cupons = Cupom::paginate(10);
            return $cupons;
        } catch (\Throwable $th) {
            return $th->getMessage();
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
            $cupom = Cupom::create($request->all());
            return $cupom;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cupom  $cupom
     * @return \Illuminate\Http\Response
     */
    public function show(Cupom $cupom)
    {
        try {
            $codigo = request()->codigo;

            if ($codigo) $cupom = Cupom::where('codigo', '=', $codigo)->first();
            
            if (!$cupom) return response("Cupom não encontrado", 404);
            else return $cupom;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cupom  $cupom
     * @return \Illuminate\Http\Response
     */
    public function edit(Cupom $cupom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cupom  $cupom
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cupom $cupom)
    {
        try {
            $cupom->update($request->all());
            return $cupom;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cupom  $cupom
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cupom $cupom)
    {
        try {
            $deleted = $cupom->delete();
            return !!$deleted;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
