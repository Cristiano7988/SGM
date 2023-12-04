<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Models\FormaDePagamento;
use Illuminate\Http\Request;

class FormaDePagamentoController extends Controller
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
            $formasDePagamento = FormaDePagamento::query();

            $formasDePagamento
                ->leftJoin('transacoes', 'formas_de_pagamento.id', 'transacoes.forma_de_pagamento_id')
                ->select(['formas_de_pagamento.*'])->groupBy('formas_de_pagamento.id');

            if (isset($transacoes)) $formasDePagamento = Filtra::resultado($formasDePagamento, $transacoes, 'transacoes.id')->with('transacoes');

            $formasDePagamento = $formasDePagamento->get('tipo');

            return $formasDePagamento;
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
            $formaDePagamento = FormaDePagamento::create($request->all());
            return $formaDePagamento;
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FormaDePagamento  $formaDePagamento
     * @return \Illuminate\Http\Response
     */
    public function show(FormaDePagamento $formaDePagamento)
    {
        try {
            return $formaDePagamento;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FormaDePagamento  $formaDePagamento
     * @return \Illuminate\Http\Response
     */
    public function edit(FormaDePagamento $formaDePagamento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FormaDePagamento  $formaDePagamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormaDePagamento $formaDePagamento)
    {
        try {
            $formaDePagamento->update($request->all());
            return $formaDePagamento;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FormaDePagamento  $formaDePagamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormaDePagamento $formaDePagamento)
    {
        try {
            $deleted = $formaDePagamento->delete();
            return !!$deleted;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
