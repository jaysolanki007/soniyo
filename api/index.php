<?php

// Set Vercel environment flag and writable storage path BEFORE booting Laravel
putenv("VERCEL=1");
$_ENV['VERCEL'] = '1';
$_SERVER['VERCEL'] = '1';

// Force HTTPS protocol for Vercel proxy headers
putenv("HTTPS=on");
$_ENV['HTTPS'] = 'on';
$_SERVER['HTTPS'] = 'on';

$storagePath = '/tmp/storage';
putenv("APP_STORAGE=$storagePath");
$_ENV['APP_STORAGE'] = $storagePath;
$_SERVER['APP_STORAGE'] = $storagePath;

// Redirect Laravel bootstrap cache files to writable /tmp
putenv("APP_SERVICES_CACHE=$storagePath/bootstrap-services.php");
putenv("APP_PACKAGES_CACHE=$storagePath/bootstrap-packages.php");
putenv("APP_CONFIG_CACHE=$storagePath/bootstrap-config.php");
putenv("APP_ROUTES_CACHE=$storagePath/bootstrap-routes.php");
putenv("APP_EVENTS_CACHE=$storagePath/bootstrap-events.php");
putenv("VIEW_COMPILED_PATH=$storagePath/framework/views");

$_ENV['VIEW_COMPILED_PATH'] = "$storagePath/framework/views";
$_SERVER['VIEW_COMPILED_PATH'] = "$storagePath/framework/views";

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

// Cookie sessions avoid a DB round trip on every request (unless overridden in Vercel env)
if (empty(getenv('SESSION_DRIVER')) && empty($_ENV['SESSION_DRIVER'])) {
    putenv("SESSION_DRIVER=cookie");
    $_ENV['SESSION_DRIVER'] = 'cookie';
    $_SERVER['SESSION_DRIVER'] = 'cookie';
}

// File cache (in /tmp) avoids a remote DB round trip on every Cache:: call
if (empty(getenv('CACHE_STORE')) && empty($_ENV['CACHE_STORE'])) {
    putenv("CACHE_STORE=file");
    $_ENV['CACHE_STORE'] = 'file';
    $_SERVER['CACHE_STORE'] = 'file';
}

// Detect Vercel Marketplace Database (Neon / Supabase / Postgres) or DB_HOST
$postgresUrl = getenv('POSTGRES_URL') ?: (getenv('DATABASE_URL') ?: ($_ENV['POSTGRES_URL'] ?? ($_ENV['DATABASE_URL'] ?? '')));
$dbHost = getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? '');

if (!empty($postgresUrl)) {
    $dbParams = parse_url($postgresUrl);
    putenv("DB_CONNECTION=pgsql");
    $_ENV['DB_CONNECTION'] = 'pgsql';
    $_SERVER['DB_CONNECTION'] = 'pgsql';

    if (isset($dbParams['host'])) {
        putenv("DB_HOST=" . $dbParams['host']);
        $_ENV['DB_HOST'] = $dbParams['host'];
        $_SERVER['DB_HOST'] = $dbParams['host'];
    }
    if (isset($dbParams['port'])) {
        putenv("DB_PORT=" . $dbParams['port']);
        $_ENV['DB_PORT'] = $dbParams['port'];
        $_SERVER['DB_PORT'] = $dbParams['port'];
    }
    if (isset($dbParams['path'])) {
        $dbName = ltrim($dbParams['path'], '/');
        putenv("DB_DATABASE=" . $dbName);
        $_ENV['DB_DATABASE'] = $dbName;
        $_SERVER['DB_DATABASE'] = $dbName;
    }
    if (isset($dbParams['user'])) {
        putenv("DB_USERNAME=" . $dbParams['user']);
        $_ENV['DB_USERNAME'] = $dbParams['user'];
        $_SERVER['DB_USERNAME'] = $dbParams['user'];
    }
    if (isset($dbParams['pass'])) {
        putenv("DB_PASSWORD=" . urldecode($dbParams['pass']));
        $_ENV['DB_PASSWORD'] = urldecode($dbParams['pass']);
        $_SERVER['DB_PASSWORD'] = urldecode($dbParams['pass']);
    }
    putenv("DB_SSLMODE=require");
    $_ENV['DB_SSLMODE'] = 'require';
    $_SERVER['DB_SSLMODE'] = 'require';

    $dbHost = $dbParams['host'] ?? $dbHost;
}

if (!empty($dbHost) && $dbHost !== '127.0.0.1' && $dbHost !== 'localhost') {
    // Run migrations & seeders only if the remote database has never been migrated.
    // The /tmp flag is just a per-instance memo; the real check is against the DB
    // itself, so new serverless instances DON'T re-run migrate/seed on cold start.
    $migratedFlag = '/tmp/vercel_db_migrated';
    if (!file_exists($migratedFlag)) {
        $needsMigration = false;
        try {
            $dsn = sprintf(
                'pgsql:host=%s;port=%s;dbname=%s;sslmode=require',
                getenv('DB_HOST'),
                getenv('DB_PORT') ?: '5432',
                getenv('DB_DATABASE')
            );
            $pdo = new \PDO($dsn, getenv('DB_USERNAME'), getenv('DB_PASSWORD'), [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_TIMEOUT => 5,
            ]);
            $stmt = $pdo->query("SELECT COUNT(*) FROM migrations");
            $needsMigration = ((int) $stmt->fetchColumn()) === 0;
            $pdo = null;
        } catch (\Throwable $e) {
            // migrations table missing -> fresh database, needs migration
            $needsMigration = true;
        }

        if ($needsMigration) {
            try {
                require_once __DIR__ . '/../vendor/autoload.php';
                $app = require __DIR__ . '/../bootstrap/app.php';
                $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
                $kernel->call('migrate', ['--force' => true]);
                $kernel->call('db:seed', ['--force' => true]);
                @touch($migratedFlag);

                // Handle request directly
                $app->handleRequest(\Illuminate\Http\Request::capture());
                exit;
            } catch (\Throwable $e) {
                error_log('Vercel DB Auto-Migration Notice: ' . $e->getMessage());
            }
        } else {
            @touch($migratedFlag);
        }
    }
} else {
    // Fallback to bundled SQLite database in /tmp
    $tmpSqlite = '/tmp/database.sqlite';
    $sourceSqlite = __DIR__ . '/../database/database.sqlite';

    if (file_exists($sourceSqlite)) {
        @copy($sourceSqlite, $tmpSqlite);
    } elseif (!file_exists($tmpSqlite)) {
        @touch($tmpSqlite);
    }

    putenv("DB_CONNECTION=sqlite");
    $_ENV['DB_CONNECTION'] = 'sqlite';
    $_SERVER['DB_CONNECTION'] = 'sqlite';

    putenv("DB_DATABASE=$tmpSqlite");
    $_ENV['DB_DATABASE'] = $tmpSqlite;
    $_SERVER['DB_DATABASE'] = $tmpSqlite;

    putenv("SESSION_DRIVER=cookie");
    $_ENV['SESSION_DRIVER'] = 'cookie';
    $_SERVER['SESSION_DRIVER'] = 'cookie';

    putenv("CACHE_STORE=file");
    $_ENV['CACHE_STORE'] = 'file';
    $_SERVER['CACHE_STORE'] = 'file';
}

// Forward request to Laravel's public/index.php
require __DIR__ . '/../public/index.php';
