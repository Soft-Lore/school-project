<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface UserRepositoryInterface
{
    public function findByUsername(string $user_name): ?User;

    public function findById(int $id): ?User;

    public function create(array $data): User;

    public function all(): iterable;
}
