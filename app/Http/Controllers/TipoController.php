<?php

namespace App\Http\Controllers;

use App\Models\Tipo;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $tipos = Tipo::paginate(10);
            return $tipos;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
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
            DB::beginTransaction();
            $tipo = Tipo::create($request->all());
            DB::commit();

            return $tipo;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tipo  $tipo
     * @return \Illuminate\Http\Response
     */
    public function show(Tipo $tipo)
    {
        try {
            return $tipo;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tipo  $tipo
     * @return \Illuminate\Http\Response
     */
    public function edit(Tipo $tipo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tipo  $tipo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tipo $tipo)
    {
        try {
            $tipo->update($request->all());
            return $tipo;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tipo  $tipo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tipo $tipo)
    {
        try {
            $deleted = $tipo->delete();
            return !!$deleted;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
