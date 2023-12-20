<?php

namespace App\Http\Controllers;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Models\Matricula;
use App\Models\Transacao;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TransacaoController extends Controller
{
        /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'user_id' => ['required'],
            'comprovante' => [
                'required',
                'file',
                'mimes:jpeg,jpg,png,gif',
                'max:20000' // No máximo 20MB
            ],
            'forma_de_pagamento_id' => ['required'],
            'data_de_pagamento' => [
                'required',
                'date',
                'date_format:Y-m-d',
                'before_or_equal:' . date('d/m/Y') // Deve ser anterior ou igual a data atual
            ]
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index():Response
    {
        try {
            extract(request()->all());
            $transacoes = Transacao::query();

            $transacoes
                ->leftJoin('matriculas', 'transacoes.matricula_id', 'matriculas.id')
                ->leftJoin('users', 'transacoes.user_id', 'users.id')
                ->leftJoin('cupons', 'transacoes.cupom_id', 'cupons.id')
                ->leftJoin('formas_de_pagamento', 'transacoes.forma_de_pagamento_id', 'formas_de_pagamento.id')
                ->select(['transacoes.*'])->groupBy('transacoes.id');

            if (isset($matriculas)) $transacoes = Filtra::resultado($transacoes, $matriculas, 'matriculas.id')->with('matricula');
            if (isset($users)) $transacoes = Filtra::resultado($transacoes, $users, 'users.id')->with('user');
            if (isset($cupons)) $transacoes = Filtra::resultado($transacoes, $cupons, 'cupons.id')->with('cupom');
            if (isset($formas_de_pagamento)) $transacoes = Filtra::resultado($transacoes, $formas_de_pagamento, 'formas_de_pagamento.id'); // Transação COM forma de pagamento vem por padrão da model.

            $transacoes = Trata::resultado($transacoes, 'transacoes.data_de_pagamento'); // Ordenação por transação, cupom, matrícula ou forma de pagamento.

            return response($transacoes);
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
            // Aqui validamos os dados da requisição
            $validator = $this->validator($request->all());
            if ($validator->fails()) return response($validator->errors(), 422);
            if (!$request->hasFile('comprovante')) return response('Parece que não foi carregado o comprovante da transação. Caso tenha carregado experimente salvar em um novo formato e enviar novamente.', 404);

            // Aqui validamos o usuário que realizará a transação
            $authUser = Auth::user();
            $user = User::find($request->user_id);
            $matricula = Matricula::find($request->matricula_id);
            if (!$authUser->is_admin) {
                if (!$user->alunos->count()) return response('Adicione um aluno antes de prosseguir.', 403); // Validação necessária pois são os alunos que ligam usuários a outros usuários!
                
                // Se ele tem relação com o usuário logado
                $usuariosRelacionados = false;
                foreach($user->alunos as $aluno) if (in_array($authUser->id, $aluno->users->pluck('id')->toArray())) $usuariosRelacionados = true;
                if (!$usuariosRelacionados) return response('Você não tem permissão para realizar uma transação em nome desse usuário.', 403);

                // Se ele tem relação com o aluno que foi matriculado
                $alunoRelacionado = false;
                if (in_array($authUser->id, $matricula->aluno->users->pluck('id')->toArray())) $alunoRelacionado = true;
                if (!$alunoRelacionado) return response('Você não tem permissão para realizar uma transação para esta matrícula.', 403);
            }

            // Aqui checamos se a transação está sendo duplicada
            $transacao = Transacao::query()
            ->where('matricula_id', $request->matricula_id)
            ->where('data_de_pagamento', $request->data_de_pagamento)
            ->where('valor_pago', $request->valor_pago)
            ->first();
            
            if ($transacao) return response('Uma transação de mesmo valor foi realizada para esta matrícula nessa data.', 403);
        
            // Aqui realizamos a transação
            DB::beginTransaction();
            $transacao = Transacao::create($request->except('comprovante'));
            if (isset($request->comprovante)) {
                $path = $request->comprovante->store('comprovantes');
                $transacao->comprovante = $path;
                $transacao->save();
            }
            DB::commit();

            return response($transacao);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transacao  $transacao
     * @return \Illuminate\Http\Response
     */
    public function show(Transacao $transacao):Response
    {
        try {
            return response($transacao);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transacao  $transacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transacao $transacao):Response
    {
        try {
            DB::beginTransaction();
            $transacao->update($request->except('comprovante'));
            if (isset($request->comprovante)) {
                Storage::delete($transacao->comprovante);
                $path = $request->comprovante->store('comprovantes');
                $transacao->comprovante = $path;
                $transacao->save();
            }

            DB::commit();
            return response($transacao);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transacao  $transacao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transacao $transacao):Response
    {
        try {
            DB::beginTransaction();
            Storage::delete($transacao->comprovante);
            $transacao->delete();
            DB::commit();
    
            return response("A transação de nº {$transacao->id}, do usuário {$transacao->user->nome} feita no dia {$transacao->data_de_pagamento},  foi deletada.");;
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
