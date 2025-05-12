<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\School;
use Throwable;

class TenantConnectionCheck extends Command
{
    protected $signature = 'tenant:check {school}';

    protected $description = 'Verifica la conexión a la base de datos del tenant según el subdominio';

    public function handle(): int
    {
        $subdomain = $this->argument('school');

        $school = School::where('subdomain', $subdomain)->first();

        if (! $school) {
            $this->error("❌ Escuela con subdominio '{$subdomain}' no encontrada.");
            return self::FAILURE;
        }

        Config::set("database.connections.tenant", [
            'driver' => 'mysql',
            'host' => $school->db_host,
            'port' => $school->db_port,
            'database' => $school->database_name,
            'username' => $school->db_username,
            'password' => $school->db_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]);

        DB::purge('tenant');

        try {
            DB::connection('tenant')->getPdo();
            $this->info("✅ Conexión exitosa a la base de datos del colegio '{$school->name}'");
            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->error("❌ Error al conectar: " . $e->getMessage());
            return self::FAILURE;
        }
    }
}
