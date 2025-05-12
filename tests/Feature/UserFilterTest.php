<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserFilterTest extends TestCase
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

    public function test_users_are_filtered_by_search_term()
    {
        DB::connection('tenant')->table('users')->insert([
            [
                'first_name' => 'Moisés',
                'second_name' => 'Hernández',
                'user_name' => 'mhernandez',
                'cedula' => '123456789',
                'password' => bcrypt('password'),
                'address' => 'San José',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Carlos',
                'second_name' => null,
                'user_name' => 'carlos99',
                'cedula' => '987654321',
                'password' => bcrypt('password'),
                'address' => 'Alajuela',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $authUserId = DB::connection('tenant')->table('users')->insertGetId([
            'first_name' => 'Auth',
            'user_name' => 'authuser',
            'cedula' => '111111111',
            'password' => bcrypt('password'),
            'address' => 'Heredia',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $authUserModel = User::find($authUserId);

        $response = $this->actingAs($authUserModel)->getJson('/api/v1/users?search=mois&school=colegio-demo');

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