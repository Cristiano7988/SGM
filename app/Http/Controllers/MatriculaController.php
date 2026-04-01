<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Models\Aluno;
use App\Models\Turma;
use App\Models\Situacao;
use App\Models\Marcacao;
use App\Models\Pacote;
use App\Models\Matricula;
use App\Models\User;
use App\Http\Requests\Settings\MatriculaRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MatriculaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        try {
            extract(request()->all());
            $matriculas = Matricula::query();

            $matriculas->with([
                'aluno',
                'situacao',
                'marcacao',
                'pacote.turma',
                'users'
            ]);

            $matriculas
                ->leftJoin('situacoes', 'matriculas.situacao_id', 'situacoes.id')
                ->leftJoin('marcacoes', 'matriculas.marcacao_id', 'marcacoes.id')
                ->leftJoin('alunos', 'matriculas.aluno_id', 'alunos.id')
                ->leftJoin('pacotes', 'matriculas.pacote_id', 'pacotes.id')
                ->select(['matriculas.*', 'alunos.nome'])->groupBy('matriculas.id');

            $user = Auth::user();
            $alunosDoUsuario = $user->alunos->pluck('id');
            if ($user && !$user->is_admin) $matriculas->whereIn('aluno_id', $alunosDoUsuario);

            if (isset($situacoes)) $matriculas = Filtra::resultado($matriculas, $situacoes, 'situacoes.id')->with('situacao');
            if (isset($marcacoes)) $matriculas = Filtra::resultado($matriculas, $marcacoes, 'marcacoes.id')->with('marcacao');
            if (isset($alunos)) $matriculas = Filtra::resultado($matriculas, $alunos, 'alunos.id')->with('aluno');
            if (isset($pacotes)) $matriculas = Filtra::resultado($matriculas, $pacotes, 'pacotes.id')->with('pacote');

            $pagination = Trata::resultado($matriculas, 'alunos.nome'); // Ordenação por situação, marcação, aluno, turma ou pacote.

            $alunos = $user && $user->is_admin ? Aluno::all() : $user->alunos;
            $situacoes = $user && $user->is_admin ? Situacao::all() : $user->alunos->flatMap->matriculas->flatMap->situacao->unique('id');
            $pacotes = $user && $user->is_admin ? Pacote::all() : $user->alunos->flatMap->matriculas->flatMap->pacote->unique('id');

            return isWeb()
                ? Inertia::render('matriculas/index', [
                    'pagination' => $pagination,
                    'alunos' => $alunos,
                    'situacoes' => $situacoes,
                    'pacotes' => $pacotes,
                ])
                : response($matriculas);
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
    public function create()
    {
        $user = Auth::user();
        $isAdmin = $user && $user->is_admin; 

        $alunos = $isAdmin
            ? Aluno::with(['matriculas.pacote'])->orderBy('nome')->get()
            : $user->alunos;

        $pacotes = $isAdmin
            ? Pacote::disponiveis()
            : Pacote::where('ativo', true)->get()->load(['turma']);

        $situacoes = Situacao::all();

        $marcacoes = Marcacao::all();

        $users = $isAdmin
            ? User::all()
            : $user->alunos->flatmap->users->unique("id");

        return Inertia::render('matriculas/create', [
            'alunos' => $alunos,
            'pacotes' => $pacotes,
            'situacoes' => $situacoes,
            'marcacoes' => $marcacoes,
            'users' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(MatriculaRequest $request)
    {
        try {
            $user = Auth::user();
            
            $aluno = Aluno::find($request->aluno_id);
            $isAdmin = $user && $user->is_admin;
            
            // Validando se o aluno a ser matrículado tem relação com o usuário logado
            if (!$isAdmin && !$aluno->users->find($user->id)) {
                $mensagem = "Você não tem permissão para matricular esse aluno";

                return isWeb()
                    ? redirect()->back()->with('error', $mensagem)
                    : response($mensagem, 403);
            }

            $matricula = Matricula::create($request->validated());
            if ($request->users) $matricula->users()->sync($request->users);

            $mensagem = "Matrícula do {$matricula->aluno->nome} na turma {$matricula->pacote->turma->nome} criada.";

            return isWeb()
                ? redirect()->route("matriculas.index")->with("success", $mensagem)
                : response($mensagem);
        } catch(\Throwable $th) {
            $mensagem = Trata::erro($th);
            return isWeb()
                ? redirect()->route("matriculas.index")
                : response($mensagem);
        }
    }

    /**
     * Display the specified resource.
     *
     */
    public function show(Matricula $matricula)
    {
        try {
            return response($matricula);
        } catch(\Throwable $th) {
            $mensagem = Trata::erro($th);
            return response($mensagem);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit(Matricula $matricula)
    {
        try {
            $user = Auth::user();
            $isAdmin = $user && $user->is_admin; 

            $alunos = $isAdmin
                ? Aluno::with(['matriculas.pacote'])->get()
                : $user->alunos;

            $pacotes = $isAdmin
                ? Pacote::disponiveis()
                : Pacote::where('ativo', true)->get()->load(['turma']);

            $situacoes = Situacao::all();

            $marcacoes = Marcacao::all();

            $users = $isAdmin
                ? User::allWithHisPivot($matricula->aluno_id)
                : $matricula->aluno->users;

            return Inertia::render('matriculas/edit', [
                'matricula' => $matricula->load(['users']),
                'alunos' => $alunos,
                'pacotes' => $pacotes,
                'situacoes' => $situacoes,
                'marcacoes' => $marcacoes,
                'users' => $users
            ]);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return redirect()->route('matriculas.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(MatriculaRequest $request, Matricula $matricula)
    {
        try {
            $matricula->update($request->validated());
            $matricula->pacote->turma->vagas_preenchidas = $matricula->pacote->turma->matriculas()->count();
            $matricula->pacote->turma->save();
            $matricula->users()->sync($request->users);

            $mensagem = "Matrícula do {$matricula->aluno->nome} na turma {$matricula->pacote->turma->nome} editada.";
            
            return isWeb()
                ? redirect()->route("matriculas.index")->with("success", $mensagem)
                : response($mensagem);
            } catch(\Throwable $th) {
                $mensagem = Trata::erro($th);

                return isWeb()
                    ? redirect()->route("matriculas.index")
                    : response($mensagem);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy(Matricula $matricula)
    {
        try {
            DB::beginTransaction();
            $excluido = Trata::exclusao($matricula, 'Matrícula');
            if ($excluido) DB::commit(); // Exclui somente se conseguir notificar o cliente

            $mensagem = "A matrícula de nº {$matricula->id}, de {$matricula->aluno->nome}, foi deletada.";
            
            return isWeb()
                ? redirect()->route("matriculas.index")->with(['success' => $mensagem])
                : response($mensagem);
        } catch(\Throwable $th) {
            $mensagem = Trata::erro($th);
            return isWeb()
                ? redirect()->route("matriculas.index")
                : response($mensagem);
        }
    }
}
