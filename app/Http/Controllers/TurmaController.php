<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
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
            
            $order_by = $order_by ?? 'turmas.nome'; // Ordenação por turma, dia e tipo de aula
            $sort = $sort ?? 'asc';
            $per_page = $per_page ?? 10;
            $turmas = $turmas->orderBy($order_by, $sort)->paginate($per_page);

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
