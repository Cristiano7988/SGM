<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
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
            extract(request()->all());
            $idades = IdadeMaxima::query();

            $idades
                ->leftJoin('nucleos', 'idades_maximas.id', 'nucleos.idade_maxima_id')
                ->select(['idades_maximas.*'])->groupBy('idades_maximas.id');

            if (isset($nucleos)) $idades = Filtra::resultado($idades, $nucleos, 'nucleos.id')->with('nucleos');
            // Medida de tempo vem por default da Model

            $order_by = $order_by ?? 'idade';
            $sort =  $sort ?? 'asc';
            $per_page = $per_page ?? 10;

            $idades = $idades->orderBy('medida_de_tempo_id', $sort)->orderBy($order_by, $sort)->paginate($per_page);

            return $idades;
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
