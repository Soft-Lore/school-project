<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\DTOs\User\DeleteUserDto;
use App\Actions\User\DeleteUserAction;

class DeleteUserActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deletes_a_user()
    {
        $user = User::factory()->create();

        $dto = new DeleteUserDto(id: $user->id);
        $action = $this->app->make(DeleteUserAction::class);

        $result = $action->execute($dto);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_it_returns_false_if_user_not_found()
    {
        $dto = new DeleteUserDto(id: 999);
        $action = $this->app->make(DeleteUserAction::class);

        $result = $action->execute($dto);

        $this->assertFalse($result);
    }
}
