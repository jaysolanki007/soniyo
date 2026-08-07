<?php

// Set Vercel environment flag and writable storage path BEFORE booting Laravel
putenv("VERCEL=1");
$_ENV['VERCEL'] = '1';
$_SERVER['VERCEL'] = '1';

$storagePath = '/tmp/storage';
putenv("APP_STORAGE=$storagePath");
$_ENV['APP_STORAGE'] = $storagePath;
$_SERVER['APP_STORAGE'] = $storagePath;

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

// Ensure APP_KEY has a valid fallback if omitted in Vercel environment settings
if (empty($_ENV['APP_KEY']) && empty(getenv('APP_KEY'))) {
    $fallbackKey = 'base64:07EDFjCvViPOKMGDL1o5MPjexlbNrkLi/Lq+Wf8C1x4=';
    putenv("APP_KEY=$fallbackKey");
    $_ENV['APP_KEY'] = $fallbackKey;
    $_SERVER['APP_KEY'] = $fallbackKey;
}

// Redirect log channel to stderr for Vercel Runtime Logs visibility
putenv("LOG_CHANNEL=stderr");
$_ENV['LOG_CHANNEL'] = 'stderr';
$_SERVER['LOG_CHANNEL'] = 'stderr';

// If DB_HOST is localhost/127.0.0.1 or not configured, fallback session and cache to prevent 500 DB connection error
$dbHost = getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? '');
if (empty($dbHost) || $dbHost === '127.0.0.1' || $dbHost === 'localhost') {
    putenv("SESSION_DRIVER=cookie");
    $_ENV['SESSION_DRIVER'] = 'cookie';
    $_SERVER['SESSION_DRIVER'] = 'cookie';

    putenv("CACHE_STORE=array");
    $_ENV['CACHE_STORE'] = 'array';
    $_SERVER['CACHE_STORE'] = 'array';
}

// Forward request to Laravel's public/index.php
require __DIR__ . '/../public/index.php';
