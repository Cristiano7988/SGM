<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Models\Pacote;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PacoteController extends Controller
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
            $pacotes = Pacote::query();

            $pacotes
                ->leftJoin('periodos', 'pacotes.id', 'periodos.pacote_id')
                ->leftJoin('matriculas', 'pacotes.id', 'matriculas.pacote_id')
                ->leftJoin('nucleos', 'pacotes.nucleo_id', 'nucleos.id')
                ->select(['pacotes.*'])->groupBy('pacotes.id');

            if (isset($matriculas)) $pacotes = Filtra::resultado($pacotes, $matriculas, 'matriculas.id')->with('matriculas');
            if (isset($periodos)) $pacotes = Filtra::resultado($pacotes, $periodos, 'periodos.id')->with('periodos');
            if (isset($nucleos)) $pacotes = Filtra::resultado($pacotes, $nucleos, 'nucleos.id')->with('nucleo');
            if (isset($ativo)) $pacotes = $pacotes->where('ativo', true);

            $pacotes = Trata::resultado($pacotes, 'pacotes.nome'); // Ordenação por pacote ou por núcleo.

            return response($pacotes);
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
            $pacote = Pacote::create($request->all());
            return response($pacote);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pacote  $pacote
     * @return \Illuminate\Http\Response
     */
    public function show(Pacote $pacote):Response
    {
        try {
            return response($pacote);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pacote  $pacote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pacote $pacote):Response
    {
        try {
            $pacote->update($request->all());
            return response($pacote);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pacote  $pacote
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pacote $pacote):Response
    {
        try {
            $pacote->delete();
            return response("O pacote de nº {$pacote->id}, {$pacote->nome},  foi deletado.");
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
