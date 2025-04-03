<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

if (!function_exists('validaImagem')) {
    function validaImagem($attribute, $value, $fail)
    {
        $isUrl = filter_var($value, FILTER_VALIDATE_URL);
        $isFile = is_file($value);

        // Lista de extensões permitidas
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        // Se for um arquivo, verificar a extensão
        if ($isFile) {
            $extension = $value->getClientOriginalExtension();
            
            if (!in_array(strtolower($extension), $allowedExtensions)) {
                $fail($attribute . ' deve ser uma imagem válida (jpg, jpeg, png, gif, webp).');
            }
        }

        // Se for uma URL, garantir que termina com uma extensão permitida
        if ($isUrl) {
            $path = parse_url($value, PHP_URL_PATH);
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            if (!in_array(strtolower($extension), $allowedExtensions)) {
                $fail($attribute . ' deve ser uma URL de imagem válida (jpg, jpeg, png, gif, webp).');
            }
        }

        if (!$isUrl && !$isFile) $fail($attribute.' deve ser uma URL válida ou um arquivo válido.');
    }
}

if (!function_exists('salvaImagem')) {
    function salvaImagem(Model $model, string $tabela)
    {
        $isAStorageFile = Str::contains($model->imagem, 'storage');
        if ($model->imagem && $isAStorageFile) {
            [$url, $storagePath] = explode('/storage/', $model->imagem);
            $isInOurEnd = Storage::disk('public')->exists($storagePath);
            $isTheSameFile = request()->imagem == $model->imagem;
            if ($isInOurEnd && !$isTheSameFile) Storage::disk('public')->delete($storagePath);
        }

        $data = request()->hasFile('imagem')
            ? request()->except('imagem')
            : request()->all();
        $model->update($data);

        if (request()->hasFile('imagem')) {
            $path = request()->imagem->store($tabela, 'public');
            $model->imagem = env('APP_URL') . "/storage/" . $path;
            $model->save();
        }
    }
}
