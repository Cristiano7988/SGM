<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Trata;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
    protected $mensagem = "";

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
            'nome' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  Request  $request
     */
    protected function create(Request $request)
    {
        try {
            // Aqui validamos os dados da requisição
            $validator = $this->validator($request->all());
            if ($validator->fails()) return response($validator->errors(), 422);
            
            DB::beginTransaction();
            $request['password'] = Hash::make($request['password']);
            $user = User::create($request->all());
            DB::commit();

            return $user;
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            
            $api = in_array('api', request()->route()->middleware());
            $this->mensagem = $mensagem;
            if ($api) return $mensagem;
        }
    }
}
