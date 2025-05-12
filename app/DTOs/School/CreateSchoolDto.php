<?php

namespace App\DTOs\School;

class CreateSchoolDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $admin_email,
        public readonly string $admin_password
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            admin_email: $data['admin_email'],
            admin_password: $data['admin_password'],
        );
    }
}
