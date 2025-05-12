<?php

namespace App\Services;

use App\Actions\TenantAuth\AttemptTenantLoginAction;
use App\DTOs\Auth\TenantLoginDto;

class TenantAuthService
{
    public function __construct(
        protected AttemptTenantLoginAction $attemptLoginAction
    ) {}

    public function login(TenantLoginDto $dto): ?string
    {
        $user = $this->attemptLoginAction->execute($dto);

        if (! $user) {
            return null;
        }

        return $user->createToken('api-token')->plainTextToken;
    }
}
