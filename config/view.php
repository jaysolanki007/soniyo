<?php

$compiledPath = env('VIEW_COMPILED_PATH');

if (! $compiledPath) {
    $isVercel = (isset($_ENV['VERCEL']) || getenv('VERCEL') || isset($_SERVER['VERCEL']));
    $compiledPath = $isVercel ? '/tmp/storage/framework/views' : storage_path('framework/views');
}

if (! is_dir($compiledPath)) {
    @mkdir($compiledPath, 0755, true);
}

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    */

    'paths' => [
        resource_path('views'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    */

    'compiled' => $compiledPath,

];
