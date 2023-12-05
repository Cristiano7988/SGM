<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Models\Pacote;
use Illuminate\Http\Request;

class PacoteController extends Controller
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

            return $pacotes;
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
            $pacote = Pacote::create($request->all());
            return $pacote;
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
    public function show(Pacote $pacote)
    {
        try {
            return $pacote;
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pacote  $pacote
     * @return \Illuminate\Http\Response
     */
    public function edit(Pacote $pacote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pacote  $pacote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pacote $pacote)
    {
        try {
            $pacote->update($request->all());
            return $pacote;
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
    public function destroy(Pacote $pacote)
    {
        try {
            $deleted = $pacote->delete();
            return $deleted;
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
