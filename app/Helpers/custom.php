<?php

if (!function_exists('isWeb')) {
    function isWeb():bool
    {
        $middlewares = request()->route()->middleware();
        return in_array('web', $middlewares);
    }
}

if (!function_exists('viteSession')) {
    function viteSession($key = false, string $value = '')
    {
        if ($key) {
            /** Atribui status a sessão */
            $error = $key == 'error' ? $value : false;
            $success = $key == 'success' ? $value : false;
        } else {
            /** Recupera o status da sessão */
            $error = session('error');
            $success = session('success');

            session()->forget('error');
            session()->forget('success');
        }

        return compact('error', 'success');
    }
}
