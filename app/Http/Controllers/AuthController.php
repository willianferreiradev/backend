<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\SignupActivate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    private $fieldsValidate = [
        'email' => 'required|string|unique:users|email',
        'name' => 'required|string',
        'password' => 'required|string',
        'type' => 'required|string',
        'phone' => 'string',
    ];

    public function signup(Request $request)
    {
        $request->validate($this->fieldsValidate, [
            'email.required' => 'O e-mail é obrigatório'
        ]);

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $data['activation_token'] = Str::random(60);

        $user = User::create($data);
        $user->notify(new SignupActivate($user));

        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }

    public function signupActivate(String $token)
    {
        $user = User::where('activation_token', $token)->first();
        if (!$user) {
            return response()->json([
                'message' => 'This activation token is invalid.'
            ], 404);
        }
        $user->active = true;
        $user->activation_token = '';
        $user->save();
        return response()->json([
            'message' => 'Usuário ativado.'
        ], 404);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function login(Request $request)
    {
        if ($request['type_user'] === 'admin' || $request['type_user'] === 'client') {
            $credentials = request(['email', 'password']);
        } else {
            $credentials = request(['cpf_cnpj', 'password']);
        }

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();
        // $user['access_token'] = [
        //     'access_token' => $tokenResult->accessToken,
        //     'token_type' => 'Bearer',
        //     'expires_at' => Carbon::parse(
        //         $tokenResult->token->expires_at
        //     )->toDateTimeString()
        // ];

        $user['access_token'] = $tokenResult->accessToken;
        return response()->json($user);
    }
}
