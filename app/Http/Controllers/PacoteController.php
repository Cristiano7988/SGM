<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Http\Requests\Settings\PacoteRequest;
use App\Models\Turma;
use App\Models\Pacote;
use App\Models\Data;
use App\Models\Matricula;
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
                ->leftJoin('datas', 'pacotes.id', 'datas.pacote_id')
                ->leftJoin('matriculas', 'pacotes.id', 'matriculas.pacote_id')
                ->leftJoin('turmas', 'pacotes.turma_id', 'turmas.id')
                ->select(['pacotes.*'])->groupBy('pacotes.id');
            
            $pacotes->with([
                'turma',
                'datas',
            ]);

            if (isset($matriculas)) $pacotes = Filtra::resultado($pacotes, $matriculas, 'matriculas.id')->with('matriculas');
            if (isset($datas)) $pacotes = Filtra::resultado($pacotes, $datas, 'datas.id')->with('datas');
            if (isset($turmaId)) $pacotes = Filtra::resultado($pacotes, $turmaId, 'turmas.id')->with('turma');
            if (isset($ativo)) $pacotes = $pacotes->where('ativo', $ativo);

            $pagination = Trata::resultado($pacotes, 'pacotes.nome'); // Ordenação por pacote.

            return isWeb()
                ? Inertia::render('pacotes/index', [
                    'pagination' => $pagination,
                    'turmas' => Turma::all(),
                    'datas' => Data::all(),
                    'matriculas' => Matricula::all()
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
     */
    public function create()
    {
        return Inertia::render('pacotes/create', [
            'turmas' => Turma::all(),
            'datas' => Data::all()
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

            $pacote->datas()->createMany($request->datas);
            $mensagem = "Pacote {$pacote->nome} criado.";

            return isWeb()
                ? redirect()->route('pacotes.index')->with('success', $mensagem)
                : response($mensagem);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return isWeb()
                ? redirect()->route('pacotes.index')
                : response($mensagem);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pacote  $pacote
     */
    public function show(Pacote $pacote)
    {
        try {
            return isWeb()
                ? Inertia::render('pacotes/show', [
                    'pacote' => $pacote,
                    'turma' => $pacote->turma,
                    'datas' => $pacote->datas,
                    'matriculas' => $pacote->matriculas,
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pacote  $pacote
     */
    public function edit(Pacote $pacote)
    {
        try {
            return Inertia::render('pacotes/edit', [
                'pacote' => $pacote->load(['datas']),
                'turmas' => Turma::all(),
                'datas' => Data::all()
            ]);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);

            return redirect()->route('pacotes.index');
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
            $pacote->datas()->delete();
            $pacote->datas()->createMany($request->datas);

            $mensagem = "Pacote {$pacote->nome} editado.";

            return isWeb()
                ? redirect()->route('pacotes.index')->with('success', $mensagem)
                : response($mensagem);
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
     */
    public function destroy(Pacote $pacote)
    {
        try {
            DB::beginTransaction();
            $pacote->datas()->delete();
            $excluido = Trata::exclusao($pacote, 'Pacote');
            if ($excluido) DB::commit(); // Exclui somente se conseguir notificar o cliente

            $mensagem = "Pacote {$pacote->nome} deletado.";

            return isWeb()
                ? redirect()->route('pacotes.index')->with('success', $mensagem)
                : response($mensagem);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);

            return isWeb()
                ? redirect()->route('pacotes.index')
                : response($mensagem);
        }
    }
}
