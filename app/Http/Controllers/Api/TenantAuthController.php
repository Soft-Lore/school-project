<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Auth\TenantLoginDto;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TenantAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TenantAuthController extends Controller
{

    public function __construct(
        protected TenantAuthService $tenantAuthService
    ) {}
    /**
 * @OA\Post(
 *     path="/api/v1/schools/login",
 *     summary="Login del administrador de una escuela",
 *     tags={"Escuelas"},
 *     @OA\Parameter(
 *         name="school",
 *         in="query",
 *         required=true,
 *         description="Subdominio de la escuela",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_name", "password"},
 *             @OA\Property(property="user_name", type="string", example="admin"),
 *             @OA\Property(property="password", type="string", example="12345678")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Login exitoso, se retorna el token"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Credenciales inválidas"
 *     )
 * )
 */
    public function login(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string',
            'password' => 'required|string',
        ]);

        $dto = TenantLoginDto::fromRequest($request->only(['user_name', 'password']));

        $token = $this->tenantAuthService->login($dto);

        if (! $token) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        return response()->json(['token' => $token]);
    }


}
