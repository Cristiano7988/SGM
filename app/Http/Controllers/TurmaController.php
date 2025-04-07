<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Models\Dia;
use App\Models\Nucleo;
use App\Models\TipoDeAula;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class TurmaController extends Controller
{
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
                'nome' => [
                    'string',
                    'required',
                    'min:3',
                    'max:30',
                ],
                'descricao' => 'string|min:10|max:1500',
                'imagem' => ['required', function($attribute, $value, $fail) {
                    validaImagem($attribute, $value, $fail);
                }],
                'vagas_fora_do_site' => ['numeric', "max:{$data['vagas_ofertadas']}"],
                'vagas_ofertadas' => ['numeric', "min:{$data['vagas_fora_do_site']}"],
                'horario' => ['required', 'regex:/^([01]\d|2[0-3]):([0-5]\d)$/'], // Valida HH:MM
                'disponivel' => 'boolean',
                'zoom' => 'url',
                'zoom_id' => 'string',
                'zoom_senha' => 'string|min:3',
                'whatsapp' => 'url',
                'spotify' => 'url',
                'nucleo_id' => 'required|numeric|min:1',
                'dia_id' => 'required|numeric|min:1',
                'tipo_de_aula_id' => 'required|numeric|min:1',
            ]);   
    }

    /**
     * Display a listing of the resource.
     *
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

            if (isset($nucleoId)) $turmas = Filtra::resultado($turmas, $nucleoId, 'nucleo_id')->with('nucleo');
            if (isset($diaId)) $turmas = Filtra::resultado($turmas, $diaId, 'dia_id'); // Turma COM dia vem por padrão da model
            if (isset($tipoDeAulaId)) $turmas = Filtra::resultado($turmas, $tipoDeAulaId, 'tipo_de_aula_id'); // Turma COM tipo de aula vem por padrão da model
            
            if (isset($disponivel)) $turmas = $turmas->where('disponivel', (int) $disponivel);
            
            $pagination = Trata::resultado($turmas, 'turmas.nome'); // Ordenação por turma, dia ou tipo de aula.

            return isWeb()
                ? Inertia::render('turmas/index', [
                    'pagination' => $pagination,
                    'nucleos' => Nucleo::all(),
                    'dias' => Dia::all(),
                    'tipos_de_aula' => TipoDeAula::all(),
                    'session' => viteSession()
                ])
                : response($turmas);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);

            return isWeb()
                ? redirect()->route('dashboard')
                : response($mensagem);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create(Turma $turma)
    {
        try {
            return isWeb()
                ? Inertia::render('turmas/create', [
                    'turma' => $turma,
                    'nucleos' => Nucleo::all(),
                    'dias' => Dia::all(),
                    'tipos_de_aula' => TipoDeAula::all()
                ])
                : response($turma);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);

            return isWeb()
                ? redirect()->route('turmas.index')
                : response($mensagem);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        try {
            $validator = $this->validator($request->all());
            if ($validator->fails()) {
                session(['error' => "Há alguma informação incorreta, revise o formulário. "]);
        
                return isWeb()
                    ? redirect()->back()->withErrors($validator)
                    : response($validator->errors(), 422);
            }

            DB::beginTransaction();
            $data = request()->hasFile('imagem')
                ? request()->except('imagem')
                : request()->all();

            $turma = Turma::create($data);

            salvaImagem($turma, 'turmas');
            DB::commit();

            session(['success' => "A turma de nº {$turma->id}, {$turma->nome}, foi criada."]);

            return isWeb()
                ? redirect()->route('turmas.index')
                : response($turma);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);

            return isWeb()
                ? redirect()->route('turmas.index')
                : response($mensagem);
            return $mensagem;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Turma  $turma
     */
    public function show(Turma $turma)
    {
        try {
            return isWeb()
                ? Inertia::render('turmas/show', [
                    'turma' => $turma->with(['nucleo', 'dia', 'tipo_de_aula'])->first(),
                ])
                : response($turma);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);

            return isWeb()
                ? redirect()->route('turmas.index')
                : response($mensagem);
        }
    }

     /**
     * Display the specified resource.
     *
     * @param  \App\Models\Turma  $turma
     */
    public function edit(Turma $turma)
    {
        try {
            return isWeb()
                ? Inertia::render('turmas/edit', [
                    'session' => viteSession(),
                    'turma' => $turma,
                    'nucleos' => Nucleo::all(),
                    'dias' => Dia::all(),
                    'tipos_de_aula' => TipoDeAula::all()
                ])
                : response($turma);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);

            return isWeb()
                ? redirect()->route('turmas.index')
                : response($mensagem);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Turma  $turma
     */
    public function update(Request $request, Turma $turma)
    {
        try {
            $validator = $this->validator($request->all());
            if ($validator->fails()) {
                session(['error' => "Há alguma informação incorreta, revise o formulário. "]);
        
                return isWeb()
                    ? redirect()->back()->withErrors($validator)
                    : response($validator->errors(), 422);
            }

            DB::beginTransaction();
            $data = request()->hasFile('imagem')
                ? request()->except('imagem')
                : request()->all();

            $turma->update($data);

            salvaImagem($turma, 'turmas');
            DB::commit();

            session(['success' => "A turma de nº {$turma->id}, {$turma->nome}, foi atualizada."]);

            return isWeb()
                ? redirect()->route('turmas.index')
                : response($turma);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);

            return isWeb()
                ? redirect()->route('turmas.index')
                : response($mensagem);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Turma  $turma
     */
    public function destroy(Turma $turma)
    {
        try {
            DB::beginTransaction();
            Storage::delete($turma->imagem);
            $turma->delete();
            DB::commit();

            $mensagem = "A turma de nº {$turma->id}, {$turma->nome}, foi excluída.";
            session(['success' => $mensagem]);

            return isWeb()
                ? redirect()->route('turmas.index')
                : response($mensagem);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);

            return isWeb()
                ? redirect()->route('turmas.index')
                : response($mensagem);
        }
    }
}
