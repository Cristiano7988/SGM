<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Models\Nucleo;
use App\Models\Turma;
use App\Models\Dia;
use App\Http\Requests\Settings\TurmaRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TurmaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        try {
            extract(request()->all());
            $turmas = Turma::query();
            $turmas->with(['aulas']);

            $turmas
                ->leftJoin('nucleos', 'turmas.nucleo_id', 'nucleos.id')
                ->select(['turmas.*'])->groupBy('turmas.id');

            if (isset($nucleoId)) $turmas = Filtra::resultado($turmas, $nucleoId, 'nucleo_id')->with('nucleo');
            
            if (isset($disponivel)) $turmas = $turmas->where('disponivel', (int) $disponivel);
            
            $pagination = Trata::resultado($turmas, 'turmas.nome'); // Ordenação por turma.

            return isWeb()
                ? Inertia::render('turmas/index', [
                    'pagination' => $pagination,
                    'nucleos' => Nucleo::all(),
                ])
                : response($turmas);
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
    public function create(Turma $turma)
    {
        try {
            return Inertia::render('turmas/create', [
                'turma' => $turma,
                'nucleos' => Nucleo::all(),
                'dias' => Dia::all()
            ]);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);

            return redirect()->route('turmas.index');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(TurmaRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = request()->hasFile('imagem')
                ? $request->safe()->except('imagem')
                : $request->validated();

            $turma = Turma::create($data);
            $turma->aulas()->createMany($request->aulas);

            salvaImagem($turma, 'turmas');
            DB::commit();

            $mensagem = "Turma {$turma->nome} criada.";

            return isWeb()
                ? redirect()->route('turmas.index')->with('success', $mensagem)
                : response($mensagem);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);

            return isWeb()
                ? redirect()->route('turmas.index')
                : response($mensagem);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Turma  $turma
     */
    public function show(Turma $turma)
    {
        try {
            return isWeb()
                ? Inertia::render('turmas/show', [
                    'turma' => $turma->with(['nucleo'])->first(),
                ])
                : response($turma);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);

            return isWeb()
                ? redirect()->route('turmas.index')
                : response($mensagem);
        }
    }

     /**
     * Display the specified resource.
     *
     * @param  \App\Models\Turma  $turma
     */
    public function edit(Turma $turma)
    {
        try {
            return Inertia::render('turmas/edit', [
                'turma' => $turma->load(['aulas']),
                'nucleos' => Nucleo::all(),
                'dias' => Dia::all()
            ]);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);

            return redirect()->route('turmas.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(TurmaRequest $request, Turma $turma)
    {
        try {
            DB::beginTransaction();
            $data = $request->hasFile('imagem')
                ? $request->safe()->except('imagem')
                : $request->validated();

            $turma->update($data);
            $turma->aulas()->createMany($request->aulas);

            salvaImagem($turma, 'turmas');
            DB::commit();

            $mensagem = "Turma {$turma->nome} editada.";

            return isWeb()
                ? redirect()->route('turmas.index')->with('success', $mensagem)
                : response($mensagem);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);

            return isWeb()
                ? redirect()->route('turmas.index')
                : response($mensagem);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Turma  $turma
     */
    public function destroy(Turma $turma)
    {
        try {
            DB::beginTransaction();
            Storage::delete($turma->imagem);
            $turma->delete();
            DB::commit();

            $mensagem = "Turma {$turma->nome} deletada.";

            return isWeb()
                ? redirect()->route('turmas.index')->with('success', $mensagem)
                : response($mensagem);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);

            return isWeb()
                ? redirect()->route('turmas.index')
                : response($mensagem);
        }
    }
}
