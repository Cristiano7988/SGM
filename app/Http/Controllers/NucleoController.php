<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Models\MedidaDeTempo;
use App\Models\Nucleo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
    public function index():Response
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

            $nucleos = Trata::resultado($nucleos, 'nome'); // Ordenação apenas por núcleo.
            
            return response($nucleos);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
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
            $nucleo = Nucleo::create($request->except('imagem'));
            
            if ($request->imagem) {
                $path = $request->imagem->store('nucleos');
                $nucleo->imagem = $path;
                $nucleo->save();
            }
            DB::commit();

            return response($nucleo);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Exibe um núcleo em específico.
     * Se o id do aluno é passado na requisição então retorna somente se o núcleo estiver disponível para sua faixa etária

     *
     * @param  \App\Models\Nucleo  $nucleo
     * @return \Illuminate\Http\Response
     */
    public function show(Nucleo $nucleo):Response
    {
        try {
            return response($nucleo);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Nucleo  $nucleo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Nucleo $nucleo):Response
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
            return response($nucleo);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Nucleo  $nucleo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Nucleo $nucleo):Response
    {
        try {
            DB::beginTransaction();
            Storage::delete($nucleo->imagem);
            $nucleo->delete();
            DB::commit();

            return response("O núcleo de nº {$nucleo->id}, {$nucleo->nome},  foi deletado.");;
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
