<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Models\Nucleo;
use App\Models\Pacote;
use App\Models\Turma;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class NucleoController extends Controller
{
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, $nucleoId = false)
    {
        return Validator::make($data, [
                'nome' => [
                    'string',
                    'required',
                    'min:3',
                    'max:30',
                    $nucleoId ? "unique:nucleos,nome,{$nucleoId}" : ''
                ],
                'descricao' => 'string|required|min:10|max:1500',
                'imagem' => ['required', function ($attribute, $value, $fail) {
                    $isUrl = filter_var($value, FILTER_VALIDATE_URL);
                    $isFile = is_file($value);

                    // Lista de extensões permitidas
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                    // Se for um arquivo, verificar a extensão
                    if ($isFile) {
                        $extension = $value->getClientOriginalExtension();
                        
                        if (!in_array(strtolower($extension), $allowedExtensions)) {
                            $fail($attribute . ' deve ser uma imagem válida (jpg, jpeg, png, gif, webp).');
                        }
                    }

                    // Se for uma URL, garantir que termina com uma extensão permitida
                    if ($isUrl) {
                        $path = parse_url($value, PHP_URL_PATH);
                        $extension = pathinfo($path, PATHINFO_EXTENSION);
                        if (!in_array(strtolower($extension), $allowedExtensions)) {
                            $fail($attribute . ' deve ser uma URL de imagem válida (jpg, jpeg, png, gif, webp).');
                        }
                    }

                    if (!$isUrl && !$isFile) $fail($attribute.' deve ser uma URL válida ou um arquivo válido.');
                }],
                'idade_minima' => 'required|numeric|min:1|max:720',
                'idade_maxima' => "required|numeric|min:{$data['idade_minima']}|max:720",
                'inicio_matricula' => "required|date|date_format:Y-m-d|before_or_equal:fim_matricula",
                'fim_matricula' => "required|date|date_format:Y-m-d|after_or_equal:inicio_matricula",
            ]);   
    } 

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
        return Inertia::render('nucleos/create');
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

            $data = $request->hasFile('imagem')
                ? $request->except('imagem')
                : $request->all();

            $nucleo = Nucleo::create($data);
            
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
            return isWeb()
                ? Inertia::render('nucleos/edit', [
                    'nucleo' => $nucleo
                ])
                : response($nucleo);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
    
            return isWeb()
                ? redirect()->route('nucleos.index')
                : response($mensagem);
        }
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
            $validator = $this->validator($request->all());
            if ($validator->fails()) {
                session(['error' => "Há alguma informação incorreta, revise o formulário. "]);
        
                return isWeb()
                    ? redirect()->back()->withErrors($validator)
                    : response($validator->errors(), 422);
            }

            DB::beginTransaction();
            $isAStorageFile = Str::contains($nucleo->imagem, 'storage');
            if ($nucleo->imagem && $isAStorageFile) {
                [$url, $storagePath] = explode('/storage/', $nucleo->imagem);
                $isInOurEnd = Storage::disk('public')->exists($storagePath);
                $isTheSameFile = $request->imagem == $nucleo->imagem;
                if ($isInOurEnd && !$isTheSameFile) Storage::disk('public')->delete($storagePath);
            }

            $data = $request->hasFile('imagem')
                ? $request->except('imagem')
                : $request->all();
            $nucleo->update($data);

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
