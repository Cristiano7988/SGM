<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Models\IdadeMinima;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IdadeMinimaController extends Controller
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

            return response($idades);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $idadeMinima = IdadeMinima::create($request->all());
            $mensagem = "Idade mínima de {$idadeMinima->idade} {$idadeMinima->medida_de_tempo->tipo} adicionada!";
            
            return web()
                ? redirect()->back()->with('success', $mensagem)
                : response($mensagem);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return web()
                ? redirect()->back()->with('failure', $mensagem)
                : response($mensagem);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\IdadeMinima  $idadeMinima
     * @return \Illuminate\Http\Response
     */
    public function show(IdadeMinima $idadeMinima):Response
    {
        try {
            return response($idadeMinima);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\IdadeMinima  $idadeMinima
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IdadeMinima $idadeMinima):Response
    {
        try {
            $idadeMinima->update($request->all());
            return response($idadeMinima);
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
    public function destroy(IdadeMinima $idadeMinima):Response
    {
        try {
            $idadeMinima->delete();
            return response("A idade Mínima de nº {$idadeMinima->id}, {$idadeMinima->idade} {$idadeMinima->medida_de_tempo->tipo},  foi deletada.");
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
