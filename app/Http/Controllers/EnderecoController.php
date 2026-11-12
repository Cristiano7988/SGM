<?php

namespace App\Http\Controllers;

use App\Http\Requests\Settings\EnderecoRequest;
use App\Models\Endereco;
use App\Models\Aluno;
use App\Helpers\Trata;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class EnderecoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $enderecos = Endereco::query();
        $enderecos->with('alunos');
        $alunos = Aluno::all();
        $pagination = Trata::resultado($enderecos, 'logradouro');
        return Inertia::render('enderecos/index', compact('pagination', 'alunos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $alunos = $user && $user->is_admin
            ? Aluno::all()
            : $user->alunos;

        return Inertia::render('enderecos/create', compact('alunos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EnderecoRequest $request)
    {
        try {
            $endereco = Endereco::create($request->validated());
            if ($request->alunos) $endereco->alunos()->sync($request->alunos);
            $mensagem = "Endereço {$endereco->logradouro}, {$endereco->numero} criado.";

            return isWeb()
                ? redirect()->route('enderecos.index')->with('success', $mensagem)
                : response($mensagem);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return isWeb()
                ? redirect()->route('enderecos.index')
                : response($mensagem);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Endereco $endereco)
    {
        $endereco->load('alunos');
        return Inertia::render('enderecos/show', compact('endereco'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Endereco $endereco)
    {
        $user = Auth::user();
        $endereco->load('alunos');
        $alunos = $user && $user->is_admin
            ? Aluno::all()
            : $user->alunos;

        return Inertia::render('enderecos/edit', compact('endereco', 'alunos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EnderecoRequest $request, Endereco $endereco)
    {
        try {
            $endereco->update($request->validated());
            if ($request->alunos) $endereco->alunos()->sync($request->alunos);
            $mensagem = "Endereço {$endereco->logradouro}, {$endereco->numero} atualizado.";

            return isWeb()
                ? redirect()->route('enderecos.index')->with('success', $mensagem)
                : response($mensagem);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return isWeb()
                ? redirect()->route('enderecos.index')
                : response($mensagem);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Endereco $endereco)
    {
        try {
            $endereco->delete();
            $mensagem = "Endereço {$endereco->logradouro}, {$endereco->numero} excluído.";

            return isWeb()
                ? redirect()->route('enderecos.index')->with('success', $mensagem)
                : response($mensagem);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return isWeb()
                ? redirect()->route('enderecos.index')
                : response($mensagem);
        }
    }
}
