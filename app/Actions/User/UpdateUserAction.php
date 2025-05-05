<?php

namespace App\Actions\User;

use App\DTOs\User\UpdateUserDto;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UpdateUserAction
{
    public function __construct(
        protected UserRepositoryInterface $users
    ) {}

    public function execute(UpdateUserDto $dto): ?User
    {
        $user = $this->users->findById($dto->id);

        if (! $user) {
            return null;
        }

        $user->update(array_filter([
            'first_name'    => $dto->first_name,
            'second_name'   => $dto->second_name,
            'email_address' => $dto->email_address,
            'address'       => $dto->address,
            'is_enable'     => $dto->is_enable,
        ], fn($value) => !is_null($value)));

        return $user;
    }
}
