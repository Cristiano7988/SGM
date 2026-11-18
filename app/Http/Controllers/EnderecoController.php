<?php

namespace App\Http\Controllers;

use App\Http\Requests\Settings\EnderecoRequest;
use App\Models\Endereco;
use App\Models\Aluno;
use App\Models\User;
use App\Helpers\Trata;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

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
            $dados = $request->validated();

            $enderecoBusca = implode(', ', array_filter([
                $dados['logradouro'] ?? null,
                $dados['numero'] ?? null,
                $dados['bairro'] ?? null,
                $dados['cidade'] ?? null,
                $dados['estado'] ?? null,
                'Brasil',
            ]));

            $resultado = Cache::remember(
                'geocode_' . md5($enderecoBusca),
                now()->addDays(30),
                fn () => Http::withHeaders([
                    'User-Agent' => 'MeuSistema/1.0 (cristianooliveiramorales@gmail.com)',
                ])->get('https://nominatim.openstreetmap.org/search', [
                    'q' => $enderecoBusca,
                    'format' => 'jsonv2',
                    'limit' => 1,
                    'countrycodes' => 'br',
                ])->json()
            );

            if (empty($resultado[0])) {
                $status = "error";
                $mensagem = "Endereço {$request->logradouro}, {$request->numero} criado, mas sem as coordenadas.";
            } else {
                $dados['longitude'] = (float) $resultado[0]['lon'];
                $dados['latitude'] = (float) $resultado[0]['lat'];

                $status = "success";
                $mensagem = "Endereço {$request->logradouro}, {$request->numero} editado.";
            }

            $endereco = Endereco::create($dados);
            if ($request->alunos) $endereco->alunos()->sync($request->alunos);

            return isWeb()
                ? redirect()->route('enderecos.index')->with($status, $mensagem)
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
            $dados = $request->validated();

            $enderecoBusca = implode(', ', array_filter([
                $dados['logradouro'] ?? null,
                $dados['numero'] ?? null,
                $dados['bairro'] ?? null,
                $dados['cidade'] ?? null,
                $dados['estado'] ?? null,
                'Brasil',
            ]));

            $resultado = Cache::remember(
                'geocode_' . md5($enderecoBusca),
                now()->addDays(30),
                fn () => Http::withHeaders([
                    'User-Agent' => 'MeuSistema/1.0 (cristianooliveiramorales@gmail.com)',
                ])->get('https://nominatim.openstreetmap.org/search', [
                    'q' => $enderecoBusca,
                    'format' => 'jsonv2',
                    'limit' => 1,
                    'countrycodes' => 'br',
                ])->json()
            );

            if (empty($resultado[0])) {
                $status = "error";
                $mensagem = "Endereço {$request->logradouro}, {$request->numero} editado, mas sem as coordenadas.";
            } else {                
                $dados['longitude'] = (float) $resultado[0]['lon'];
                $dados['latitude'] = (float) $resultado[0]['lat'];

                $status = "success";
                $mensagem = "Endereço {$request->logradouro}, {$request->numero} editado.";
            }

            $endereco->update($dados);

            if ($request->users) {
                User::whereIn('id', $request->users)
                    ->update(['endereco_id' => $endereco->id]);
            }

            return isWeb()
                ? redirect()->route('enderecos.index')->with($status, $mensagem)
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
