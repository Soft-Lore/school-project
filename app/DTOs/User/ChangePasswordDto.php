<?php

namespace App\DTOs\User;

class ChangePasswordDto
{
    public function __construct(
        public readonly int $user_id,
        public readonly string $current_password,
        public readonly string $new_password
    ) {}

    public static function fromArray(array $data, int $user_id): self
    {
        return new self(
            user_id: $user_id,
            current_password: $data['current_password'],
            new_password: $data['new_password']
        );
    }
}
