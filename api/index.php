<?php

// Prepare writable storage directory in Vercel serverless environment
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

// Forward request to Laravel's public/index.php
require __DIR__ . '/../public/index.php';
