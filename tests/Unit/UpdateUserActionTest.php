<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\DTOs\User\UpdateUserDto;
use App\Actions\User\UpdateUserAction;

class UpdateUserActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_a_user()
    {
        $user = User::factory()->create([
            'first_name' => 'OldName',
        ]);

        $dto = new UpdateUserDto(
            id: $user->id,
            first_name: 'NewName'
        );

        $action = $this->app->make(UpdateUserAction::class);
        $updated = $action->execute($dto);

        $this->assertEquals('NewName', $updated->first_name);
        $this->assertDatabaseHas('users', ['first_name' => 'NewName']);
    }
}
