<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use Illuminate\Http\Request;

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

            if (!isset($situacoes) && !isset($marcacoes)) $matriculas = Matricula::paginate(10);

            if (!isset($situacoes) && isset($marcacoes)) {
                $matriculas = Matricula::whereHas('marcacao', function ($query) {
                    $marcacoes = explode(',', request()->marcacoes);
                    $query->whereIn('marcacao_id', $marcacoes);
                })->paginate(10);
            }

            if (isset($situacoes) && !isset($marcacoes)) {
                $matriculas = Matricula::whereHas('situacao', function ($query) {
                    $situacoes = explode(',', request()->situacoes);
                    $query->whereIn('situacao_id', $situacoes);
                })->paginate(10);
            }

            if (isset($situacoes) && isset($marcacoes)) {
                $matriculas = Matricula::whereHas('situacao', function ($query) {
                    $situacoes = explode(',', request()->situacoes);
                    $query->whereIn('situacao_id', $situacoes);
                })->whereHas('marcacao', function($query) {
                    $marcacoes = explode(',', request()->marcacoes);
                    $query->whereIn('marcacao_id', $marcacoes);
                })->paginate(10);
            }

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
