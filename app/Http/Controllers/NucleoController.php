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
            $meses = request()->meses;
            $matricular = !!request()->matricular;
            $now = Carbon::now();
            
            if (!$matricular && !$meses)
                $nucleos = Nucleo::paginate(10);
            if (!$matricular && $meses)
                $nucleos = Nucleo::where('idade_minima', '<', $meses)->where('idade_maxima', '>', $meses)->paginate(10);
            if ($matricular && !$meses)
                $nucleos = Nucleo::where('fim_rematricula', '>=', $now)->where('inicio_rematricula', '<=', $now)->paginate(10);
            if ($matricular && $meses)
                $nucleos = Nucleo::where('idade_minima', '<', $meses)->where('idade_maxima', '>', $meses)->where('fim_rematricula', '>=', $now)->where('inicio_rematricula', '<=', $now)->paginate(10);

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
     * Exibe um núcleo em específico.
     * Se o id do aluno é passado na requisição então retorna somente se o núcleo estiver disponível para sua faixa etária

     *
     * @param  \App\Models\Nucleo  $nucleo
     * @return \Illuminate\Http\Response
     */
    public function show(Nucleo $nucleo)
    {
        try {
            $meses = request()->meses;
            $matricular = request()->matricular;
            $now = Carbon::now();

            $escopoDaIdade = $nucleo->idade_minima < $meses && $nucleo->idade_maxima > $meses;
            $noPeriodoDeRematricula = $nucleo->inicio_rematricula <= $now && $nucleo->fim_rematricula >= $now;

            if ($meses && !$escopoDaIdade) return response("Esse núcleo está indisponível para esta faixa etária", 403);
            if ($matricular && !$noPeriodoDeRematricula) return response("Este núcleo não está disponível para matrículas ou rematrículas no momento.", 403);

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
