<?php

namespace App\Actions\User;

use App\DTOs\User\CreateUserDto;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class CreateUserAction
{
    public function __construct(
        protected UserRepositoryInterface $users
    ) {}

    public function execute(CreateUserDto $dto): User
    {
        return $this->users->create([
            'first_name'    => $dto->first_name,
            'second_name'   => $dto->second_name,
            'user_name'     => $dto->user_name,
            'password'      => Hash::make($dto->password),
            'cedula'        => $dto->cedula,
            'address'       => $dto->address,
            'is_enable'     => $dto->is_enable,
            'email_address' => $dto->email_address,
        ]);
    }
}
