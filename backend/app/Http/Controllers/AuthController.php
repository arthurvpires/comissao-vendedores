<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }
    
        $user = $request->user();
        $token = $user->createToken('personal-token')->plainTextToken;
    
        return response()->json([
            'token' => $token,
            'user' => $user,
        ], 200);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name(),
            'email' => $request->email(),
            'password' => Hash::make($request->password()),
        ]);

        $token = $user->createToken('personal-token')->plainTextToken;
        return response()->json([
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout realizado com sucesso'], 200);
    }
}