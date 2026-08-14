<?php

// 1. Buat folder temporary runtime yang dibutuhkan Laravel
$storageDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/storage/app/public',
    '/tmp/bootstrap/cache',
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// 2. Set environment path storage & Blade compilation ke /tmp
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
putenv('APP_STORAGE=/tmp/storage');
putenv('APP_CONFIG_CACHE=/tmp/bootstrap/cache/config.php');
putenv('APP_SERVICES_CACHE=/tmp/bootstrap/cache/services.php');
putenv('APP_PACKAGES_CACHE=/tmp/bootstrap/cache/packages.php');
putenv('APP_ROUTES_CACHE=/tmp/bootstrap/cache/routes.php');

// 3. Autoload Composer
require __DIR__ . '/../vendor/autoload.php';

// 4. Inisialisasi Application Laravel 11
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->useStoragePath('/tmp/storage');

// 5. Tangani request menggunakan router/HTTP handler Laravel 11
$request = \Illuminate\Http\Request::capture();

if (method_exists($app, 'handleRequest')) {
    $response = $app->handleRequest($request);
} else {
    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle($request);
    $response->send();
    $kernel->terminate($request, $response);
}
