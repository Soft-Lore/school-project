<?php

use App\Http\Middleware\DetectTenantFromRequest;
use App\Http\Middleware\IdentifyTenantFromRequest;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->prependToGroup('api', \App\Http\Middleware\EnsureTenantConnection::class);

        $middleware->alias([
            'tenant' => \App\Http\Middleware\EnsureTenantConnection::class,
        ]);

        $middleware->group('api/v1', [
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {})
    ->create();
