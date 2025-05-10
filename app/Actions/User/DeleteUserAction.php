<?php

namespace App\Actions\User;

use App\DTOs\User\DeleteUserDto;
use App\Repositories\Contracts\UserRepositoryInterface;

class DeleteUserAction
{
    public function __construct(
        protected UserRepositoryInterface $users
    ) {}

    public function execute(DeleteUserDto $dto): bool
    {
        $user = $this->users->findById($dto->id);

        if (! $user) {
            return false;
        }

        return $user->delete();
    }
}
