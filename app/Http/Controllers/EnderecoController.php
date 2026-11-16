<?php

namespace App\Http\Controllers;

use App\Http\Requests\Settings\EnderecoRequest;
use App\Models\Endereco;
use App\Models\Aluno;
use App\Models\User;
use App\Helpers\Trata;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class EnderecoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $enderecos = Endereco::query();
        $enderecos->with('users.alunos');
        $users = User::all();
        $pagination = Trata::resultado($enderecos, 'logradouro');
        return Inertia::render('enderecos/index', compact('pagination', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $users = $user && $user->is_admin
            ? User::all()
            : [$user];
        $endereco = new Endereco();
        $endereco->setRelation("users", collect([]));
        return Inertia::render('enderecos/create', compact('users', 'endereco'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EnderecoRequest $request)
    {
        try {
            $access_token = env("VITE_APP_MAPBOX_TOKEN");
            $dados = $request->validated();

            $dados['localidade'] = $dados['cidade'];
            $dados['uf'] = $dados['estado'];
            
            $resultado = Http::get('https://api.mapbox.com/search/geocode/v6/forward', [
                'q' => $dados,
                'access_token' => $access_token,
                'country' => 'BR',
                'language' => 'pt',
                'limit' => 1,
            ])->json();

            $coordenadas = $resultado['features'][0]['properties']['coordinates'];

            $dados['longitude'] = $coordenadas['longitude'];
            $dados['latitude'] = $coordenadas['latitude'];

            $endereco = Endereco::create($dados);
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
        $endereco->load('users');
        return Inertia::render('enderecos/show', compact('endereco'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Endereco $endereco)
    {
        $user = Auth::user();
        $endereco->load('users');
        $users = $user && $user->is_admin
            ? User::all()
            : [$user];

        return Inertia::render('enderecos/edit', compact('endereco', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EnderecoRequest $request, Endereco $endereco)
    {
        try {
            $access_token = env("VITE_APP_MAPBOX_TOKEN");
            $dados = $request->validated();

            $dados['localidade'] = $dados['cidade'];
            $dados['uf'] = $dados['estado'];
            
            $resultado = Http::get('https://api.mapbox.com/search/geocode/v6/forward', [
                'q' => $dados,
                'access_token' => $access_token,
                'country' => 'BR',
                'language' => 'pt',
                'limit' => 1,
            ])->json();

            $coordenadas = $resultado['features'][0]['properties']['coordinates'];

            $dados['longitude'] = $coordenadas['longitude'];
            $dados['latitude'] = $coordenadas['latitude'];

            $endereco->update($dados);

            if ($request->users) {
                User::whereIn('id', $request->users)
                    ->update(['endereco_id' => $endereco->id]);
            }

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
