<?php

namespace App\Actions\User;

use App\DTOs\User\ChangePasswordDto;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class ChangePasswordAction
{
    public function __construct(
        protected UserRepositoryInterface $users
    ) {}

    public function execute(ChangePasswordDto $dto): bool
    {
        $user = $this->users->findById($dto->user_id);

        if (! $user || ! Hash::check($dto->current_password, $user->password)) {
            return false;
        }

        $user->password = Hash::make($dto->new_password);
        $user->save();

        return true;
    }
}
