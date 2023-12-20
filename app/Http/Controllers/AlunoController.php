<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Models\Aluno;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AlunoController extends Controller
{
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, $aluno = false)
    {
        return Validator::make($data, [
            'nome' => [
                $aluno ? 'nullable' : 'required',
                'string',
                'min:2',
                'max:255'],
            'data_de_nascimento' => [
                $aluno ? 'nullable' : 'required',
                'date',
                'date_format:Y-m-d',
                'before:'.date('d/m/Y', strtotime('-30 days')) // Deve ter no mínimo 1 mês de idade
            ]
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index():Response
    {
        try {
            extract(request()->all());
            $user = Auth::user();
            $alunos = Aluno::query();

            $alunos
                ->leftJoin('aluno_user', 'alunos.id', 'aluno_user.aluno_id')
                ->leftJoin('matriculas', 'alunos.id', 'matriculas.aluno_id')
                ->select(['alunos.*'])->groupBy('alunos.id');

            if (!$user->is_admin) $alunos = $alunos->whereIn('alunos.id', $user->alunos->pluck('id'));

            if (isset($matriculas)) $alunos = Filtra::resultado($alunos, $matriculas, 'matriculas.id')->with('matriculas');
            if (isset($users)) {
                if ($users != '*') $alunos->whereIn('aluno_user.user_id', explode(',', $users));
                $alunos->with('users');
            }

            $alunos = Trata::resultado($alunos, 'nome'); // Ordenação apenas por aluno.

            return response($alunos);
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
            // Aqui validamos os dados
            $validator = $this->validator($request->all());
            if ($validator->fails()) return response($validator->errors(), 422);

            $user = Auth::user();

            // Aqui criamos o aluno
            DB::beginTransaction();
            $aluno = Aluno::create($request->all());
            $aluno->users()->attach($user->id);
            DB::commit();

            return response($aluno);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Aluno  $aluno
     * @return \Illuminate\Http\Response
     */
    public function show(Aluno $aluno):Response
    {
        try {
            return response($aluno);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Aluno  $aluno
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Aluno $aluno):Response
    {
        try {
            // Aqui validamos os dados
            $validator = $this->validator($request->all(), $aluno);
            if ($validator->fails()) return response($validator->errors(), 422);

            // Aqui validamos se o aluno a ser atualizado tem relação com o usuário logado
            $authUser = Auth::user();
            if (!$authUser->is_admin) {                
                $usuariosRelacionados = false;
                if (in_array($authUser->id, $aluno->users->pluck('id')->toArray())) $usuariosRelacionados = true;
                if (!$usuariosRelacionados) return response('Você não tem permissão para editar esse aluno', 403);
            }

            // Aqui atualizamos os dados
            $aluno->update($request->all());
            if (isset($request->users) && !!count($request->users)) $aluno->users()->sync($request->users);

            return response($aluno);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Aluno  $aluno
     * @return \Illuminate\Http\Response
     */
    public function destroy(Aluno $aluno):Response
    {
        try {
            $aluno->users()->detach();
            $aluno->delete();
            return response("O aluno de nº {$aluno->id}, {$aluno->nome},  foi deletado.");
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
