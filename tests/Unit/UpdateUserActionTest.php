<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\DTOs\User\UpdateUserDto;
use App\Actions\User\UpdateUserAction;
use App\Models\School;

class UpdateUserActionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        School::where('subdomain', 'colegio-demo')->delete();

        School::create([
            'name' => 'Colegio Demo',
            'subdomain' => 'colegio-demo',
            'database_name' => 'colegio_demo_db',
            'db_host' => 'mysql',
            'db_port' => '3306',
            'db_username' => 'root',
            'db_password' => 'root',
        ]);

        DB::statement('DROP DATABASE IF EXISTS colegio_demo_db');
        DB::statement('CREATE DATABASE colegio_demo_db');

        config()->set("database.connections.tenant", [
            'driver' => 'mysql',
            'host' => 'mysql',
            'port' => '3306',
            'database' => 'colegio_demo_db',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]);

        DB::purge('tenant');
        DB::connection('tenant')->getPdo();

        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant',
            '--force' => true,
        ]);
    }

    public function test_it_updates_a_user()
    {
        $userId = DB::connection('tenant')->table('users')->insertGetId([
            'first_name' => 'OldName',
            'user_name' => 'olduser',
            'password' => bcrypt('password'),
            'cedula' => '123456789',
            'address' => 'Heredia',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $dto = new UpdateUserDto(
            id: $userId,
            first_name: 'NewName'
        );

        $action = $this->app->make(UpdateUserAction::class);

        $updated = DB::connection('tenant')->transaction(function () use ($action, $dto) {
            return $action->execute($dto);
        });

        $this->assertEquals('NewName', $updated->first_name);
        $this->assertDatabaseHas('users', ['first_name' => 'NewName'], 'tenant');
    }
}