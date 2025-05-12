<?php

namespace App\Actions\School;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use App\DTOs\School\CreateSchoolDto;
use App\Models\School;

class CreateSchoolAction
{
    public function execute(CreateSchoolDto $dto): School
    {
        $subdomain = Str::slug($dto->name);
        $databaseName = $subdomain . '_db';

        // Create the database
        DB::statement("CREATE DATABASE IF NOT EXISTS `$databaseName`");

        // Save in the main database
        $school = School::create([
            'name' => $dto->name,
            'subdomain' => $subdomain,
            'database_name' => $databaseName,
            'db_host' => '127.0.0.1',
            'db_port' => '3306',
            'db_username' => 'root',
            'db_password' => env('DB_PASSWORD', ''),
        ]);

        // Define dynamic connection
        Config::set("database.connections.tenant", [
            'driver' => 'mysql',
            'host' => 'mysql',
            'port' => '3306',
            'database' => $school->database_name,
            'username' => $school->db_username,
            'password' => 'root',
        ]);

        DB::purge('tenant');
        DB::connection('tenant')->getPdo();

        // Run migrations within the tenant's database
        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => '/database/migrations/tenant',
            '--force' => true,
        ]);

        return $school;
    }
}
