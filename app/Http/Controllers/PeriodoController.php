<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Http\Requests\Settings\PeriodoRequest;
use App\Models\Pacote;
use App\Models\Periodo;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

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
                ->with('pacote') // Carrega o pacote relacionado ao período
                ->leftJoin('pacotes', 'pacotes.id', 'periodos.pacote_id')
                ->select(['periodos.*'])->groupBy('periodos.id');

            if (isset($pacoteId)) $periodos = Filtra::resultado($periodos, $pacoteId, 'pacotes.id')->with('pacote');

            $pagination = Trata::resultado($periodos, 'periodos.inicio'); // Ordenação por período ou pacote.

            return isWeb()
                ? Inertia::render('periodos/index', [
                    'pagination' => $pagination,
                    'pacotes' => \App\Models\Pacote::all(),
                ])
                : response($periodos);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);

            return isWeb()
                ? redirect()->back()->with('error', $mensagem)
                : response($mensagem, 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return Inertia::render('periodos/create', [
                'pacotes' => Pacote::all(),
            ]);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);

            return isWeb()
                ? redirect()->route('periodos.index')->with('error', $mensagem)
                : response($mensagem, 500);
        }
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(PeriodoRequest $request)
    {
        try {
            $periodo = Periodo::create($request->validated());

            return isWeb()
                ? redirect()->route('periodos.index')->with('success', "O período de nº {$periodo->id}, de {$periodo->inicio} à {$periodo->fim}, foi criado com sucesso.")
                : response($periodo);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return isWeb()
                ? redirect()->route('periodos.index')->with('error', $mensagem)
                : response($mensagem);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Periodo  $periodo
     */
    public function show(Periodo $periodo)
    {
        try {
            $periodo->load('pacote');

            return isWeb()
                ? Inertia::render('periodos/show', [
                    'periodo' => $periodo,
                    'pacotes' => Pacote::all(),
                ])
                : response($periodo);
        } catch(\Throwable $th) {
            $mensagem = Trata::erro($th);
            return isWeb()
                ? redirect()->route('periodos.index')->with('error', $mensagem)
                : response($mensagem, 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Periodo  $periodo
     */
    public function edit(Periodo $periodo)
    {
        try {
            return isWeb()
                ? Inertia::render('periodos/edit', [
                    'periodo' => $periodo,
                    'pacotes' => \App\Models\Pacote::all(),
                ])
                : response($periodo);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);

            return isWeb()
                ? redirect()->route('periodos.index')->with('error', $mensagem)
                : response($mensagem, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Periodo  $periodo
     */
    public function update(PeriodoRequest $request, Periodo $periodo)
    {
        try {
            $periodo->update($request->validated());

            return isWeb()
                ? redirect()->route('periodos.index')->with('success', "O período de nº {$periodo->id}, de {$periodo->inicio} à {$periodo->fim}, foi atualizado com sucesso.")
                : response($periodo);
        } catch(\Throwable $th) {
            $mensagem = Trata::erro($th);

            return isWeb()
                ? redirect()->route('periodos.index')->with('error', $mensagem)
                : response($mensagem, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Periodo  $periodo
     */
    public function destroy(Periodo $periodo)
    {
        try {
            DB::beginTransaction();
            $excluido = Trata::exclusao($periodo, 'Período');
            if ($excluido) DB::commit(); // Exclui somente se conseguir notificar o cliente

            return isWeb()
                ? redirect()->route('periodos.index')->with('success', "O período de nº {$periodo->id}, de {$periodo->inicio} à {$periodo->fim}, foi deletado.")
                : response("O período de nº {$periodo->id}, de {$periodo->inicio} à {$periodo->fim},  foi deletado.");;
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);

            return isWeb()
                ? redirect()->route('periodos.index')->with('error', $mensagem)
                : response($mensagem, 500);
        }
    }
}
