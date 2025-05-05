<?php

namespace App\DTOs;

class LoginRequestDto
{
    public function __construct(
        public readonly string $user_name,
        public readonly string $password
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            user_name: $data['user_name'],
            password: $data['password']
        );
    }
}
