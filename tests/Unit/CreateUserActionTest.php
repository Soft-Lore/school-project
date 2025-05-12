<?php

namespace Tests\Unit;

use App\Actions\User\CreateUserAction;
use Tests\TestCase;
use App\DTOs\User\CreateUserDto;
use App\Models\School;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateUserActionTest extends TestCase
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

        $user = DB::connection('tenant')->transaction(function () use ($action, $dto) {
            return $action->execute($dto);
        });

        $this->assertDatabaseHas('users', [
            'user_name' => 'jperez',
            'email_address' => 'juan@example.com',
        ], 'tenant');
    }
}