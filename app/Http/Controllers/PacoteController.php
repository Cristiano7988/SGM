<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Http\Requests\Settings\PacoteRequest;
use App\Models\Turma;
use App\Models\Pacote;
use App\Models\Aula;
use App\Models\Aluno;
use App\Models\Matricula;
use App\Models\User;
use App\Models\Endereco;
use App\Models\Nucleo;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PacoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        try {
            extract(request()->all());
            $pacotes = Pacote::query();

            $pacotes
                ->leftJoin('aulas', 'pacotes.id', 'aulas.pacote_id')
                ->leftJoin('matriculas', 'pacotes.id', 'matriculas.pacote_id')
                ->select(['pacotes.*'])->groupBy('pacotes.id');
            
            $pacotes->with([
                'aulas.turma',
            ]);

            if (isset($matriculas)) $pacotes = Filtra::resultado($pacotes, $matriculas, 'matriculas.id')->with('matriculas');
            if (isset($aulas)) $pacotes = Filtra::resultado($pacotes, $aulas, 'aulas.id')->with('aulas');
            if (isset($turmaId)) $pacotes = Filtra::resultado($pacotes, $turmaId, 'turmas.id')->with('turma');
            if (isset($ativo)) $pacotes = $pacotes->where('ativo', $ativo);

            $pagination = Trata::resultado($pacotes, 'pacotes.id'); // Ordenação por pacote.

            return isWeb()
                ? Inertia::render('pacotes/index', [
                    'pagination' => $pagination,
                    'turmas' => Turma::all(),
                    'aulas' => Aula::all()->unique('dia')->values(),
                    'matriculas' => Matricula::all()
                ])
                : response($pacotes);
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
        $user = auth()->user();
        if ($user && $user->is_admin) {
            $enderecos = Endereco::all();
            $turmas = Turma::all();
            $alunos = Aluno::all();
        } else {
            // endereços relacionados aos alunos do usuário
            $enderecosDosAlunos = $user->getEnderecosDosAlunos();
    
            $turmas = Nucleo::getTurmas($user->alunos->pluck("meses"));
            $alunos = $user->alunos;

            // endereços relacionados aos usuários dos professores responsáveis pelas turmas pertencentes aos núcleos disponíveis para os alunos do usuário logado
            $enderecosDosProfessores = $turmas->pluck("professor.user.endereco");

            // Merge das turmas pertencentes aos núcleos voltados para os alunos do usuário logado 
            $enderecos = $enderecosDosAlunos
                ->concat($enderecosDosProfessores)
                ->filter()
                ->unique('id')
                ->values();
        }

        $pacote = new Pacote([
            "valor" => 0,
            "ativo" => 0
        ]);
        $aula = new Aula([
            'dia' => "",
            'valor' => 0,
            'tempo_de_deslocamento' => 0,
            'distancia' => 0,
            'taxa_de_deslocamento' => 0,
        ]);

        $turma = new Turma();
        $aluno = new Aluno([
            "id" => 0,
            "nome" => ""
        ]);

        $aula->setRelation('turma', $turma);
        $aula->setRelation('alunos', collect([$aluno]));
        $pacote->setRelation('aulas', collect([$aula]));
        return Inertia::render('pacotes/create', [
            'pacote' => $pacote,
            'turmas' => Turma::all()->load('professor'),
            'enderecos' => $enderecos,
            'turmas' => $turmas->load("professor"),
            'alunos' => $alunos,
        ]);
    }

/**
 * @param  \App\Http\Requests\Settings\PacoteRequest  $request
 */
