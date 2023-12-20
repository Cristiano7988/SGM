<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Trata;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class LoginController extends Controller
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
            'email' => ['required'],
            'password' => ['required'],
        ]);
    }
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    protected function loginViaAPI(Request $request):Response
    {
        try {
            // Aqui validamos os dados da requisição
            $validator = $this->validator($request->only('email', 'password'));
            if ($validator->fails()) return response($validator->errors(), 422);

            $user = User::where('email', $request->email)->first();
            if (!$user) return response('Email não cadastrado', 403);

            $passwordChecked = Hash::check($request->password, $user->password);
            if (!$passwordChecked) return response('Senha inválida', 403);

            $user->tokens()->delete();

            $token = $user->createToken($request->email)->plainTextToken;

            return response($token);
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    public function logoutViaApi(Request $request)
    {
        try {
            $accessToken = $request->bearerToken();
            $token = PersonalAccessToken::findToken($accessToken);
            $token->delete();

            return response("Volte sempre!");
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
