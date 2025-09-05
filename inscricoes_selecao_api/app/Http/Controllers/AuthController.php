<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|min:2',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'ok' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'token' => $token,
            'message' => 'Usuário registrado com sucesso'
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email:rfc,dns',
            'password' => 'required|min:6'
        ]);

        if (Auth::attempt($validated)) {
            $user = User::where('email', $validated['email'])->first();
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'ok' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'token' => $token,
                'message' => 'Login realizado com sucesso'
            ]);
        }

        return response()->json([
            'ok' => false,
            'message' => 'Credenciais inválidas'
        ], 401);
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'ok' => false,
                'message' => 'Token em falta'
            ], 400);
        }

        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken) {
            return response()->json([
                'ok' => false,
                'message' => 'Token inválido'
            ], 400);
        }

        $accessToken->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Logout realizado com sucesso'
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'ok' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
            ]
        ]);
    }
}