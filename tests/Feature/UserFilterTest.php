<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_are_filtered_by_search_term()
    {
        // User that should be found
        User::factory()->create([
            'first_name' => 'Moisés',
            'second_name' => 'Hernández',
            'user_name' => 'mhernandez',
            'cedula' => '123456789',
        ]);

        // User that should NOT appear in the result
        User::factory()->create([
            'first_name' => 'Carlos',
            'user_name' => 'carlos99',
            'cedula' => '987654321',
        ]);

        // Authenticated user for the test
        $authUser = User::factory()->create();

        $response = $this->actingAs($authUser)->getJson('/api/v1/users?search=mois');

        $response->assertStatus(200);
        
        $response->assertJsonFragment([
            'first_name' => 'Moisés',
            'user_name' => 'mhernandez',
        ]);

        $response->assertJsonMissing([
            'first_name' => 'Carlos',
        ]);
    }
}
