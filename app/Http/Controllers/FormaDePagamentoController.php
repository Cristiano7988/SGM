<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Models\FormaDePagamento;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FormaDePagamentoController extends Controller
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
            $formasDePagamento = FormaDePagamento::query();

            $formasDePagamento
                ->leftJoin('transacoes', 'formas_de_pagamento.id', 'transacoes.forma_de_pagamento_id')
                ->select(['formas_de_pagamento.*'])->groupBy('formas_de_pagamento.id');

            if (isset($transacoes)) $formasDePagamento = Filtra::resultado($formasDePagamento, $transacoes, 'transacoes.id')->with('transacoes');

            $formasDePagamento = $formasDePagamento->get('tipo');

            return response($formasDePagamento);
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
            $formaDePagamento = FormaDePagamento::create($request->all());
            return response($formaDePagamento);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FormaDePagamento  $formaDePagamento
     * @return \Illuminate\Http\Response
     */
    public function show(FormaDePagamento $formaDePagamento):Response
    {
        try {
            return response($formaDePagamento);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FormaDePagamento  $formaDePagamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormaDePagamento $formaDePagamento):Response
    {
        try {
            $formaDePagamento->update($request->all());
            return response($formaDePagamento);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FormaDePagamento  $formaDePagamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormaDePagamento $formaDePagamento):Response
    {
        try {
            $formaDePagamento->delete();
            return response("A forma de pagamento de nº {$formaDePagamento->id}, {$formaDePagamento->tipo},  foi deletada.");;
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
