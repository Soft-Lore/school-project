<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'first_name' => 'Moisés',
            'second_name' => 'Hernández',
            'user_name' => 'mhernandez',
            'password' => Hash::make('12345678'),
            'cedula' => '123456789',
            'address' => 'Calle Falsa 123',
            'is_enable' => true,
            'email_address' => 'moises@example.com',
        ]);
    }
}
