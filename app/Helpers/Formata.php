<?php

namespace App\Helpers;

use App\Models\Cupom;
use Carbon\Carbon;

class Formata
{
    public static function moeda(float $valor): string
    {
        return "R$" . " " . number_format($valor, 2, ',', '');
    }

    public static function imagem(string $imagem): string
    {
        return env('APP_URL') . '/storage/' . $imagem;
    }

    public static function boolean(bool $boolean): string
    {
        return $boolean ? 'sim' : 'não';
    }

    public static function data(string $data): string
    {
        try {
            return Carbon::parse($data)->format('d/m/Y');
        } catch (\Throwable $th) {
            return $data;
        }
    }

    public static function desconto(Cupom $cupom): string
    {
        return $cupom->medida->tipo == '%'
            ? $cupom->desconto . $cupom->medida->tipo
            : Formata::moeda($cupom->desconto);
    }

    public static function sePrecisa(string $key, $value, $model)
    {
        if (!$value) return "";
 
        $imagens = [
            'imagem', // núcleo|turma
            'comprovante' // transacao
        ];

        $datas = [
            'inicio_matricula', // núcleo
            'fim_matricula', // núcleo
            'data_de_nascimento', // aluno
            'inicio', // periodo
            'fim', // periodo
            'data_de_pagamento', // transacao
        ];

        $booleanos = [
            'disponivel', // turma
            'ativo' // pacote
        ];

        $moedas = [
            'valor' // pacote
        ];

        if (in_array($key, $imagens)) $value = Formata::imagem($value);
        else if (in_array($key, $datas)) $value = Formata::data($value);
        else if (in_array($key, $booleanos)) $value = Formata::boolean($value);
        else if (in_array($key, $moedas)) $value = Formata::moeda($value);
        else if ($key == 'desconto') $value = Formata::desconto($model);

        return $value;
    }
}