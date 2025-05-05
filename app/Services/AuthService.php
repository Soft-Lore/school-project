<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\DTOs\LoginRequestDto;

class AuthService
{
    public function __construct(
        protected UserRepositoryInterface $users
    ) {}

    public function login(LoginRequestDto $dto): ?string
    {
        $user = $this->users->findByUsername($dto->user_name);

        if (! $user || ! Hash::check($dto->password, $user->password)) {
            return null;
        }

        return $user->createToken('api-token')->plainTextToken;
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }
}
