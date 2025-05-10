<?php

namespace App\DTOs\User;

class CreateUserDto
{
    public function __construct(
        public readonly string $first_name,
        public readonly string $second_name,
        public readonly string $user_name,
        public readonly string $password,
        public readonly string $cedula,
        public readonly string $address,
        public readonly bool $is_enable,
        public readonly ?string $email_address = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            first_name:     $data['first_name'],
            second_name:    $data['second_name'],
            user_name:      $data['user_name'],
            password:       $data['password'],
            cedula:         $data['cedula'],
            address:        $data['address'],
            is_enable:      filter_var($data['is_enable'], FILTER_VALIDATE_BOOL),
            email_address:  $data['email_address'] ?? null
        );
    }
}
