<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Models\Transacao;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransacaoController extends Controller
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
            $transacoes = Transacao::query();

            $transacoes
                ->leftJoin('matriculas', 'transacoes.matricula_id', 'matriculas.id')
                ->leftJoin('users', 'transacoes.user_id', 'users.id')
                ->leftJoin('cupons', 'transacoes.cupom_id', 'cupons.id')
                ->leftJoin('formas_de_pagamento', 'transacoes.forma_de_pagamento_id', 'formas_de_pagamento.id')
                ->select(['transacoes.*'])->groupBy('transacoes.id');

            if (isset($matriculas)) $transacoes = Filtra::resultado($transacoes, $matriculas, 'matriculas.id')->with('matricula');
            if (isset($users)) $transacoes = Filtra::resultado($transacoes, $users, 'users.id')->with('user');
            if (isset($cupons)) $transacoes = Filtra::resultado($transacoes, $cupons, 'cupons.id')->with('cupom');
            if (isset($formas_de_pagamento)) $transacoes = Filtra::resultado($transacoes, $formas_de_pagamento, 'formas_de_pagamento.id'); // Transação COM forma de pagamento vem por padrão da model.

            $transacoes = Trata::resultado($transacoes, 'transacoes.data_de_pagamento'); // Ordenação por transação, cupom, matrícula ou forma de pagamento.

            return response($transacoes);
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
    public function store(Request $request):Response
    {
        try {
            DB::beginTransaction();
            $transacao = Transacao::create($request->except('comprovante'));
            if (isset($request->comprovante)) {
                $path = $request->comprovante->store('comprovantes');
                $transacao->comprovante = $path;
                $transacao->save();
            }
            DB::commit();

            return response($transacao);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transacao  $transacao
     * @return \Illuminate\Http\Response
     */
    public function show(Transacao $transacao):Response
    {
        try {
            return response($transacao);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transacao  $transacao
     * @return \Illuminate\Http\Response
     */
    public function edit(Transacao $transacao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transacao  $transacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transacao $transacao):Response
    {
        try {
            DB::beginTransaction();
            $transacao->update($request->except('comprovante'));
            if (isset($request->comprovante)) {
                Storage::delete($transacao->comprovante);
                $path = $request->comprovante->store('comprovantes');
                $transacao->comprovante = $path;
                $transacao->save();
            }

            DB::commit();
            return response($transacao);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transacao  $transacao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transacao $transacao):Response
    {
        try {
            DB::beginTransaction();
            Storage::delete($transacao->comprovante);
            $transacao->delete();
            DB::commit();
    
            return response("A transação de nº {$transacao->id}, do usuário {$transacao->user->nome} feita no dia {$transacao->data_de_pagamento},  foi deletada.");;
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
