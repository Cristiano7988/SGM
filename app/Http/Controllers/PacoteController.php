<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Http\Requests\Settings\PacoteRequest;
use App\Models\Nucleo;
use App\Models\Pacote;
use App\Models\Periodo;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PacoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
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
            
            $pacotes->with([
                'nucleo',
                'periodos',
            ]);

            // if (isset($matriculas)) $pacotes = Filtra::resultado($pacotes, $matriculas, 'matriculas.id')->with('matriculas');
            // if (isset($periodos)) $pacotes = Filtra::resultado($pacotes, $periodos, 'periodos.id')->with('periodos');
            if (isset($nucleoId)) $pacotes = Filtra::resultado($pacotes, $nucleoId, 'nucleos.id')->with('nucleo');
            if (isset($ativo)) $pacotes = $pacotes->where('ativo', $ativo);

            $pagination = Trata::resultado($pacotes, 'pacotes.nome'); // Ordenação por pacote ou por núcleo.

            return isWeb()
                ? Inertia::render('pacotes/index', [
                    'pagination' => $pagination,
                    'nucleos' => Nucleo::all(),
                    'session' => viteSession(),
                ])
                : response($pacotes);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);

            return isWeb()
                ? redirect()->route('dashboard')
                : response($mensagem);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('pacotes/create', [
            'session' => viteSession(),
            'nucleos' => Nucleo::all(),
            'periodos' => Periodo::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Settings\PacoteRequest  $request
     */
    public function store(PacoteRequest $request)
    {
        try {
            $pacote = Pacote::create($request->validated());
            session(['success' => "O Pacote de nº {$pacote->id}, {$pacote->nome}, foi criado."]);

            return isWeb()
                ? redirect()->route('pacotes.index')
                : response($pacote, 201);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return isWeb()
                ? redirect()->route('pacotes.index')
                : response($mensagem, 500);
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pacote  $pacote
     */
    public function edit(Pacote $pacote)
    {
        try {
            return isWeb()
                ? Inertia::render('pacotes/edit', [
                    'session' => viteSession(),
                    'pacote' => $pacote,
                    'nucleos' => Nucleo::all(),
                ])
                : response($pacote);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);

            return isWeb()
                ? redirect()->route('pacotes.index')
                : response($mensagem);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Settings\PacoteRequest;  $request
     * @param  \App\Models\Pacote  $pacote
     */
    public function update(PacoteRequest $request, Pacote $pacote)
    {
        try {
            $pacote->update($request->validated());

            session(['success' => 'Pacote atualizado com sucesso!']);
            return isWeb()
                ? redirect()->route('pacotes.index')
                : response('');
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);

            return isWeb()
                ? redirect()->route('pacotes.index')
                : response($mensagem);
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
            DB::beginTransaction();
            $excluido = Trata::exclusao($pacote, 'Pacote');
            if ($excluido) DB::commit(); // Exclui somente se conseguir notificar o cliente

            return response("O pacote de nº {$pacote->id}, {$pacote->nome},  foi deletado.");
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
