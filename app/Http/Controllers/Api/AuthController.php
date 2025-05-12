<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Auth\LoginRequestDto;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $auth
    ) {}

    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     summary="Iniciar sesión",
     *     tags={"Autenticación"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_name", "password"},
     *             @OA\Property(property="user_name", type="string", example="mhernandez"),
     *             @OA\Property(property="password", type="string", example="12345678")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Token generado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="1|eyJ0eXAiOiJK...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales inválidas"
     *     )
     * )
     */
    
    public function login(Request $request)
    {
        $dto = LoginRequestDto::fromArray($request->only(['user_name', 'password']));
    
        $token = $this->auth->login($dto);
    
        if (! $token) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }
    
        return response()->json(['token' => $token]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/logout",
     *     summary="Cerrar sesión",
     *     tags={"Autenticación"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Sesión cerrada correctamente"
     *     )
     * )
     */

    public function logout(Request $request)
    {
        $this->auth->logout($request->user());
        return response()->json(['message' => 'Token eliminado'], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/me",
     *     summary="Obtener usuario autenticado",
     *     tags={"Autenticación"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Datos del usuario autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="user_name", type="string", example="mhernandez"),
     *             @OA\Property(property="email_address", type="string", example="moises@example.com")
     *         )
     *     )
     * )
     */

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

}