public function store(PacoteRequest $request)
{
    try {
        $pacote = DB::transaction(function () use ($request) {
            $dadosValidados = $request->validated();

            $pacote = Pacote::create($dadosValidados);

            if (! empty($dadosValidados['aulas'])) {
                foreach ($dadosValidados['aulas'] as $aulaData) {
                    // 1. Cria a aula vinculada ao pacote
                    $aula = $pacote->aulas()->create($aulaData);

                    // 2. Salva os alunos na tabela pivô alunos_aulas
                    if (! empty($aulaData['aluno_ids'])) {
                        $aula->alunos()->sync($aulaData['aluno_ids']);
                    }
                }
            }

            return $pacote;
        });

        $mensagem = "Pacote {$pacote->vigencia} criado.";

        return isWeb()
            ? redirect()->route('pacotes.index')->with('success', $mensagem)
            : response($mensagem);
    } catch (\Throwable $th) {
        $mensagem = Trata::erro($th);

        return isWeb()
            ? redirect()->route('pacotes.index')->with('error', $mensagem)
            : response($mensagem);
    }
}

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pacote  $pacote
     */
    public function show(Pacote $pacote)
    {
        try {
            return isWeb()
                ? Inertia::render('pacotes/show', [
                    'pacote' => $pacote,
                    'turma' => $pacote->turma,
                    'aulas' => $pacote->aulas->load('endereco'),
                    'matriculas' => $pacote->matriculas,
                ])
                : response($pacote);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);

            return isWeb()
                ? redirect()->route('pacotes.index')
                : response($mensagem);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pacote  $pacote
     */
    public function edit(Pacote $pacote)
    {
        try {
            $user = auth()->user();
            $enderecos = $user && $user->is_admin
                ? Endereco::all()
                : $user->enderecos;

            $alunos = $user && $user->is_admin
                ? Aluno::all()
                : $user->alunos;

            return Inertia::render('pacotes/edit', [
                'pacote' => $pacote->load([
                    'aulas',
                    'aulas.endereco',
                    'aulas.alunos',
                    'aulas.turma.professor.users.endereco',
                ]),
                'turmas' => Turma::all()->load(['professor']),
                'enderecos' => $enderecos,
                'alunos' => $alunos->load([
                    'aulas',
                    'aulas.endereco',
                    'aulas.alunos',
                    'aulas.turma',
                ])
            ]);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);

            return redirect()->route('pacotes.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Settings\PacoteRequest;  $request
     * @param  \App\Models\Pacote  $pacote
     */
public function update(PacoteRequest $request, Pacote $pacote)
{
    try {
        DB::transaction(function () use ($request, $pacote) {
            $dadosValidados = $request->validated();

            $pacote->update($dadosValidados);

            // Remove as aulas antigas (e seus vínculos se houver cascade ou detach)
            $pacote->aulas()->each(function ($aulaAntiga) {
                $aulaAntiga->alunos()->detach();
                $aulaAntiga->delete();
            });

            if (! empty($dadosValidados['aulas'])) {
                foreach ($dadosValidados['aulas'] as $aulaData) {
                    // 1. Cria a aula no banco
                    $aula = $pacote->aulas()->create($aulaData);

                    // 2. Salva os alunos na tabela pivô alunos_aulas
                    if (! empty($aulaData['aluno_ids'])) {
                        $aula->alunos()->sync($aulaData['aluno_ids']);
                    }
                }
            }
        });

        $mensagem = "Pacote {$pacote->vigencia} editado.";

        return isWeb()
            ? redirect()->route('pacotes.index')->with('success', $mensagem)
            : response($mensagem);
    } catch (\Throwable $th) {
        $mensagem = Trata::erro($th);

        return isWeb()
            ? redirect()->route('pacotes.index')->with('error', $mensagem)
            : response($mensagem);
    }
}


/**
 * Remove the specified resource from storage.
 *
 * @param  \App\Models\Pacote  $pacote
 */
public function destroy(Pacote $pacote)
{
    try {
        DB::beginTransaction();

        // 1. Guarda a vigência antes de deletar o registro
        $vigencia = $pacote->vigencia;

        // 2. Remove os vínculos na tabela pivô (alunos_aulas) e deleta cada aula
        $pacote->aulas()->each(function ($aula) {
            $aula->alunos()->detach();
            $aula->delete();
        });

        // 3. Tenta excluir o pacote e notificar
        $excluido = Trata::exclusao($pacote, 'Pacote');

        if (! $excluido) {
            DB::rollBack();

            $erro = "Não foi possível excluir o pacote {$vigencia}.";

            return isWeb()
                ? redirect()->route('pacotes.index')->with('error', $erro)
                : response($erro, 422);
        }

        DB::commit();

        $mensagem = "Pacote {$vigencia} deletado.";

        return isWeb()
            ? redirect()->route('pacotes.index')->with('success', $mensagem)
            : response($mensagem);
    } catch (\Throwable $th) {
        DB::rollBack();
        $mensagem = Trata::erro($th);

        return isWeb()
            ? redirect()->route('pacotes.index')->with('error', $mensagem)
            : response($mensagem, 500);
    }
}

}
