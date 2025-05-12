<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\School;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChangePasswordTest extends TestCase
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

        DB::connection('tenant')->table('users')->insert([
            'first_name' => 'Admin',
            'second_name' => 'User',
            'user_name' => 'admin',
            'password' => bcrypt('oldpassword'),
            'cedula' => '123456789',
            'address' => 'San José',
            'is_enable' => true,
            'email_address' => 'admin@demo.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_user_can_change_password()
    {
        $user = DB::connection('tenant')->table('users')->where('user_name', 'admin')->first();

        $response = $this->postJson('/api/v1/users/change-password?school=colegio-demo', [
            'current_password' => 'oldpassword',
            'new_password' => 'newsecurepass123'
        ], [
            'Authorization' => 'Bearer ' . $this->generateToken($user),
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Contraseña actualizada correctamente']);

        $updatedUser = DB::connection('tenant')->table('users')->where('user_name', 'admin')->first();
        $this->assertTrue(Hash::check('newsecurepass123', $updatedUser->password));
    }


    private function generateToken($user)
    {
        $userModel = \App\Models\User::find($user->id);
        return $userModel->createToken('Test Token')->plainTextToken;
    }
}