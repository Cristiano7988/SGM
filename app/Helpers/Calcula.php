<?php

namespace App\Helpers;

use App\Models\Cupom;
use App\Models\FormaDePagamento;

class Calcula
{
    public static function desconto($pacotes)
    {
        $codigo = request()->codigo;
        $cupom = Cupom::where('codigo', '=', $codigo)->first();
        $forma_de_pagamento = FormaDePagamento::find(request()->forma_de_pagamento_id);

        foreach($pacotes as $pacote) {
            if ($forma_de_pagamento && $forma_de_pagamento->tipo == 'paypal') $pacote->valor = Calcula::paypal($pacote->valor);

            if ($cupom) {
                request()['cupom_id'] = $cupom->id;
                $pacote->desconto_aplicado = Formata::desconto($cupom);
    
                $desconto = $cupom->medida->tipo == '%'
                    ? $pacote->valor * ($cupom->desconto / 100)
                    : $cupom->desconto;
                
                $pacote->valor_a_pagar = Formata::moeda($pacote->valor - $desconto);
            }
        }

        return $pacotes;
    }

    public static function paypal($valor)
    {
        $taxa = 0.07;
        return $valor + ($valor * $taxa);
    }
}
