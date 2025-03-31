<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

            $turmas
                ->leftJoin('nucleos', 'turmas.nucleo_id', 'nucleos.id')
                ->leftJoin('dias', 'turmas.dia_id', 'dias.id')
                ->leftJoin('tipos_de_aula', 'turmas.tipo_de_aula_id', 'tipos_de_aula.id')
                ->select(['turmas.*'])->groupBy('turmas.id');

            if (isset($nucleos)) $turmas = Filtra::resultado($turmas, $nucleos, 'nucleo_id')->with('nucleo');
            if (isset($dias)) $turmas = Filtra::resultado($turmas, $dias, 'dia_id'); // Turma COM dia vem por padrão da model
            if (isset($tipos_de_aula)) $turmas = Filtra::resultado($turmas, $tipos_de_aula, 'tipo_de_aula_id'); // Turma COM tipo de aula vem por padrão da model
            
            if (isset($disponivel)) $turmas = $turmas->where('disponivel', '=', true);
            
            $pagination = Trata::resultado($turmas, 'turmas.nome'); // Ordenação por turma, dia ou tipo de aula.

            return isWeb()
                ? Inertia::render('turmas/index', [
                    'pagination' => $pagination,
                    'session' => viteSession()
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request):Response
    {
        try {
            DB::beginTransaction();
            $turma = Turma::create($request->except('imagem'));

            if ($request->imagem) {
                $path = $request->imagem->store('turmas');
                $turma->imagem = $path;
                $turma->save();
            }
            DB::commit();

            return response($turma);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Turma  $turma
     * @return \Illuminate\Http\Response
     */
    public function show(Turma $turma):Response
    {
        try {
            return response($turma);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Turma  $turma
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Turma $turma):Response
    {
        try {
            DB::beginTransaction();
            $turma->update($request->except('imagem'));

            if ($request->imagem) {
                Storage::delete($turma->imagem);
                $path = $request->imagem->store('turmas');
                $turma->imagem = $path;
                $turma->save();
            }
            DB::commit();

            return response($turma);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Turma  $turma
     * @return \Illuminate\Http\Response
     */
    public function destroy(Turma $turma):Response
    {
        try {
            DB::beginTransaction();
            Storage::delete($turma->imagem);
            $turma->delete();
            DB::commit();

            return response("A turma de nº {$turma->id}, {$turma->nome},  foi deletada.");;
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
