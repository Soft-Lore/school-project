<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\DTOs\User\CreateUserDto;
use App\DTOs\User\DeleteUserDto;
use App\DTOs\User\UpdateUserDto;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}
    
    /**
     * @OA\Get(
     *     path="/api/v1/users",
     *     summary="Listar todos los usuarios",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuarios",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="first_name", type="string", example="Ana"),
     *                 @OA\Property(property="user_name", type="string", example="arojas"),
     *                 @OA\Property(property="email_address", type="string", example="ana@example.com")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json($this->userService->getAllUsers());
    }
    /**
     * @OA\Post(
     *     path="/api/v1/users/register",
     *     summary="Registrar nuevo usuario",
     *     tags={"Usuarios"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name", "second_name", "user_name", "password", "cedula", "address", "is_enable"},
     *             @OA\Property(property="first_name", type="string", example="Ana"),
     *             @OA\Property(property="second_name", type="string", example="Rojas"),
     *             @OA\Property(property="user_name", type="string", example="arojas"),
     *             @OA\Property(property="password", type="string", example="12345678"),
     *             @OA\Property(property="cedula", type="string", example="123456789"),
     *             @OA\Property(property="address", type="string", example="Alajuela"),
     *             @OA\Property(property="is_enable", type="boolean", example=true),
     *             @OA\Property(property="email_address", type="string", example="ana@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario creado correctamente"
     *     )
     * )
     */
    public function register(Request $request)
    {
        $dto = CreateUserDto::fromArray($request->only([
            'first_name',
            'second_name',
            'user_name',
            'password',
            'cedula',
            'address',
            'is_enable',
            'email_address'
        ]));

        $user = $this->userService->registerUser($dto);

        return response()->json($user, 201);
    }
    /**
     * @OA\Put(
     *     path="/api/v1/users/update",
     *     summary="Actualizar usuario",
     *     tags={"Usuarios"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="first_name", type="string", example="Juan"),
     *             @OA\Property(property="address", type="string", example="San José")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario actualizado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado"
     *     )
     * )
     */
    public function update(Request $request)
    {
        $dto = UpdateUserDto::fromArray($request->all());

        $user = $this->userService->updateUser($dto);

        return $user
            ? response()->json($user)
            : response()->json(['message' => 'Usuario no encontrado'], 404);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/users/delete",
     *     summary="Eliminar usuario",
     *     tags={"Usuarios"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario eliminado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado"
     *     )
     * )
     */
    public function delete(Request $request)
    {
        $dto = DeleteUserDto::fromArray($request->only(['id']));

        $deleted = $this->userService->deleteUser($dto);

        return $deleted
            ? response()->json(['message' => 'Usuario eliminado'])
            : response()->json(['message' => 'Usuario no encontrado'], 404);
    }

   
}
