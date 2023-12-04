<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Models\MedidaDeTempo;
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
            extract(request()->all());
            $nucleos = Nucleo::query();
            $medidas = MedidaDeTempo::all();

            $nucleos
                ->leftJoin('idades_minimas', 'idade_minima_id', 'idades_minimas.id')
                ->leftJoin('idades_maximas', 'idade_maxima_id', 'idades_maximas.id')
                ->leftJoin('medidas_de_tempo as m_min', 'idades_minimas.medida_de_tempo_id', 'm_min.id')
                ->leftJoin('medidas_de_tempo as m_max', 'idades_maximas.medida_de_tempo_id', 'm_max.id')
                ->leftJoin('turmas', 'nucleos.id', 'turmas.nucleo_id')
                ->leftJoin('pacotes', 'nucleos.id', 'pacotes.nucleo_id')
                ->select(['nucleos.*'])->groupBy('nucleos.id');

            /**
             * Seleciona todos os núcleos dentro da faixa etária especificada
             * Tenham sido eles definidos em meses ou em anos
             */
            if (isset($meses) && isset($anos)) {
                $nucleos->where(function ($query) use ($medidas, $meses, $anos) {
                    $query->where('idades_minimas.medida_de_tempo_id', $medidas->first()->id)->where('idades_minimas.idade', '<=', $meses);
                    $query->orWhere('idades_minimas.medida_de_tempo_id', $medidas->last()->id)->where('idades_minimas.idade', '<=', $anos);
                });

                $nucleos->where(function ($query) use ($medidas, $meses, $anos) {
                    $query->where('idades_maximas.medida_de_tempo_id', $medidas->first()->id)->where('idades_maximas.idade', '>=', $meses);
                    $query->orWhere('idades_maximas.medida_de_tempo_id', $medidas->last()->id)->where('idades_maximas.idade', '>=', $anos);
                 });
            }

            $now = Carbon::now();
            if (isset($matricular)) $nucleos->where('fim_rematricula', '>=', $now)->where('inicio_rematricula', '<=', $now);
            
            if (isset($turmas)) $nucleos = Filtra::resultado($nucleos, $turmas, 'turmas.id')->with('turmas');
            if (isset($pacotes)) $nucleos = Filtra::resultado($nucleos, $pacotes, 'pacotes.id')->with('pacotes');

            $order_by = $order_by ?? 'nome'; // Apenas por núcleo
            $sort = $sort ?? 'asc';
            $per_page = $per_page ?? 10;

            $nucleos = $nucleos->orderBy($order_by, $sort)->paginate($per_page);
            
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
