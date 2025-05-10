<?php

namespace App\DTOs\User;

class UserFilterDto
{
    public function __construct(
        public readonly ?string $search = null
    ) {}

    public static function fromQuery(array $query): self
    {
        return new self(
            search: $query['search'] ?? null
        );
    }
}
