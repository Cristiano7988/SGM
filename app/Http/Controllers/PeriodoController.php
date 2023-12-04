<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Models\Periodo;
use Illuminate\Http\Request;

class PeriodoController extends Controller
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
            $periodos = Periodo::query();

            $periodos
                ->leftJoin('pacotes', 'pacotes.id', 'periodos.pacote_id')
                ->select(['periodos.*'])->groupBy('periodos.id');

            if (isset($pacotes)) $periodos = Filtra::resultado($periodos, $pacotes, 'pacotes.id')->with('pacote');

            $order_by = $order_by ?? 'periodos.inicio'; // Ordenação por períodos e pacotes.
            $sort = $sort ?? 'asc';
            $per_page = $per_page ?? 10;

            $periodos = $periodos->orderBy($order_by, $sort)->paginate($per_page);

            return $periodos;
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
            $periodo = Periodo::create($request->all());
            return $periodo;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Periodo  $periodo
     * @return \Illuminate\Http\Response
     */
    public function show(Periodo $periodo)
    {
        try {
            return $periodo;
        } catch(\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Periodo  $periodo
     * @return \Illuminate\Http\Response
     */
    public function edit(Periodo $periodo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Periodo  $periodo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Periodo $periodo)
    {
        try {
            $periodo->update($request->all());
            return $periodo;
        } catch(\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Periodo  $periodo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Periodo $periodo)
    {
        try {
            $deleted = $periodo->delete();
            return !!$deleted;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
