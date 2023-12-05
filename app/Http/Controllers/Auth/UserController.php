<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Filtra;
use App\Helpers\Trata;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
    protected function index():Response
    {
        try {

            $user = Auth::user();
            extract(request()->all());
            /**
             * $transacoes_feitas_pelo_usuario
             * Variável que alteram o circuito relacional das informações requisitadas para pegar
             * usuários que fizeram transações
             * ou
             * usuários que tenham alunos matrículados cuja as transações foram realizadas
             */
            
            // Conecta todas tabelas (exceto a relação pacotes -> núcleos)
            $users = User::query();
            
                // Tipos
            $users
                ->leftJoin('tipo_user', 'users.id', 'tipo_user.user_id' )
                ->leftJoin('tipos', 'tipo_user.tipo_id', 'tipos.id')
                // Emails
                ->leftJoin('email_user', 'users.id', 'email_user.user_id')
                ->leftJoin('emails', 'email_user.email_id', 'emails.id')
                // Alunos
                ->leftJoin('aluno_user', 'users.id', 'aluno_user.user_id' )
                ->leftJoin('alunos', 'aluno_user.aluno_id', 'alunos.id')
                // Transações
                ->leftJoin('transacoes', 'users.id', 'transacoes.user_id')
                ->leftJoin('formas_de_pagamento', 'transacoes.forma_de_pagamento_id', 'formas_de_pagamento.id')
                ->leftJoin('cupons', 'transacoes.cupom_id', 'cupons.id')
                ->leftJoin('medidas', 'cupons.medida_id', 'medidas.id')
                // Matrículas
                ->leftJoin('matriculas', isset($transacoes_feitas_pelo_usuario) ? 'transacoes.matricula_id' : 'alunos.id', isset($transacoes_feitas_pelo_usuario) ? 'matriculas.id' : 'matriculas.aluno_id')
                ->leftJoin('pacotes', 'matriculas.pacote_id', 'pacotes.id')
                ->leftJoin('periodos', 'periodos.pacote_id', 'pacotes.id')
                ->leftJoin('situacoes', 'matriculas.situacao_id', 'situacoes.id')
                ->leftJoin('marcacoes', 'matriculas.marcacao_id', 'marcacoes.id')
                // Turmas
                ->leftJoin('turmas', 'matriculas.turma_id', 'turmas.id')
                ->leftJoin('tipos_de_aula', 'turmas.tipo_de_aula_id', 'tipos_de_aula.id')
                ->leftJoin('dias', 'turmas.dia_id', 'dias.id')
                // Núcleos
                ->leftJoin('nucleos', 'turmas.nucleo_id', 'nucleos.id')
                ->leftJoin('idades_minimas', 'nucleos.idade_minima_id', 'idades_minimas.id')
                ->leftJoin('medidas_de_tempo as m_min', 'idades_minimas.medida_de_tempo_id', 'm_min.id')
                ->leftJoin('idades_maximas', 'nucleos.idade_maxima_id', 'idades_maximas.id')
                ->leftJoin('medidas_de_tempo as m_max', 'idades_maximas.medida_de_tempo_id', 'm_max.id')
                ->select(['users.*'])->groupBy('users.id');

            // Aqui é estabelecido que o usuário poderá acessar apenas informações relacionadas ao aluno ao qual está associado
            if (!$user->is_admin) $users = $users->whereIn('alunos.id', $user->alunos->pluck('id'));
            
            // Aqui filtramos os usuários de acordo com suas relações
            if (isset($usuarios))                       $users = Filtra::resultado($users, $usuarios, 'users.id');
            if (isset($tipos))                          $users = Filtra::resultado($users, $tipos, 'tipo_id')->with('tipos');
            if (isset($emails))                         $users = Filtra::resultado($users, $emails, 'emails.id')->with('emails:id,assunto,anexo');
            if (isset($transacoes_feitas_pelo_usuario)) $users = Filtra::resultado($users, $transacoes_feitas_pelo_usuario, 'transacoes.id');
            else if (isset($transacoes))                $users = Filtra::resultado($users, $transacoes, 'transacoes.id');
            if (isset($formas_de_pagamento))            $users = Filtra::resultado($users, $formas_de_pagamento, 'formas_de_pagamento.id');
            if (isset($cupons))                         $users = Filtra::resultado($users, $cupons, 'cupons.id');
            if (isset($medidas))                        $users = Filtra::resultado($users, $medidas, 'medidas.id');
            if (isset($matriculas))                     $users = Filtra::resultado($users, $matriculas, 'matriculas.id');
            if (isset($pacotes))                        $users = Filtra::resultado($users, $pacotes, 'pacotes.id');
            if (isset($periodos))                       $users = Filtra::resultado($users, $periodos, 'periodos.id');
            if (isset($situacoes))                      $users = Filtra::resultado($users, $situacoes, 'situacoes.id');
            if (isset($marcacoes))                      $users = Filtra::resultado($users, $marcacoes, 'marcacoes.id');
            if (isset($turmas))                         $users = Filtra::resultado($users, $turmas, 'turmas.id');
            if (isset($tipos_de_aula))                  $users = Filtra::resultado($users, $tipos_de_aula, 'tipos_de_aula.id');
            if (isset($dias))                           $users = Filtra::resultado($users, $dias, 'dias.id');
            if (isset($nucleos))                        $users = Filtra::resultado($users, $nucleos, 'nucleos.id');
            if (isset($alunos))                         $users = Filtra::resultado($users, $alunos, 'alunos.id');
            if (isset($idades_minimas))                 $users = Filtra::resultado($users, $idades_minimas, 'idades_minimas.id');
            if (isset($idades_maximas))                 $users = Filtra::resultado($users, $idades_maximas, 'idades_maximas.id');
            if (isset($medidas_minimas_de_tempo))       $users = Filtra::resultado($users, $medidas_minimas_de_tempo, 'm_min.id');
            if (isset($medidas_maximas_de_tempo))       $users = Filtra::resultado($users, $medidas_maximas_de_tempo, 'm_max.id');

            // Aqui retornamos as informações requisitadas no formato de eager Loading
            // Tabelas renomeadas não tem efeito a partir daqui
            if (isset($transacoes_feitas_pelo_usuario)) $users->with([
                'transacoes' => function ($query) {
                    extract(request()->all());
                    if ($transacoes_feitas_pelo_usuario != '*') $query->whereIn('transacoes.id', explode(',', $transacoes_feitas_pelo_usuario));
                    // Formas de pagamento vem por padrão da model

                    // Cupons
                    if (isset($cupons)) {
                        $query->whereNotNull('cupom_id');

                        // With
                        $query->with([
                            'cupom' => function ($query) {
                                extract(request()->all());
                                if ($cupons != '*') $query->whereIn('cupons.id', explode(',', $cupons));
                                // Medidas vem por padrão da model
                            }
                        ]);
                    }

                    // Matrículas
                    if (isset($matriculas)) {
                        $query->whereNotNull('matricula_id');

                        // With
                        $query->with([
                            'matricula' => function ($query) {
                                extract(request()->all());
                                if ($matriculas != '*') $query->whereIn('matriculas.id', explode(',', $matriculas));

                                // Alunos
                                if (isset($alunos)) {
                                    $query->whereNotNull('aluno_id');

                                    // With
                                    $query->with([
                                        'aluno' => function ($query) {
                                            extract(request()->all());
                                            if ($alunos != '*') $query->whereIn('alunos.id', explode(',', $alunos));
                                        }
                                    ]);
                                }

                                // Situações
                                if (isset($situacoes)) {
                                    $query->whereNotNull('situacao_id');

                                    // With
                                    $query->with([
                                        'situacao' => function ($query) {
                                            extract(request()->all());
                                            if ($situacoes != '*') $query->whereIn('situacoes.id', explode(',', $situacoes));
                                        }
                                    ]);
                                }

                                // Marcações
                                if (isset($marcacoes)) {
                                    $query->whereNotNull('marcacao_id');

                                    // With
                                    $query->with([
                                        'marcacao' => function ($query) {
                                            extract(request()->all());
                                            if ($marcacoes != '*') $query->whereIn('marcacoes.id', explode(',', $marcacoes));
                                        }
                                    ]);
                                }

                                // Pacotes
                                if (isset($pacotes)) {
                                    $query->whereNotNull('pacote_id');

                                    // With
                                    $query->with([
                                        'pacote' => function ($query) {
                                            extract(request()->all());
                                            if ($pacotes != '*') $query->whereIn('pacotes.id', explode(',', $pacotes));

                                            // Períodos
                                            if (isset($periodos)) {
                                                $query->whereNotNull('periodo_id');

                                                // With
                                                $query->with([
                                                    'periodos' => function ($query) {
                                                        extract(request()->all());
                                                        if ($periodos != '*') $query->whereIn('periodos.id', explode(',', $periodos));
                                                    }
                                                ]);
                                            }
                                        }
                                    ]);
                                }

                                // Turmas
                                if (isset($turmas)) {
                                    $query->whereNotNull('turma_id');

                                    // With
                                    $query->with([
                                        'turma' => function ($query) {
                                            extract(request()->all());
                                            if ($turmas != '*') $query->whereIn('turmas.id', explode(',', $turmas));
                                            // Tipos de aula e Dias vem por padrão da model

                                            // Núcleos
                                            if (isset($nucleos)) {
                                                $query->whereNotNull('nucleo_id');

                                                // With
                                                $query->with([
                                                    'nucleo' => function ($query) {
                                                        extract(request()->all());
                                                        if ($nucleos != '*') $query->whereIn('nucleos.id', explode(',', $nucleos));
                                                        // Idades Mínimas e Máximas vem por padrão da Model
                                                    }
                                                ]);
                                            }
                                        }
                                    ]);
                                }
                            }
                        ]);
                    }
                }
            ]);
            // Alunos do usuário
            else if (isset($alunos)) $users->with([
                'alunos' => function ($query) {
                    extract(request()->all());
                    if ($alunos != '*') $query->whereIn('alunos.id', explode(',', $alunos));

                    // Matrículas
                    if (isset($matriculas)) {
                        $query->whereNotNull('matricula_id');

                        // With
                        $query->with([
                            'matriculas' => function ($query) {
                                extract(request()->all());
                                if ($matriculas != '*') $query->whereIn('matriculas.id', explode(',', $matriculas));

                                // Situações
                                if (isset($situacoes)) {
                                    $query->whereNotNull('situacao_id');

                                    // With
                                    $query->with([
                                        'situacao' => function ($query) {
                                            extract(request()->all());
                                            if ($situacoes != '*') $query->whereIn('situacoes.id', explode(',', $situacoes));
                                        }
                                    ]);
                                }

                                // Marcações
                                if (isset($marcacoes)) {
                                    $query->whereNotNull('marcacao_id');

                                    // With
                                    $query->with([
                                        'marcacao' => function ($query) {
                                            extract(request()->all());
                                            if ($marcacoes != '*') $query->whereIn('marcacoes.id', explode(',', $marcacoes));
                                        }
                                    ]);
                                }

                                // Pacotes
                                if (isset($pacotes)) {
                                    $query->whereNotNull('pacote_id');

                                    // With
                                    $query->with([
                                        'pacote' => function ($query) {
                                            extract(request()->all());
                                            if ($pacotes != '*') $query->whereIn('pacotes.id', explode(',', $pacotes));

                                            // Períodos
                                            if (isset($periodos)) {
                                                $query->whereNotNull('periodo_id');

                                                // With
                                                $query->with([
                                                    'periodos' => function ($query) {
                                                        extract(request()->all());
                                                        if ($periodos != '*') $query->whereIn('periodos.id', explode(',', $periodos));
                                                    }
                                                ]);
                                            }
                                        }
                                    ]);
                                }

                                // Turmas
                                if (isset($turmas)) {
                                    $query->whereNotNull('turma_id');

                                    // With
                                    $query->with([
                                        'turma' => function ($query) {
                                            extract(request()->all());
                                            if ($turmas != '*') $query->whereIn('turmas.id', explode(',', $turmas));
                                            // Tipos de aula e Dias vem por padrão da model

                                            // Núcleos
                                            if (isset($nucleos)) {
                                                $query->whereNotNull('nucleo_id');

                                                // With
                                                $query->with([
                                                    'nucleo' => function ($query) {
                                                        extract(request()->all());
                                                        if ($nucleos != '*') $query->whereIn('nucleos.id', explode(',', $nucleos));
                                                        // Idades Mínimas e Máximas vem por padrão da Model
                                                        // Pacotes do núcleo não adicionado
                                                    }
                                                ]);
                                            }
                                        }
                                    ]);
                                }

                                // Transações
                                if (isset($transacoes)) {
                                    $query->whereNotNull('turma_id');

                                    // With
                                    $query->with([
                                        'transacoes' => function ($query) {
                                            extract(request()->all());
                                            if ($transacoes != '*') $query->whereIn('transacoes.id', explode(',', $transacoes));
                                            // Formas de pagamento vem por padrão da model
                                        }
                                    ]);
                                }
                            }
                        ]);
                    }
                }
            ]);

            $users = Trata::resultado($users, 'users.nome'); // Ordenação por usuário ou por transação (bem como por seu cupom, forma de pagamento ou matrícula) feitas pelo usuário.

            return response($users);
        } catch(\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  Request  $request
     * @return \App\Models\User
     */
    protected function store(Request $request):Response
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
    
            return response($newUser);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Show an user.
     *
     * @param  User  $user
     * @return \App\Models\User
     */
    protected function show(User $user):Response
    {
        try {
            return response($user);
        } catch(\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Update an user.
     *
     * @param  User  $user
     * @param  Request  $request
     * @return \App\Models\User
     */
    protected function update(User $user, Request $request):Response
    {
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
    
            return response($user);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    /**
     * Delete an user.
     *
     * @param  User  $user
     * @return Boolean
     */
    protected function delete(User $user):Response
    {
        try {
            DB::beginTransaction();
            $user->tipos()->detach();
            $user->alunos()->detach();
            $excluido = Trata::exclusao($user, 'Usuário');
            if ($excluido) DB::commit(); // Exclui somente se conseguir notificar o cliente
        
            return response("O usuário de nº {$user->id}, {$user->nome},  foi deletado.");
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
