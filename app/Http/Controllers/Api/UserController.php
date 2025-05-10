<?php

namespace App\Http\Controllers\Api;

use App\DTOs\User\ChangePasswordDto;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\DTOs\User\CreateUserDto;
use App\DTOs\User\DeleteUserDto;
use App\DTOs\User\UpdateUserDto;
use App\DTOs\User\UserFilterDto;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/v1/users",
     *     summary="Listar o buscar usuarios",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Texto a buscar (nombre, usuario o cédula)",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuarios filtrados o completa",
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
    public function index(Request $request)
    {
        $dto = UserFilterDto::fromQuery($request->query());

        $users = $dto->search
            ? $this->userService->filterUsers($dto)
            : $this->userService->getAllUsers();

        return response()->json($users);
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
     * @OA\Post(
     *     path="/api/v1/users/change-password",
     *     summary="Cambiar contraseña del usuario autenticado",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"current_password", "new_password"},
     *             @OA\Property(property="current_password", type="string", example="12345678"),
     *             @OA\Property(property="new_password", type="string", example="nuevaClave123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contraseña cambiada correctamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Contraseña actual incorrecta"
     *     )
     * )
     */
    public function changePassword(Request $request)
    {
        $dto = ChangePasswordDto::fromArray(
            $request->only(['current_password', 'new_password']),
            $request->user()->id
        );

        $success = $this->userService->changePassword($dto);

        return $success
            ? response()->json(['message' => 'Contraseña actualizada correctamente'])
            : response()->json(['message' => 'Contraseña actual incorrecta'], 400);
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
