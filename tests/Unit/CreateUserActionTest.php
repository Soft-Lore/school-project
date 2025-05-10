<?php

namespace Tests\Unit;

use App\Actions\User\CreateUserAction;
use Tests\TestCase;
use App\DTOs\User\CreateUserDto;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateUserActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_user_successfully()
    {
        $dto = new CreateUserDto(
            first_name: 'Juan',
            second_name: 'Perez',
            user_name: 'jperez',
            password: '12345678',
            cedula: '123456789',
            address: 'Heredia',
            is_enable: true,
            email_address: 'juan@example.com'
        );

        $action = $this->app->make(CreateUserAction::class);
        
        $user = $action->execute($dto);

        $this->assertDatabaseHas('users', [
            'user_name' => 'jperez',
            'email_address' => 'juan@example.com',
        ]);
    }
}
