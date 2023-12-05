<?php

namespace App\Helpers;

use App\Mail\AvisoDeErro;
use App\Models\Erro;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class Trata
{
    public static function resultado(Builder $table, string $padrao)
    {
        extract(request()->all());

        $order_by = $order_by ?? $padrao;
        $sort = $sort ?? 'asc';
        $per_page = $per_page ?? 10;

        $table = $table->orderBy($order_by, $sort)->paginate($per_page);
        
        return $table;
    }

    public static function erro($th)
    {
        DB::rollBack();
        $user = Auth::user();

        DB::beginTransaction();
        $erro = Erro::create([
            'user_id' => $user->id,
            'rota' => $_SERVER['REQUEST_URI'],
            'metodo' => $_SERVER['REQUEST_METHOD'],
            'acessado_via' => request()->userAgent(),
            'corpo_da_requisicao' => request()->getContent(),
            'mensagem' => $th->getMessage(),
            'codigo' => $th->getCode(),
            'arquivo' => $th->getFile(),
            'linha' => $th->getLine(),
            'vestigio' => $th->getTraceAsString()
        ]);
        DB::commit();

        $desenvolvedor = User::find(1);
        $desenvolvedor->name = $desenvolvedor->nome;

        $email = new AvisoDeErro($user, $erro);
        $email->subject = "O usuário {$user->nome} registrou o erro de nº {$erro->id}!";
        Mail::to($desenvolvedor)->send($email);

        return response("Não foi possível prosseguir com esta ação!\n\nJá registramos essa ocorrência e nossa equipe de desenvolvimento já foi informada.\nEm breve entraremos em contato.\n\nObrigado pela compreensão!", 500);
    }
}
