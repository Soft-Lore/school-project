<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use App\DTOs\User\UserFilterDto;

interface UserRepositoryInterface
{
    public function findByUsername(string $user_name): ?User;

    public function findById(int $id): ?User;

    public function create(array $data): User;
    
    public function filter(UserFilterDto $dto): iterable;

    public function all(): iterable;
}
