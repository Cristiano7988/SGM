<?php

use App\Models\Aluno;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

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

if (! function_exists('metodo')) {
    /**
     * Retorna o método utilizado com base na URI
     *
     * @return string
     */
    function metodo()
    {
        $paths = explode('/', $_SERVER['REQUEST_URI']);
        
        return $paths[count($paths) - 1];
    }
}

if (! function_exists('alunos')) {
    /**
     * Retorna os alunos relacionados ao usuário ou todos os alunos para os admins
     *
     * @return string
     */
    function alunos()
    {
        $user = Auth::user();
        if (!$user) return;
        
        return $user->is_admin ? Aluno::all() : $user->alunos;
    }
}
