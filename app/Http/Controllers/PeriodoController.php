<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Models\Periodo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PeriodoController extends Controller
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
            $periodos = Periodo::query();

            $periodos
                ->leftJoin('pacotes', 'pacotes.id', 'periodos.pacote_id')
                ->select(['periodos.*'])->groupBy('periodos.id');

            if (isset($pacotes)) $periodos = Filtra::resultado($periodos, $pacotes, 'pacotes.id')->with('pacote');

            $periodos = Trata::resultado($periodos, 'periodos.inicio'); // Ordenação por período ou pacote.

            return response($periodos);
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
            $periodo = Periodo::create($request->all());
            return response($periodo);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Periodo  $periodo
     * @return \Illuminate\Http\Response
     */
    public function show(Periodo $periodo):Response
    {
        try {
            return response($periodo);
        } catch(\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Periodo  $periodo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Periodo $periodo):Response
    {
        try {
            $periodo->update($request->all());
            return response($periodo);
        } catch(\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Periodo  $periodo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Periodo $periodo):Response
    {
        try {
            DB::beginTransaction();
            $excluido = Trata::exclusao($periodo, 'Período');
            if ($excluido) DB::commit(); // Exclui somente se conseguir notificar o cliente

            return response("O período de nº {$periodo->id}, de {$periodo->inicio} à {$periodo->fim},  foi deletado.");;
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
