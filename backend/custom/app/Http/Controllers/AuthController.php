<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth('api')->user()
        ]);
    }

    public function me() { return response()->json(auth('api')->user()); }
    public function logout() { auth('api')->logout(); return response()->json(); }
}
