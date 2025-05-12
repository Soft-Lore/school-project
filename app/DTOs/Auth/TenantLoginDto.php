<?php

namespace App\DTOs\Auth;

class TenantLoginDto
{
    public function __construct(
        public readonly string $user_name,
        public readonly string $password
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            user_name: $data['user_name'],
            password: $data['password']
        );
    }
}