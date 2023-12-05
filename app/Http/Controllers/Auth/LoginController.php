<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Trata;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class LoginController extends Controller
{
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

    protected function login(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required']
            ]);

            $request->only('email', 'password');

            $user = User::where('email', '=', $request->email)->first();
            if (!$user) throw ValidationException::withMessages([
                'email' => ['Email não cadastrado']
            ]);

            $passwordChecked = Hash::check($request->password, $user->password);
            if (!$passwordChecked) throw ValidationException::withMessages([
                'password' => ['Senha inválida']
            ]);

            $user->tokens()->delete();

            $token = $user->createToken($request->email)->plainTextToken;

            return $token;
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }

    public function logout(Request $request)
    {
        try {
            $accessToken = $request->bearerToken();
            $token = PersonalAccessToken::findToken($accessToken);
            $loggedOut = $token->delete();

            return $loggedOut;
        } catch (\Throwable $th) {
            $mensagem = Trata::erro($th);
            return $mensagem;
        }
    }
}
