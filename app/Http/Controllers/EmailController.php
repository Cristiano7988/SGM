<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Formata;
use App\Helpers\Substitui;
use App\Helpers\Trata;
use App\Mail\EmailGenerico;
use App\Mail\TodasTransacoes;
use App\Models\Email;
use App\Models\Transacao;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index():Response
    {
        try {
            extract(request()->all());
            $emails = Email::query();

            $emails
                ->leftJoin('email_user', 'emails.id', 'email_user.email_id')
                ->leftJoin('users', 'users.id', 'email_user.user_id')
                ->select(['emails.id as id', 'emails.assunto', 'emails.anexo'])->groupBy('emails.id');

            if (isset($users)) $emails = Filtra::resultado($emails, $users, 'users.id')->with([
                'users' => function ($query) use ($users) {
                    if ($users != '*') $query->whereIn('users.id', explode(',', $users));
                }
            ]);

            $emails = Trata::resultado($emails, 'emails.assunto'); // Ordenação por email e email_user.

            return response($emails);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request):Response
    {
        try {
            DB::beginTransaction();
            $email = Email::create($request->except('anexo'));

            if ($request->anexo) {
                $path = $request->anexo->store('anexos');
                $email->anexo = $path;
                $email->save();
            }
            DB::commit();

            return response($email);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function show(Email $email):Response
    {
        try {
            return response($email);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Envia email populado com as informações específicadas na requisição
     * de acordo com a convenção {{model-propriedade}} especificada no corpo ou assunto do email.
     * ex.: aluno-nome | matricula-nucleo-turma-horario
     *
     * @param  Illuminate\Http\Request  $request
     * @param  \App\Models\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request, Email $email):Response
    {
        try {
            if (!$request->destinatarios) return response("Informe algum destinatário", 403);
            
            $users = User::all();

            if ($request->destinatarios != '*') $users = $users->whereIn('id', explode(',', $request->destinatarios));
            
            $conteudo = new EmailGenerico(
                $request,
                $email->conteudo,
                $email->anexo
            );
            
            foreach ($users as $user) {
                if (!$user) return response("Usuário não encontrado");
                $conteudo->subject = Substitui::masAntesChecaSePrecisa($request, $email->assunto);;
                
                $user->name = $user->nome;
                Mail::to($user)->send($conteudo);
                $user->emails()->attach($email);

                $id_da_contadora = env('ACCOUNTANT_ID');
                if (($user->id == $id_da_contadora) && $request->transacao_id) {
                    $transacao = Transacao::find($request->transacao_id);
                    $transacao->update(['enviada_para_contadora' => true]);
                }
            }

            return response($email);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Envia as transações por e-mail conforme especificado na requisição
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendTransactions(Request $request):Response
    {
        try {
            $id_da_contadora = env('ACCOUNTANT_ID');
            $user = User::find($id_da_contadora);

            if (!$user) return response("Usuário não encontrado", 404);
            if (!$request->transacoes) return response("Selecione as transações a serem enviadas", 403);
            
            $transacoes = Transacao::all()->whereIn('id', explode(',', $request->transacoes));

            if (!$transacoes->count()) return response("Transações não encontradas", 404);
    
            foreach($transacoes as $index => $transacao) {
                foreach($transacao->matricula->pacote->periodos as $key => $periodo) {
                    $separador = $key ? " - " : "";
                    $transacoes[$index]['vigencia_do_pacote'] .= $separador . "De " . Formata::data($periodo->inicio) . " até " . Formata::data($periodo->fim);
                }
            }

            $conteudo = new TodasTransacoes($transacoes);
            $user->name = $user->nome;

            Mail::to($user)->send($conteudo);            

            foreach($transacoes as $index => $transacao) {
                unset($transacoes[$index]['vigencia_do_pacote']);
                $transacao->update(['enviada_para_contadora' => true]);
            }

            return response($user);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Email $email):Response
    {
        try {
            DB::beginTransaction();
            $email->update($request->except('anexo'));
            
            if ($request->anexo) {
                Storage::delete($email->anexo);
                $path = $request->anexo->store('anexos');
                $email->anexo = $path;
                $email->save();
            }
            DB::commit();
            
            return response($email);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function destroy(Email $email):Response
    {
        try {
            DB::beginTransaction();
            Storage::delete($email->anexo);
            $email->delete();
            DB::commit();

            return response("O Email de nº {$email->id}, {$email->assunto},  foi deletado.");
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
