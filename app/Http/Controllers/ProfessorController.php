<?php

namespace App\Http\Controllers;

use App\Models\Professor;
use App\Http\Requests\Settings\ProfessorRequest;
use Inertia\Inertia;
use App\Helpers\Trata;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ProfessorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $users = $user && $user->is_admin
            ? User::all()
            : $user->professores;

        $professores = Professor::query()
            ->with('users');
        
        $pagination = Trata::resultado($professores, 'nome'); // Ordenação apenas por professor.
    
        return Inertia::render('professores/index', [
            'pagination' => $pagination,
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Professor $professor)
    {
        $user = auth()->user();

        $users = $user && $user->is_admin
            ? User::allWithHisPivot(false)
            : $user->professores;
    
        $user = new User();

        $professor->setRelation('users', collect([]));

        return Inertia::render('professores/create', [
            'professor' => $professor,
            'users' => $users->load('professores'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProfessorRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = request()->hasFile('imagem')
                ? $request->safe()->except('imagem')
                : $request->validated();

            $professor = Professor::create($data);
            if (isset($request->users)) $professor->users()->sync($request->users);

            salvaImagem($professor, 'professores');
            DB::commit();

            $mensagem = "Professor {$professor->nome} criado.";

            return isWeb()
                ? redirect()->route('professores.index')->with('success', $mensagem)
                : response($mensagem);
        } catch (\Exception $e) {
            $mensagem = Trata::erro($e);

            return isWeb()
                ? redirect()->route('professores.index')
                : response($mensagem);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Professor $professor)
    {
        return Inertia::render('professores/show', [
            'professor' => $professor->load(['users'])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Professor $professor)
    {
        try {
            $user = auth()->user();

            $usuarios = $user && $user->is_admin
                ? User::all()
                : $professor->users;

            return Inertia::render('professores/edit', [
                'professor' => $professor->load(['users']),
                'users' => $usuarios->load(['professores'])
            ]);
        } catch (\Exception $e) {
            $mensagem = Trata::erro($e);

            return isWeb()
                ? redirect()->route('professores.index')
                : response($mensagem);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfessorRequest $request, Professor $professor)
    {
        try {
            $professor->update($request->validated());
            $professor->users()->sync($request->users);

            $mensagem = "Professor {$professor->nome} editado.";

            return isWeb()
                ? redirect()->route('professores.index')->with('success', $mensagem)
                : response($mensagem);
        } catch (\Exception $e) {
            $mensagem = Trata::erro($e);

            return isWeb()
                ? redirect()->route('professores.index')
                : response($mensagem);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Professor $professor)
    {
        try {
            $professor->users()->detach();
            $professor->delete();

            $mensagem = "Professor {$professor->nome} deletado.";

            return isWeb()
                ? redirect()->route('professor.index')->with('success', $mensagem)
                : response($mensagem);
        } catch (\Exception $e) {
            $mensagem = Trata::erro($th);
            return isWeb()
                ? redirect()->route('alunos.index')
                : response($mensagem);
        }
    }
}
