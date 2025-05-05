<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\DTOs\LoginRequestDto;
use Illuminate\Http\Request;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $auth
    ) {}

    public function login(Request $request)
    {
        $dto = LoginRequestDto::fromArray($request->only(['user_name', 'password']));
    
        $token = $this->auth->login($dto);
    
        if (! $token) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }
    
        return response()->json(['token' => $token]);
    }

    public function logout(Request $request)
    {
        $this->auth->logout($request->user());
        return response()->json(['message' => 'Token eliminado']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
