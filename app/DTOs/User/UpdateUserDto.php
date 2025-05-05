<?php

namespace App\DTOs\User;

class UpdateUserDto
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $first_name = null,
        public readonly ?string $second_name = null,
        public readonly ?string $email_address = null,
        public readonly ?string $address = null,
        public readonly ?bool $is_enable = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            first_name: $data['first_name'] ?? null,
            second_name: $data['second_name'] ?? null,
            email_address: $data['email_address'] ?? null,
            address: $data['address'] ?? null,
            is_enable: array_key_exists('is_enable', $data)
                ? filter_var($data['is_enable'], FILTER_VALIDATE_BOOL)
                : null
        );
    }
}
