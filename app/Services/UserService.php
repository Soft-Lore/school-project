<?php

namespace App\Services;

use App\Actions\User\ChangePasswordAction;
use App\Models\User;
use App\Actions\User\CreateUserAction;
use App\Actions\User\DeleteUserAction;
use App\Actions\User\UpdateUserAction;
use App\DTOs\User\ChangePasswordDto;
use App\DTOs\User\CreateUserDto;
use App\DTOs\User\DeleteUserDto;
use App\DTOs\User\UpdateUserDto;
use App\DTOs\User\UserFilterDto;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserService
{
    public function __construct(
        protected ChangePasswordAction $changePasswordAction,
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

    public function changePassword(ChangePasswordDto $dto): bool
    {
        return $this->changePasswordAction->execute($dto);
    }

    public function deleteUser(DeleteUserDto $dto): bool
    {
        return $this->deleteUserAction->execute($dto);
    }

    public function getAllUsers(): iterable
    {
        return $this->users->all();
    }

    public function filterUsers(UserFilterDto $dto): iterable
    {
        return $this->users->filter($dto);
    }
}
