<?php

namespace App\Helpers;

use App\Mail\AvisoDeErro;
use App\Mail\BackupDeExcluidos;
use App\Models\Erro;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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
            'user_id' => $user ? $user->id : 'Ainda não cadastrado',
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
        $usuario = $user ? $user->nome : "Novo";
        $email->subject = "O usuário {$usuario} registrou o erro de nº {$erro->id}!";
        Mail::to($desenvolvedor)->send($email);

        $message = "Não foi possível prosseguir com esta ação!\n\nJá registramos essa ocorrência e nossa equipe de desenvolvimento já foi informada.\nEm breve entraremos em contato.\n\nObrigado pela compreensão!";

        return response($message, 500);
    }

    public static function exclusao(Model $item, string $tipo)
    {
        // Deleta o item
        $item->delete();

        try {
            // Notifica o cliente
            $cliente = User::find(2);
            $cliente->name = $cliente->nome;

            $email = new BackupDeExcluidos($item, $tipo);
            $email->subject = "Cópia de {$tipo} excluído(a)";

            Mail::to($cliente)->send($email);
            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
