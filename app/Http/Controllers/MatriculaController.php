<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Models\Matricula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $matriculas = Matricula::query();
            extract(request()->all());
            if (isset($situacoes)) $matriculas = Filtra::resultado($matriculas, $situacoes, 'situacao_id')->with('situacao');
            if (isset($marcacoes)) $matriculas = Filtra::resultado($matriculas, $marcacoes, 'marcacao_id')->with('marcacao');
            if (isset($alunos)) $matriculas = Filtra::resultado($matriculas, $alunos, 'aluno_id')->with('aluno');
            if (isset($turmas)) $matriculas = Filtra::resultado($matriculas, $turmas, 'turma_id')->with('turma');
            if (isset($pacotes)) $matriculas = Filtra::resultado($matriculas, $pacotes, 'pacote_id')->with('pacote');
            
            return $matriculas->paginate(10);
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
