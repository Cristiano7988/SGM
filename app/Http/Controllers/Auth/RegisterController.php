<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Show all users.
     *
     * @return \App\Models\User
     */
    protected function all() {
        $users = User::all();
        return $users;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  Request  $request
     * @return \App\Models\User
     */
    protected function create(Request $request)
    {
        DB::beginTransaction();
        $request['password'] = Hash::make($request['password']);
        $user = User::create($request->all());
        DB::commit();

        return $user;
    }

    /**
     * Show an user.
     *
     * @param  User  $user
     * @return \App\Models\User
     */
    protected function show(User $user)
    {
         return $user;
    }

    /**
     * Update an user.
     *
     * @param  User  $user
     * @param  Request  $request
     * @return \App\Models\User
     */
    protected function update(User $user, Request $request) {
        DB::beginTransaction();
        $request['password'] = Hash::make($request['password']);
        $user->update($request->all());
        DB::commit();

        return $user;
    }

    /**
     * Delete an user.
     *
     * @param  User  $user
     * @return Boolean
     */
    protected function delete(User $user) {
        DB::beginTransaction();
        $deleted = $user->delete();
        DB::commit();
    
        return !!$deleted;
    }
}
