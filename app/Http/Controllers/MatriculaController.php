<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Models\Aluno;
use App\Models\Matricula;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MatriculaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index():Response
    {
        try {
            extract(request()->all());
            $matriculas = Matricula::query();

            $matriculas
                ->leftJoin('situacoes', 'matriculas.situacao_id', 'situacoes.id')
                ->leftJoin('marcacoes', 'matriculas.marcacao_id', 'marcacoes.id')
                ->leftJoin('alunos', 'matriculas.aluno_id', 'alunos.id')
                ->leftJoin('turmas', 'matriculas.turma_id', 'turmas.id')
                ->leftJoin('pacotes', 'matriculas.pacote_id', 'pacotes.id')
                ->select(['matriculas.*'])->groupBy('matriculas.id');

            $user = Auth::user();
            $alunosDoUsuario = $user->alunos->pluck('id');
            if (!$user->is_admin) $matriculas->whereIn('aluno_id', $alunosDoUsuario);

            if (isset($situacoes)) $matriculas = Filtra::resultado($matriculas, $situacoes, 'situacoes.id')->with('situacao');
            if (isset($marcacoes)) $matriculas = Filtra::resultado($matriculas, $marcacoes, 'marcacoes.id')->with('marcacao');
            if (isset($alunos)) $matriculas = Filtra::resultado($matriculas, $alunos, 'alunos.id')->with('aluno');
            if (isset($turmas)) $matriculas = Filtra::resultado($matriculas, $turmas, 'turmas.id')->with('turma');
            if (isset($pacotes)) $matriculas = Filtra::resultado($matriculas, $pacotes, 'pacotes.id')->with('pacote');

            $matriculas = Trata::resultado($matriculas, 'alunos.nome'); // Ordenação por situação, marcação, aluno, turma ou pacote.

            return response($matriculas);
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
            $authUser = Auth::user();
            
            // Aqui validamos se o aluno a ser matrículado tem relação com o usuário logado
            $aluno = Aluno::find($request->aluno_id);
            if (!$authUser->is_admin) {                
                $usuariosRelacionados = false;
                if (in_array($authUser->id, $aluno->users->pluck('id')->toArray())) $usuariosRelacionados = true;
                if (!$usuariosRelacionados) return response('Você não tem permissão para matricular esse aluno', 403);
            }

            // Aqui validamos se o usuário logado possui algum tipo de responsabilidade pelo aluno a ser matriculado
            if (!$authUser->tipos->count()) return response("Atualize as suas informações de usuário para sabermos qual será sua participação na vida letiva de {$aluno->nome} dentro da Toca.");
            
            $temAcompanhante = false;
            $temAcompananhteReserva = false;
            foreach ($aluno->users as $user) {
                if (count($user->tipos->where('nome', 'acompanhante'))) $temAcompanhante = true;
                if (count($user->tipos->where('nome', 'acompanhante reserva'))) $temAcompananhteReserva = true;
            }

            if (!$temAcompanhante) return response("Informe quem acompanhará {$aluno->nome} nas aulas.", 403);
            if (!$temAcompananhteReserva) return response("Informe quem acompanhará {$aluno->nome} nas aulas quando o principal acompanhante não puder.", 403);

            $matricula = Matricula::create($request->except('situacao_id', 'marcacao_id'));
            return response($matricula);
        } catch(\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Matricula  $matricula
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Matricula $matricula):Response
    {
        try {
            $matricula->update($request->all());
            $matricula->turma->vagas_preenchidas = $matricula->turma->matriculas()->count();
            $matricula->turma->save();

            return response($matricula);
        } catch(\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Matricula  $matricula
     * @return \Illuminate\Http\Response
     */
    public function destroy(Matricula $matricula):Response
    {
        try {
            DB::beginTransaction();
            $excluido = Trata::exclusao($matricula, 'Matrícula');
            if ($excluido) DB::commit(); // Exclui somente se conseguir notificar o cliente

            return response("A matrícula de nº {$matricula->id}, de {$matricula->aluno->nome},  foi deletada.");
        } catch(\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
