<?php

namespace App\Http\Controllers;

use App\Helpers\Substitui;
use App\Mail\EmailGenerico;
use App\Mail\TodasTransacoes;
use App\Models\Email;
use App\Models\User;
use Illuminate\Http\Request;
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
    public function index()
    {
        try {
            $emails = Email::paginate(10);
            return $emails;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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

            return $email;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function show(Email $email)
    {
        try {
            return $email;
        } catch (\Throwable $th) {
            return $th->getMessage();
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
    public function send(Request $request, Email $email)
    {
        try {
            $user = User::find($request->user_id);
            if (!$user) return response("Usuário não encontrado");
            
            $conteudo = new EmailGenerico(
                $request,
                $user,
                $email->conteudo,
                $email->anexo
            );

            $user->name = $user->nome;
            $conteudo->subject = Substitui::masAntesChecaSePrecisa($request, $email->assunto);;

            Mail::to($user)->send($conteudo);            

            return response()->json($email);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Envia as transações por e-mail conforme especificado na requisição
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send_transactions(Request $request)
    {
        try {
            $user = User::find($request->user_id);

            if (!$user) return response("Usuário não encontrado", 404);
            if (!$request->ids) return response("Selecione as transações a serem enviadas");
            
            $conteudo = new TodasTransacoes($request);
            $user->name = $user->nome;

            Mail::to($user)->send($conteudo);            

            return response()->json($user);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function edit(Email $email)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Email $email)
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
            
            return $email;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function destroy(Email $email)
    {
        try {
            DB::beginTransaction();
            Storage::delete($email->anexo);
            $deleted = $email->delete();
            DB::commit();
            return !!$deleted;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
