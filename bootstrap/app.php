<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

// Set Vercel writable storage path BEFORE Application is configured
if (isset($_ENV['VERCEL']) || getenv('VERCEL') || file_exists('/var/task')) {
    $storagePath = '/tmp/storage';
    putenv("APP_STORAGE=$storagePath");
    $_ENV['APP_STORAGE'] = $storagePath;
    $_SERVER['APP_STORAGE'] = $storagePath;

    putenv("APP_SERVICES_CACHE=$storagePath/bootstrap-services.php");
    putenv("APP_PACKAGES_CACHE=$storagePath/bootstrap-packages.php");
    putenv("APP_CONFIG_CACHE=$storagePath/bootstrap-config.php");
    putenv("APP_ROUTES_CACHE=$storagePath/bootstrap-routes.php");
    putenv("APP_EVENTS_CACHE=$storagePath/bootstrap-events.php");
    putenv("VIEW_COMPILED_PATH=$storagePath/framework/views");

    $storageFolders = [
        "$storagePath/app/public",
        "$storagePath/framework/cache/data",
        "$storagePath/framework/sessions",
        "$storagePath/framework/views",
        "$storagePath/logs",
    ];

    foreach ($storageFolders as $folder) {
        if (!is_dir($folder)) {
            @mkdir($folder, 0755, true);
        }
    }
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureAdmin::class,
            'module' => \App\Http\Middleware\ModuleAccess::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
