<?php

namespace App\Helpers;

use App\Models\Cupom;

class Calcula
{
    public static function desconto($pacotes)
    {
        $codigo = request()->codigo;
        $cupom = Cupom::where('codigo', '=', $codigo)->first();        
        
        if (!$cupom) return $pacotes;

        foreach($pacotes as $pacote) {
            $pacote->desconto_aplicado = $cupom->medida->tipo == '%'
                ? $cupom->desconto . $cupom->medida->tipo
                : Formata::moeda($cupom->desconto);

            $desconto = $cupom->medida->tipo == '%'
                ? $pacote->valor * ($cupom->desconto / 100)
                : $cupom->desconto;
            
            $pacote->valor_a_pagar = Formata::moeda($pacote->valor - $desconto);
        }

        return $pacotes;
    }
}
