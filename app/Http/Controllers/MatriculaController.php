<?php

namespace App\Http\Controllers;

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
            $matriculas = DB::table('matriculas');
            extract(request()->all());
            if (isset($situacoes)) $matriculas = $matriculas->whereIn('situacao_id', explode(',', $situacoes));
            if (isset($marcacoes)) $matriculas = $matriculas->whereIn('marcacao_id', explode(',', $marcacoes));
            if (isset($alunos)) $matriculas = $matriculas->whereIn('aluno_id', explode(',', $alunos));
            if (isset($turmas)) $matriculas = $matriculas->whereIn('turma_id', explode(',', $turmas));
            if (isset($pacotes)) $matriculas = $matriculas->whereIn('pacote_id', explode(',', $pacotes));

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
