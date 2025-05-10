<?php

namespace App\DTOs\Auth;


/**
 * @OA\Schema(
 *     schema="LoginRequestDto",
 *     type="object",
 *     required={"user_name", "password"},
 *     @OA\Property(property="user_name", type="string", example="mhernandez"),
 *     @OA\Property(property="password", type="string", example="12345678")
 * )
 */
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
