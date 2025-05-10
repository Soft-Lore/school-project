<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'first_name'    => $this->faker->firstName(),
            'second_name'   => $this->faker->lastName(),
            'user_name'     => $this->faker->unique()->userName(),
            'password'      => bcrypt('12345678'),
            'cedula'        => $this->faker->unique()->numerify('#########'),
            'address'       => $this->faker->address(),
            'is_enable'     => true,
            'email_address' => $this->faker->unique()->safeEmail(),
        ];
    }
}
