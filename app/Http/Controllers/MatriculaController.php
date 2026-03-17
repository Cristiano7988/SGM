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
use Illuminate\Http\Response;
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
                'turma',
                'situacao',
                'marcacao',
                'pacote',
            ]);

            $matriculas
                ->leftJoin('situacoes', 'matriculas.situacao_id', 'situacoes.id')
                ->leftJoin('marcacoes', 'matriculas.marcacao_id', 'marcacoes.id')
                ->leftJoin('alunos', 'matriculas.aluno_id', 'alunos.id')
                ->leftJoin('turmas', 'matriculas.turma_id', 'turmas.id')
                ->leftJoin('pacotes', 'matriculas.pacote_id', 'pacotes.id')
                ->select(['matriculas.*'])->groupBy('matriculas.id');

            $user = Auth::user();
            $alunosDoUsuario = $user->alunos->pluck('id');
            if ($user && !$user->is_admin) $matriculas->whereIn('aluno_id', $alunosDoUsuario);

            if (isset($situacoes)) $matriculas = Filtra::resultado($matriculas, $situacoes, 'situacoes.id')->with('situacao');
            if (isset($marcacoes)) $matriculas = Filtra::resultado($matriculas, $marcacoes, 'marcacoes.id')->with('marcacao');
            if (isset($alunos)) $matriculas = Filtra::resultado($matriculas, $alunos, 'alunos.id')->with('aluno');
            if (isset($turmas)) $matriculas = Filtra::resultado($matriculas, $turmas, 'turmas.id')->with('turma');
            if (isset($pacotes)) $matriculas = Filtra::resultado($matriculas, $pacotes, 'pacotes.id')->with('pacote');

            $pagination = Trata::resultado($matriculas, 'alunos.nome'); // Ordenação por situação, marcação, aluno, turma ou pacote.

            $alunos = $user && $user->is_admin ? Aluno::all() : $user->alunos;
            $turmas = $user && $user->is_admin ? Turma::all() : $user->alunos->flatMap->matriculas->flatMap->turma->unique('id');
            $situacoes = $user && $user->is_admin ? Situacao::all() : $user->alunos->flatMap->matriculas->flatMap->situacao->unique('id');
            $pacotes = $user && $user->is_admin ? Pacote::all() : $user->alunos->flatMap->matriculas->flatMap->pacote->unique('id');

            return isWeb()
                ? Inertia::render('matriculas/index', [
                    'pagination' => $pagination,
                    'alunos' => $alunos,
                    'turmas' => $turmas,
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
            ? Aluno::all()
            : $user->alunos;

        $turmas = Turma::all()->load(['nucleo']);

        $pacotes = $isAdmin
            ? Pacote::all()->load(['nucleo'])
            : Pacote::where('ativo', true)->get()->load(['nucleo']);

        $situacoes = Situacao::all();

        $marcacoes = Marcacao::all();

        return Inertia::render('matriculas/create', [
            'alunos' => $alunos,
            'turmas' => $turmas,
            'pacotes' => $pacotes,
            'situacoes' => $situacoes,
            'marcacoes' => $marcacoes,
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
            
            // Aqui validamos se o aluno a ser matrículado tem relação com o usuário logado
            $aluno = Aluno::find($request->aluno_id);
            if ($user && !$user->is_admin) {                
                $usuariosRelacionados = false;
                if (in_array($user->id, $aluno->users->pluck('id')->toArray())) $usuariosRelacionados = true;
                if (!$usuariosRelacionados) {
                        $mensagem = "Você não tem permissão para matricular esse aluno";
                        session(['error' => $mensagem]);

                        return isWeb()
                            ? redirect()->back()
                            : response($mensagem, 403);
                }
            }
            $pivot = $user->pivot;
            // Aqui validamos se o usuário logado possui algum tipo de responsabilidade pelo aluno a ser matriculado
            if ($pivot && !$pivot->vinculo) {
                $mensagem = "Atualize as suas informações de usuário para sabermos qual será sua participação na vida letiva de {$aluno->nome} dentro da Toca.";
                return isWeb()
                    ? redirect()->back()->with('errou', $mensagem)
                    : response($mensagem);
            }

            // $temAcompanhante = false;
            // $temAcompananhteReserva = false;
            // foreach ($aluno->users as $user) la{
            //     if (count($user->tipos->where('nome', 'acompanhante'))) $temAcompanhante = true;
            //     if (count($user->tipos->where('nome', 'acompanhante reserva'))) $temAcompananhteReserva = true;
            // }

            // if (!$temAcompanhante) return response("Informe quem acompanhará {$aluno->nome} nas aulas.", 403);
            // if (!$temAcompananhteReserva) return response("Informe quem acompanhará {$aluno->nome} nas aulas quando o principal acompanhante não puder.", 403);

            $matricula = Matricula::create($request->validated());

            session(['success' => "Matrícula do {$matricula->aluno->nome} na turma {$matricula->turma->nome} criada."]);

            return isWeb()
                ? redirect()->route("matriculas.index")
                : response($matricula);
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
     * @param  \App\Models\Matricula  $matricula
     * @return \Illuminate\Http\Response
     */
    public function show(Matricula $matricula):Response
    {
        try {
            return response($matricula);
        } catch(\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
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
                ? Aluno::all()
                : $user->alunos;

            $turmas = Turma::all()->load(['nucleo']);

            $pacotes = $isAdmin
                ? Pacote::all()->load(['nucleo'])
                : Pacote::where('ativo', true)->get()->load(['nucleo']);

            $situacoes = Situacao::all();

            $marcacoes = Marcacao::all();

            return Inertia::render('matriculas/edit', [
                'matricula' => $matricula,
                'alunos' => $alunos,
                'turmas' => $turmas,
                'pacotes' => $pacotes,
                'situacoes' => $situacoes,
                'marcacoes' => $marcacoes,
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
            $matricula->turma->vagas_preenchidas = $matricula->turma->matriculas()->count();
            $matricula->turma->save();

            session(['success' => "Matrícula do {$matricula->aluno->nome} na turma {$matricula->turma->nome} editada."]);

            return isWeb()
                ? redirect()->back()
                : response($matricula);
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
            session(['success' => $mensagem]);
            
            return isWeb()
                ? redirect()->route("matriculas.index")
                : response($mensagem);
        } catch(\Throwable $th) {
            $mensagem = Trata::erro($th);
            return isWeb()
                ? redirect()->route("matriculas.index")
                : response($mensagem);
        }
    }
}
