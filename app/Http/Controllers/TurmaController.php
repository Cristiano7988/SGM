<?php

namespace App\Http\Controllers;

use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TurmaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $disponivel = !!request()->disponivel;
            $nucleo_id = !!request()->nucleo_id;

            if (!$disponivel && !$nucleo_id) $turmas = Turma::paginate(10);
            else if (!$disponivel && $nucleo_id) $turmas = Turma::where('nucleo_id', '=', $nucleo_id)->paginate(10);
            else if ($disponivel && !$nucleo_id) $turmas = Turma::where('disponivel', '=', $disponivel)->paginate(10);
            else if ($disponivel && $nucleo_id) $turmas = Turma::where('nucleo_id', '=', $nucleo_id)->where('disponivel', '=', $disponivel)->paginate(10);

            return $turmas;
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
            $turma = Turma::create($request->except('imagem'));

            if ($request->imagem) {
                $path = $request->imagem->store('turmas');
                $turma->imagem = $path;
                $turma->save();
            }
            DB::commit();

            return $turma;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Turma  $turma
     * @return \Illuminate\Http\Response
     */
    public function show(Turma $turma)
    {
        try {
            return $turma;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Turma  $turma
     * @return \Illuminate\Http\Response
     */
    public function edit(Turma $turma)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Turma  $turma
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Turma $turma)
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
            return $turma;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Turma  $turma
     * @return \Illuminate\Http\Response
     */
    public function destroy(Turma $turma)
    {
        try {
            DB::beginTransaction();
            Storage::delete($turma->imagem);
            $deleted = $turma->delete();
            DB::commit();
            return !!$deleted;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
