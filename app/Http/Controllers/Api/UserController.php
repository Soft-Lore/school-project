<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\DTOs\CreateUserDto;
use App\DTOs\User\DeleteUserDto;
use App\DTOs\User\UpdateUserDto;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}
    
    public function index()
    {
        return response()->json($this->userService->getAllUsers());
    }
    
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

    public function update(Request $request)
    {
        $dto = UpdateUserDto::fromArray($request->all());

        $user = $this->userService->updateUser($dto);

        return $user
            ? response()->json($user)
            : response()->json(['message' => 'Usuario no encontrado'], 404);
    }

    public function delete(Request $request)
    {
        $dto = DeleteUserDto::fromArray($request->only(['id']));

        $deleted = $this->userService->deleteUser($dto);

        return $deleted
            ? response()->json(['message' => 'Usuario eliminado'])
            : response()->json(['message' => 'Usuario no encontrado'], 404);
    }

   
}
