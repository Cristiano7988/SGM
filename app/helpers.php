<?php

use Illuminate\Support\Carbon;

if (! function_exists('web')) {
    /**
     * Função que auxilia na verificação de acesso aos métodos, se estão sendo acessados via web ou api.
     *
     */
    function web()
    {
        return in_array('web', request()->route()->middleware());
    }
}

if (! function_exists('data_formatada')) {
    /**
     * Formata a data pro padrão d/m/Y.
     *
     * @param  string  $data
     * @return string
     */
    function data_formatada(string $data)
    {
        return Carbon::parse($data)->format('d/m/Y');
    }
}
