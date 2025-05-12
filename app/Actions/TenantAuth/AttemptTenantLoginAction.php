<?php

namespace App\Actions\TenantAuth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\DTOs\Auth\TenantLoginDto;

class AttemptTenantLoginAction
{
    public function execute(TenantLoginDto $dto): ?User
    {
        $user = User::where('user_name', $dto->user_name)->first();

        if (! $user || ! Hash::check($dto->password, $user->password)) {
            return null;
        }

        return $user;
    }
}