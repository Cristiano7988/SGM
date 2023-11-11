<?php

namespace App\Helpers;

class Formata
{
    public static function moeda($valor)
    {
        return "R$" . " " . number_format($valor, 2, ',', '');
    }
}