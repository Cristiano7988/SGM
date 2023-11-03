<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Nucleo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class NucleoController extends Controller
{
    /**
     * Exibe os núcleos registrados.
     * Se o id do aluno é passado na requisição então retorna somente os núcleos disponíveis para essa faixa etária
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $id = request()->aluno_id;

            if ($id) {
                $aluno = Aluno::find($id);
                $now = Carbon::now();

                if (!$aluno) return response("Aluno não encontrado", 403);

                $data_de_nascimento = Carbon::create($aluno->data_de_nascimento);
                $meses = $data_de_nascimento->diffInMonths($now);

                $nucleos = Nucleo::where('idade_minima', '<', $meses)->where('idade_maxima', '>', $meses)->paginate(10);
            } else {
                $nucleos = Nucleo::paginate(10);
            }

            return $nucleos;
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
            DB::beginTransaction();
            $nucleo = Nucleo::create($request->except('imagem'));
            
            if ($request->imagem) {
                $path = $request->imagem->store('nucleos');
                $nucleo->imagem = $path;
                $nucleo->save();
            }
            DB::commit();

            return $nucleo;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Nucleo  $nucleo
     * @return \Illuminate\Http\Response
     */
    public function show(Nucleo $nucleo)
    {
        try {
            return $nucleo;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Nucleo  $nucleo
     * @return \Illuminate\Http\Response
     */
    public function edit(Nucleo $nucleo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Nucleo  $nucleo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Nucleo $nucleo)
    {
        try {
            DB::beginTransaction();
            $nucleo->update($request->except('imagem'));

            if ($request->imagem) {
                Storage::delete($nucleo->imagem);
                $path = $request->imagem->store('nucleos');
                $nucleo->imagem = $path;
                $nucleo->save();
            }
            DB::commit();
            return $nucleo;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Nucleo  $nucleo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Nucleo $nucleo)
    {
        try {
            Storage::delete($nucleo->imagem);
            $deleted = $nucleo->delete();
            return !!$deleted;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
