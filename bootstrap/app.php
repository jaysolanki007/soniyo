<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

// Set Vercel writable storage path BEFORE Application is configured
if (isset($_ENV['VERCEL']) || getenv('VERCEL')) {
    putenv('APP_STORAGE=/tmp/storage');
    $_ENV['APP_STORAGE'] = '/tmp/storage';
    $_SERVER['APP_STORAGE'] = '/tmp/storage';


    $storagePath = '/tmp/storage';
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


