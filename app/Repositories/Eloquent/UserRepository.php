<?php

namespace App\Repositories\Eloquent;
use App\DTOs\User\UserFilterDto;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function findByUsername(string $user_name): ?User
    {
        return User::where('user_name', $user_name)->first();
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function all(): iterable
    {
        return User::all();
    }

    public function filter(UserFilterDto $dto): iterable
    {
        return User::when($dto->search, function ($query) use ($dto) {
            $query->where('first_name', 'LIKE', "%{$dto->search}%")
                ->orWhere('second_name', 'LIKE', "%{$dto->search}%")
                ->orWhere('cedula', 'LIKE', "%{$dto->search}%")
                ->orWhere('user_name', 'LIKE', "%{$dto->search}%");
        })->get();
    }
}
