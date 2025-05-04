<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Contracts\UserRepositoryInterface;

class AuthService
{
    public function __construct(
        protected UserRepositoryInterface $users
    ) {}

    public function login(string $user_name, string $password): ?string
    {
        $user = $this->users->findByUsername($user_name);

        if (! $user || ! Hash::check($password, $user->password)) {
            return null;
        }

        return $user->createToken('api-token')->plainTextToken;
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }
}
