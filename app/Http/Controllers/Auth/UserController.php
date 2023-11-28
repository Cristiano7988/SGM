<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Adiciona;
use App\Helpers\Filtra;
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
            extract(request()->all());
            /**
             * Variáves que alteram o circuito relacional das informações requisitadas para pegar
             * usuários que fizeram transações
             * ou
             * usuários que tenham alunos matrículados cuja as transações foram realizadas
             */
            $que_fizeram_transacoes = isset($que_fizeram_transacoes) && isset($transacoes);
            $nucleo_do_pacote = isset($nucleo_do_pacote); // Dissocia o núcleo do pacote do núcleo da turma, embora sejam o mesmo na requisição a específicação pode surgir

            // Conecta todas tabelas que possuem relação direta com o usuário
            $usuariosFiltrados = User::leftJoin('tipo_user', 'users.id', '=', 'tipo_user.user_id' )
                ->leftJoin('tipos', 'tipo_id', '=', 'tipos.id')
                ->leftJoin('aluno_user', 'users.id', '=', 'aluno_user.user_id' )
                ->leftJoin('alunos', 'aluno_user.aluno_id', '=', 'alunos.id')
                ->leftJoin('transacao', 'users.id', '=', 'transacao.user_id')
                ->leftJoin('matriculas', $que_fizeram_transacoes ? 'transacao.matricula_id' : 'alunos.id', '=', $que_fizeram_transacoes ? 'matriculas.id' : 'matriculas.aluno_id')
                ->leftJoin('situacao', 'matriculas.situacao_id', '=', 'situacao.id')
                ->leftJoin('marcacao', 'matriculas.marcacao_id', '=', 'marcacao.id')
                ->leftJoin('pacotes', 'matriculas.pacote_id', '=', 'pacotes.id')
                ->leftJoin('periodos', 'periodos.pacote_id', '=', 'pacotes.id')
                ->leftJoin('turmas', 'matriculas.turma_id', '=', 'turmas.id')
                ->leftJoin('dias', 'turmas.dia_id', '=', 'dias.id')
                ->leftJoin('status', 'turmas.status_id', '=', 'status.id')
                ->leftJoin('nucleos', $nucleo_do_pacote ? 'pacotes.nucleo_id' : 'turmas.nucleo_id', '=', 'nucleos.id')
                ->leftJoin('idade_minima', 'nucleos.idade_minima_id', '=', 'idade_minima.id')
                ->leftJoin('medida_de_tempo as m_min', 'idade_minima.medida_de_tempo_id', '=', 'm_min.id')
                ->leftJoin('idade_maxima', 'nucleos.idade_maxima_id', '=', 'idade_maxima.id')
                ->leftJoin('medida_de_tempo as m_max', 'idade_maxima.medida_de_tempo_id', '=', 'm_max.id')
                ->leftJoin('cupons', 'transacao.cupom_id', '=', 'cupons.id')
                ->leftJoin('medidas', 'cupons.medida_id', '=', 'medidas.id')
                ->leftJoin('forma_de_pagamento', 'transacao.forma_de_pagamento_id', '=', 'forma_de_pagamento.id')
                ->leftJoin('email_user', 'users.id', '=', 'email_user.user_id')
                ->leftJoin('emails', 'email_id', '=', 'emails.id')
                ->get([
                    // Users
                    'users.*',
                    'users.id as user_id',
                    'alunos.id as aluno_id', // Alunos
                    'tipo_user.tipo_id', // Tipos
                    'transacao.id as transacao_id', // Transações
                    'matriculas.id as matricula_id', // Matrículas
                    'pacotes.id as pacote_id', // Pacotes
                    'periodos.id as periodo_id', // Periodos
                    'cupons.id as cupom_id', // Cupons
                    'medidas.id as medida_id', // Medidas
                    'forma_de_pagamento.id as forma_de_pagamento_id', // Formas de Pagamento
                    'turmas.id as turma_id', // Turmas
                    'dias.id as dia_id', // Dias
                    'status.id as status_id', // Status
                    'nucleos.id as nucleo_id', // Núcleos
                    'idade_minima.id as idade_minima_id', // Idade Mínima
                    'm_min.id as medida_minima_id', // Medida Mínima de Tempo
                    'idade_maxima.id as idade_maxima_id', // Idade Máxima
                    'm_max.id as medida_maxima_id', // Medida Mínima de Tempo
                    'situacao.id as situacao_id', // Situações
                    'marcacao.id as marcacao_id', // Marcações
                    'email_user.email_id'
                ]);

            if (!$user->is_admin) $usuariosFiltrados = $usuariosFiltrados->whereIn('aluno_id', $user->alunos->pluck('id')); // Aqui é estabelecido que o usuário poderá acessar apenas informações relacionadas ao aluno ao qual está associado

            // Aqui filtramos os usuários de acordo com suas relações
            if (isset($usuarios) && !isset($todos)) $usuariosFiltrados = Filtra::resultado($usuariosFiltrados, $usuarios, 'user_id');
            else if (isset($usuarios) && isset($todos)) $usuariosFiltrados = $usuariosFiltrados->whereNotIn('user_id', explode(',', $usuarios));
            
            if (isset($emails)) $usuariosFiltrados = Filtra::resultado($usuariosFiltrados, $emails, 'email_id');
            if (isset($tipos)) $usuariosFiltrados = Filtra::resultado($usuariosFiltrados, $tipos, 'tipo_id');
            if ($que_fizeram_transacoes) {
                $usuariosFiltrados = Filtra::resultado($usuariosFiltrados, $transacoes, 'transacao_id');
                if (isset($forma_de_pagamento)) $usuariosFiltrados = Filtra::resultado($usuariosFiltrados, $forma_de_pagamento, 'forma_de_pagamento_id');
                if (isset($cupons)) {
                    $usuariosFiltrados = Filtra::resultado($usuariosFiltrados, $cupons, 'cupom_id');
                    if (isset($medidas)) $usuariosFiltrados = Filtra::resultado($usuariosFiltrados, $medidas, 'medida_id');
                }
            }
            else if (isset($alunos)) {
                $usuariosFiltrados = Filtra::resultado($usuariosFiltrados, $alunos, 'aluno_id');
            }

            if (isset($matriculas) && (isset($alunos) || $que_fizeram_transacoes)) {
                $usuariosFiltrados = Filtra::resultado($usuariosFiltrados, $matriculas, 'matricula_id');
                if (isset($situacoes)) $usuariosFiltrados = Filtra::resultado($usuariosFiltrados, $situacoes, 'situacao_id');
                if (isset($marcacoes)) $usuariosFiltrados = Filtra::resultado($usuariosFiltrados, $marcacoes, 'marcacao_id');
    
                if (isset($turmas)) {
                    $usuariosFiltrados = Filtra::resultado($usuariosFiltrados, $turmas, 'turma_id');
                    if (isset($dias)) $usuariosFiltrados = Filtra::resultado($usuariosFiltrados, $dias, 'dia_id');
                    if (isset($status)) $usuariosFiltrados = Filtra::resultado($usuariosFiltrados, $status, 'status_id');
                }
                if (isset($pacotes)) {
                    $usuariosFiltrados = Filtra::resultado($usuariosFiltrados, $pacotes, 'pacote_id');
                    if (isset($periodos)) $usuariosFiltrados = Filtra::resultado($usuariosFiltrados, $periodos, 'periodo_id');
                }

                if (isset($nucleos) && (isset($turmas) || isset($pacotes))) {
                    $usuariosFiltrados = Filtra::resultado($usuariosFiltrados, $nucleos, 'nucleo_id');
                    if (isset($idade_minima)) $usuariosFiltrados = Filtra::resultado($usuariosFiltrados, $idade_minima, 'idade_minima_id');
                    if (isset($medida_minima_de_tempo)) $usuariosFiltrados = Filtra::resultado($usuariosFiltrados, $medida_minima_de_tempo, 'medida_minima_id');
                    if (isset($idade_maxima)) $usuariosFiltrados = Filtra::resultado($usuariosFiltrados, $idade_maxima, 'idade_maxima_id');
                    if (isset($medida_maxima_de_tempo)) $usuariosFiltrados = Filtra::resultado($usuariosFiltrados, $medida_maxima_de_tempo, 'medida_maxima_id');
                }
            }


            // Aqui retornamos as informações requisitadas no formato de eager Loading
            $ids = $usuariosFiltrados->pluck('id');
            $order = isset($order) ? $order : "asc";
            $orderBy = isset($orderBy) ? $orderBy : "nome";
            $users = User::whereIn('id', $ids)->orderBy($orderBy, $order);

            if (isset($tipos)) $users = $users->with('tipos');
            if (isset($emails)) $users = $users->with('emails:id,assunto,anexo');

            if ($que_fizeram_transacoes) $users = $users->with([
                'transacoes' => function ($transacao) use ($request) {
                    if ($request['transacoes'] !== '*') $transacao = $transacao->whereIn('id', explode(',', $request['transacoes']));
                    if ($request['matriculas']) $transacao = Adiciona::matriculas($request, $transacao, true);
                    if ($request['forma_de_pagamento']) $transacao = Adiciona::modelRelacionada($transacao, $request['forma_de_pagamento'], 'forma_de_pagamento_id', 'forma_de_pagamento');
                    if ($request['cupons'] && ($request['cupons'] !== '*')) $transacao = $transacao->whereIn('cupom_id', explode(',', $request['cupons']));

                    if ($request['cupons']) $transacao = $transacao->whereNotNull('cupom_id')->with([
                            'cupom' => function ($cupom) use ($request) {
                                if ($request['medidas']) $cupom = Adiciona::modelRelacionada($cupom, $request['medidas'], 'medida_id', 'medida');
                                return $cupom;
                            }
                        ]);
                        
                    return $transacao;
                }
            ]);
            else if (isset($alunos)) $users = $users->with([
                'alunos' => function ($aluno) use($request) {
                    if ($request['alunos'] !== '*') $aluno = $aluno->whereIn('aluno_id', explode(',', $request['alunos']));
                    if ($request['matriculas']) $aluno = Adiciona::matriculas($request, $aluno);

                    return $aluno;
                }
            ]);

            
            $users = isset($todos) ? $users->get() : $users->paginate(10);

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
