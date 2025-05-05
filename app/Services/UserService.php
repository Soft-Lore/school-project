<?php

namespace App\Services;

use App\DTOs\CreateUserDto;
use App\Models\User;
use App\Actions\CreateUserAction;
use App\Actions\User\DeleteUserAction;
use App\Actions\User\UpdateUserAction;
use App\DTOs\User\DeleteUserDto;
use App\DTOs\User\UpdateUserDto;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserService
{
    public function __construct(
        protected CreateUserAction $createUserAction,
        protected UpdateUserAction $updateUserAction,
        protected DeleteUserAction $deleteUserAction,
        protected UserRepositoryInterface $users
    ) {}

    public function registerUser(CreateUserDto $dto): User
    {
        return $this->createUserAction->execute($dto);
    }

    public function updateUser(UpdateUserDto $dto): ?User
    {
        return $this->updateUserAction->execute($dto);
    }

    public function deleteUser(DeleteUserDto $dto): bool
    {
        return $this->deleteUserAction->execute($dto);
    }

    public function getAllUsers(): iterable
    {
        return $this->users->all();
    }
}
