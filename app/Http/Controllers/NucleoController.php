<?php

namespace App\Http\Controllers;

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
            $nucleos = Nucleo::leftJoin('idade_minima', 'idade_minima_id', '=', 'idade_minima.id')
                ->leftJoin('idade_maxima', 'idade_maxima_id', '=', 'idade_maxima.id')
                ->leftJoin('medida_de_tempo as m_min', 'idade_minima.medida_de_tempo_id', '=', 'm_min.id')
                ->leftJoin('medida_de_tempo as m_max', 'idade_maxima.medida_de_tempo_id', '=', 'm_max.id')
                ->where(function ($query) {
                    $meses = request()->meses;
                    $anos = request()->anos;
                    $matricular = request()->matricular;
                    $now = Carbon::now();
                    /**
                     * Seleciona todos os núcleos dentro da faixa etária especificada
                     * Tenham sido eles definidos em meses ou em anos
                     */
                    if ($meses && $anos) {
                        $idMeses = 1;
                        $idAnos = 2;
                        $query->whereRaw("
                            ((medida_minima_id = {$idMeses} AND minima <= {$meses}) OR
                             (medida_minima_id = {$idAnos}  AND minima <= {$anos} ))
                            AND
                            ((medida_maxima_id = {$idMeses} AND maxima >= {$meses}) OR
                             (medida_maxima_id = {$idAnos}  AND maxima >= {$anos} ))
                        ");
                    }

                    if ($matricular) $query->where('fim_rematricula', '>=', $now)->where('inicio_rematricula', '<=', $now);
                })
                ->get([
                    'nucleos.id as id',
                    'idade_minima.id as idade_minima_id',
                    'idade_minima.idade as minima',
                    'idade_maxima.idade as maxima',
                    'idade_maxima.id as idade_maxima_id',
                    'm_min.id as medida_minima_id',
                    'm_max.id as medida_maxima_id',
                ]);

            $ids = $nucleos->pluck('id');
            $nucleos = Nucleo::whereIn('id', $ids);
            $nucleos = $nucleos->paginate(10);
            
            return $nucleos;
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
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

            return response($nucleo);
        } catch (\Throwable $th) {
            return response($th->getMessage(), 500);
        }
    }

    /**
     * Exibe um núcleo em específico.
     * Se o id do aluno é passado na requisição então retorna somente se o núcleo estiver disponível para sua faixa etária

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
            DB::beginTransaction();
            Storage::delete($nucleo->imagem);
            $deleted = $nucleo->delete();
            DB::commit();
            return !!$deleted;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
