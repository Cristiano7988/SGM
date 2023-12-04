<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Models\Transacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransacaoController extends Controller
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

            $order_by = $order_by ?? 'transacoes.data_de_pagamento';
            $sort =  $sort ?? 'asc';
            $per_page = $per_page ?? 10;

            $transacoes = $transacoes->orderBy($order_by, $sort)->paginate($per_page);

            return $transacoes;
        } catch (\Throwable $th) {
            return $th->getMessage();
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
            DB::beginTransaction();
            $transacao = Transacao::create($request->except('comprovante'));
            if (isset($request->comprovante)) {
                $path = $request->comprovante->store('comprovantes');
                $transacao->comprovante = $path;
                $transacao->save();
            }
            DB::commit();

            return $transacao;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transacao  $transacao
     * @return \Illuminate\Http\Response
     */
    public function show(Transacao $transacao)
    {
        try {
            return $transacao;
        } catch (\Throwable $th) {
            return $th->getMessage();
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
    public function update(Request $request, Transacao $transacao)
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
            return $transacao;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transacao  $transacao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transacao $transacao)
    {
        try {
            DB::beginTransaction();
            Storage::delete($transacao->comprovante);
            $deleted = $transacao->delete();
            DB::commit();
    
            return !!$deleted;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
