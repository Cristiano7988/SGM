<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Models\Matricula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MatriculaController extends Controller
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
            $matriculas = Matricula::query();

            $matriculas
                ->leftJoin('situacoes', 'matriculas.situacao_id', 'situacoes.id')
                ->leftJoin('marcacoes', 'matriculas.marcacao_id', 'marcacoes.id')
                ->leftJoin('alunos', 'matriculas.aluno_id', 'alunos.id')
                ->leftJoin('turmas', 'matriculas.turma_id', 'turmas.id')
                ->leftJoin('pacotes', 'matriculas.pacote_id', 'pacotes.id')
                ->select(['matriculas.*'])->groupBy('matriculas.id');

            $user = Auth::user();
            $alunosDoUsuario = $user->alunos->pluck('id');
            if (!$user->is_admin) $matriculas->whereIn('aluno_id', $alunosDoUsuario);

            if (isset($situacoes)) $matriculas = Filtra::resultado($matriculas, $situacoes, 'situacoes.id')->with('situacao');
            if (isset($marcacoes)) $matriculas = Filtra::resultado($matriculas, $marcacoes, 'marcacoes.id')->with('marcacao');
            if (isset($alunos)) $matriculas = Filtra::resultado($matriculas, $alunos, 'alunos.id')->with('aluno');
            if (isset($turmas)) $matriculas = Filtra::resultado($matriculas, $turmas, 'turmas.id')->with('turma');
            if (isset($pacotes)) $matriculas = Filtra::resultado($matriculas, $pacotes, 'pacotes.id')->with('pacote');

            $matriculas = Trata::resultado($matriculas, 'alunos.nome'); // Ordenação por situação, marcação, aluno, turma ou pacote.

            return $matriculas;
        } catch (\Throwable $th) {
            return $th->getMessage();
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
            $matricula = Matricula::create($request->all());
            return $matricula;
        } catch(\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Matricula  $matricula
     * @return \Illuminate\Http\Response
     */
    public function show(Matricula $matricula)
    {
        try {
            return $matricula;
        } catch(\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Matricula  $matricula
     * @return \Illuminate\Http\Response
     */
    public function edit(Matricula $matricula)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Matricula  $matricula
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Matricula $matricula)
    {
        try {
            $matricula->update($request->all());
            $matricula->turma->vagas_preenchidas = $matricula->turma->matriculas()->count();
            $matricula->turma->save();

            return $matricula;
        } catch(\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Matricula  $matricula
     * @return \Illuminate\Http\Response
     */
    public function destroy(Matricula $matricula)
    {
        try {
            $deleted = $matricula->delete();
            return $deleted;
        } catch(\Throwable $th) {
            return $th->getMessage();
        }
    }
}
