<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
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
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Show all users.
     *
     * @return \App\Models\User
     */
    protected function index(Request $request) {
        try {
            $user = Auth::user();
            $usuarios = explode(',', $request['usuarios']);
            $tipos = explode(',', $request['tipos']);
            $alunos = explode(',', $request['alunos']);
            $transacoes = explode(',', $request['transacoes']);
            $matriculas = explode(',', $request['matriculas']);
            $cupons = explode(',', $request['cupons']);
            $medidas = explode(',', $request['medidas']);

            // Conecta todas tabelas que possuem relaão direta com o usuário
            $usuariosFiltrados = User::leftJoin('aluno_user', 'users.id', '=', 'aluno_user.user_id' )
                ->leftJoin('alunos', 'aluno_user.aluno_id', '=', 'alunos.id')
                ->leftJoin('tipo_user', 'users.id', '=', 'tipo_user.user_id' )
                ->leftJoin('tipos', 'tipo_id', '=', 'tipos.id')
                ->leftJoin('transacao', 'users.id', '=', 'transacao.user_id')
                ->leftJoin('cupons', 'transacao.cupom_id', '=', 'cupons.id')
                ->leftJoin('matriculas', 'transacao.matricula_id', '=', 'matriculas.id')
                ->leftJoin('medidas', 'cupons.medida_id', '=', 'medidas.id')
                ->get([
                    // Users
                    'users.*',
                    'users.id as user_id',
                    // Alunos
                    'alunos.id as aluno_id',
                    'alunos.nome as aluno',
                    'alunos.data_de_nascimento',
                    // Tipos
                    'tipos.nome as tipo',
                    'tipo_user.tipo_id',
                    // Transações
                    'transacao.id as transacao_id',
                    'transacao.matricula_id as matricula_id',
                    'transacao.comprovante',
                    'transacao.valor_pago',
                    'transacao.desconto_aplicado',
                    'transacao.data_de_pagamento',
                    'transacao.obs',
                    'transacao.forma_de_pagamento',
                    'transacao.nome_do_aluno',
                    'transacao.nome_do_usuario',
                    'transacao.nome_do_pacote',
                    'transacao.vigencia_do_pacote',
                    'transacao.valor_do_pacote',
                    // Matrículas
                    'matriculas.id as matricula_id',
                    'matriculas.aluno_id as matricula_aluno_id',
                    'matriculas.pacote_id as matricula_pacote_id',
                    'matriculas.turma_id as matricula_turma_id',
                    'matriculas.situacao_id',
                    'matriculas.marcacao_id',
                    // Cupons
                    'cupons.id as cupom_id',
                    'cupons.codigo',
                    'cupons.desconto',
                    // Medidas
                    'medidas.id as medida_id',
                    'medidas.tipo'
                ]);

            if (!$user->is_admin) $usuariosFiltrados = $usuariosFiltrados->whereIn('aluno_id', $user->alunos->pluck('id')); // Aqui é estabelecido que o usuário poderá acessar apenas informações relacionadas ao aluno ao qual está associado
                
            if ($request['usuarios']) $usuariosFiltrados = $request['usuarios'] == '*'
                ? $usuariosFiltrados->whereNotNull('user_id') // Filtra usuários que tenham algum aluno
                : $usuariosFiltrados->whereIn('user_id', $usuarios); // Filtra usuários pelos alunos especificados
            
            if ($request['alunos']) $usuariosFiltrados = $request['alunos'] == '*'
                ? $usuariosFiltrados->whereNotNull('aluno_id') // Filtra usuários que tenham algum aluno
                : $usuariosFiltrados->whereIn('aluno_id', $alunos); // Filtra usuários pelos alunos especificados

            if ($request['tipos']) $usuariosFiltrados = $request['tipos'] == '*'
                ? $usuariosFiltrados->whereNotNull('tipo_id')
                : $usuariosFiltrados->whereIn('tipo_id', $tipos);

            if ($request['transacoes']) $usuariosFiltrados = $request['transacoes'] == '*'
                ? $usuariosFiltrados->whereNotNull('transacao_id')
                : $usuariosFiltrados->whereNotNull('transacao_id')->whereIn('transacao_id', $transacoes);

            if ($request['matriculas']) $usuariosFiltrados = $request['matriculas'] == '*'
                ? $usuariosFiltrados->whereNotNull('matricula_id')
                : $usuariosFiltrados->whereIn('matricula_id', $matriculas);

            if ($request['cupons']) $usuariosFiltrados = $request['cupons'] == '*'
                ? $usuariosFiltrados->whereNotNull('cupom_id')
                : $usuariosFiltrados->whereNotNull('cupom_id')->whereIn('cupom_id', $cupons);

            if ($request['medidas']) $usuariosFiltrados = $request['medidas'] == '*'
                ? $usuariosFiltrados
                : $usuariosFiltrados->whereIn('medida_id', $medidas);
    
            $ids = $usuariosFiltrados->pluck('id');
            $users = User::whereIn('id', $ids);

            if ($request['alunos']) $users = $users->with('alunos'); // Retorna usuários com seus alunos
            if ($request['tipos']) $users = $users->with('tipos');

            if ($request['transacoes']) $users = $users->with([
                'transacoes' => function ($transacao) use ($request, $transacoes, $matriculas, $cupons, $medidas) {
                    if ($request['matriculas']) $transacao = $request['matriculas'] == '*'
                        ? $transacao->with('matricula')
                        : $transacao->with('matricula')->whereIn('id', $matriculas);
                        
                    if ($request['cupons']) $transacao = $request['cupons'] == '*'
                        ? $transacao->whereNotNull('cupom_id')->with([
                            'cupom' => function ($cupom) use ($request, $medidas) {

                                if ($request['medidas']) $cupom = $request['medidas'] == '*'
                                    ? $cupom->with('medida')
                                    : $cupom->with('medida')->whereIn('medida_id', $medidas);
                                return $cupom;
                            }
                        ])
                        : $transacao->with([
                            'cupom' => function ($cupom) use ($request, $medidas) {

                                if ($request['medidas']) $cupom = $request['medidas'] == '*'
                                    ? $cupom->with('medida')
                                    : $cupom->with('medida')->whereIn('medida_id', $medidas);
                                return $cupom;
                            }
                        ])->whereIn('cupom_id', $cupons);
                        
                    if ($request['transacoes'] !== '*') $transacao = $transacao->whereIn('id', $transacoes);
                        
                    return $transacao;
                }
            ]);
            
            $users = $users->get();

            return response()->json($users);

        } catch(\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  Request  $request
     * @return \App\Models\User
     */
    protected function store(Request $request)
    {
        try {
            $user = Auth::user();
            $senhaAleatoria = Str::random(8);
            $senhaCriptografada = $request['password'] || $senhaAleatoria;
            $request['password'] = Hash::make($senhaCriptografada);

            DB::beginTransaction();
            $newUser = User::create($request->all());
            if (isset($request['tipos']) && !!count($request['tipos'] ?? [])) $newUser->tipos()->attach($request['tipos']);
            $newUser->alunos()->attach($user->alunos);
            DB::commit();
    
            return $newUser;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Show an user.
     *
     * @param  User  $user
     * @return \App\Models\User
     */
    protected function show(User $user)
    {
        try {
            return $user;
        } catch(\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Update an user.
     *
     * @param  User  $user
     * @param  Request  $request
     * @return \App\Models\User
     */
    protected function update(User $user, Request $request) {
        try {
            DB::beginTransaction();
            if (!!$request['password']) $request['password'] = Hash::make($request['password']);
            $user->update($request->all());

            if (isset($request['tipos']) && !!count($request['tipos'])) {
                $user->tipos()->detach();
                $user->tipos()->attach($request['tipos']);
            }
            if (isset($request['alunos']) && !!count($request['alunos'])) {
                $user->alunos()->detach();
                $user->alunos()->attach($request['alunos']);
            }
            DB::commit();
    
            return $user;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Delete an user.
     *
     * @param  User  $user
     * @return Boolean
     */
    protected function delete(User $user) {
        try {
            DB::beginTransaction();
            $user->tipos()->detach();
            $user->alunos()->detach();
            $deleted = $user->delete();
            DB::commit();
        
            return !!$deleted;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
