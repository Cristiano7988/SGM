<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Models\Aluno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AlunoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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

            return $alunos;
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
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
            $user = Auth::user();

            DB::beginTransaction();
            $aluno = Aluno::create($request->all());
            $aluno->users()->attach($user->id);
            DB::commit();

            return $aluno;
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
    public function show(Aluno $aluno)
    {
        try {
            return $aluno;
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Aluno  $aluno
     * @return \Illuminate\Http\Response
     */
    public function edit(Aluno $aluno)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Aluno  $aluno
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Aluno $aluno)
    {
        try {
            $aluno->update($request->all());
            if (isset($request->users) && !!count($request->users)) $aluno->users()->sync($request->users);

            return $aluno;
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
    public function destroy(Aluno $aluno)
    {
        try {
            $aluno->users()->detach();
            $deleted = $aluno->delete();
            return !!$deleted;
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
