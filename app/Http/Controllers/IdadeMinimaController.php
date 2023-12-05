<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
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
            extract(request()->all());
            $idades = IdadeMinima::query();

            $idades
                ->leftJoin('nucleos', 'idades_minimas.id', 'nucleos.idade_minima_id')
                ->select(['idades_minimas.*'])->groupBy('idades_minimas.id');

            if (isset($nucleos)) $idades = Filtra::resultado($idades, $nucleos, 'nucleos.id')->with('nucleos');
            // Medida de tempo vem por default da Model

            $order_by = $order_by ?? 'idade';
            $sort =  $sort ?? 'asc';
            $per_page = $per_page ?? 10;

            $idades = $idades->orderBy('medida_de_tempo_id', $sort)->orderBy($order_by, $sort)->paginate($per_page);

            return $idades;
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
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
            $mensagem = Trata::erro($th);
            return $mensagem;
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
        try {
            return $idadeMinima;
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
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
            $mensagem = Trata::erro($th);
            return $mensagem;
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
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
