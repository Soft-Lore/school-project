<?php

namespace App\Actions\School;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CreateAdminUserAction
{
    public function execute(string $email, string $password): void
    {
        DB::connection('tenant')->table('users')->insert([
            'first_name' => 'Administrador',
            'second_name' => 'General',
            'user_name' => 'admin',
            'email_address' => $email,
            'password' => Hash::make($password),
            'cedula' => uniqid(),
            'address' => 'Sin dirección',
            'is_enable' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
