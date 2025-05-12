<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\School;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class LogoutTest extends TestCase
{
    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();

        School::where('subdomain', 'colegio-demo')->delete();

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

        DB::connection('tenant')->table('users')->insert([
            'first_name' => 'Admin',
            'second_name' => 'User',
            'user_name' => 'admin',
            'password' => bcrypt('12345678'),
            'cedula' => '123456789',
            'address' => 'San José',
            'is_enable' => true,
            'email_address' => 'admin@demo.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response =$this->postJson('/api/v1/schools/login?school=colegio-demo', [
            'user_name' => 'admin',
            'password' => '12345678'
        ]);

        $this->token = $response->json('token');
    }

 
public function test_logout_deletes_current_token()
{
    $response = $this->postJson('/api/v1/users/logout?school=colegio-demo', [], [
        'Authorization' => 'Bearer ' . $this->token
    ]);

    $response->assertStatus(200)
             ->assertJson(['message' => 'Token eliminado']);
}
}
