<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\School;
use App\DTOs\User\DeleteUserDto;
use App\Actions\User\DeleteUserAction;

class DeleteUserActionTest extends TestCase
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

    public function test_it_deletes_a_user()
    {
        $userId = DB::connection('tenant')->table('users')->insertGetId([
            'first_name' => 'Juan',
            'user_name' => 'jperez',
            'password' => bcrypt('password'),
            'cedula' => '123456789',
            'address' => 'Heredia',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $dto = new DeleteUserDto(id: $userId);
        $action = $this->app->make(DeleteUserAction::class);

        $result = DB::connection('tenant')->transaction(function () use ($action, $dto) {
            return $action->execute($dto);
        });

        $this->assertTrue($result);
        $this->assertDatabaseMissing('users', ['id' => $userId], 'tenant');
    }

    public function test_it_returns_false_if_user_not_found()
    {
        $dto = new DeleteUserDto(id: 999);
        $action = $this->app->make(DeleteUserAction::class);

        $result = DB::connection('tenant')->transaction(function () use ($action, $dto) {
            return $action->execute($dto);
        });

        $this->assertFalse($result);
    }
}