<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_change_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword'),
        ]);

        $response = $this->actingAs($user)->postJson('/api/v1/users/change-password', [
            'current_password' => 'oldpassword',
            'new_password' => 'newsecurepass123'
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Contraseña actualizada correctamente']);

        // Verify that the new password was saved
        $this->assertTrue(Hash::check('newsecurepass123', $user->fresh()->password));
    }

    public function test_change_password_fails_with_wrong_current_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword'),
        ]);

        $response = $this->actingAs($user)->postJson('/api/v1/users/change-password', [
            'current_password' => 'wrongpassword',
            'new_password' => 'newsecurepass123'
        ]);

        $response->assertStatus(400)
                 ->assertJson(['message' => 'Contraseña actual incorrecta']);
    }
}
