<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'user_name' => 'mhernandez',
            'password' => Hash::make('12345678'),
        ]);

        $response = $this->postJson('/api/v1/login', [
            'user_name' => 'mhernandez',
            'password' => '12345678',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }

    public function test_login_fails_with_invalid_password()
    {
        $user = User::factory()->create([
            'user_name' => 'mhernandez',
            'password' => Hash::make('12345678'),
        ]);

        $response = $this->postJson('/api/v1/login', [
            'user_name' => 'mhernandez',
            'password' => 'wrongpass',
        ]);

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Credenciales inválidas']);
    }
}
