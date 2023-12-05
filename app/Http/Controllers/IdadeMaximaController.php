<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Models\IdadeMaxima;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IdadeMaximaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index():Response
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

            return response($idades);
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
    public function store(Request $request):Response
    {
        try {
            $idadeMaxima = IdadeMaxima::create($request->all());
            return response($idadeMaxima);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\IdadeMaxima  $idadeMaxima
     * @return \Illuminate\Http\Response
     */
    public function show(IdadeMaxima $idadeMaxima):Response
    {
        try {
            return response($idadeMaxima);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
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
    public function update(Request $request, IdadeMaxima $idadeMaxima):Response
    {
        try {
            $idadeMaxima->update($request->all());
            return response($idadeMaxima);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\IdadeMaxima  $idadeMaxima
     * @return \Illuminate\Http\Response
     */
    public function destroy(IdadeMaxima $idadeMaxima):Response
    {
        try {
            $idadeMaxima->delete();
            return response("A idade máxima de nº {$idadeMaxima->id}, {$idadeMaxima->idade} {$idadeMaxima->medida_de_tempo->tipo},  foi deletada.");
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
