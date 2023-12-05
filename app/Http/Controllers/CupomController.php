<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Models\Cupom;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CupomController extends Controller
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
            $cupons = Cupom::query();

            $cupons
                ->leftJoin('transacoes', 'transacoes.cupom_id', 'cupons.id')
                ->select(['cupons.*'])->groupBy('cupons.id');

            if (isset($medidas)) $cupons = Filtra::resultado($cupons, $medidas, 'medida_id'); // Cupom COM medida vem por padrão da model
            if (isset($transacoes)) $cupons = Filtra::resultado($cupons, $transacoes, 'transacoes.id')->with('transacoes');

            $cupons = Trata::resultado($cupons, 'desconto'); // Ordenação apenas por cupom.

            return response($cupons);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
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
            $cupom = Cupom::create($request->all());
            return response($cupom);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cupom  $cupom
     * @return \Illuminate\Http\Response
     */
    public function show(Cupom $cupom):Response
    {
        try {
            $codigo = request()->codigo;

            if ($codigo) $cupom = Cupom::where('codigo', '=', $codigo)->first();
            
            if (!$cupom) return response("Cupom não encontrado", 404);
            else return response($cupom);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cupom  $cupom
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cupom $cupom):Response
    {
        try {
            $cupom->update($request->all());
            return response($cupom);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cupom  $cupom
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cupom $cupom):Response
    {
        try {
            $cupom->delete();
            return response("O cupom de nº {$cupom->id}, {$cupom->codigo},  foi deletado.");
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
