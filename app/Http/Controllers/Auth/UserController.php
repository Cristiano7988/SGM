<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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
    protected function index() {
        try {
            $user = Auth::user();
            $tipo_id = request()->tipo_id;

            if (!$user->is_admin && !$tipo_id) $users = $user->alunos->first()->users;
            if (!$user->is_admin && $tipo_id) {
                $users = User::whereHas('tipos', function ($query) {
                    $tipo_id = request()->tipo_id;
                    $ids = Auth::user()->alunos->first()->users->pluck('id')->toArray();
                    $query->where('tipo_id', '=',  $tipo_id)->whereIn('user_id', $ids);
                })->get();
            }

            if ($user->is_admin && !$tipo_id) $users = User::paginate(10);
            if ($user->is_admin && $tipo_id) {
                $users = User::whereHas('tipos', function ($query) {
                    $tipo_id = request()->tipo_id;
                    $query->where('tipo_id', '=',  $tipo_id);
                })->get();
            }

            return $users;
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

            DB::beginTransaction();
            if (!!$request['password']) $request['password'] = Hash::make($request['password']);
            $newUser = User::create($request->all());
            
            if (isset($request['tipos']) && !!count($request['tipos'])) $newUser->tipos()->attach($request['tipos']);
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
