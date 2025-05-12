<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use App\Models\School;
use App\Models\User;

class TenantLoginTest extends TestCase
{
    public function setUp(): void
{
    parent::setUp();

    School::where('subdomain', 'colegio-demo')->delete();

    $this->assertEquals('mysql', config('database.default'), 'La conexión por defecto NO es MySQL');

    Artisan::call('migrate');

    School::create([
        'name' => 'Colegio Demo',
        'subdomain' => 'colegio-demo',
        'database_name' => 'colegio_demo_db',
        'db_host' => 'mysql',
        'db_port' => '3306',
        'db_username' => 'root',
        'db_password' => 'root',
    ]);

    if (config('database.default') === 'mysql') {
        DB::statement('DROP DATABASE IF EXISTS colegio_demo_db');
        DB::statement('CREATE DATABASE colegio_demo_db');
    }

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

    DB::connection('tenant')->table('users')->insert([
        'first_name' => 'Test',
        'second_name' => 'User',
        'user_name' => 'testuser',
        'password' => bcrypt('12345678'),
        'cedula' => '123456789',
        'address' => 'San José',
        'is_enable' => true,
        'email_address' => 'test@example.com',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

}


    public function test_login_returns_token_for_valid_user()
    {
        $response = $this->postJson('/api/v1/schools/login?school=colegio-demo', [
            'user_name' => 'testuser',
            'password' => '12345678',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }
}
