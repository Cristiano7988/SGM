<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Models\IdadeMaxima;
use App\Models\IdadeMinima;
use App\Models\MedidaDeTempo;
use App\Models\Nucleo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class NucleoController extends Controller
{
    /**
     * Valida os dados vindos da requisição de criação e atualização.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data, $nucleo = false)
    {
        return Validator::make($data, [
            'nome' => [
                $nucleo ? 'nullable' : 'required',
                $nucleo ? Rule::unique('nucleos')->ignore($nucleo->id) : 'unique:nucleos',
                'string',
                'min:2',
                'max:255'
            ],
            'imagem' => [
                'image',
                'mimes:jpeg,jpg,png,gif',
                'max:20000' // No máximo 20MB
            ],
            'descricao' => [
                'min:20',
                'string'
            ],
            'idade_minima_id' => [
                'required',
                'numeric',
                'min:1'
            ],
            'idade_maxima_id' => [
                'required',
                'numeric',
                'min:1'
            ],
            'inicio_rematricula' => [
                'required',
                'date',
                'date_format:Y-m-d',
                'before:' . $data['fim_rematricula']
            ],
            'fim_rematricula' => [
                'required',
                'date',
                'date_format:Y-m-d',
                'after:' . $data['inicio_rematricula']
            ]
        ]);
    }

    /**
     * Exibe os núcleos registrados.
     * Se o id do aluno é passado na requisição então retorna somente os núcleos disponíveis para essa faixa etária
     *
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
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

            $nucleos = Trata::resultado($nucleos, 'nome'); // Ordenação apenas por núcleo.

            return web()
                ? view('auth.nucleos.index', compact('nucleos'))
                : response($nucleos);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return web()
                ? redirect()->back()->with('failure', $mensagem)
                : response($mensagem, 500);
        }
    }

    /**
     *  Mostra o formulário para a criação de um núcleo
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $dados['medidasDeTempo'] = MedidaDeTempo::all();
            $dados['idadesMinimas'] = IdadeMinima::all();
            $dados['idadesMaximas'] = IdadeMaxima::all();
    
            return view('auth.nucleos.create', $dados);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return redirect()->back()->with('failure', $mensagem);
        }
    }

    /**
     * Salva um novo núcleo no BD.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Aqui validamos os dados
            $validator = $this->validator($request->all());
            if ($validator->fails()) return web()
                ? redirect()->back()->withErrors($validator)
                : response($validator->errors(), 422);
            
            $idadeMinima = IdadeMinima::find($request->idade_minima_id);
            $idadeMaxima = IdadeMaxima::find($request->idade_maxima_id);
            
            if ($idadeMinima->medida_de_tempo->tipo == "anos") $idadeMinima->idade *= 12;
            if ($idadeMaxima->medida_de_tempo->tipo == "anos") $idadeMaxima->idade *= 12;
            
            $mensagem = "A idade mínima deve ser menor que a idade Máxima.";

            if ($idadeMinima->idade > $idadeMaxima->idade) return web()
                ? redirect()->back()->with('failure', $mensagem)
                : response($mensagem, 500);

            // Aqui adicionamos o núcleo
            DB::beginTransaction();
            $nucleo = Nucleo::create($request->except('imagem'));
            
            if ($request->imagem) {
                $path = $request->imagem->store('nucleos');
                $nucleo->imagem = $path;
                $nucleo->save();
            }
            DB::commit();

            $mensagem = "O núcleo de nº {$nucleo->id}, {$nucleo->nome}, foi criado.";

            return web()
                ? redirect()->back()->with('success', $mensagem)
                : response($mensagem);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return web()
                ? redirect()->back()->with('failure', $mensagem)
                : response($mensagem, 500);
        }
    }

    /**
     * Exibe um núcleo em específico.
     * Se o id do aluno é passado na requisição então retorna somente se o núcleo estiver disponível para sua faixa etária
     *
     * @param  \App\Models\Nucleo  $nucleo
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function show(Nucleo $nucleo)
    {
        try {
            return web()
                ? view('auth.nucleos.show', compact('nucleo'))
                : response($nucleo);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return web()
                ? redirect()->back()->with('failure', $mensagem)
                : response($mensagem, 500);
        }
    }

    /**
     * Mostra o formulário para edição de um núcleo em específico
     *
     * @param  \App\Models\Nucleo  $nucleo
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function edit(Nucleo $nucleo)
    {
        try {
            $dados['nucleo'] = $nucleo;
            $dados['medidasDeTempo'] = MedidaDeTempo::all();
            $dados['idadesMinimas'] = IdadeMinima::all();
            $dados['idadesMaximas'] = IdadeMaxima::all();
    
            return view('auth.nucleos.edit', $dados);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return redirect()->back()->with('failure', $mensagem);
        }
    }

    /**
     * Atualiza um núcleo específico no BD.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Nucleo  $nucleo
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Nucleo $nucleo)
    {
        try {
            // Aqui validamos os dados
            $validator = $this->validator($request->all(), $nucleo);
            if ($validator->fails()) return web()
                ? redirect()->back()->withErrors($validator)
                : response($validator->errors(), 422);
            
            DB::beginTransaction();
            $nucleo->update($request->except('imagem'));

            if ($request->imagem) {
                Storage::delete($nucleo->imagem);
                $path = $request->imagem->store('nucleos');
                $nucleo->imagem = $path;
                $nucleo->save();
            }
            DB::commit();

            $mensagem = "O núcleo de nº {$nucleo->id}, {$nucleo->nome}, foi alterado.";

            return web()
                ? redirect()->route('nucleos.index')->with('success', $mensagem)
                : response($mensagem);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return web()
                ? redirect()->back()->with('failure', $mensagem)
                : response($mensagem, 500);
        }
    }

    /**
     * Deleta um núcleo específico do BD.
     *
     * @param  \App\Models\Nucleo  $nucleo
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Nucleo $nucleo)
    {
        try {
            DB::beginTransaction();
            Storage::delete($nucleo->imagem);
            $nucleo->delete();
            DB::commit();

            $mensagem = "O núcleo de nº {$nucleo->id}, {$nucleo->nome}, foi deletado.";

            return web()
                ? redirect()->route('listar-nucleos')->with('success', $mensagem)
                : response($mensagem);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return web()
                ? redirect()->back()->with('failure', $mensagem)
                : response($mensagem, 500);
        }
    }
}
