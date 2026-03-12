<?php

namespace App\Http\Controllers;

use App\Helpers\Trata;
use App\Models\Aluno;
use App\Models\User;
use App\Models\Matricula;
use App\Http\Requests\Settings\AlunoRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class AlunoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        try {
            extract(request()->all());
            $user = Auth::user();
            $alunos = Aluno::query();

            $alunos->with([
                'users',
                'matriculas',
            ]);

            $alunos
                ->leftJoin('aluno_user', 'alunos.id', 'aluno_user.aluno_id')
                ->leftJoin('matriculas', 'alunos.id', 'matriculas.aluno_id')
                ->select(['alunos.*'])->groupBy('alunos.id');

            if ($user && !$user->is_admin) $alunos = $alunos->whereIn('alunos.id', $user->alunos->pluck('id'));

            // if (isset($matriculas)) $alunos = Filtra::resultado($alunos, $matriculas, 'matriculas.id')->with('matriculas');
            if (isset($users)) {
                if ($users != '*') $alunos->whereIn('aluno_user.user_id', explode(',', $users));
                $alunos->with('users');
            }

            $pagination = Trata::resultado($alunos, 'nome'); // Ordenação apenas por aluno.

            return isWeb()
                ? Inertia::render('alunos/index', [
                    'pagination' => $pagination,
                    'users' => $user && $user->is_admin ? User::all() : $user->alunos->flatMap->users->unique('id'),
                    'matriculas' => $user && $user->is_admin ? Matricula::all() : $user->alunos->flatMap->matriculas->unique('id'),
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
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        $user = Auth::user();

        $users = $user && $user->is_admin
            ? User::all()
            : $user->alunos->flatMap->users->unique('id');

        return Inertia::render('alunos/create', [ 'users' => $users ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AlunoRequest $request)
    {
        try {
            $user = Auth::user();

            DB::beginTransaction();
            $aluno = Aluno::create($request->validated());
            if (isset($request->users) && !!count($request->users)) $aluno->users()->sync($request->users);
            DB::commit();

            return isWeb()
                ? redirect()->route('alunos.index')->with('success', 'Aluno criado com sucesso.')
                : response($aluno);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return isWeb()
                ? redirect()->route('alunos.index')->with('error', $mensagem)
                : response($mensagem, 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Aluno  $aluno
     */
    public function show(Aluno $aluno)
    {
        try {
            return isWeb()
                ? Inertia::render('alunos/show', [
                    'aluno' => $aluno->load(['users', 'matriculas']),
                ])
                : response($aluno->load(['users', 'matriculas']));
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return isWeb()
                ? redirect()->route('alunos.index')->with('error', $mensagem)
                : response($mensagem, 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Aluno  $aluno
     */
    public function edit(Aluno $aluno)
    {
        try {
            $user = Auth::user();

            $users = $user && $user->is_admin
                ? User::all()
                : $user->alunos->flatMap->users->unique('id');

            $matriculas = $user && $user->is_admin
                ? Matricula::all()
                : $user->alunos->flatMap->matriculas->unique('id');

            return Inertia::render('alunos/edit', [
                'aluno' => $aluno->load(['users', 'matriculas']),
                'users' => $users,
                'matriculas' => $matriculas,
            ]);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return redirect()->route('alunos.index')->with('error', $mensagem);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Aluno  $aluno
     */
    public function update(AlunoRequest $request, Aluno $aluno)
    {
        try {
            // Aqui validamos se o aluno a ser atualizado tem relação com o usuário logado
            $user = Auth::user();
            if ($user && !$user->is_admin) {                
                $alunoIds = $user->alunos->pluck('id')->toArray();
                if (!in_array($aluno->id, $alunoIds)) return response("Você não tem permissão para editar esse aluno.", 403);
            }
            // Aqui atualizamos os dados
            $aluno->update($request->validated());
            if (isset($request->users) && !!count($request->users)) $aluno->users()->sync($request->users);

            return isWeb()
                ? redirect()->route('alunos.index')->with('success', 'Aluno atualizado com sucesso.')
                : response($aluno);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return isWeb()
                ? redirect()->route('alunos.index')->with('error', $mensagem)
                : response($mensagem, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Aluno  $aluno
     */
    public function destroy(Aluno $aluno)
    {
        try {
            $aluno->users()->detach();
            $aluno->delete();
            return isWeb()
                ? redirect()->route('alunos.index')->with('success', 'Aluno deletado com sucesso.')
                : response("Aluno deletado com sucesso.");
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return isWeb()
                ? redirect()->route('alunos.index')->with('error', $mensagem)
                : response($mensagem, 500);
        }
    }
}
