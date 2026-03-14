<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Models\Nucleo;
use App\Models\Pacote;
use App\Models\Turma;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\Settings\NucleoRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class NucleoController extends Controller
{
    /**
     * Exibe os núcleos registrados.
     * Se o id do aluno é passado na requisição então retorna somente os núcleos disponíveis para essa faixa etária
     *
     */
    public function index()
    {
        try {
            extract(request()->all());
            $nucleos = Nucleo::query();

            $nucleos
                ->leftJoin('turmas', 'nucleos.id', 'turmas.nucleo_id')
                ->leftJoin('pacotes', 'nucleos.id', 'pacotes.nucleo_id')
                ->select(['nucleos.*'])->groupBy('nucleos.id');

            /**
             * Seleciona todos os núcleos dentro da faixa etária especificada
             * Tenham sido eles definidos em meses ou em anos
             */
            // if (isset($meses)) $nucleos->where('idade_minima', '<=', $meses)->where('idade_maxima', '>=', $meses);

            $now = Carbon::now();
            // if (isset($matricular)) $nucleos->where('fim_matricula', '>=', $now)->where('inicio_matricula', '<=', $now);
            
            if (isset($turmas)) $nucleos = Filtra::resultado($nucleos, $turmas, 'turmas.id')->with('turmas');
            if (isset($pacotes)) $nucleos = Filtra::resultado($nucleos, $pacotes, 'pacotes.id')->with('pacotes');

            $pagination = Trata::resultado($nucleos, 'nome'); // Ordenação apenas por núcleo.

            return isWeb()
                ? Inertia::render('nucleos/index', [
                    'pagination' => $pagination,
                    'turmas' => Turma::all(),
                    'pacotes' => Pacote::all(),
                ])
                : response($pagination);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);

            return isWeb()
                ? redirect()->route('dashboard')
                : response($mensagem);
        }
    }

     /**
     * Exibe formulário para a criação do núcleo.
     *
     */
    public function create()
    {
        $turmas = Turma::all();
        $pacotes = Pacote::all();

        return Inertia::render('nucleos/create',  [
            'turmas' => $turmas,
            'pacotes' => $pacotes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(NucleoRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->hasFile('imagem')
                ? $request->safe()->except('imagem')
                : $request->validated();

            $nucleo = Nucleo::create($data);
            Turma::whereIn('id', $request->turmas)->update(['nucleo_id' => $nucleo->id]);
            Pacote::whereIn('id', $request->pacotes)->update(['nucleo_id' => $nucleo->id]);

            if ($request->hasFile('imagem')) {
                $path = $request->imagem->store('nucleos', 'public');
                $nucleo->imagem = env('APP_URL') . "/storage/" . $path;
                $nucleo->save();
            }
            DB::commit();

            session(['success' => "Núcleo {$nucleo->nome} criado."]);

            return isWeb()
                ? redirect()->route('nucleos.index')
                : response($nucleo);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);

            return isWeb()
                ? redirect()->route('nucleos.index')
                : response($mensagem);
        }
    }

    /**
     * Exibe um núcleo em específico.
     * Se o id do aluno é passado na requisição então retorna somente se o núcleo estiver disponível para sua faixa etária

     *
     * @param  \App\Models\Nucleo  $nucleo
     */
    public function show(Nucleo $nucleo)
    {
        try {
            return isWeb()
                ? Inertia::render('nucleos/show', [
                    'nucleo' => $nucleo
                ])
                : response($nucleo);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return isWeb()
                ? redirect()->route('dashboard')
                : response($mensagem);
        }
    }

    /**
     * Exibe formulário para a atualização do núcleo.
     *
     * @param  \App\Models\Nucleo  $nucleo
     */
    public function edit(Nucleo $nucleo)
    {
        try {
            return Inertia::render('nucleos/edit', [
                'nucleo' => $nucleo->load(['turmas', 'pacotes']),
                'turmas' => Turma::all(),
                'pacotes' => Pacote::all()
            ]);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
    
            return redirect()->route('nucleos.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(NucleoRequest $request, Nucleo $nucleo)
    {
        try {
            DB::beginTransaction();
            $isAStorageFile = Str::contains($nucleo->imagem, 'storage');
            if ($nucleo->imagem && $isAStorageFile) {
                [$url, $storagePath] = explode('/storage/', $nucleo->imagem);
                $isInOurEnd = Storage::disk('public')->exists($storagePath);
                $isTheSameFile = $request->imagem == $nucleo->imagem;
                if ($isInOurEnd && !$isTheSameFile) Storage::disk('public')->delete($storagePath);
            }

            $data = $request->hasFile('imagem')
                ? $request->safe()->except('imagem')
                : $request->validated();

            $nucleo->update($data);
            Turma::whereIn('id', $request->turmas)->update(['nucleo_id' => $nucleo->id]);
            Pacote::whereIn('id', $request->pacotes)->update(['nucleo_id' => $nucleo->id]);

            if ($request->hasFile('imagem')) {
                $path = $request->imagem->store('nucleos', 'public');
                $nucleo->imagem = env('APP_URL') . "/storage/" . $path;
                $nucleo->save();
            }
            DB::commit();

            session(['success' => "Núcleo {$nucleo->nome} editado."]);

            return isWeb()
                ? redirect()->route('nucleos.index')
                : response($nucleo);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
        
            return isWeb()
                ? redirect()->route('nucleos.index')
                : response($mensagem);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Nucleo  $nucleo
     */
    public function destroy(Nucleo $nucleo)
    {
        try {
            DB::beginTransaction();
            Storage::delete($nucleo->imagem);
            $nucleo->delete();
            DB::commit();

            $mensagem = "O núcleo de nº {$nucleo->id}, {$nucleo->nome},  foi deletado.";
            session(['success' => $mensagem]);

            return isWeb()
                ? redirect()->route('nucleos.index')
                : response($mensagem);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);

            return isWeb()
                ? redirect()->route('nucleos.index')
                : response($mensagem);
        }
    }
}
