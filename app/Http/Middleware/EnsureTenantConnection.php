<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\School;

class EnsureTenantConnection
{
    /**
    * List of routes to be excluded from the middleware
     */
    protected array $excludedPaths = [
        'api/v1/schools/register',
        'api/v1/schools/register/*',
    ];

    public function handle(Request $request, Closure $next)
    {
        // Skip if the route is excluded
        foreach ($this->excludedPaths as $excluded) {
            if ($request->is($excluded)) {
                return $next($request);
            }
        }

        $schoolSlug = $request->query('school');

        if (! $schoolSlug) {
            return response()->json(['error' => 'Parámetro ?school requerido'], 400);
        }

        $school = School::where('subdomain', $schoolSlug)->first();

        if (! $school) {
            return response()->json(['error' => 'Escuela no encontrada'], 404);
        }

        config()->set("database.connections.tenant", [
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
        DB::connection('tenant')->getPdo();

        return $next($request);
    }
}
